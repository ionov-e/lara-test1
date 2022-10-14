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

    private Client $client;
    private WithAuth $authHeaders;

    public function __construct(User $user)
    {
        $this->client = new Client(['base_uri' => $user->userSetting->url]);
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

            $url = uri($model)->asString();

            $response = $this->client->request('GET', $url, $options);

            $response = json_decode(strval($response->getBody()), true);

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
        try {
            $url = uri($model)->asString();
            $options = [
                'headers' => $this->authHeaders->asKeyValue(),
                'json' => $validatedData
            ];

            $response = $this->client->request('POST', $url, $options);

            $response = json_decode(strval($response->getBody()), true);

            if ($response['success']) {
                return true;
            }

            logger("APICreate failed: Response: " . json_encode($response));

        } catch (\Throwable $e) {
            logger("APICreate: Exception: " . $e->getMessage());
        }

        return false;
    }

    public function edit(string $model, ValidatedInput|array $validatedData, int $id): bool
    {
        try {
            $url = uri($model)->asString() . "/$id";
            $options = [
                'headers' => $this->authHeaders->asKeyValue(),
                'json' => $validatedData
            ];

            $response = $this->client->request('PUT', $url, $options);

            $response = json_decode(strval($response->getBody()), true);

            if ($response['success']) {
                return true;
            }

            logger("APIEdit failed: Response: " . json_encode($response));

        } catch (\Throwable $e) {
            logger("APIEdit: Exception: " . $e->getMessage());
        }

        return false;
    }

    public function delete(string $model, int $id): bool
    {
        try {
            $url = uri($model)->asString() . "/$id";
            $options = ['headers' => $this->authHeaders->asKeyValue()];
            $response = $this->client->delete($url, $options);

            $response = json_decode(strval($response->getBody()), true);

            if ($response['success']) {
                return true;
            }

            logger("APIDelete failed: Response: " . json_encode($response));

        } catch (\Throwable $e) {
            logger("APIDelete: Exception: " . $e->getMessage());
        }

        return false;
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
