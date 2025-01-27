<?php

namespace App\Service;

use GuzzleHttp\Client;

class OpenAIService
{
    protected $client;
    protected $apiKey;

    public function __construct()
    {
        $this->client = new Client();
        $this->apiKey = env('COHERE_API_KEY');
    }

    public function generateText($prompt)
    {
        try {
            $response = $this->client->post('https://api.cohere.ai/generate', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->apiKey,
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'model' => 'command-r-plus',
                    'prompt' => $prompt,
                    'max_tokens' => 800,
                    'temperature' => 0.7,
                ],
            ]);

            $body = json_decode($response->getBody()->getContents(), true);



            return json_decode($body['text']) ?? null;
        } catch (\Exception $exception) {
            return $exception->getMessage();
        }
    }
}
