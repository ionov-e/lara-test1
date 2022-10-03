<?php

namespace App\Services;

use Otis22\VetmanagerRestApi\Headers\WithAuth;
use Otis22\VetmanagerRestApi\Headers\Auth\ByApiKey;
use Otis22\VetmanagerRestApi\Headers\Auth\ApiKey;
use GuzzleHttp\Client;

class VetApiService
{

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

        } catch (\Exception $e) {}

        return false;

    }
}
