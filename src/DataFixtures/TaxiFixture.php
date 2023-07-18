<?php

namespace App\DataFixtures;

use App\Entity\Taxi;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;

class TaxiFixture extends Fixture
{
    private $filesystem;

    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    public function load(ObjectManager $manager)
    {
        $taxi1 = new Taxi();
        $taxi1->setMarca('Toyota')
            ->setVelocidad(140)
            ->setActivo(true)
            ->setNombre('grand highland')
            ->setPropietario('johny')
            ->setModelo("https://upload.wikimedia.org/wikipedia/commons/thumb/8/83/2014_Toyota_Highlander_XLE_NYC_yellow_cab_front.jpg/800px-2014_Toyota_Highlander_XLE_NYC_yellow_cab_front.jpg?20140721035842")
            ->setLocalizacion('huelva');
        
        $taxi2 = new Taxi();
        $taxi2->setMarca('Ford')
            ->setVelocidad(90)
            ->setActivo(false)
            ->setNombre('mustang mach-e')
            ->setPropietario('juana')
            ->setModelo("https://hips.hearstapps.com/hmg-prod/images/2021-ford-mustang-mach-e-nyc-yellow-cab-3-1640170960.jpg?crop=1.00xw:1.00xh;0,0&resize=1200:*")
            ->setLocalizacion('madrid');
        
        $manager->persist($taxi1);
        $manager->persist($taxi2);
        $manager->flush();
        
        $this->moveImagesToDestination();
    }


    private function moveImagesToDestination()
    {
        $publicDirectory = realpath(__DIR__ . '/../../public');

        $sourceDirectory = $publicDirectory . '/images';
        $destinationDirectory = $publicDirectory . '/images';

        $filesystem = new Filesystem();
        $filesystem->mirror($sourceDirectory, $destinationDirectory);
    }

}

?>