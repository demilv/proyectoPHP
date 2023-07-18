<?php

namespace App\Controller;

use App\Entity\Taxi;
use App\Form\TaxiType;
use App\Manager\CocheManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class TaxiController extends AbstractController {

    #[Route("/inicio", name:"inicio")]
    public function inicio() {  

     return $this->render("Taxi/inicio.html.twig");
    }


    #[Route("/taxi/{id}", name:"getTaxi")]
    public function getTaxi(EntityManagerInterface $doctrine, $id) {
     $repository = $doctrine->getRepository(Taxi::class);
     $taxi=$repository->find($id);

     return $this->render("Taxi/taxi.html.twig",["taxi"=>$taxi]);
    }

    #[Route("/insert/taxi", name:"insertTaxi")]
    public function insertTaxi(
        EntityManagerInterface $doctrine, 
        Request $request, 
        CocheManager $manager
        ) {
        
        $form=$this-> createForm(TaxiType::class);
        $form->handleRequest($request);
        if($form->isSubmitted()and $form->isValid())
        {
            $taxi=$form->getData();

            $taxiImage = $form->get('modelo')->getData();
            if($taxiImage)
            {
                $imageUrl = $manager->uploadImage($taxiImage, $this->getParameter('kernel.project_dir').'/public/images');
                $taxi->setModelo($imageUrl);
                
            }

            $doctrine->persist($taxi);
            $doctrine->flush();
            return $this->redirectToRoute('listTaxis');
        }
        return $this->renderForm('Taxi/insertTaxi.html.twig', ['taxiForm'=>$form]);
    }

    #[Route("/edit/taxi/{id}", name:"editTaxi")]
    //#[IsGranted("ROLE_ADMIN")]
    public function editTaxi(EntityManagerInterface $doctrine, Request $request, CocheManager $manager, $id) {

        $repository = $doctrine->getRepository(Taxi::class);
        $taxi = $repository->find($id);
        
        $form=$this-> createForm(TaxiType::class, $taxi);
        $form->handleRequest($request);
        if($form->isSubmitted()and $form->isValid())
        {
            $taxi=$form->getData();
            $taxiImage = $form->get('modelo')->getData();
            if($taxiImage)
            {
                $imageUrl = $manager->uploadImage($taxiImage, $this->getParameter('kernel.project_dir').'/public/images');
                $taxi->setModelo($imageUrl);
                
            }

            $doctrine->persist($taxi);
            $doctrine->flush();
            return $this->redirectToRoute('listTaxis');
        }
        return $this->renderForm('Taxi/insertTaxi.html.twig', ['taxiForm'=>$form]);
    }

    #[Route("/")]
    public function home()
    {
        return new Response("Estoy en la home");
    }

    #[Route("/taxis", name:"listTaxis")]
    public function listTaxis(EntityManagerInterface $doctrine) {

        $repository = $doctrine->getRepository(Taxi::class);

        $taxis = $repository->findAll();

        return $this->render("Taxi/listTaxis.html.twig",["taxis"=>$taxis]);
    }


#[Route('/taxi/delete/{id}', name: 'deleteTaxi')]
public function deleteTaxi($id, EntityManagerInterface $entityManager)
{
    $repository = $entityManager->getRepository(Taxi::class);
    $taxi = $repository->find($id);

    if (!$taxi) {
        return $this->redirectToRoute('listTaxis');
    }

    $entityManager->remove($taxi);
    $entityManager->flush();

    return $this->redirectToRoute('listTaxis');
}

    

    #[Route("/new/taxi")]
    public function newTaxi(EntityManagerInterface $doctrine)
    {
        $taxi1 = new Taxi();

        $taxi1->setMarca("Mazda");
        $taxi1->setVelocidad(200);
        $taxi1->setActivo(true);
        $taxi1->setNombre("Mazda CX-5");
        $taxi1->setPropietario("Juan Valles Carrasco");
        $taxi1->setModelo("https://de.mazda-press.com/siteassets/assets/news-heroes/mazdacx-5taxi.jpg/highdefinitionhalfsize?token=ad9Nt8elpM85aG_D9FuTSWpGgtWUQJIqefS-BmkPeo41");
        $taxi1->setLocalizacion("Mazagon");

        $taxi2 = new Taxi();

        $taxi2->setMarca("Mazda");
        $taxi2->setVelocidad(186);
        $taxi2->setActivo(true);
        $taxi2->setNombre("Mazda CX-30");
        $taxi2->setPropietario("Juanita Peña Cara");
        $taxi2->setLocalizacion("Caceres");

        $taxi3 = new Taxi();

        $taxi3->setMarca("Volvo");
        $taxi3->setVelocidad(180);
        $taxi3->setActivo(false);
        $taxi3->setPropietario("Marcos Dominguez Tazon");
        $taxi3->setModelo("https://all-andorra.com/wp-content/uploads/2022/01/2020-Volvo-V90.-Taxi-from-Marbella_side-view_front_specs-min.png");
        $taxi3->setLocalizacion("La Rioja");
        
        $doctrine->persist($taxi1);
        $doctrine->persist($taxi2);
        $doctrine->persist($taxi3);      

        $doctrine->flush();
        return new Response("Taxis insertados correctamente");


        // return new Response("Estoy en la home");
    }

    #[Route("/call/taxis", name: "callTaxis")]
    public function callTaxis(Request $request, EntityManagerInterface $doctrine)
    {
        $repository = $doctrine->getRepository(Taxi::class);
        $taxis = $repository->findAll();
    
        if ($request->isMethod('POST')) {
            $localizacion = $request->request->get('localizacion');
    
            $taxiEncontrado = Taxi::buscarTaxiActivoEnZona($localizacion, $repository);
    
            if ($taxiEncontrado) {
                $mensaje = 'Le enviaremos un taxi a su zona.';
            } else {
                $mensaje = 'No hay taxis disponibles en su zona.';
            }
        } else {
            $mensaje = null;
        }
    
        return $this->render("Taxi/findTaxis.html.twig", ["taxis" => $taxis, "mensaje" => $mensaje]);
    }
    


}
