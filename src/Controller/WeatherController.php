<?php


namespace App\Controller;


use App\Repository\HistoryWeatherRepository;
use App\Service\CurrentWeatherService;
use App\Service\HistoryWeatherService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class WeatherController extends AbstractController
{

    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @Route("/", name="weather")
     */
    public function index()
    {
        return $this->render('searchCity.html.twig');
    }

    /**
     * @Route ("/currentWeather",name="currentWeather")
     */
    public function currentWeather(CurrentWeatherService $currentWeatherService, HistoryWeatherService $historyWeatherService): Response
    {
        $parametersAsArray = $currentWeatherService->getCurrentWeatherFromOpenweathermap();

        $name = $parametersAsArray['name'];
        $date = date("jS F, Y", time());
        $temp = $parametersAsArray['main']['temp_max'];
        // save to DB using HistoryWeatherService
        $historyWeatherService->saveToDataBase($name, $date, $temp);

        return $this->render('currentWeather.html.twig',
            [
                'nameInView' => $parametersAsArray['name'],
                'timeInView' => date("l g:i a", time()),
                'dateInView' => date("jS F, Y", time()),
                'descriptionInView' => $parametersAsArray['weather'][0]['description'],
                'iconInView' => $parametersAsArray['weather'][0]['icon'],
                'temp_maxInView' => $parametersAsArray['main']['temp_max'],
                'temp_minInView' => $parametersAsArray['main']['temp_min'],
                'humidityInView' => $parametersAsArray['main']['humidity'],
                'speedInView' => $parametersAsArray['wind']['speed'],
            ]
        );
    }

    /**
     * @Route ("/historicalWeather",name="historicalWeather")
     */
    public function historicalWeather(HistoryWeatherRepository $historyWeatherRepository)
    {
        $historyWeatherData = $historyWeatherRepository->findAll();

        return $this->render('historicalWeather.html.twig', [
            'historyWeatherData' => $historyWeatherData
        ]);
    }
}
