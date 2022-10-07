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
     * Возвращает массив с моделям, удовлетворяющих запросу. Если используется только перван параметр - фильтра не будет
     *
     * @param string $model например 'client'
     * @param string $searchKey например 'last_name'
     * @param string $searchValue например 'Михалков'
     * @param int $limit максимальное количество возвращаемых элементов в массиве
     * @param int $currentPage для пагинации
     *
     * @return array Каждый элемент будет в себе содержать все значения от сервера
     */
    public function search(string $model, string $searchKey = '', string $searchValue = '', string $operator = self::LIKE_OPERATOR, int $limit = 50, int $currentPage = 0): array
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

            return $response['data'][$model];
        } catch (\Throwable $e) {
            logger("APISearch: Exception: " . $e->getMessage());
            return [];
        }
    }

    public function get(string $model, int $id): array
    {
        $url = uri('client')->asString() . "/$id";
        $options = ['headers' => $this->authHeaders->asKeyValue()];
        $response = json_decode(strval($this->client->request('GET', $url, $options)->getBody()), true);
        return $response['data'][$model];
    }

    public function create(string $model, ValidatedInput|array $validatedData): void
    {
        $url = uri($model)->asString();
        $options = [
            'headers' => $this->authHeaders->asKeyValue(),
            'json' => $validatedData
        ];
        $this->client->request('POST', $url, $options);
    }

    public function edit(string $model, ValidatedInput|array $validatedData, int $id): void
    {
        $url = uri($model)->asString() . "/$id";
        $options = [
            'headers' => $this->authHeaders->asKeyValue(),
            'json' => $validatedData
        ];
        $this->client->request('PUT', $url, $options);
    }

    public function delete(string $model, int $id): void
    {
        $url = uri($model)->asString() . "/$id";
        $options = ['headers' => $this->authHeaders->asKeyValue()];
        $this->client->delete($url, $options)->getStatusCode();
    }


    public function deleteClient(int $id): void
    {
        $petsData = $this->search(VetApiService::PET_MODEL, 'owner_id', $id, VetApiService::EQUAL_OPERATOR);

        if (!empty($petsData)) {
            $petIdsLog = [];
            foreach ($petsData as $pet) {
                $petId = $pet['id'];
                $this->delete(VetApiService::PET_MODEL, $petId);
                $petIdsLog[] = $petId;
            }
            logger("APIDeletedPets for Client $id: " . implode(",", $petIdsLog));
        }

        $this->delete(VetApiService::CLIENT_MODEL, $id);
    }

    static function authenticateUser(string $apiKey, string $uri): bool
    {
        try {
            $client = new Client(['base_uri' => $uri]);

            $authHeaders = new WithAuth(new ByApiKey(new ApiKey($apiKey)));

            $return = $client->request('GET', '/rest/api/user', ['headers' => $authHeaders->asKeyValue()]);

            if (200 == $return->getStatusCode()) {
                return true;
            }

        } catch (\Exception|GuzzleException $e) {
        }

        return false;

    }
}
