<?php

namespace App\Http;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class EmailVerifyService
{
    protected $client;
    protected $apiKey = '1d5fbb684a8f379600ad85efb02ec9fa525dceb3'; // Your API key

    public function __construct()
    {
        $this->client = new Client();
    }

    public function verifyEmail($email)
    {
        $url = "https://api.hunter.io/v2/email-verifier?email={$email}&api_key={$this->apiKey}";

        try {
            $response = $this->client->request('GET', $url);
            $data = json_decode($response->getBody()->getContents(), true);

            // Extract needed information
            $status = $data['data']['status'] ?? 'unknown';
            $result = $data['data']['result'] ?? 'unknown';
            $score = $data['data']['score'] ?? 0;

            return [
                'status' => $status,
                'result' => $result,
                'score' => $score
            ];
        } catch (RequestException $e) {
            // Handle the exception or return an error response
            return [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }

    }
}
