<?php

namespace App\Services;

use App\Models\User;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\ValidatedInput;
use Otis22\VetmanagerRestApi\Headers\WithAuth;
use Otis22\VetmanagerRestApi\Headers\Auth\ByApiKey;
use Otis22\VetmanagerRestApi\Headers\Auth\ApiKey;
use Otis22\VetmanagerRestApi\Query\Filters;
use Otis22\VetmanagerRestApi\Query\PagedQuery;
use Otis22\VetmanagerRestApi\Query\Query;
use Otis22\VetmanagerRestApi\Query\Sorts;

use function Otis22\VetmanagerRestApi\uri;

class VetApiService
{

    private Client $client;
    private WithAuth $authHeaders;

    public function __construct(User $user)
    {
        $this->client = new Client(['base_uri' => $user->userSetting->url]);
        $this->authHeaders = new WithAuth(new ByApiKey(new ApiKey($user->userSetting->key)));
    }

    public function getClientList(int $currentPage = 0): array
    {
        $model = 'client';
        $pagedQuery = new PagedQuery(new Query(new Filters(), new Sorts()), 50, $currentPage);
        $options = [
            'headers' => $this->authHeaders->asKeyValue(),
            'query' => $pagedQuery->asKeyValue()
        ];
        $url = uri($model)->asString();

        $response = $this->client->request('GET', $url, $options);

        $response = json_decode(strval($response->getBody()), true);

        return $response['data'][$model];
    }

    public function getClient(int $id): array
    {
        $model = 'client';
        $url = uri('client')->asString() . "/$id";
        $options = ['headers' => $this->authHeaders->asKeyValue()];
        $response = json_decode(strval($this->client->request('GET', $url, $options)->getBody()), true);
        return $response['data'][$model];
    }

    public function deleteClient(int $id): void
    {
        $url = uri('client')->asString() . "/$id";
        $options = ['headers' => $this->authHeaders->asKeyValue()];
        $this->client->delete($url, $options)->getStatusCode();
    }

    public function createClient(ValidatedInput|array $validatedData): void
    {
        $url = uri('client')->asString();
        $options = [
            'headers' => $this->authHeaders->asKeyValue(),
            'json' => $validatedData
        ];
        $this->client->request('POST', $url, $options);
    }

    public function editClient(ValidatedInput|array $validatedData, int $id): void
    {
        $url = uri('client')->asString() . "/$id";
        $options = [
            'headers' => $this->authHeaders->asKeyValue(),
            'json' => $validatedData
        ];
        $this->client->request('PUT', $url, $options);
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
