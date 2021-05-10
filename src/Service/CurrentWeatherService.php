<?php


namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class CurrentWeatherService
{
    private HttpClientInterface $client;

    public function __construct(HttpClientInterface $objectFromConstructor)
    {
        $this->client = $objectFromConstructor;
    }

    public function getCurrentWeatherFromOpenweathermap(): array
    {

        $response = $this->client->request(
            'GET',
            'http://api.openweathermap.org/data/2.5/weather?id=765876&lang=en&units=metric&APPID=7356db16df1dd398de39acf500a1a54f'
        );
        $parametersAsArray = [];
        if ($content = $response->getContent()) {
            $parametersAsArray = json_decode($content, true);
        }

        return $parametersAsArray;
    }

}