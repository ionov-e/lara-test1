<?php

namespace App\Services;

use App\Models\User;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\ValidatedInput;
use Otis22\VetmanagerRestApi\Headers\WithAuth;
use Otis22\VetmanagerRestApi\Headers\Auth\ByApiKey;
use Otis22\VetmanagerRestApi\Headers\Auth\ApiKey;
use Otis22\VetmanagerRestApi\Model\Property;
use Otis22\VetmanagerRestApi\Query\Filter\Value\StringValue;
use Otis22\VetmanagerRestApi\Query\Filters;
use Otis22\VetmanagerRestApi\Query\PagedQuery;
use Otis22\VetmanagerRestApi\Query\Query;
use Otis22\VetmanagerRestApi\Query\Sorts;

use function Otis22\VetmanagerRestApi\uri;

class VetApiService
{

    const CLIENT_MODEL = 'client';
    const PET_MODEL = 'pet';

    const LIKE_OPERATOR = 'Otis22\VetmanagerRestApi\Query\Filter\Like';
    const EQUAL_OPERATOR = 'Otis22\VetmanagerRestApi\Query\Filter\EqualTo';

    private WithAuth $authHeaders;
    private string $url;

    public function __construct(User $user)
    {
        $this->url = $user->userSetting->url;
        $this->authHeaders = new WithAuth(new ByApiKey(new ApiKey($user->userSetting->key)));
    }

    /**
     * Возвращает массив с моделям, удовлетворяющих запросу. Если используется только первый параметр - фильтра не будет
     *
     * @param string $model например 'client'
     * @param string $searchKey например 'last_name'
     * @param string $searchValue например 'Михалков'
     * @param int $limit максимальное количество возвращаемых элементов в массиве
     * @param int $currentPage для пагинации
     *
     * @return array Каждый элемент будет в себе содержать все значения от сервера
     */
    public function get(string $model, string $searchKey = '', string $searchValue = '', string $operator = self::LIKE_OPERATOR, int $limit = 50, int $currentPage = 0): array
    {
        try {
            if (empty($searchKey)) {
                $filters = new Filters();
            } else {
                $filters = new Filters(new $operator (new Property($searchKey), new StringValue($searchValue)));
            }

            $query = new PagedQuery(new Query($filters, new Sorts()), $limit, $currentPage);

            $options = [
                'headers' => $this->authHeaders->asKeyValue(),
                'query' => $query->asKeyValue()
            ];

            $response = $this->getResponse('GET', $model, $options);

            if ($response['success']) {
                return $response['data'][$model];
            }

            logger("APISearch failed. Response: " . json_encode($response));

        } catch (\Throwable $e) {
            logger("APISearch: Exception: " . $e->getMessage());
        }

        return [];
    }

    public function create(string $model, ValidatedInput|array $validatedData): bool
    {
        return $this->request('POST', $model, $validatedData);
    }

    public function edit(string $model, ValidatedInput|array $validatedData, int $id): bool
    {
        return $this->request('PUT', $model, $validatedData, $id);
    }

    public function delete(string $model, int $id): bool
    {
        return $this->request('DELETE', $model, id: $id);
    }


    /** "Удаляет" клиента, предварительно удалив всех его питомцев */
    public function deleteClient(int $id): bool
    {
        try {
            $petsData = $this->get(VetApiService::PET_MODEL, 'owner_id', $id, VetApiService::EQUAL_OPERATOR);

            if (!empty($petsData)) {
                $petIdsDeletedWithSuccess = [];
                foreach ($petsData as $pet) {
                    $petId = $pet['id'];
                    if (!$this->delete(VetApiService::PET_MODEL, $petId)) {
                        throw new \Exception("Pet {$pet['id']} Delete Fail");
                    }
                    $petIdsDeletedWithSuccess[] = $petId;
                }
                logger("APIDeletedPets for Client $id: " . implode(",", $petIdsDeletedWithSuccess));
            }

            return $this->delete(VetApiService::CLIENT_MODEL, $id);

        } catch (\Throwable $e) {
            logger("APIDeleteClient: Exception: " . $e->getMessage());
            return false;
        }
    }

    private function request(string $method, string $model, array $validatedData = [], int $id = 0): bool
    {
        try {
            $options = ['headers' => $this->authHeaders->asKeyValue()];

            if ($validatedData) {
                $options['json'] = $validatedData;
            }

            $response = $this->getResponse($method, $model, $options, $id);

            if ($response['success']) {
                return true;
            }

            logger("APIService failed. Method: $method, Model: $model, Id: $id. Response: " . json_encode($response));

        } catch (\Throwable $e) {
            logger("APIService failed. Method: $method, Model: $model, Id: $id. Exception: " . $e->getMessage());
        }

        return false;
    }

    /**
     * @throws GuzzleException
     * @throws \Exception
     */
    private function getResponse(string $method, string $model, array $options, int $id = 0): array
    {
        $url = $id ? uri($model)->asString() . "/$id" : uri($model)->asString();

        $response = (new Client(['base_uri' => $this->url]))->request($method, $url, $options);

        return json_decode(strval($response->getBody()), true);
    }

    static function authenticateUser(string $apiKey, string $uri): bool
    {
        try {
            $client = new Client(['base_uri' => $uri]);

            $authHeaders = new WithAuth(new ByApiKey(new ApiKey($apiKey)));

            $response = $client->request('GET', '/rest/api/user', ['headers' => $authHeaders->asKeyValue()]);

            $response = json_decode(strval($response->getBody()), true);

            if ($response['success']) {
                return true;
            }

            logger("Authentication failed: Response: " . json_encode($response));

        } catch (\Exception|GuzzleException $e) {
            logger("Authentication: Exception: " . $e->getMessage());
        }

        return false;
    }
}
