<?php

namespace App\Services;

use App\Models\User;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
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

    private string $key;
    private Client $client;

    public function __construct(User $user)
    {
        $this->key = $user->userSetting->key;
        $this->client = new Client(['base_uri' => $user->userSetting->url]);
    }

    public function getClients(int $currentPage = 0): array
    {
        $model = 'client';
        $pagedQuery = new PagedQuery(new Query(new Filters(), new Sorts()), 50, $currentPage);

        $response = json_decode(
            strval(
                $this->client->request(
                    'GET',
                    uri($model)->asString(),
                    [
                        'headers' => $this->getAuthHeaders()->asKeyValue(),
                        'query' => $pagedQuery->asKeyValue()
                    ]
                )->getBody()
            ),
            true
        );

        return $response['data'][$model];
    }


    private function getAuthHeaders(?string $apiKey = null): WithAuth
    {
        $apiKey = $apiKey ?? $this->key;

        return new WithAuth(
            new ByApiKey(
                new ApiKey($apiKey)
            )
        );
    }

    static function authenticateUser(string $apiKey, string $uri): bool
    {
        try {
            $client = new Client(['base_uri' => $uri]);

            $authHeaders = new WithAuth(
                new ByApiKey(
                    new ApiKey($apiKey)
                )
            );

            $return = $client->request(
                'GET',
                '/rest/api/user',
                ['headers' => $authHeaders->asKeyValue()]
            );

            if (200 == $return->getStatusCode()) {
                return true;
            }

        } catch (\Exception|GuzzleException $e) {
        }

        return false;

    }
}
