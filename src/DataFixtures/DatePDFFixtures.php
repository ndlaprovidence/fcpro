<?php

namespace App\DataFixtures;

use DateTime;
use App\Entity\DatePDF;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class DatePDFFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        
        $datea = '2022-09-01';
        $dateaTime = new DateTime($datea);
        $date = new DatePDF();
        $date->setDateCreation($dateaTime);
        $date->setDateModif($dateaTime);
        $date->setNumMaj(1);
        $manager->persist($date);

        $manager->flush();
    }
}
