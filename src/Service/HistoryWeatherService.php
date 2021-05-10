<?php


namespace App\Service;




use App\Entity\HistoryWeather;
use Doctrine\ORM\EntityManagerInterface;

class HistoryWeatherService
{

    // entity manager
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }


    public function saveToDataBase(string $name, string $date, string $temperature){

        $historyWeather = new HistoryWeather();

        $historyWeather->setCity($name);
        $historyWeather->setDate($date);
        $historyWeather->setTemperature($temperature);

        $this->em->persist($historyWeather);
        $this->em->flush();
    }
}