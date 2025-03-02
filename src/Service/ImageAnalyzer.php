<?php
namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class ImageAnalyzer
{
    private $httpClient;

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function analyzeImage(string $filePath): bool
    {
        $response = $this->httpClient->request('POST', 'http://localhost:5000/analyze', [
            'body' => [
                'image' => fopen($filePath, 'r'),
            ],
        ]);

        $data = $response->toArray();
        return $data['healthy'];
    }
}
