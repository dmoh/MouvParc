<?php
/**
 * Created by PhpStorm.
 * User: Service Info
 * Date: 27/12/2017
 * Time: 15:43
 */
namespace App\Controller;

use App\Entity\Carrosserie;
use App\Entity\Image;
use App\Entity\Rubrique;
use App\Form\CarrosserieType;
use App\Form\MiseajourType;
use App\Form\RubriqueType;
use App\Form\UserType;
use App\Repository\PanneRepository;
use App\Service\HTML2PDF;
use App\Service\php;
use Doctrine\DBAL\Types\DateType;

use Doctrine\ORM\Mapping\Entity;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use App\Form\CarType;
use App\Entity\Cars;
use App\Entity\Panne;
use App\Form\PanneType;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Repository\CarsRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use App\Entity\Miseajour;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use Symfony\Component\HttpFoundation\JsonResponse;

Class MainController extends Controller
{
    public function index(Request $req)
    {
        $em = $this->getDoctrine()->getManager();

        $car = new Cars;

        $form = $this->createForm(CarType::class, $car);



        if($req->isMethod('POST'))
        {

            $form->handleRequest($req);
            $data = $form->getData();

            $repository = $this->getDoctrine()->getRepository(Cars::class);
            $car_R = $repository->findOneBy(['immat' => $data->getImmat()]);
            //$test = $data->getImmat();
             if($car_R == NULL)
             {
                 if($form->isValid()){
                     $em -> persist($car);
                     $em -> flush();
                     //$this->addFlash('info', 'Oui oui, il est bien enregistré !');
                    return $this->redirectToRoute('consultation');
                 }

             }
             else
             {
                 $this->addFlash('info', 'Ce véhicule existe déjà dans la base');
                 return $this->render('front/index.html.twig', array(
                     'form' => $form->createView(),
                 ));
             }

        }



        return $this->render('front/index.html.twig', array(
            'form' => $form->createView(),
        ));

    }

    public function miseajour(Request $request)
    {
        $docExcel = new Miseajour();

        $form = $this->createForm(MiseajourType::class, $docExcel);
        $form->handleRequest($request);

        $em = $this->getDoctrine()->getManager();
        $dql = "SELECT c FROM App\Entity\Cars c";
        $queryC = $em->createQuery($dql);


        //Liste des autocars present dans la Bdd
        $listeCars = $queryC->getResult(Query::HYDRATE_ARRAY);

        $nbCars = count($listeCars);


        if ($request->isMethod('POST')) {
            $file = $docExcel->getDocExcel();
            $carMiseAjour = 0;

            $fileName = $this->generateUniqueFileName() . '.' . $file->guessExtension();
            $directory = $this->getParameter('kernel.root_dir') . '/../public/uploads';
            $fileSize = $file->getClientSize();
            $litLeFicheir = file($file);

            /*var_dump($litLeFicheir);
            die();*/
            $carLouper = array();
            $carDansBDD = array();

            $carAbsBdd = array();

            $tt = count($litLeFicheir);
            //unset($litLeFicheir[0]);

            //Boucle Fichier envoyé
            for ($i = 1; $i < $tt; $i++) {
                $car = explode(";", $litLeFicheir[$i]);
                $test = array();
                //MAj du fichier identifiants truck et immat
                if(!isset($car[10]) && isset($car[0]) && isset($car[1]) && strlen($car[1]) === 9 && strlen($car[0]) > 4)
                {
                    //Mettre a jour a l'aide des identifiants truck
                    $immatFichier = $car[1];
                    $idTruck = $car[0];
                    //fichier csv 2 colonnes avec idTruck et Immat
                    $majCar = $em->getRepository(Cars::class)->findOneBy(array(
                       "immat" => $immatFichier
                    ));
                    if(null !== $majCar)
                    {
                        $majCar->setIdTruck($idTruck);
                        $em->persist($majCar);
                    }
                    array_push($test, $immatFichier);
                    var_dump($test);
                    $em->flush();
                }
                else
                {
                    $immat = $car[1];
                    //Trouver dans la bdd si cette immat correspond
                    $immatOk  = array_search($immat, array_column($listeCars, 'immat'));
                    if ($immatOk === false)
                    {
                        //var_dump("Cet autocar immatricule ".$immat." a été ajouté à la bdd");
                        $carAbsBdd['immat'] = $immat;
                        $carNew = new Cars();
                        $nb_places = $car[4];
                        if (isset($nb_places)) {
                            $nb_places = (int)$nb_places;
                        }

                        $marque = $car[6];

                        if (isset($marque)) {

                        }

                        $centre = $car[10];
                        if ($centre != 'LRF' || $centre != 'ACY') {
                            $centre = 'LRF';
                        }

                        $date_entree = $car[12];
                        //$date_entree = substr($date_entree, 0, 10);
                        $date_entree = \DateTime::createFromFormat('d/m/Y', $date_entree);
                        //$date_entree = date_format($date_entree, 'Y-m-d')

                        $date_mar = $car[13];
                        if($date_mar == "")
                        {
                            $date_mar = new \DateTime('-1 year');
                        }else {
                            $date_mar = \DateTime::createFromFormat('d/m/Y', $date_mar);
                            //$date_mar = date_format($date_mar, 'Y-m-d');
                        }

                        $siege_guide = $car[17];

                        if ($siege_guide == 'O') {
                            $siege_guide = (int)1;

                        } else if ($siege_guide == 'N') {
                            $siege_guide = 0;
                        } else {
                            $siege_guide = NULL;
                        }

                        $observations = $car[23];
                        if ($observations != '')
                        {
                            $observations = null;
                        }

                        $euro = $car[35];
                        if($euro)
                        {
                            if ($euro == '') {
                                $euro = NULL;
                            }
                        }

                        $num_serie = $car[36];
                        $len = strlen($num_serie);
                        if ($len < 15 || $len > 25) {
                            $num_serie = "ENTREZLENUMSERIESVP";
                        }
                        //Changer format date
                        $date_ethylo = $car[48];

                        if ($date_ethylo != "") {
                            $date_ethylo = \DateTime::createFromFormat('d/m/Y', $date_ethylo);
                        } else {
                            $date_ethylo = NULL;
                        }



                        $date_extincteur = $car[49];

                        if ($date_extincteur != "") {
                            $date_extincteur = \DateTime::createFromFormat('d/m/Y', $date_extincteur);

                        } else {
                            $date_extincteur = NULL;
                        }



                        $date_limiteur = $car[50];

                        if ($date_limiteur != "") {
                            $date_limiteur = \DateTime::createFromFormat('d/m/Y', $date_limiteur);
                        } else {
                            $date_limiteur = NULL;
                        }

                        $ct = $car[51];

                        if ($ct != "") {
                            $ct = \DateTime::createFromFormat('d/m/Y', $ct);
                        } else {
                            $ct = NULL;
                        }


                        $date_tachy = $car[53];
                        $date_tachy = substr($date_tachy, 0, 10);
                        if ($date_tachy != "") {
                            $date_tachy = \DateTime::createFromFormat('d/m/Y', $date_tachy);
                        } else {
                            $date_tachy = NULL;
                        }



                        $carNew->setImmat($immat);
                        //
                        $carNew->setAuteur('Guillaume');
                        $carNew->setMarque($marque);
                        $carNew->setSite($centre);
                        $carNew->setNumSerie($num_serie);

                        $carNew->setDateMar($date_mar);

                        $carNew->setNbPlaces($nb_places);
                        $carNew->setEtatCar('roulant');
                        $carNew->setSiegeGuide($siege_guide);
                        $carNew->setCt($ct);
                        $carNew->setEuro($euro);
                        $carNew->setDateEthylo($date_ethylo);

                        $carNew->setDateTachy($date_tachy);
                        $carNew->setDateExtincteur($date_extincteur);
                        $carNew->setDateLimiteur($date_limiteur);


                        $em->persist($carNew);
                        $em->flush();
                    }
                    else
                    {

                        $nb_places = $car[4];
                        if (isset($nb_places)) {
                            $nb_places = (int)$nb_places;
                        }

                        $marque = $car[6];

                        if (isset($marque)) {
                            $marque;
                        }


                        $centre = $car[10];


                        if ($centre != 'LRF' || $centre != 'ACY') {
                            $centre = 'LRF';
                        }


                        $date_entree = $car[12];
                        //$date_entree = substr($date_entree, 0, 10);

                        if($date_entree == "")
                        {
                            $date_entree = new \DateTime('-1 year');
                        }
                        else if ($date_entree != "") {

                            $date_entree = date_create_from_format('d/m/Y', $date_entree);
                            $date_entree = date_format($date_entree, 'Y-m-d');
                        } else {
                            $date_entree = new \DateTime();
                        }


                        $date_mar = $car[13];

                        if($date_mar == "")
                        {
                            $date_mar = new \DateTime('-1 year');
                        }else if ($date_mar != "") {
                            $date_mar = date_create_from_format('d/m/Y', $date_mar);
                            $date_mar = date_format($date_mar, 'Y-m-d');
                        }else {
                            $date_mar = new \DateTime('-1 year');
                        }


                        $siege_guide = $car[17];

                        if ($siege_guide == 'O') {
                            $siege_guide = (int)1;

                        } else if ($siege_guide == 'N') {
                            $siege_guide = 0;
                        } else {
                            $siege_guide = NULL;
                        }

                        $euro = $car[35];

                        if ($euro == '') {
                            $euro = NULL;
                        }
                        /**/

                        $num_serie = $car[36];


                        $len = strlen($num_serie);
                        if ($len < 15 || $len > 25) {
                            $num_serie = "ENTREZLENUMSERIESVP";
                        }


                        //Changer format date
                        $date_ethylo = $car[48];

                        if ($date_ethylo != "" && $date_ethylo != 0) {

                            $date_ethylo = \DateTime::createFromFormat('d/m/Y', $date_ethylo);
                        } else {
                            $date_ethylo = NULL;
                        }

                        $date_extincteur = $car[49];

                        if ($date_extincteur != "" && $date_extincteur != 0) {
                            //$date_extincteur = date_create_from_format('d/m/Y', $date_extincteur);
                            $date_extincteur = \DateTime::createFromFormat('d/m/Y', $date_extincteur);

                        } else {
                            $date_extincteur = NULL;
                        }

                        $date_limiteur = $car[50];

                        if ($date_limiteur != "" && $date_extincteur != 0) {
                            //$date_limiteur = date_create_from_format('d/m/Y', $date_limiteur);
                            $date_limiteur = \DateTime::createFromFormat('d/m/Y', $date_limiteur);
                        } else {
                            $date_limiteur = NULL;
                        }
                        $ct = $car[51];

                        if ($ct != "") {
                            //$ct = null;
                            $ct = \DateTime::createFromFormat('d/m/Y', $ct);
                            //$ct = date_format($ct, 'Y-m-d');
                        } else {
                            $ct = NULL;
                        }

                        $date_tachy = $car[53];
                        $date_tachy = substr($date_tachy, 0, 10);
                        if ($date_tachy != "" && $date_tachy != 0) {
                            $date_tachy = date_create_from_format('d/m/Y', $date_tachy);
                            //$date_tachy = date_format($date_tachy, 'Y-m-d');
                        } else {
                            $date_tachy = NULL;
                        }

                        $query = $em->createQuery('UPDATE App\Entity\Cars c SET c.nb_places = :nb_places, c.siege_guide = :siege_guide, c.euro = :euro, c.date_ethylo = :date_ethylo, c.date_extincteur = :date_extincteur, c.date_limiteur = :date_limiteur, c.ct = :ct, c.date_tachy = :date_tachy, c.marque = :marque, c.site = :site, c.date = :date_entree, c.date_mar = :date_mar    WHERE c.immat = :immat');
                        $query->setParameters(array(
                            'nb_places'         => $nb_places,
                            'siege_guide'       => $siege_guide,
                            'euro'              => $euro,
                            'date_ethylo'       => $date_ethylo,
                            'date_extincteur'   => $date_extincteur,
                            'date_limiteur'     => $date_limiteur,
                            'ct'                => $ct,
                            'date_tachy'        => $date_tachy,
                            'immat'             => $immat,
                            'marque'            => $marque,
                            'site'              => $centre,
                            'date_entree'       => $date_entree,
                            'date_mar'          => $date_mar
                        ));
                        $query->execute();
                        $em->flush();
                    }//fin Else update des autocars
                }//Fin Else Savoir si fichier pour Maj Id TruckOnline
            }//end For Fichier Envoyé

            $file->move($directory, $fileName);


            return $this->render('front/miseajour.html.twig', array(
                'form'          => $form->createView(),
                'listeCars'     => $listeCars,
                'carAbsBdd'     => $carAbsBdd,
                'majBdd'        => "La Base de données a bien été mise à jour.",
            ));

        }

        return $this->render('front/miseajour.html.twig', array(
            'form' => $form->createView(),
            'listeCars' => $listeCars
        ));

    }

    public function generateUniqueFileName()
    {
        return md5(uniqid());
    }

    public function consultation(Request $request, AuthenticationUtils $authUtils)
    {
        $em = $this->getDoctrine()->getManager();
        $dql = "SELECT c FROM App\Entity\Cars c";
        $query = $em->createQuery($dql);

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator-> paginate(
          $query,
          $request->query->getInt('page', 1),20
        );

        $repository = $this->getDoctrine()
            ->getManager()
            ->getRepository(Cars::class);
        $car_listing = $repository->findBy(array(),
             array('date' => 'DESC')
        );

        // get the login error if there is one
        $error = $authUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authUtils->getLastUsername();


        /*var_dump($car_listing);
        die();*/

        return $this->render('front/consultation.html.twig',
            //array('Cars' => $car_listing)
            array(
                'pagination'=> $pagination,
                'Cars' => $car_listing,
                'last_username' => $lastUsername,
                'error'         => $error,
            )
            );
    }


    //Historique du Car
    public function car(Request $req, $id)
    {
        $repo = $this->getDoctrine()->getManager();
        $kms = 0;
        $geoc_lat = 0;
        $geoc_long = 0;
        $car_info = $repo->getRepository(Cars::class)->find(
           $id
        );


        $immat =  $car_info->getImmat();
        $ti = time();
        //&& $immat !== "EV-910-DF" && $immat !== "EV-855-DF"
        if($immat !== "CS-223-JK" && $immat !== "BZ-668-TH" && $immat !== "BM-276-ZC" && $immat !== "BL-182-AH" && $immat !== "EQ-757-AY" && $immat !== "EQ-796-AP")
        {
            $hache = base64_encode(hash_hmac("SHA1", "apa-aps-t39-c1ws.truckonline.proGET/apis/rest/v2.2/fleet/vehicles?vehicle_vrn=".$immat."".$ti."", "5a35101a-62ae-4cba-b70a-b1efd5cd75f0", true));
            $opts = array(
                'http' => array(
                    'method'=>'GET',
                    'header' => "x-tonl-client-id:  apa-aps-t39-c1\r\n".
                                "x-tonl-timestamp:  ".$ti."\r\n".
                                "x-tonl-signature: ".$hache.""
                )
            );
            // Recherche api truck online tous les vehicules
            $context = stream_context_create($opts);
            $kil = file_get_contents("https://ws.truckonline.pro/apis/rest/v2.2/fleet/vehicles?vehicle_vrn=".$immat."", false, $context);
            $result = json_decode($kil, true);

            if($result[0])
            {
                $kms = $result[0]["totalKms"];

            }

            $hache2 = base64_encode(hash_hmac("SHA1", "apa-aps-t39-c1ws.truckonline.proGET/apis/rest/v2.2/gpstracking?count=1&vehicle_vrn=".$immat."".$ti."", "5a35101a-62ae-4cba-b70a-b1efd5cd75f0", true));

            $opts2 = array(
                'http' => array(
                    'method'=>'GET',
                    'header' => "x-tonl-client-id:  apa-aps-t39-c1\r\n".
                        "x-tonl-timestamp:  ".$ti."\r\n".
                        "x-tonl-signature: ".$hache2.""
                )
            );
            $context2 = stream_context_create($opts2);
            $kil2 = file_get_contents("https://ws.truckonline.pro/apis/rest/v2.2/gpstracking?count=1&vehicle_vrn=".$immat."", false, $context2);
            $result2 = json_decode($kil2, true);
            if($result2[0])
            {
                $geoc_lat = $result2[0]["gpsInfo"]["latitude"];
                $geoc_long = $result2[0]["gpsInfo"]["longitude"];
            }

        }
        $em = $this->getDoctrine()->getManager();

        $pannes = $repo->getRepository(Panne::class)
                 ->findBy(array(
                     'cars' => $car_info),
                     array('id' => 'DESC',
                 ));
        $nombrep = count($pannes);

        if($req->isXmlHttpRequest())
        {


            $delUrl =  $req->get('del');

            $delUrl =substr($delUrl, 3);
            $filename = substr($delUrl, 9);
           
            $image2 = $em->getRepository(Image::class)->findOneBy(array('url'=> $delUrl));
            $image2->deleteFiles($filename);

            $em->remove($image2);
            $em->flush();
            return new JsonResponse(array('success' => true));

        }

        if($nombrep == 0)
        {
            if($car_info->getEtatCar() != 'roulant' && $car_info->getDatePanneDeb() != null && $car_info->getDatePanneDeb() != '' ) {


                $car_info->setDatePanneFin(new \DateTime());
                $v = $car_info->getDatePannefin();
                $car_info->setEtatCar('roulant');
                /*$car_info->setDatePanneFin(new \DateTime());
                $fin_panne = $car_info->getDatePannefin();*/
                $s = $car_info->getDatePanneDeb();
                if (!$v || $v!= null && !$s || $s != null) {
                    # code...
                    $duree_panne = date_diff($s, $v);
                    $duree_panne_prev = $duree_panne->format('%d');
                    $car_info->setDureePanne($duree_panne_prev);
                }

                $em->flush();


            }
        }

        $car_info->setKm($kms);
        $car_info->setCompteurPanne($nombrep);
        $em->persist($car_info);
        $em->flush();

        return $this->render('front/car.html.twig',
            array(
                'car'       => $car_info,
                'nombrep'   => $nombrep,
                'pannes'    => $pannes,
                'kms'       => $kms,
                'geoc_lat'  => $geoc_lat,
                'geoc_long' => $geoc_long,
                )
        );

    }


    public function addPanne(Request $req, $id, \Swift_Mailer $mailer)
    {
        //enntity manager
        $em = $this->getDoctrine()->getManager();

        //repository
        $repo = $this->getDoctrine()->getManager();

        $panne = new Panne;
        $form = $this->createForm(PanneType::class, $panne);
        $car = $repo->getRepository(Cars::class)->find($id);
        $condition_garantie = $car->getConditionGarantie();



        if($req->isMethod('POST'))
        {
            $form->handleRequest($req);

            if($form->isValid()){

                if(!$panne->getDateDebPanne())
                {
                    $panne->setDateDebPanne(new \DateTime());
                }
               // $this->addFlash('info', 'Oui oui, il est bien enregistré !');

                $test = $form->getData();


                $etat_car=$test->getEtatCar();

                if($etat_car == 'roulant_ano' || $etat_car == 'roulant ano')
                {
                    $panne->setNaturePanne($test->getNaturePanne());
                    $panne->setDescPanneAnoP($test->getDescPanne());
                    $panne->setAuteur($test->getAuteur());
                    $panne->setGarantie($test->getGarantie());

                    $car->setAuteur($test->getAuteur());
                    $car->setNaturePanneCar($test->getNaturePanne());
                    $car->setDescPanneAno($test->getDescPanne());
                    $car->setDescPanneCar($test->getDescPanne());
                    $car->setGarantie($test->getGarantie());

                    //Envoie de mail
                    $mess = (new \Swift_Message('Mouvparc : Panne ou Entretien'))
                        ->setFrom('info@caraps.fr', 'Mouv Parc')
                        ->setTo([
                            'mkanoute74@gmail.com'                          =>  'Mohamed Kanoute',
                            'kevin.perrillat@autocarspaysdesavoie.fr'       =>  'Kevin Perrillat',
                            'thierry.janeriat@autocarspaysdesavoie.fr'      =>  'Thierry Janeriat',
                            'guillaume-aps@outlook.fr'                      =>  'Guillaume Waquet',
                            'mounir.smirani@autocarspaysdesavoie.fr'        =>  'Mounir Smirani',
                            'cedric.coppin@autocarspaysdesavoie.fr'         =>  'Cedric Coppin'
                        ])
                        ->setBody(
                            $this->renderView(
                                'emails/panne.html.twig',
                                array(
                                    'immat'         => $car->getImmat(),
                                    'marque'        => $car->getMarque(),
                                    'etatCar'       => 'état roulant, avec des anomalies',
                                    'naturePanne'   => $test->getNaturePanne(),
                                    'descPanne'     => $test->getDescPanne(),
                                    'suiteDonne'    => $test->getSuitesDonnes(),
                                    'datedebPanne'  => $test->getDatePrev()
                                )),
                            'text/html'
                        );
                    $mailer->send($mess);

                }
                elseif ($etat_car == 'atelier')
                {
                    $panne->setCars($car);
                    $d_today = new \DateTime();
                    $d_prev = $test->getDatePrev();
                    $panne->setNaturePanne($test->getNaturePanne());
                    $panne->setAuteur($test->getAuteur());
                    $panne->setGarantie($test->getGarantie());

                    $date_prev = $test->getDatePrev();
                    $date_effective = $test->getDateEffective();

                    if($date_effective != null  && $date_prev != null)
                    {
                        // Durée de panne
                        $duree_panne_prev = date_diff($date_prev, $date_effective);
                        $duree_panne_prev = $duree_panne_prev->format('%d');

                        $panne->setDureePannePrev($duree_panne_prev);
                        $car->setDureePanne($duree_panne_prev);

                    }

                    if($d_today >= $d_prev)
                    {}
                        $car->setGarantie($test->getGarantie());
                        $car->setAuteur($test->getAuteur());
                        $car->setDateMaj(new \DateTime());
                        $car->setNaturePanneCar($test->getNaturePanne());
                        $car->setDescPanneCar($test->getDescPanne());
                        $car->setEtatCar($test->getEtatCar());

                        //Envoie de mail
                        $mess = (new \Swift_Message('Mouvparc : Panne ou Entretien'))
                                ->setFrom('info@caraps.fr', 'Mouv Parc')
                                ->setTo([
                                    'mkanoute74@gmail.com'                          =>  'Mohamed Kanoute',
                                    'kevin.perrillat@autocarspaysdesavoie.fr'       =>  'Kevin Perrillat',
                                    'thierry.janeriat@autocarspaysdesavoie.fr'      =>  'Thierry Janeriat',
                                    'guillaume-aps@outlook.fr'                      =>  'Guillaume Wacquet',
                                    'mounir.smirani@autocarspaysdesavoie.fr'        =>  'Mounir Smirani',
                                    'cedric.coppin@autocarspaysdesavoie.fr'         =>  'Cedric Coppin'
                                ])
                                ->setBody(
                                    $this->renderView(
                                    'emails/panne.html.twig',
                                    array(
                                        'immat'         => $car->getImmat(),
                                        'etatCar'       => $test->getEtatCar(),
                                        'naturePanne'   => $test->getNaturePanne(),
                                        'descPanne'     => $test->getDescPanne(),
                                        'suiteDonne'    => $test->getSuitesDonnes(),
                                        'datedebPanne'  => $test->getDatePrev(),
                                        'marque'        => $car->getMarque()
                                    )),
                                    'text/html'
                                );
                        $mailer->send($mess);

                    $em -> persist($car);
                    $em -> persist($panne);
                    $em -> flush();

                    return $this->redirectToRoute('liste_panne');

                }
                else if ($etat_car == 'panne')
                {
                    $panne->setCars($car);
                    $d_today = new \DateTime();
                    $d_prev = $test->getDatePrev();
                    $panne->setNaturePanne($test->getNaturePanne());
                    $panne->setAuteur($test->getAuteur());
                    $panne->setGarantie($test->getGarantie());

                    $date_prev = $test->getDatePrev();
                    $date_effective = $test->getDateEffective();

                    if($date_effective != null  && $date_prev != null)
                    {
                        // Durée de panne
                        $duree_panne_prev = date_diff($date_prev, $date_effective);
                        $duree_panne_prev = $duree_panne_prev->format('%d');

                        $panne->setDureePannePrev($duree_panne_prev);
                        $car->setDureePanne($duree_panne_prev);

                    }

                    if($d_today >= $d_prev)
                    {}
                    $car->setGarantie($test->getGarantie());
                    $car->setAuteur($test->getAuteur());
                    $car->setDateMaj(new \DateTime());
                    $car->setNaturePanneCar($test->getNaturePanne());
                    $car->setDescPanneCar($test->getDescPanne());
                    $car->setEtatCar($test->getEtatCar());


                    $em -> persist($car);
                    $em -> persist($panne);
                    $em -> flush();

                    $panne->setCars($car);
                    $d_today = new \DateTime();
                    $d_prev = $test->getDatePrev();
                    $panne->setNaturePanne($test->getNaturePanne());
                    $panne->setAuteur($test->getAuteur());
                    $panne->setGarantie($test->getGarantie());

                    $date_prev = $test->getDatePrev();
                    $date_effective = $test->getDateEffective();

                    if($date_effective != null  && $date_prev != null)
                    {
                        // Durée de panne
                        $duree_panne_prev = date_diff($date_prev, $date_effective);
                        $duree_panne_prev = $duree_panne_prev->format('%d');

                        $panne->setDureePannePrev($duree_panne_prev);
                        $car->setDureePanne($duree_panne_prev);

                    }

                    if($d_today >= $d_prev)
                    {}
                    $car->setGarantie($test->getGarantie());
                    $car->setAuteur($test->getAuteur());
                    $car->setDateMaj(new \DateTime());
                    $car->setNaturePanneCar($test->getNaturePanne());
                    $car->setDescPanneCar($test->getDescPanne());
                    $car->setEtatCar($test->getEtatCar());

                    //Envoie de mail
                    $mess = (new \Swift_Message('Mouvparc : Panne ou Entretien'))
                        ->setFrom('info@caraps.fr', 'Mouv Parc')
                        ->setTo([
                            'mkanoute74@gmail.com'                          =>  'Mohamed Kanoute',
                            'kevin.perrillat@autocarspaysdesavoie.fr'       =>  'Kevin Perrillat',
                            'thierry.janeriat@autocarspaysdesavoie.fr'      =>  'Thierry Janeriat',
                            'guillaume-aps@outlook.fr'                      =>  'Guillaume Wacquet',
                            'mounir.smirani@autocarspaysdesavoie.fr'        =>  'Mounir Smirani',
                            'cedric.coppin@autocarspaysdesavoie.fr'         =>  'Cedric Coppin'
                        ])
                        ->setBody(
                            $this->renderView(
                                'emails/panne.html.twig',
                                array(
                                    'immat'         => $car->getImmat(),
                                    'etatCar'       => $test->getEtatCar(),
                                    'naturePanne'   => $test->getNaturePanne(),
                                    'descPanne'     => $test->getDescPanne(),
                                    'suiteDonne'    => $test->getSuitesDonnes(),
                                    'datedebPanne'  => $test->getDatePrev(),
                                    'marque'        => $car->getMarque()
                                )),
                            'text/html'
                        );
                    $mailer->send($mess);


                    $em -> persist($car);
                    $em -> persist($panne);
                    $em -> flush();

                    return $this->redirectToRoute('liste_panne');
                }

                $panne->setCars($car);

                // recup la date de début
                $car->setDatePanneDeb(new \DateTime());
                $car->setAuteur($test->getAuteur());

                $date_prev = $test->getDatePrev();
                $date_effective = $test->getDateEffective();

                if( $date_effective != null  && $date_prev != null)
                {
                    // Durée de panne
                    $duree_panne_prev = date_diff($date_prev, $date_effective);
                    $duree_panne_prev = $duree_panne_prev->format('%d');

                    $panne->setDureePannePrev($duree_panne_prev);

                }

                //On ajoute une panne au compteur
                $c = (int) $car->getCompteurPanne();
                $car->setCompteurPanne($c++);

                //Met à jour la desc de la panne actuelle
                $desc_panne_car= $test->getDescPanne();
                $car->setDescPanneCar($desc_panne_car);
                $car->setNaturePanneCar($test->getNaturePanne());

                //Mettre à jour l'etat du car
                $car->setEtatCar($test->getEtatCar());

                //mettre à jour date de modif
                $car->setDateMaj(new \DateTime());


                $em -> persist($car);
                $em -> persist($panne);
                $em -> flush();

                return $this->redirectToRoute('consultation');
            }

        }


        return $this->render('front/edit.html.twig',
            array('car' => $car,
                  'form' => $form->createView(),
                'cond_garantie'=> $condition_garantie,
                )
            );

    }



    public function recherche(Request $req)
    {
        //get entity manager
        $em = $this->getDoctrine()->getManager();
        $car_trouver = 'rien';
        $form = $this->createFormBuilder()
                ->add('recherche', TextType::class, array(
                    'required' => false
                ))
                ->add('Rechercher', SubmitType::class)
                ->getForm();

        $repository = $this->getDoctrine()->getManager()->getRepository(Cars::class);

        $car_listing = $repository->findBy(array(), array('date' => 'DESC'));


        $form_avancee = $this->createFormBuilder()
            ->add('date', \Symfony\Component\Form\Extension\Core\Type\DateType::class, array(
                'widget' => 'single_text',
                'required'      =>  false,
                'html5'         =>  false,
                'format'        =>  'd/MM/yyyy'
            ))
            ->add('date_2', \Symfony\Component\Form\Extension\Core\Type\DateType::class, array(
                'widget' => 'single_text',
                'required'  =>  false,
                'format'    =>  'd/MM/yyyy'
            ))
            ->add('recherche_av', TextType::class, array(
                'required' => false
            ))
            ->add('marque', ChoiceType::class, array(
                'choices' => array(
                    'Sélectionner une marque'   => null,
                    'IVECO CROSSWAY'             => "iveco crossway",
                    'IVECO MAGELYS'              => "iveco magelys",
                    'MERCEDES TOURISMO'          => 'mercedes tourismo',
                    'IVECO DAILY'                => "iveco daily",
                    'VOLVO 9700HD'               => "volvo 9700 hd",
                    'SCANIA TOURING'             => "scania touring",
                    'IVECO RECREO'               => "iveco recreo",
                    'SOLARIS'                    => "solaris",
                    'BOVA'                       => "bova",
                    'IRIZAR I4'                  => "irizar i4",
                ),))
            ->add('Valider', SubmitType::class)
            ->getForm();
        //ajax 
        if ($req->isXmlHttpRequest()) { 
        }

         if($req->isMethod('POST') )
        {
            $form->handleRequest($req);

            $immat_car = $form->getData();
            if($immat_car['recherche'] == NULL )
            {
                $form_avancee->handleRequest($req);
                $form_av = $form_avancee->getData();

                if($form_av['date'] && $form_av['date_2'])
                {
                    $date_de = $form_av['date'];
                    $date_a = $form_av['date_2'];

                    if($form_av['recherche_av'] && $form_av['marque'] === null )
                    {
                        $mot_cles = $form_av['recherche_av'];
                        $query = $em->createQuery('SELECT p FROM App\Entity\Panne p WHERE p.nature_panne LIKE :nature')
                                    ->setParameter( 'nature','%'.$mot_cles.'%');

                        $car_trouver = $query->getResult();
                        $r = count($car_trouver);


                        $form = $this->createFormBuilder()
                            ->add('recherche', TextType::class)
                            ->add('Rechercher', SubmitType::class)
                            ->getForm();

                        $form_avancee = $this->createFormBuilder()
                            ->add('date', \Symfony\Component\Form\Extension\Core\Type\DateType::class, array(
                                'widget' => 'single_text',
                                'required'      =>  false,
                                'html5'         =>  false,
                                'format'        =>  'd/MM/yyyy'
                            ))
                            ->add('date_2', \Symfony\Component\Form\Extension\Core\Type\DateType::class, array(
                                'widget' => 'single_text',
                                'required'  =>  false,
                                'format'    =>  'd/MM/yyyy'
                            ))
                            ->add('recherche_av', TextType::class, array(
                                'required' => false
                            ))
                            ->add('marque', ChoiceType::class, array(
                                'choices' => array(
                                    'Sélectionner une marque'   => null,
                                    'IVECO CROSSWAY'             => "iveco crossway",
                                    'IVECO MAGELYS'              => "iveco magelys",
                                    'MERCEDES TOURISMO'          => 'mercedes tourismo',
                                    'IVECO DAILY'                => "iveco daily",
                                    'VOLVO 9700HD'               => "volvo 9700 hd",
                                    'SCANIA TOURING'             => "scania touring",
                                    'IVECO RECREO'               => "iveco recreo",
                                    'SOLARIS'                    => "solaris",
                                    'BOVA'                       => "bova",
                                    'IRIZAR I4'                  => "irizar i4",
                                ),))
                            ->add('Valider', SubmitType::class)
                            ->getForm();;



                        return $this->render('front/recherche.html.twig', array(
                            'form' => $form->createView(),
                            'form_avancee' => $form_avancee->createView(),
                            'car' => 'trouver',
                            'car_panne_listing'  => $car_trouver,
                            'plusieurs' => 'OK',
                        ));

                    }
                    elseif ($form_av['recherche_av'] && $form_av['marque'] != null)
                    {
                        $marque = $form_av['marque'];
                        $mot_cles = $form_av['recherche_av'];
                        //*$query = $em->createQuery('SELECT p FROM App\Entity\Panne p WHERE p.nature_panne LIKE :nature')
                        // ->setParameter( 'nature','%'.$marque.'%');


                        $query = $em->createQuery('SELECT p FROM App\Entity\Panne p JOIN App\Entity\Cars car WHERE car.marque = :marque AND p.nature_panne LIKE :mots AND p.date_prev BETWEEN :date1 AND :date2 ')
                            ->setParameter('marque', $marque)
                            ->setParameter('mots',   '%'.$mot_cles.'%')
                            ->setParameter('date1', $date_de)
                            ->setParameter('date2', $date_a)
                            //->setMaxResults(10)
                        ;

                        $car_trouver = $query->getResult();
                        $r = count($car_trouver);



                        for($i = 0 ; $i < $r ; $i++)
                        {
                            $marque_recu = strtolower($car_trouver[$i]->getCars()->getMarque());

                            if($marque == "iveco crossway") {
                                if ($marque == "iveco crossway" && $marque_recu == "iveco crossway" || $marque_recu == "iveco crossway ufr")
                                {}
                                else
                                {
                                    unset($car_trouver[$i]);
                                }
                            }else if($marque != $marque_recu)
                            {
                                unset($car_trouver[$i]);
                            }/**/


                        }
                        // $r2 = count($car_trouver);
                        $form = $this->createFormBuilder()
                            ->add('recherche', TextType::class)
                            ->add('Rechercher', SubmitType::class)
                            ->getForm();

                        $form_avancee = $this->createFormBuilder()
                            ->add('date', \Symfony\Component\Form\Extension\Core\Type\DateType::class, array(
                                'widget' => 'single_text',
                                'required'      =>  false,
                                'html5'         =>  false,
                                'format'        =>  'd/MM/yyyy'
                            ))
                            ->add('date_2', \Symfony\Component\Form\Extension\Core\Type\DateType::class, array(
                                'widget' => 'single_text',
                                'required'  =>  false,
                                'format'    =>  'd/MM/yyyy'
                            ))
                            ->add('recherche_av', TextType::class, array(
                                'required' => false
                            ))
                            ->add('marque', ChoiceType::class, array(
                                'choices' => array(
                                    'Sélectionner une marque'   => null,
                                    'IVECO CROSSWAY'             => "iveco crossway",
                                    'IVECO MAGELYS'              => "iveco magelys",
                                    'MERCEDES TOURISMO'          => 'mercedes tourismo',
                                    'IVECO DAILY'                => "iveco daily",
                                    'VOLVO 9700HD'               => "volvo 9700 hd",
                                    'SCANIA TOURING'             => "scania touring",
                                    'IVECO RECREO'               => "iveco recreo",
                                    'SOLARIS'                    => "solaris",
                                    'BOVA'                       => "bova",
                                    'IRIZAR I4'                  => "irizar i4",
                                ),))
                            ->add('Valider', SubmitType::class)
                            ->getForm();



                        return $this->render('front/recherche.html.twig', array(
                            'form' => $form->createView(),
                            'form_avancee' => $form_avancee->createView(),
                            'car' => 'trouver',
                            'car_panne_listing'  => $car_trouver,
                            'plusieurs' => 'OK',
                        ));
                    }
                    elseif ( $form_av['recherche_av'] === null && $form_av['marque'] !== null )
                    {
                        $marque = $form_av['marque'];
                        //*$query = $em->createQuery('SELECT p FROM App\Entity\Panne p WHERE p.nature_panne LIKE :nature')
                                   // ->setParameter( 'nature','%'.$marque.'%');

                        $query = $em->createQuery('SELECT p FROM App\Entity\Panne p JOIN App\Entity\Cars car WITH car.marque = :marque WHERE p.date_prev BETWEEN :date1 AND :date2 ')
                                    ->setParameter('marque', $marque)
                                    ->setParameter('date1', $date_de)
                                    ->setParameter('date2', $date_a)
                        ;

                        $car_trouver = $query->getResult();

                        $r = count($car_trouver);



                        for($i = 0 ; $i < $r ; $i++)
                        {
                             $marque_recu = strtolower($car_trouver[$i]->getCars()->getMarque());
                            if($marque != $marque_recu)
                            {
                                unset($car_trouver[$i]);
                            }

                        }
                        $form = $this->createFormBuilder()
                            ->add('recherche', TextType::class)
                            ->add('Rechercher', SubmitType::class)
                            ->getForm();

                        $form_avancee = $this->createFormBuilder()
                            ->add('date', \Symfony\Component\Form\Extension\Core\Type\DateType::class, array(
                                'widget' => 'single_text',
                                'required'      =>  false,
                                'html5'         =>  false,
                                'format'        =>  'd/MM/yyyy'
                            ))
                            ->add('date_2', \Symfony\Component\Form\Extension\Core\Type\DateType::class, array(
                                'widget' => 'single_text',
                                'required'  =>  false,
                                'format'    =>  'd/MM/yyyy'
                            ))
                            ->add('recherche_av', TextType::class, array(
                                'required' => false
                            ))
                            ->add('marque', ChoiceType::class, array(
                                'choices' => array(
                                    'Sélectionner une marque'   => null,
                                    'IVECO CROSSWAY'             => "iveco crossway",
                                    'IVECO MAGELYS'              => "iveco magelys",
                                    'MERCEDES TOURISMO'          => 'mercedes tourismo',
                                    'IVECO DAILY'                => "iveco daily",
                                    'VOLVO 9700HD'               => "volvo 9700 hd",
                                    'SCANIA TOURING'             => "scania touring",
                                    'IVECO RECREO'               => "iveco recreo",
                                    'SOLARIS'                    => "solaris",
                                    'BOVA'                       => "bova",
                                    'IRIZAR I4'                  => "irizar i4",
                                ),))
                            ->add('Valider', SubmitType::class)
                            ->getForm();;

                        return $this->render('front/recherche.html.twig', array(
                            'form' => $form->createView(),
                            'form_avancee' => $form_avancee->createView(),
                            'car' => 'trouver',
                            'car_panne_listing'  => $car_trouver,
                            'plusieurs' => 'OK',
                        ));
                    }
                    else
                        {
                        $date = $form_av['date'];
                        $date2 = $form_av['date_2'];

                        $query = $em->createQuery('SELECT p FROM App\Entity\Panne p WHERE p.date_prev BETWEEN :date1 AND :date2')
                            ->setParameter('date1', $date)
                            ->setParameter('date2', $date2);

                            $car_trouver = $query->getResult();
                            $r = count($car_trouver);


                            $form = $this->createFormBuilder()
                                ->add('recherche', TextType::class)
                                ->add('Rechercher', SubmitType::class)
                                ->getForm();

                            $form_avancee = $this->createFormBuilder()
                                ->add('date', \Symfony\Component\Form\Extension\Core\Type\DateType::class, array(
                                    'widget' => 'single_text',
                                    'required'      =>  false,
                                    'html5'         =>  false,
                                    'format'        =>  'd/MM/yyyy'
                                ))
                                ->add('date_2', \Symfony\Component\Form\Extension\Core\Type\DateType::class, array(
                                    'widget' => 'single_text',
                                    'required'  =>  false,
                                    'format'    =>  'd/MM/yyyy'
                                ))
                                ->add('recherche_av', TextType::class, array(
                                    'required' => false
                                ))
                                ->add('marque', ChoiceType::class, array(
                                    'choices' => array(
                                        'Sélectionner une marque'   => null,
                                        'IVECO CROSSWAY'             => "iveco crossway",
                                        'IVECO MAGELYS'              => "iveco magelys",
                                        'MERCEDES TOURISMO'          => 'mercedes tourismo',
                                        'IVECO DAILY'                => "iveco daily",
                                        'VOLVO 9700HD'               => "volvo 9700 hd",
                                        'SCANIA TOURING'             => "scania touring",
                                        'IVECO RECREO'               => "iveco recreo",
                                        'SOLARIS'                    => "solaris",
                                        'BOVA'                       => "bova",
                                        'IRIZAR I4'                  => "irizar i4",
                                    ),))
                                ->add('Valider', SubmitType::class)
                                ->getForm();;



                            return $this->render('front/recherche.html.twig', array(
                                'form' => $form->createView(),
                                'form_avancee' => $form_avancee->createView(),
                                'car' => 'trouver',
                                'car_panne_listing'  => $car_trouver,
                                'plusieurs' => 'OK',
                            ));
                    }

                }// endif $form_av['date'] && $form_av['date_2']
                elseif($form_av['recherche_av'] && $form_av['marque'])
                {
                    $mot_cles = $form_av['recherche_av'];
                    $marque = $form_av['marque'];

                    $query = $em->createQuery('SELECT p FROM App\Entity\Panne p JOIN App\Entity\Cars car WHERE car.marque = :marque AND p.nature_panne LIKE :mots ')
                        ->setParameter('marque', $marque)
                        ->setParameter('mots',   '%'.$mot_cles.'%');

                    $car_trouver = $query->getResult();
                    $r = count($car_trouver);


                    $form = $this->createFormBuilder()
                        ->add('recherche', TextType::class)
                        ->add('Rechercher', SubmitType::class)
                        ->getForm();

                    $form_avancee = $this->createFormBuilder()
                        ->add('date', \Symfony\Component\Form\Extension\Core\Type\DateType::class, array(
                            'widget' => 'single_text',
                            'required'      =>  false,
                            'html5'         =>  false,
                            'format'        =>  'd/MM/yyyy'
                        ))
                        ->add('date_2', \Symfony\Component\Form\Extension\Core\Type\DateType::class, array(
                            'widget' => 'single_text',
                            'required'  =>  false,
                            'format'    =>  'd/MM/yyyy'
                        ))
                        ->add('recherche_av', TextType::class, array(
                            'required' => false
                        ))
                        ->add('marque', ChoiceType::class, array(
                            'choices' => array(
                                'Sélectionner une marque'   => null,
                                'IVECO CROSSWAY'             => "iveco crossway",
                                'IVECO MAGELYS'              => "iveco magelys",
                                'MERCEDES TOURISMO'          => 'mercedes tourismo',
                                'IVECO DAILY'                => "iveco daily",
                                'VOLVO 9700HD'               => "volvo 9700 hd",
                                'SCANIA TOURING'             => "scania touring",
                                'IVECO RECREO'               => "iveco recreo",
                                'SOLARIS'                    => "solaris",
                                'BOVA'                       => "bova",
                                'IRIZAR I4'                  => "irizar i4",
                            ),))
                        ->add('Valider', SubmitType::class)
                        ->getForm();;



                    return $this->render('front/recherche.html.twig', array(
                        'form' => $form->createView(),
                        'form_avancee' => $form_avancee->createView(),
                        'car' => 'trouver',
                        'car_panne_listing'  => $car_trouver,
                        'plusieurs' => 'OK',
                    ));
                }
            }

            $imm = implode($immat_car);
            $car_trouver= $em->getRepository(Cars::class)->findOneByImmat(
            $imm
            );
           if(!$car_trouver)
           {
                $imm = strtolower($imm);
                $qb = $em->createQueryBuilder();

                $car_trouver = $qb->select('c')->from('App\Entity\Cars', 'c')
                  ->where( $qb->expr()->like('c.immat', $qb->expr()->literal('%' . $imm . '%')) )
                  ->getQuery()
                  ->getResult();

                $form = $this->createFormBuilder()
                        ->add('recherche', TextType::class)
                        ->add('Rechercher', SubmitType::class)
                        ->getForm();

               $form_avancee = $this->createFormBuilder()
                   ->add('date', \Symfony\Component\Form\Extension\Core\Type\DateType::class, array(
                       'widget' => 'single_text',
                       'required'      =>  false,
                       'html5'         =>  false,
                       'format'        =>  'd/MM/yyyy'
                   ))
                   ->add('date_2', \Symfony\Component\Form\Extension\Core\Type\DateType::class, array(
                       'widget' => 'single_text',
                       'required'  =>  false,
                       'format'    =>  'd/MM/yyyy'
                   ))
                   ->add('recherche_av', TextType::class, array(
                       'required' => false
                   ))
                   ->add('marque', ChoiceType::class, array(
                       'choices' => array(
                           'Sélectionner une marque'   => null,
                           'IVECO CROSSWAY'             => "iveco crossway",
                           'IVECO MAGELYS'              => "iveco magelys",
                           'MERCEDES TOURISMO'          => 'mercedes tourismo',
                           'IVECO DAILY'                => "iveco daily",
                           'VOLVO 9700HD'               => "volvo 9700 hd",
                           'SCANIA TOURING'             => "scania touring",
                           'IVECO RECREO'               => "iveco recreo",
                           'SOLARIS'                    => "solaris",
                           'BOVA'                       => "bova",
                           'IRIZAR I4'                  => "irizar i4",
                       ),))
                   ->add('Valider', SubmitType::class)
                   ->getForm();


                if(!$car_trouver)
                {
                    $car_trouver = $qb->select('a')->from('App\Entity\Cars', 'a')
                        ->where($qb->expr()->like('a.marque', $qb->expr()->literal('%'.$imm.'%')))
                        ->getQuery()
                        ->getResult();
                }

                return $this->render('front/recherche.html.twig', array(
                    'form' => $form->createView(),
                    'form_avancee' => $form_avancee->createView(),
                    'car' => 'trouver',
                    'car_listing'  => $car_trouver,
                    'plusieurs' => 'OK', 
                ));


            }

            $form = $this->createFormBuilder()
                    ->add('recherche', TextType::class)
                    ->add('Rechercher', SubmitType::class)
                    ->getForm()
            ;

            return $this->render('front/recherche.html.twig', array(
                'form' => $form->createView(),
                'car'  => $car_trouver,
                'plusieurs' => 'rien',
            )); 
        }//end if post


        //$repo = $em->getRepository(Cars::class)->find($id);
        return $this->render('front/recherche.html.twig', array(
            'form' => $form->createView(),
            'form_avancee' => $form_avancee->createView(),
            'car'  => $car_trouver,
            'car_listing' => $car_listing,
            'plusieurs' => 'debut',
        ));
    }

    public function listePanne(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        /*$car_en_atelier = $em->getRepository(Panne::class)->findBy(
          'eta'
        );*/

        $date_atelier = $em->getRepository(Panne::class)->findParDateAtelier("atelier");
        /*var_dump($s);
        die();*/

        $list_panne = $em->getRepository(Cars::class)->findBy(
          ['etat_car'  => 'panne' ],
          ['date_prev'       => 'DESC']
        );

        $list_atelier = $em->getRepository(Cars::class)->findBy(
           ['etat_car'  => 'atelier'],
           ['id'      => 'DESC']
        );/**/





        /* Vérifier la date
        $s = count($date_atelier);
        if($s > 0)
        {
            for($i=0 ; $i < $s ; $i++)
            {
                if($date_atelier[$i]->getCars()->getEtatCar() != "atelier")
                {
                    $date_atelier[$i]->getCars()->setEtatCar("atelier");
                    $nature=$date_atelier[$i]->getNaturePanne();
                    $desc=$date_atelier[$i]->getDescPanne();
                    $date_atelier[$i]->getCars()->setDateMaj(new \DateTime());
                    $date_atelier[$i]->getCars()->setNaturePanneCar($nature);
                    $date_atelier[$i]->getCars()->setDescPanneCar($desc);
                    $date_atelier[$i]->getCars()->setGarantie($date_atelier[$i]->getGarantie());
                    $date_atelier[$i]->getCars()->setDatePanneDeb($date_atelier[$i]->getDatePrev());
                }
            }
        }*/

        $list_panne_ano = $em->getRepository(Cars::class)->findBy(
          ['etat_car'   =>  'roulant_ano'],
          ['date_panne_deb'   =>  'DESC']
        );





        /*$list_panne_ano_p = $em->getRepository(Panne::class)->findBy(
           ['etat_car'  => 'roulant_ano'],
           ['id'        => 'DESC']
        );*/

        if(!$list_panne)
        {
            $list_panne = array();
        }

        if(!$list_panne_ano)
        {
            $list_panne_ano = array();
        }

        if(!$list_atelier)
        {
            $list_atelier = array();
        }


        if ($request->isMethod('POST'))
        {


        }


        //ajax
        if ($request->isXmlHttpRequest()) {
        }
        //var_dump($list_panne);

           $form = $this->createFormBuilder()
            ->add('etat_car', ChoiceType::class,array(
                'choices' => array(
                'NON' => "non",
                'OUI' => "oui",
                ),
                'choice_attr' => function($val, $key, $index) {
                    // adds a class like attending_yes, attending_no, etc
                    return ['class' => 'attending_'.strtolower($key)];
                },))
            ->add('enregistrer', SubmitType::class)
            ->getForm();


        /*$options = $form->get('id')->getConfig()->getOptions();
        $choices = $options['choice_list']->getChoices();*/




        return $this->render('front/listep.html.twig', array(
            'liste_panne'       =>      $list_panne,
            'liste_panne_ano'   =>      $list_panne_ano,
            'liste_atelier'     =>      $list_atelier,
            'form'              =>      $form->createView(),
        ));
    }


    public function editCar(Request $request, $id){
        $em = $this->getDoctrine()->getManager();

        $car = $em->getRepository(Cars::class)->find($id);
        $panne = new Panne($car);


        $etat_car  = strtolower($car->getEtatCar());


        //Edition d'un autocar
        $form = $this->get('form.factory')->create(CarType::class, $car);


        /*$form->get('etat_car')->setData($etat_car);
        $dd = $form->getData();
        var_dump($dd->getEtatCar());
        die();*/

        if(null === $form)
        {
            throw new NotFoundHttpException("Cet autocar n'existe pas.");
        }

        if($request->isMethod('POST') && $form->handleRequest($request)->isValid())
        {
            $rep = $form->getData();
            
            $car->setAuteur($rep->getAuteur());
            //$rep->setDate(new \DateTime());
            $car->setEtatCar($etat_car);

            $em->flush();

            return $this->redirectToRoute('car', array('id'=> $car->getId()));

        }
        /*var_dump($form);
        die();*/
        return $this->render('front/editcar.html.twig',
                array('car'         => $car,
                      'etat_car'    => $etat_car,
                      'form'        => $form->createView(),
                )
        );
    }


    public function deleteCar($id)
    {
        $em = $this->getDoctrine()->getManager();
        $car = $em->getRepository(Cars::class)->find($id);


        if(null === $car)
        {
            throw new NotFoundHttpException("Cet autocar n'existe pas !");
        }


        // On récupère le car avec ses pannes
        foreach ($car->getPannes()as $panne){
            $car->removePanne($panne);
        }

        foreach ($car->getImages() as $image){
            $car->removeImage($image);
        }

        foreach ($car->getCarrosserie() as $carroserie){
            $car->removeCarrosserie($carroserie);
        }

        $em->remove($car);
        $em->flush();
        return $this->redirectToRoute('consultation');
    }





    public function editPanne(Request $req, $id)
    {
        $em= $this->getDoctrine()->getManager();

        $car = $em->getRepository(Cars::class)->findOneBy(array('id' => $id));

        $etat_actu = $car->getEtatCar();
        $car_id = $car->getId();
        $c_garantie = $car->getConditionGarantie();


        $panne = new Panne($car);

        //$list= $em->getRepository(Panne::class)->getCarWithLastPanne($car->getId());
        $liste_panne = $em->getRepository(Panne::class)->findOneBy(
            ['cars'     => $car ],
            ['id'       => 'DESC']
        );

        $etat_panne = $liste_panne->getEtatCar();

        $liste_panne_anterieur = $em->getRepository(Panne::class)->findBy(
          ['cars'   =>  $car],
          ['id'   => 'DESC']
        );
       // $liste_panne= $em->getRepository(Panne::class)->findBy(array('cars' => $car));
        //$test =$liste_panne->getPannes();




        //Edition d'un autocar
        $form = $this->get('form.factory')->create(PanneType::class, $liste_panne);

        if ($req->isMethod('POST'))
        {
            $form->handleRequest($req);
            if($form->isValid())
            {
                $test = $form->getData();

                $etat_car = $test->getEtatCar();

                if($etat_car == 'panne')
                {
                    $car->setEtatCar($etat_car);
                    $car->setDateMaj(new \DateTime());
                    $car->setAuteur($test->getAuteur());
                    $car->setGarantie($test->getGarantie());

                    $panne->setEtatCar($etat_car);
                    $panne->setAuteur($test->getAuteur());
                    $panne->setDatePrev($test->getDatePrev());
                    $panne->setDescPanne($test->getDescPanne());
                    $panne->setSuitesDonnes($test->getSuitesDonnes());
                    $panne->setGarantie($test->getGarantie());

                    $car->setNaturePanneCar($test->getNaturePanne());
                    $car->setDatePanneDeb($test->getDatePrev());
                    $car->setDescPanneCar($test->getDescPanne());
                    $idcar = $car->getId();

                    $panne->setCars($car);

                    if($test->getDateEffective())
                    {
                        //Date_ <-> DateFin de panne
                        $car->setDateFinPanne($test->getDateEffective());
                        $panne->setDateEffective($test->getDateEffective());
                    }

                    /*$em->persist($car);
                    $em->persist($panne);*/
                    $em->flush();

                    return $this->redirectToRoute('liste_panne');



                }
                elseif ($etat_car == 'roulant ano' || $etat_car  == 'roulant_ano' && $etat_actu == 'roulant_ano')
                {
                    //Roulant avec anomalie écrase panne

                    $car->setEtatCar($etat_actu);
                    $car->setDateMaj(new \DateTime());
                    $car->setGarantie($test->getGarantie());



                    $panne->setEtatCar($etat_car);
                    $panne->setAuteur($test->getAuteur());
                    $panne->setDatePrev($test->getDatePrev());
                    $panne->setGarantie($test->getGarantie());

                    $car->setNaturePanneCar($test->getNaturePanne());
                    $panne->setNaturePanne($test->getNaturePanne());
                    $car->setAuteur($test->getAuteur());
                    $car->setDatePanneDeb($test->getDatePrev());

                   // $car->setPannes($panne);
                    $panne->setCars($car);


                    //Desc panne ano dans l'entitè Car
                    $car->setDescPanneAno($test->getDescPanne());

                    $panne->setDescPanneAnoP($test->getDescPanne());
                    //$car->setDescPanneAno($test->getDescPanne());


                    if($test->getDateEffective())
                    {
                        //Date_ <-> DateFin de panne
                        $car->setDateFinPanne($test->getDateEffective());
                        $panne->setDateEffective($test->getDateEffective());

                        if ($test->getDatePrev() && $test->getDateEffective())
                        {
                            $deb_panne = $test->getDatePrev();
                            $fin_panne = $test->getDateEffective();
                            $duree_panne = date_diff($deb_panne, $fin_panne);

                            $duree_panne = $duree_panne->format('%d');

                            $panne->setDureePannePrev($duree_panne);
                        }

                    }

                    $id_car = $car->getId();

                    /*$em->persist($car);
                    $em->persist($panne);*/
                    $em->flush();


                    return $this->redirectToRoute('liste_panne');
                }
                elseif ($etat_car == 'roulant ano' || $etat_car  == 'roulant_ano' && $etat_actu != 'roulant_ano')
                {
                    //Roulant avec anomalie écrase panne

                    $car->setEtatCar($etat_car);
                    $car->setDateMaj(new \DateTime());
                    $car->setGarantie($test->getGarantie());



                    $panne->setEtatCar($etat_car);
                    $panne->setAuteur($test->getAuteur());
                    $panne->setDatePrev($test->getDatePrev());
                    $panne->setGarantie($test->getGarantie());

                    $car->setNaturePanneCar($test->getNaturePanne());
                    $panne->setNaturePanne($test->getNaturePanne());
                    $car->setAuteur($test->getAuteur());
                    $car->setDatePanneDeb($test->getDatePrev());

                    // $car->setPannes($panne);
                    $panne->setCars($car);


                    //Desc panne ano dans l'entitè Car
                    $car->setDescPanneAno($test->getDescPanne());

                    $panne->setDescPanneAnoP($test->getDescPanne());
                    //$car->setDescPanneAno($test->getDescPanne());


                    if($test->getDateEffective())
                    {
                        //Date_ <-> DateFin de panne
                        $car->setDateFinPanne($test->getDateEffective());
                        $panne->setDateEffective($test->getDateEffective());

                        if ($test->getDatePrev() && $test->getDateEffective())
                        {
                            $deb_panne = $test->getDatePrev();
                            $fin_panne = $test->getDateEffective();
                            $duree_panne = date_diff($deb_panne, $fin_panne);

                            $duree_panne = $duree_panne->format('%d');

                            $panne->setDureePannePrev($duree_panne);
                        }

                    }

                    $id_car = $car->getId();

                    /*$em->persist($car);
                    $em->persist($panne);*/
                    $em->flush();


                    return $this->redirectToRoute('liste_panne');
                }
                elseif ($etat_car == 'panne' && $etat_actu == 'panne')
                {
                    $panne->setCars($car);
                    $p = $em->getRepository(Panne::class)->find($test->getId());
                    $car->setNaturePanneCar($test->getNaturePanne());
                    $car->setGarantie($test->getGarantie());
                    $panne->setGarantie($test->getGarantie());

                    $em->persist($p);
                    $em->flush();

                   return $this->redirectToRoute('consultation');
                    //modifie la panne en cour

                }
                elseif ($etat_car == 'atelier')
                {
                    $car->setEtatCar($etat_car);
                    $car->setDateMaj(new \DateTime());
                    $car->setAuteur($test->getAuteur());
                    $car->setGarantie($test->getGarantie());

                    $panne->setEtatCar($etat_car);
                    $panne->setAuteur($test->getAuteur());
                    $panne->setDatePrev($test->getDatePrev());
                    $panne->setDescPanne($test->getDescPanne());
                    $panne->setSuitesDonnes($test->getSuitesDonnes());
                    $panne->setGarantie($test->getGarantie());

                    $car->setNaturePanneCar($test->getNaturePanne());
                    $car->setDatePanneDeb($test->getDatePrev());
                    $car->setDescPanneCar($test->getDescPanne());
                    $car->setDescPanneCar($test->getDescPanne());
                    $idcar = $car->getId();

                    $panne->setCars($car);

                    if($test->getDateEffective())
                    {
                        //Date_ <-> DateFin de panne
                        $car->setDateFinPanne($test->getDateEffective());
                        $panne->setDateEffective($test->getDateEffective());
                    }

                    /*$em->persist($car);
                    $em->persist($panne);*/
                    $em->flush();

                    return $this->redirectToRoute('liste_panne');
                }
                elseif ($etat_car == 'roulant')
                {



                    $liste_panne->setEtatCar($etat_actu);
                    /*$panne->setAuteur($test->getAuteur());

                    $panne->setDatePrev($test->getDatePrev());
                    $panne->setDateEffective($test->getDateEffective());
                    $panne->setDescPanne($test->getDescPanne());
                    $panne->setSuitesDonnes($test->getSuitesDonnes());
                    //$car->setPannes($panne);
                    $panne->setCars($car);*/


                    $car->setEtatCar($etat_car);
                    $car->setDescPanneCar($test->getDescPanne());
                    $car->setDateMaj(new \DateTime());
                    $car->setAuteur($test->getAuteur());
                    $car->setDateFinPanne($test->getDateEffective());

                    $date_prev = $test->getDatePrev();
                    $date_effective = $test->getDateEffective();



                    if( $date_effective != null  && $date_prev != null)
                    {
                        // Durée de panne
                        $duree_panne_prev = date_diff($date_prev, $date_effective);
                        $duree_panne_prev = $duree_panne_prev->format('%d');
                        $liste_panne->setDureePannePrev($duree_panne_prev);
                        $car->setDureePanne($duree_panne_prev);
                    }
                    /*$em->persist($car);*/

                    $em->flush();

                    return $this->redirectToRoute('car', array('id' => $id));

                }
            }
        }


        return $this->render('front/edit-panne.html.twig',
            array(
            'car_panne'             => $car,
            'liste_panne'           => $liste_panne,
            'liste_panne_anterieur' => $liste_panne_anterieur,
            'c_garantie'            => $c_garantie,
            'form'                  => $form->createView()
            ));
    }

    public function EditpanneAnt(Request $req, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $panne = $em->getRepository(Panne::class)->find($id);

         $car_id =$panne->getCars();
         $id_car = $car_id->getId();



        //Edition d'un autocar
        $form = $this->get('form.factory')->create(PanneType::class, $panne);


        if ($req->isMethod('POST') && $form->handleRequest($req)->isValid())
        {

            $res = $form->getData();
           // $panne->setCars()
            //var_dump($res->getEtatCar());
            //die();
            $panne->setEtatCar($res->getEtatCar());
            $panne->setDescPanne($res->getDescPanne());
            $panne->setSuitesDonnes($res->getSuitesDonnes());
            $panne->setnaturePanne($res->getNaturePanne());
            //var_dump($res->getDatePrev());
            //die();

            if ($res->getDatePrev() && $res->getDateEffective())
            {
                 $deb_panne = $res->getDatePrev();
                 $fin_panne = $res->getDateEffective();
                $duree_panne = date_diff($deb_panne, $fin_panne);

                $duree_panne = $duree_panne->format('%d');

                $panne->setDureePannePrev($duree_panne);
            }

            $em->flush();
            return $this->redirectToRoute('car',
                array(
                 'id' => $id_car
                ));
        }

        return $this->render('front/edit-panne-ant.html.twig',
            array(
              'panne'       => $panne,
              'form'        => $form->createView(),
            )
            );
    }

    public function deletePanne($id)
    {
        $em = $this->getDoctrine()->getManager();
        $panne = $em->getRepository(Panne::class)->findOneBy(array(
            'id'=> $id
        ));

        //recupere le car associe
        $car = $panne->getCars()->getId();


        $em->remove($panne);
        $em->flush();

        return $this->redirectToRoute('car', array('id' =>$car));


    }

    public function changeEtatCar($id)
    {
        $em = $this->getDoctrine()->getManager();
        $car = $em->getRepository(Cars::class)->findOneBy(array('id' => $id));

        $last_panne = $em->getRepository(Panne::class)->findOneBy(
            ['cars'  => $car ],
            ['id'   => 'DESC']
        );



    }


    public function mevCar(Request $request, $id)
    {
        $em= $this->getDoctrine()->getManager();
        $car = $em->getRepository(Cars::class)->find($id);
        //Obliger de set par défaut marque
        $marque = $car->getMarque();


        // Hydrate le form
        $form = $this->get('form.factory')->create(CarType::class, $car);

        $form
            ->add('regulateur_vitesse', CheckboxType::class, array(
                'required'  => false,
            ))
            ->add('ralentisseur', ChoiceType::class, array(
                'required'  => false,
                "choices" => array(
                "Voith"         => "voith",
                "Intarder"      => "intarder",
                "Telma"         => "telma"
            )))
            ->add('km', NumberType::class)
            ->add('gps', CheckboxType::class, array(
                'required'  => false,
            ))
            ->add('abs', CheckboxType::class, array(
                'required'  => false,
            ))
            ->add('esp', CheckboxType::class, array(
                'required'  => false,
            ))
            ->add('asr', CheckboxType::class, array(
                'required'  => false,
            ))
            ->add('ceinture_securite', ChoiceType::class, array(
                'required'  => false,
                "choices" => array(
                "2 points" => "2 points",
                "3 points" => "3 points"
            )))
            ->add('repose_mollet', CheckboxType::class, array(
                'required'  => false,
            ))
            ->add('tachygraphe', ChoiceType::class, array(
                'required'  => false,
                "choices" => array(
                "Numérique"     => "numérique",
                "Analogique"    =>  "analogique"
            )))
            ->add('video', ChoiceType::class, array(
                'required'  => false,
                "choices" => array(
                "DVD"     => "DVD",
                "VHS"    =>  "VHS"
            )))
            ->add('radio', ChoiceType::class, array(
                'required'  => false,
                "choices" => array(
                    "Cassettes"     => "cassettes",
                    "CD"    =>  "CD"
                )))
            ->add('camera', ChoiceType::class, array(
                'required'  => false,
                "choices" => array(
                "Recul"     => "Recul",
                "Route"    =>  "Route",
                "Guide"    =>  "Guide",
                "Porte N2"    =>  "Porte N2",
            )))
            ->add('micro_conducteur', CheckboxType::class, array(
                'required'  => false,
            ))
            ->add('micro_guide', CheckboxType::class, array(
                'required'  => false,
            ))
            ->add('chauffage_independant', CheckboxType::class, array(
                'required'  => false,
            ))
            ->add('buses_individuelles', CheckboxType::class, array(
                'required'  => false,
            ))
            ->add('tablettes', CheckboxType::class, array(
                'required'  => false,
            ))
            ->add('sieges_decalables', CheckboxType::class, array(
                'required'  => false,
            ))
            ->add('frigo', CheckboxType::class, array(
                'required'  => false,
            ))
            ->add('girouette', CheckboxType::class, array(
                'required'  => false,
            ))
            ->add('rideaux', CheckboxType::class, array(
                'required'  => false,
            ))

            ->add('puissance', NumberType::class)
            ->add('energie', ChoiceType::class, array(
                'required'  => false,
                "choices" => array(
                    "Diesel"        => "Diesel",
                    "Electric"      =>  "électric",
                    "Essence"       =>  "Essence",
                    "GNV"           =>  "gnv",
                    "GPL"           =>  "gpl",
                    "Hybride"       =>  "hybride"
                )))
            ->add('transmission', ChoiceType::class, array(
                'required'  => false,
                "choices" => array(
                    "Boite mécanique"        => "boite mécanique",
                    "Boite automatique"      =>  "boite automatique",
                    "Boite robotisée"       =>  "boite robotisée",
                )))
            ->add('bv', TextType::class)
            ->add('clim', CheckboxType::class, array(
                'required'  => false,
            ))
            ->add('longueur', NumberType::class, array(
                'grouping'  => true,
            ))
            ->add('hauteur', NumberType::class, array(
                'grouping'  => true,
            ))
            ->add('accessibilite', ChoiceType::class, array(
                'required'  => false,
                "choices" => array(
                    "Prédisposé UFR"        => "prédisposé ufr",
                    "Rampe manuelle"        =>  "rampe manuelle",
                    "Ascenseur"             =>  "ascenseur",
                    "Rampe électrique"    =>  "rampe électrique",
                )))
            ->add('prix', NumberType::class, array(
                'required' => false
            ))

        ;

        $form->handleRequest($request);

        if($request->isXmlHttpRequest())
        {
            $delUrl =  $request->get('del');
            
            $delUrl =substr($delUrl, 6);
            $filename = substr($delUrl, 10);

            $image2 = $em->getRepository(Image::class)->findOneBy(array('url'=> $delUrl));
            $image2->deleteFiles($filename);
            $em->remove($image2);
            $em->flush();
            return new JsonResponse(array('success' => true));
        }



        if ($request->isMethod("POST"))
        {
           /*$ralentisseur = $request->request->get("car")["ralentisseur"];

            }*/
           $car->setMarque($marque);
           $em->persist($car);
           $em->flush();
           return $this->redirectToRoute('annonce_car', array('id'=> $car->getId()));
        }

        return $this->render('front/mev.html.twig', array(
            'form'  => $form->createView(),
            'id'    => $car->getId(),
            'car'   => $car
        ));
    }

    public function getUploadDir()
    {
        return 'photosCar';
    }

    protected function getUploadRootDir()
    {
        return __DIR__.'/../../public/'.$this->getUploadDir();
    }


    public function annonceCar(Request $request, $id, \Swift_Mailer $mailer, AuthorizationCheckerInterface $authorizationChecker)
    {

        //Voir si nous sommmes en local ou non
        $varDirectory = __DIR__;
        $varDirectory = substr($varDirectory, 0, 6);



        $em = $this->getDoctrine()->getManager();
        $car = $em->getRepository(Cars::class)->find($id);
        if (null === $car) {
            throw new NotFoundHttpException("L'annonce de cet autocar n'existe pas.");
        }


        $carPrix = $car->getPrix();

        /*if(false === $authorizationChecker->isGranted('ROLE_USER') && $carPrix === null)
        {
            throw new AccessDeniedException('VOUS n\'êtes pas autorisé a accéder a cette page ! ');
        }*/




        $data= array();
        $form =$this->createFormBuilder($data)
                ->add('mailclients', CollectionType::class, array(
                    'entry_type'        => EmailType::class,
                    'allow_add'         => true,
                    'entry_options'     => array('label'  => false)

                ))
                ->add('memo', TextareaType::class, array(
                    'required' => false,
                ))
                ->add('envoyer', SubmitType::class)
                ->getForm()
        ;
        if ($authorizationChecker->isGranted('IS_AUTHENTICATED_ANONYMOUSLY'))
        {
            if ($this->container->has('profiler'))
            {
                $this->container->get('profiler')->disable();
            }
        }
        else
        {
            if ($this->container->has('profiler'))
            {
                $this->container->get('profiler')->enable();
            }
        }


        if($request->isMethod("POST"))
        {
            $form->handleRequest($request);

            // $data is a simply array with your form fields
            // like "query" and "category" as defined above.


            $data = $form->getData();
            $nbMails = count($data['mailclients']);

            $memo = '';

            $memo = $data['memo'];



            if($nbMails < 2) {
                $mailClient = $data['mailclients'][0];
                $NomClient = explode("@", $mailClient);
                $nomC = $NomClient[0];

                if (filter_var($mailClient, FILTER_VALIDATE_EMAIL)) {
                    //  $this->returnPDFResponseFROMHtml($car->getId());
                    if($varDirectory == "C:\\wam")
                    {
                        $carID = 'http://localhost:8000/admin/annoncecar/' . $car->getId();
                    }
                    else
                    {
                        $carID = 'http://caraps.fr/admin/annoncecar/'.$car->getId();
                    }
                    //$carID = 'http://caraps.fr/admin/annoncecar/'.$car->getId();

                    $dateMar = $car->getDateMar();
                    $dateSend = date_format($dateMar, 'Y');
                    $mess = (new \Swift_Message('Vente autocar '.$car->getMarque().', '. $car->getPrix().'€, '.$dateSend))
                        ->setFrom('info@autocarspaysdesavoie.fr', 'Autocars Pays de Savoie')
                        ->setTo($mailClient)
                        ->setBody(
                            $this->renderView(
                                'emails/mev.html.twig',
                                array(
                                    'marque' => $car->getMarque(),
                                    'euro' => $car->getEuro(),
                                    'puissance' => $car->getPuissance(),
                                    'energie' => $car->getEnergie(),
                                    'transmission' => $car->getTransmission(),
                                    'bv' => $car->getBv(),
                                    'longueur' => $car->getLongueur(),
                                    'hauteur' => $car->getHauteur(),
                                    'nbPlaces' => $car->getNbPlaces(),
                                    'accessibilite' => $car->getAccessibilite(),
                                    'urlAnnonce' => $carID,
                                    'mail' => $mailClient,
                                    'memo' => $memo
                                )),
                            'text/html'
                        )//->attach(\Swift_Attachment::fromPath('/uploads/annonce'.$id.'.pdf'));
                    ;
                    $mailer->send($mess);
                }

            }
            else
            {
                //On modifie le format de la date pour envoyer par mail
                $dateMar = $car->getDateMar();
                $dateSend = date_format($dateMar, 'Y');
                $mess = (new \Swift_Message('Vente autocar '.$car->getMarque().', '. $car->getPrix().'€, '.$dateSend))
                    ->setFrom('info@caraps.fr', 'Autocars Pays de Savoie');

                for ($i = 0 ; $i < $nbMails; $i++)
                {
                    $mailClient = $data['mailclients'][$i];
                    $NomClient = explode("@", $mailClient);
                    $nomC = $NomClient[0];

                    if (filter_var($mailClient, FILTER_VALIDATE_EMAIL)) {
                       // $addresses[$i] = array($mailClient => $nomC);
                        $mess->addTo($mailClient);
                    }


                }

                //  $this->returnPDFResponseFROMHtml($car->getId());
                if($varDirectory == "C:\\wam")
                {
                    $carID = 'http://localhost:8000/admin/annoncecar/' . $car->getId();
                }
                else
                {
                    $carID = 'http://caraps.fr/admin/annoncecar/'.$car->getId();
                }
                //$carID = 'http://caraps.fr/admin/annoncecar/'.$car->getId();

                $mess->setBody(
                        $this->renderView(
                            'emails/mev.html.twig',
                            array(
                                'marque' => $car->getMarque(),
                                'euro' => $car->getEuro(),
                                'puissance' => $car->getPuissance(),
                                'energie' => $car->getEnergie(),
                                'transmission' => $car->getTransmission(),
                                'bv' => $car->getBv(),
                                'longueur' => $car->getLongueur(),
                                'hauteur' => $car->getHauteur(),
                                'nbPlaces' => $car->getNbPlaces(),
                                'accessibilite' => $car->getAccessibilite(),
                                'urlAnnonce' => $carID,
                                'mail' => $mailClient,
                                'memo' => $memo
                            )),
                        'text/html'
                    )//->attach(\Swift_Attachment::fromPath('/uploads/annonce'.$id.'.pdf'));
                ;
                $mailer->send($mess);
            }


            $data= array();
            $form =$this->createFormBuilder($data)
                ->add('mailclients', CollectionType::class, array(
                    'entry_type'        => EmailType::class,
                    'allow_add'         => true,
                    'entry_options'     => array('label'  => false)

                ))
                ->add('memo', TextareaType::class, array(
                    'required' => false,
                ))
                ->add('envoyer', SubmitType::class)
                ->getForm()
            ;

            $this->addFlash("success", "This is a success message");

            return $this->render('front/annonceCar.html.twig',
                array(
                    "car"    => $car,
                    "form"   => $form->createView(),
                )
            );

        }

        return $this->render('front/annonceCar.html.twig',
               array(
                   "car"    => $car,
                   "form"   => $form->createView()
               )
        );

    }

    /*Generate pdf
    public function returnPDFResponseFROMHtml($id)
    {
        $em = $this->getDoctrine()->getManager();
        $car = $em->getRepository(Cars::class)->find($id);
       // $form = $this->createForm(CarsType::class, $trajet);



        $pdf = $this->container->get(HTML2PDF::class);
        $template = $this->renderView('emails/annoncecarMail.html.twig',
            array(
                'car'=> $car
            )
        );
        // $car_images = count($car->getImages());


        $pdf->create('P', 'A4', 'fr', true, 'UTF-8', array(10, 15, 1, 15), $template, 'annonce'.$id);


        return $this->redirectToRoute('annonce_car',
            array(
                'id'      => $id,
            )
        );
    }*/


    public function ajaxSnippetImageSend(Request $request, $id)
    {
        //on vérifie d'ou vient la requete
        $routeName = $request->get('_route');

        $em = $this->getDoctrine()->getManager();
        $car = $em->getRepository(Cars::class)->find($id);
        $carrosserie = new Carrosserie();

        $images = new Image();
        $media = $request->files->get('file');
        if($routeName != "carrosserie_car")
        {
            $sizeImage = $media->getClientSize();
            $extensionImage = $media->guessExtension();

            $photoName = $this->generateUniqueFileName().'.'.$extensionImage;
            //set Name photo
            $images->setUrl($this->getUploadDir().'/'.$photoName);
            if($extensionImage == "pdf" || $extensionImage == "PDF")
            {
                $images->setAlt("Annexe_pdf".$car->getId());
            }
            else
            {
                $images->setAlt("Photo du Car : ". $car->getId());
            }
            //On lie l'image au car
            $images->setCar($car);

        }

        $images->setFile($media);
        $images->setPath($media->getPathName());
        //$images->setName($media->getClientOriginalName());
        $images->upload();
        $em->persist($images);
        $em->flush();

        //infos sur le document envoyé
        //var_dump($request->files->get('file'));die;
        return new JsonResponse(array('success' => true));
    }

    public function ajaxSnippetImageCarrosserie(Request $request, $id)
    {
        //on vérifie d'ou vient la requete
        $routeName = $request->get('_route');

        $em = $this->getDoctrine()->getManager();
        $car = $em->getRepository(Cars::class)->find($id);

        $images = new Image();
        $media = $request->files->get('file');
        //Carrosserie
        $sizeImage = $media->getClientSize();
        $extensionImage = $media->guessExtension();

        $photoName = $this->generateUniqueFileName().'.'.$extensionImage;


        //set Name photo
        $images->setUrl($this->getUploadDir().'/carro_'.$photoName);
        if($extensionImage == "pdf" || $extensionImage == "PDF")
        {
            $images->setAlt("Annexe_pdf".$car->getId());
        }
        else
        {
            $images->setAlt("Photo accrochage : ". $car->getId());
        }
        //On lie l'image  à la carrosserie($car);
        //$images->setCarrosserie($carrosserie);

        $images->setCar($car);
        $images->setFile($media);
        $images->setPath($media->getPathName());
        //$images->setName($media->getClientOriginalName());
        $images->upload();
        $em->persist($images);
        $em->flush();

        //infos sur le document envoyé
        //var_dump($request->files->get('file'));die;
        return new JsonResponse(array('success' => true));
    }


    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('consultation');
        }
        return $this->render(
          'front/register.html.twig',
          array('form'=> $form->createView())
        );
    }

    public function login(Request $request, AuthenticationUtils $authUtils)
    {
        if ($this->container->has('profiler'))
        {
            $this->container->get('profiler')->disable();
        }

        // get the login error if there is one
        $error = $authUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authUtils->getLastUsername();

        return $this->render('front/login.html.twig', array(
            'last_username' => $lastUsername,
            'error'         => $error,
        ));
    }


    public function logout()
    {
        $this->get('security.token_storage')->setToken(NULL);
        $response = new Response();
        //$response->headers->setCookie(new Cookie('REMEMBERME', 'true', 0, '/', null, false, false));
        $response->headers->clearCookie('REMEMBERME');

        $response->send();
        return $this->redirectToRoute('consultation');
    }


    public function addRubrique(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $rub_rec = $em->getRepository(Rubrique::class)->findAll();
        $rub = new Rubrique();

        $form = $this->createForm(RubriqueType::class, $rub);


        if ($request->isMethod('POST'))
        {
                $form->handleRequest($request);
                $em->persist($rub);
                $em->flush();
                return $this->redirectToRoute('add_rubrique');
        }

        return $this->render('front/rubrique.html.twig', array(
                'form'  => $form->createView(),
                'rub'   => $rub_rec,
            ));


    }

    public function editRubrique(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $rub = $em->getRepository(Rubrique::class)->find($id);

        $form = $this->get('form.factory')->create(RubriqueType::class, $rub);

        if ($request->isMethod('POST'))
        {
            $form->handleRequest($request);
            $em->flush();
            return $this->redirectToRoute('add_rubrique');

        }

        return $this->render('front/edit-rubrique.html.twig',array(
           'form'   => $form->createView()
        ));
    }

    public function deleteRubrique(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $rub = $em->getRepository(Rubrique::class)->find($id);
        $em->remove($rub);
        $em->flush();
        return $this->redirectToRoute('add_rubrique');

    }



    public function donneesKilometriques(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $dql = "SELECT c.idTruck, c.immat FROM App\Entity\Cars c WHERE c.idTruck is not null";
        $queryC = $em->createQuery($dql);
        $listeCars = $queryC->getResult();
        $ti = time();
        //Date de récupération chaque mois le premier
        $dateDuPremier = date('c',mktime(23,59,0,date('m'),1,date('y')));
        $dateDuPremier = substr($dateDuPremier, 0, -6);




        $nbCars = count($listeCars);

        $chaineImmat =implode('|', array_map(function ($entry) {
            return $entry['idTruck'];
        }, $listeCars));


        //Recherche km 1 de chaque mois via truckonline
        //gpstracking?vehicle_uids=*&date=2018-06-01T21:59:00Z&count=1
        /**/$hache = base64_encode(hash_hmac("SHA1", "apa-aps-t39-c1ws.truckonline.proGET/apis/rest/v2.2/gpstracking?vehicles_vrn=*&date=".$dateDuPremier."Z&count=1".$ti."", "5a35101a-62ae-4cba-b70a-b1efd5cd75f0", true));
        $opts = array(
            'http' => array(
                'method'=>'GET',
                'header' => "x-tonl-client-id:  apa-aps-t39-c1\r\n".
                    "x-tonl-timestamp:  ".$ti."\r\n".
                    "x-tonl-signature: ".$hache.""
            )
        );
        // Recherche api truck online tous les vehicules
        $context = stream_context_create($opts);
        $kil = file_get_contents("https://ws.truckonline.pro/apis/rest/v2.2/gpstracking?vehicles_vrn=*&date=".$dateDuPremier."Z&count=1", false, $context);
        $result = json_decode($kil, true);



        //$nbResult = count($result);
        /*var_dump($result);
        die();*/

        /*$hache = base64_encode(hash_hmac("SHA1", "apa-aps-t39-c1ws.truckonline.proGET/apis/rest/v2.2/gpstracking?vehicle_vrn=DL-193-TZ&date=".$dateDuPremier."Z&count=1".$ti."", "5a35101a-62ae-4cba-b70a-b1efd5cd75f0", true));
        $opts = array(
            'http' => array(
                'method'=>'GET',
                'header' => "x-tonl-client-id:  apa-aps-t39-c1\r\n".
                    "x-tonl-timestamp:  ".$ti."\r\n".
                    "x-tonl-signature: ".$hache.""
            )
        );
        // Recherche api truck online tous les vehicules
        $context = stream_context_create($opts);
        $kil = file_get_contents("https://ws.truckonline.pro/apis/rest/v2.2/gpstracking?vehicle_vrn=DL-193-TZ&date=".$dateDuPremier."Z&count=1", false, $context);
        $result = json_decode($kil, true);*/

        $nbResult = count($result);


        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        /*var_dump($result[1]["gpsInfo"]["totalKm"]);
        die();*/

        $kmManquants = array();
        $compteurExcel = 1;

        //Associer les uids au immats
        for ($i = 0; $i < $nbResult; $i++)
        {
            $testUid =array_search($result[$i]["vehicleUid"], array_column($listeCars, 'idTruck'));


                if( $testUid !== false)
                {
                    $totalKm = $result[$i]["gpsInfo"]["totalKm"];
                    $immat = $listeCars[$testUid]["immat"];
                    $sheet->setCellValueByColumnAndRow(1, $compteurExcel, $immat);
                    $sheet->setCellValueByColumnAndRow(2, $compteurExcel, $totalKm);
                    //Enregistrement en BDD
                    /* $CarsT = $em->getRepository(Cars::class)->findOneBy(array(
                        "immat" => $immat
                    ));
                    $CarsT->setKm($totalKm);
                    $em->persist($CarsT);*/
                    $compteurExcel = $compteurExcel+1;
                }



            /*for($j=0; $j < $nbCars; $j++)
            {

                $idTruckBdd = $listeCars[$j]["idTruck"];

                if($result[$i]["vehicleUid"] == $idTruckBdd)
                {

                    $gpsInfo = $result[$i]["gpsInfo"];
                    if(isset($gpsInfo["totalKm"])) {
                        $totalKm = $gpsInfo["totalKm"];


                        if ($i < 162 && $totalKm > 0) {
                            //Ecriture dans le fichier excel
                            $sheet->setCellValueByColumnAndRow($j, 1, $listeCars[$j]["immat"]);
                            $sheet->setCellValueByColumnAndRow($j, 2, $totalKm);
                            //Enregistrement en BDD
                            $CarsT = $em->getRepository(Cars::class)->findOneBy(array(
                                "immat" => $listeCars[$j]["immat"]
                            ));
                            $CarsT->setKm($totalKm);
                            $em->persist($CarsT);
                        } else {
                            array_push($kmManquants, $listeCars[$j]["immat"]);
                        }
                    }

                }
                else
                {
                    $sheet->setCellValueByColumnAndRow($j, 1, $listeCars[$j]["immat"]);
                   //array_push($kmManquants, $listeCars[$j]["immat"]);
                }
            }*/

        }



        $kmM = implode('|', $kmManquants);
        $ti2 = time();
        /*if(count($kmManquants) > 0)
        {
            $hache1 = base64_encode(hash_hmac("SHA1", "apa-aps-t39-c1ws.truckonline.proGET/apis/rest/v2.2/gpstracking?vehicle_uids=*&date=".$dateDuPremier."Z&count=1".$ti2."", "5a35101a-62ae-4cba-b70a-b1efd5cd75f0", true));
            $opts1 = array(
                'http' => array(
                    'method'=>'GET',
                    'header' => "x-tonl-client-id:  apa-aps-t39-c1\r\n".
                        "x-tonl-timestamp:  ".$ti2."\r\n".
                        "x-tonl-signature: ".$hache1.""
                )
            );
            // Recherche api truck online tous les vehicules
            $context1 = stream_context_create($opts1);
            $kil1 = file_get_contents("https://ws.truckonline.pro/apis/rest/v2.2/gpstracking?vehicle_uids=*&date=".$dateDuPremier."Z&count=1", false, $context1);
            $result2 = json_decode($kil1, true);
        }*/
        $em->flush();
        $writer = new Xlsx($spreadsheet);
        $writer->save('DonneesKm2.xlsx');

        return $this->render("front/donneesKm.html.twig", array(
            "datePremier" => $dateDuPremier
        ));
    }

    public function etatCarrosseries(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $listeAccrochages = $em->getRepository(Carrosserie::class)->findAll();
        return $this->render("front/etatcarrosseries.html.twig", array(
            "liste" => $listeAccrochages
        ));
    }

    public function carrosserieCar(Request $request,$id, \Swift_Mailer $mailer)
    {

        $routeName = $request->get('_route');
        $em = $this->getDoctrine()->getManager();
        $car = $em->getRepository(Cars::class)->find($id);
        $carrosserieCar = new Carrosserie();
        $form = $this->createForm(CarrosserieType::class, $carrosserieCar);

        if($request->isMethod("POST"))
        {
            //$carID = $carrosserieCar->getId();
            $medias = $request->files->get('file');
            $dataCarro = $request->request->get('carrosserie');
            if($dataCarro != NULL)
            {
                $form->handleRequest($request);
                $carrosserieCar->setCar($car);
                $em->persist($carrosserieCar);
                $em->flush();

                //Envoie de mail
                $mess = (new \Swift_Message('Mouvparc : Accrochage'))
                    ->setFrom('info@caraps.fr', 'Mouv Parc')
                    ->setTo([
                        'mkanoute74@gmail.com'                          =>  'Mohamed Kanoute',
                        'guillaume-aps@outlook.fr'                      =>  'Guillaume Waquet',
                        'kevin.perrillat@autocarspaysdesavoie.fr'       =>  'Kevin Perrillat',
                        'thierry.janeriat@autocarspaysdesavoie.fr'      =>  'Thierry Janeriat',
                        'mounir.smirani@autocarspaysdesavoie.fr'        =>  'Mounir Smirani',
                        'cedric.coppin@autocarspaysdesavoie.fr'         =>  'Cedric Coppin',
                        'cyril.bogdan@autocarspaysdesavoie.fr'          =>  'Cyril Bogdan'/**/
                    ])
                    ->setBody(
                        $this->renderView(
                            'emails/carrosserie.html.twig',
                            array(
                                'immat'             => $car->getImmat(),
                                'marque'            => $car->getMarque(),
                                'etatCar'           => $carrosserieCar->getEtatCar(),
                                'natureAccro'       => $carrosserieCar->getNatureAccro(),
                                'descAccro'         => $carrosserieCar->getDescAccro(),
                                'suiteDonnee'       => $carrosserieCar->getSuiteDonnee(),
                                'dateSignalement'   => $carrosserieCar->getDateSignalement(),
                                'accrochage'        => $carrosserieCar->getId()
                            )),
                        'text/html'
                    );
                $mailer->send($mess);

                //$carId = $carrosserieCar->getId();

                if($medias != NULL)
                {
                    //$cccc = $em->getRepository(Carrosserie::class)->find($carId);

                    foreach ($medias as $media) {
                        $images = new Image();
                        $sizeImage = $media->getClientSize();
                        $extensionImage = $media->guessExtension();
                        $photoName = $this->generateUniqueFileName().'.'.$extensionImage;
                        //set Name photo
                        $images->setUrl($this->getUploadDir().'/'.$photoName);
                        $images->setAlt("Photo accrochage: ". $car->getId());
                        $images->setFile($media);
                        $images->setPath($media->getPathName());
                        //$images->setName($media->getClientOriginalName());
                        $images->upload();
                        //On lie l'image au car
                        $images->setCarrosserie($carrosserieCar);
                        $images->setCar($car);
                        $em->persist($images);
                        $em->flush();
                    }

                }
                return new JsonResponse(array('data' => "OK"));
            }
             //return $this->redirectToRoute("etat_carrosseries");
        }


        if($request->isXmlHttpRequest())
        {}

        return $this->render("front/carrosserie.html.twig", array(
            "car"   => $car,
            "form"  => $form->createView()
        ));
    }


    public function viewCarrosserieCar(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $carrosserie = $em->getRepository(Carrosserie::class)->find($id);
        return $this->render('front/viewcarrosserie.html.twig', array(
            "carro" => $carrosserie
        ));
    }

    public function deleteCarrosserie(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $carrosserie = $em->getRepository(Carrosserie::class)->find($id);
        $lesImages = $carrosserie->getImages();
        if(null !== $lesImages)
        {
            foreach ($lesImages as $image) {
                $webPath = $image->getUrl();
                $filename = substr($webPath,10);
                var_dump($filename." Va etre supprimé");
                $image->deleteFiles($filename);
                var_dump($filename." est supprimé");
                $em->remove($image);
            }
        }

        $em->remove($carrosserie);
        $em->flush();
        return new JsonResponse(array('data'=> 'ok'));
    }


    public function editCarrosserie(Request $request, $id)
    {
        $em= $this->getDoctrine()->getManager();
        $carrosserie = $em->getRepository(Carrosserie::class)->find($id);

        if($carrosserie->getImages())
        {
            $images = $carrosserie->getImages();
        }

        $nb = var_dump(count($images));

        if( $images !== null )
        {
            foreach ($images as $image) {
                var_dump($image->getUrl());
            }
            //var_dump("plus d'image lol ");
        }


        //On hydrate le formulaire avec les entrées sauvegardés de la bdd
        $form = $this->createForm(CarrosserieType::class, $carrosserie);

        if($request->isXmlHttpRequest())
        {
            $delUrl =  $request->get('del');
            $delUrl =substr($delUrl, 9);
            $filename = substr($delUrl, 10);
            $image2 = $em->getRepository(Image::class)->findOneBy(array('url'=> $delUrl));
            $image2->deleteFiles($filename);
            $em->remove($image2);
            $em->flush();
            return new JsonResponse(array('success' => true));
        }


        if ($request->isMethod("POST"))
        {
            //manipuler le formulaire soumis
            $form->handleRequest($request);
            //on persist les changements apporté par l'entité  carrosserie
            $em->persist($carrosserie);
            //on enregistre les nouvelles modifications
            $em->flush();

            return $this->redirectToRoute("etat_carrosseries");
        }

        return $this->render('front/edit-carrosserie.html.twig', array(
            'form'  => $form->createView(),
            'images' => $images
        ));
    }

    public function editionCars(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $listeCars = $em->getRepository(Cars::class)->findAll();
        //$car = array();
        $form = $this->createFormBuilder()
            ->add('modele_car', TextType::class, array(
                    'required' => false))
            ->add('annee', TextType::class, array(
                'required' => false
            ))
            ->add('nb_places', NumberType::class, array(
                'required' => false
            ))
            ->add('siege_guide', CheckboxType::class, array(
                'required'  => false,
            ))
            ->add('wc', CheckboxType::class, array(
                'required'  => false,
            ))
            ->add('ufr', CheckboxType::class, array(
                'required'  => false,
            ))
            ->add('usb', CheckboxType::class, array(
                'required'  => false,
            ))
            ->add('prises_elec', CheckboxType::class, array(
                'required'  => false,
            ))
            ->add('porte_ski', CheckboxType::class, array(
                'required'  => false,
            ))
            ->add('regulateur_vitesse', CheckboxType::class, array(
                'required'  => false,
            ))
            ->add('gps', CheckboxType::class, array(
                'required'  => false,
            ))
            ->add('abs', CheckboxType::class, array(
                'required'  => false,
            ))
            ->add('esp', CheckboxType::class, array(
                'required'  => false,
            ))
            ->add('asr', CheckboxType::class, array(
                'required'  => false,
            ))
            ->add('repose_mollet', CheckboxType::class, array(
                'required'  => false,
            ))
            ->add('micro_conducteur', CheckboxType::class, array(
                'required'  => false,
            ))
            ->add('micro_guide', CheckboxType::class, array(
                'required'  => false,
            ))
            ->add('chauffage_independant', CheckboxType::class, array(
                'required'  => false,
            ))
            ->add('buses_individuelles', CheckboxType::class, array(
                'required'  => false,
            ))
            ->add('tablettes', CheckboxType::class, array(
                'required'  => false,
            ))
            ->add('sieges_decalables', CheckboxType::class, array(
                'required'  => false,
            ))
            ->add('frigo', CheckboxType::class, array(
                'required'  => false,
            ))
            ->add('girouette', CheckboxType::class, array(
                'required'  => false,
            ))
            ->add('rideaux', CheckboxType::class, array(
                'required'  => false,
            ))
            ->add('clim', CheckboxType::class, array(
                'required'  => false,
            ))
            ->add('garantie', CheckboxType::class, array(
                'required'  => false,
            ))
            ->add('rechercher', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);

        if($request->isMethod("POST"))
        {
            $marque = $request->request->get('form')['modele_car'];
            $annee = $request->request->get('form')['annee'];
            $nb_places = $request->request->get('form')['nb_places'];
            $nb_places = (int)$nb_places;
            //var_dump($nb_places);
            //die();
            $formulaireSoumi = $form->getData();

            $garantie = $formulaireSoumi['garantie'];
            $garantie_bdd ="";
            if($garantie === true)
            {
                $garantie_bdd = "oui";
            }

            $mld = array();

           // var_dump($formulaireSoumi);


            foreach ($formulaireSoumi as $item => $v) {
                if($v === true)
                {
                    array_push($mld, $item, $v);
                }
            }

            //je verifie si la garantie a ete coche
            if(in_array("garantie", $mld, true))
            {

                $ligneAsupMld = array_search("garantie", $mld,true);
                unset($mld[$ligneAsupMld]);
                $ligneAsupMld = $ligneAsupMld + 1;
                unset($mld[$ligneAsupMld]);
                $garantie_bdd = "oui";

            }
            else
            {
                $garantie_bdd = "AND c.garantie IS NULL OR c.garantie IS NOT NULL";
            }

            if($marque && $annee && !empty($mld))
            {
                $equipements = array();
                $t_equipements = array();


                for ($i=0;$i<count($mld); $i ++)
                {
                    if($mld[$i] !== true)
                    {
                        array_push($equipements, "c.".$mld[$i]);
                        array_push($t_equipements, "AND c.".$mld[$i]." = true");
                    }
                }


                $imp_equipements = implode(",", $equipements);
                $req_equipements = implode(" ", $t_equipements);



                //$query = $em->createQuery("SELECT c.id, c.immat, c.date_mar, c.marque, c.km, c.nb_places FROM App\Entity\Cars c WHERE  c.garantie = :garantie");
                //$query->setParameters(['garantie' => $garantie_bdd]);
                $query = $em->createQuery("SELECT c.id, c.immat, c.date_mar, c.marque, c.km, c.nb_places,  $imp_equipements FROM App\Entity\Cars c WHERE c.garantie = :garantie AND c.marque LIKE :marque AND DATE_FORMAT(c.date_mar, '%Y') =  :dateMec  $req_equipements ");
                $query->setParameters(array('marque' => '%'.$marque.'%', 'dateMec' =>  $annee, 'garantie' => $garantie_bdd));
                $carss = $query->getResult();

                $nb = count($carss);

                return $this->render('front/editioncar.html.twig', array(
                    'listeCars'         => $listeCars,
                    'nb_trouver'        => $nb,
                    'cars_trouver'      => $carss,
                            'form'      => $form->createView()
                ));

            }
            elseif($marque && $annee && empty($mld))
            {
                if($garantie_bdd === "oui")
                {
                    $garantie_bdd = " AND c.garantie = oui";
                }


                $query = $em->createQuery("SELECT c.id, c.immat, c.date_mar, c.marque, c.km, c.nb_places, c.garantie  FROM App\Entity\Cars c WHERE c.marque LIKE :marque AND DATE_FORMAT(c.date_mar, '%Y') =  :dateMec $garantie_bdd");
                $query->setParameters(['marque' => '%'.$marque.'%', 'dateMec' =>  $annee]);
                $carss = $query->getResult();

                $nb = count($carss);



                return $this->render('front/editioncar.html.twig', array(
                    'listeCars'         => $listeCars,
                    'nb_trouver'        => $nb,
                    'cars_trouver'      => $carss,
                    'form'      => $form->createView()
                ));
            }
            elseif($marque && !$annee && empty($mld))
            {
                if($garantie_bdd === "oui")
                {
                    $garantie_bdd = "AND c.garantie = oui";
                }

                $query = $em->createQuery("SELECT c.id, c.immat, c.date_mar, c.marque, c.km, c.nb_places, c.garantie FROM App\Entity\Cars c WHERE c.marque LIKE :marque $garantie_bdd");

                $query->setParameter('marque', '%'.$marque.'%');
                $carss = $query->getResult();
                $nb = count($carss);

                return $this->render('front/editioncar.html.twig', array(
                    'listeCars'         => $listeCars,
                    'nb_trouver'        => $nb,
                    'cars_trouver'      => $carss,
                    'form'      => $form->createView()
                ));
            }
            elseif(!$marque && $annee && empty($mld))
            {
                if($garantie_bdd === "oui")
                {
                    $garantie_bdd = "AND c.garantie = oui";
                }


                $query = $em->createQuery("SELECT c.id, c.immat, c.date_mar, c.marque, c.km, c.nb_places, c.garantie FROM App\Entity\Cars c WHERE DATE_FORMAT(c.date_mar, '%Y') =  :dateMec $garantie_bdd");

                $query->setParameter('dateMec', $annee);
                $carss = $query->getResult();
                $nb = count($carss);

                return $this->render('front/editioncar.html.twig', array(
                    'listeCars'         => $listeCars,
                    'nb_trouver'        => $nb,
                    'cars_trouver'      => $carss,
                    'form'      => $form->createView()
                ));
            }
            elseif ($marque && !$annee && !empty($mld))
            {
                if($garantie_bdd === "oui")
                {
                    $garantie_bdd = " AND c.garantie = oui";
                }

                $equipements = array();
                $t_equipements = array();


                for ($i=0;$i<count($mld); $i ++)
                {
                    if($mld[$i] !== true)
                    {
                        array_push($equipements, "c.".$mld[$i]);
                        array_push($t_equipements, " AND c.".$mld[$i]." = true");
                    }
                }

                $imp_equipements = implode(",", $equipements);
                $req_equipements = implode(" ", $t_equipements);


                $query = $em->createQuery("SELECT c.id, c.immat, c.date_mar, c.marque, c.km, c.nb_places, c.garantie, $imp_equipements FROM App\Entity\Cars c WHERE c.marque LIKE :marque $req_equipements $garantie_bdd");
                $query->setParameter('marque' ,'%'.$marque.'%');
                $carss = $query->getResult();
                $nb = count($carss);

                return $this->render('front/editioncar.html.twig', array(
                    'listeCars'         => $listeCars,
                    'nb_trouver'        => $nb,
                    'cars_trouver'      => $carss,
                    'form'      => $form->createView()
                ));
            }
            elseif (!$marque && $annee && !empty($mld))
            {
                if($garantie_bdd === "oui")
                {
                    $garantie_bdd = " AND c.garantie = oui";
                }

                $equipements = array();
                $t_equipements = array();


                for ($i=0;$i<count($mld); $i ++)
                {
                    if($mld[$i] !== true)
                    {
                        array_push($equipements, "c.".$mld[$i]);
                        array_push($t_equipements, " AND c.".$mld[$i]." = true");
                    }
                }

                $imp_equipements = implode(",", $equipements);
                $req_equipements = implode(" ", $t_equipements);

                $query = $em->createQuery("SELECT c.id, c.immat, c.date_mar, c.marque, c.km, c.nb_places, c.garantie $imp_equipements FROM App\Entity\Cars c WHERE DATE_FORMAT(c.date_mar, '%Y') =  :dateMec $req_equipements $garantie_bdd");
                $query->setParameter( 'dateMec',  $annee);
                $carss = $query->getResult();

                $nb =count($carss);

                return $this->render('front/editioncar.html.twig', array(
                    'listeCars'         => $listeCars,
                    'nb_trouver'        => $nb,
                    'cars_trouver'      => $carss,
                    'form'      => $form->createView()
                ));
            }
            elseif (!$marque && !$annee && !empty($mld))
            {
                $equipements = array();
                $t_equipements = array();

                if($garantie_bdd === "oui")
                {
                    $garantie_bdd = "AND c.garantie = oui";
                }


                for ($i=0;$i<count($mld); $i ++)
                {
                    if($mld[$i] !== true)
                    {
                        array_push($equipements, "c.".$mld[$i]);
                        array_push($t_equipements, " AND c.".$mld[$i]." = true");
                    }
                }

                $imp_equipements = implode(",", $equipements);
                $req_equipements = implode(" ", $t_equipements);
                $rr_equipements =  substr($req_equipements, 5 );


                $query = $em->createQuery("SELECT c.id, c.immat, c.date_mar, c.marque, c.km, c.nb_places, c.garantie, $imp_equipements FROM App\Entity\Cars c WHERE $rr_equipements $garantie_bdd");
                $carss = $query->getResult();
                $nb = count($carss);


                return $this->render('front/editioncar.html.twig', array(
                    'listeCars'         => $listeCars,
                    'nb_trouver'        => $nb,
                    'cars_trouver'      => $carss,
                    'form'      => $form->createView()
                ));
            }
            elseif (!$marque && !$annee && empty($mld) )
            {
                $carss = $em->getRepository(Cars::class)->findAll();
                $nb = count($carss);
                return $this->render('front/editioncar.html.twig', array(
                    'listeCars'         => $listeCars,
                    'nb_trouver'        => $nb,
                    'cars_trouver'      => $carss,
                    'form'      => $form->createView()
                ));
            }
        }

        if($request->isXmlHttpRequest())
        {
        }

        return $this->render('front/editioncar.html.twig', array(
            'listeCars' => $listeCars,
            'form'      => $form->createView()
        ));
    }



    public function geolocbus(Request $request, AuthorizationCheckerInterface $authorizationChecker)
    {
        if ($authorizationChecker->isGranted('IS_AUTHENTICATED_ANONYMOUSLY'))
        {
            if ($this->container->has('profiler'))
            {
                $this->container->get('profiler')->disable();
            }
        }

        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
                ->add('immatriculation', TextType::class)
                ->add('rechercher', SubmitType::class)
                ->getForm();
        if($authorizationChecker->isGranted('ROLE_USER'))
        {
            $form->add('nomConducteur', TextType::class, array(
                'required' => false
            ));
        }



        if($request->isMethod("POST"))
        {
            $immat = $request->request->get('form')['immatriculation'];


            $query = $em->createQuery('SELECT c FROM App\Entity\Cars c WHERE c.immat LIKE :immat')
                ->setParameter( 'immat','%'.$immat.'%');

            $car_trouver = $query->getResult(Query::HYDRATE_ARRAY);


            $r = count($car_trouver);


            if($car_trouver)
            {
                $cars_immat = array();
                for($i = 0; $i < $r ; $i++)
                {
                     array_push($cars_immat, $car_trouver[$i]['immat']);
                }


                $form = $this->createFormBuilder()
                    ->add('immatriculation', TextType::class)
                    ->add('rechercher', SubmitType::class)
                    ->getForm();

                return $this->render('front/geolocbus.html.twig', array(
                    'form' => $form->createView(),
                    'cars_immat' => $cars_immat

                ));
            }
            else
            {
                $form = $this->createFormBuilder()
                    ->add('immatriculation', TextType::class)
                    ->add('rechercher', SubmitType::class)
                    ->getForm();

                return $this->render('front/geolocbus.html.twig', array(
                    'form' => $form->createView(),
                    'ras' => 'aucun autocars',

                ));
            }/**/

        }
        if ($request->isXmlHttpRequest())
        {

            $immat = $request->request->get('imat');

            $query = $em->createQuery('SELECT c FROM App\Entity\Cars c WHERE c.immat LIKE :immat')
               ->setParameter( 'immat','%'.$immat.'%');

            $car_trouver = $query->getResult(Query::HYDRATE_ARRAY);


             $r = count($car_trouver);

             if($car_trouver)
             {
                 return new JsonResponse(array('immats' => $car_trouver[0]['immat']));
             }
             else
             {
                 return new JsonResponse(array('immats' => " Il n'y a pas d'immatriculation"));
             }



        }

        return $this->render('front/geolocbus.html.twig', array(
            'form' => $form->createView()
        ));
    }


    public function positionCar(Request $request, $immat, AuthorizationCheckerInterface $authorizationChecker)
    {
        if ($authorizationChecker->isGranted('IS_AUTHENTICATED_ANONYMOUSLY'))
        {
            if ($this->container->has('profiler'))
            {
                $this->container->get('profiler')->disable();
            }
        }

        $ti = time();
        $em= $this->getDoctrine()->getManager();
        $car = $em->getRepository(Cars::class)->findByImmat(
            array(
                'immat' => $immat
            )
        );
        if(!$car)
        {
            throw new NotFoundHttpException("Aucun autocar !");
        }
        else if($immat !== "CS-223-JK" && $immat !== "BZ-668-TH" && $immat !== "BM-276-ZC" && $immat !== "BL-182-AH" )
        {
            $hache = base64_encode(hash_hmac("SHA1", "apa-aps-t39-c1ws.truckonline.proGET/apis/rest/v2.2/fleet/vehicles?vehicle_vrn=".$immat."".$ti."", "5a35101a-62ae-4cba-b70a-b1efd5cd75f0", true));
            $opts = array(
                'http' => array(
                    'method'=>'GET',
                    'header' => "x-tonl-client-id:  apa-aps-t39-c1\r\n".
                        "x-tonl-timestamp:  ".$ti."\r\n".
                        "x-tonl-signature: ".$hache.""
                )
            );
            // Recherche api truck online tous les vehicules
            $context = stream_context_create($opts);
            $kil = file_get_contents("https://ws.truckonline.pro/apis/rest/v2.2/fleet/vehicles?vehicle_vrn=".$immat."", false, $context);
            $result = json_decode($kil, true);

            if($result[0])
            {
                $kms = $result[0]["totalKms"];
            }

            $hache2 = base64_encode(hash_hmac("SHA1", "apa-aps-t39-c1ws.truckonline.proGET/apis/rest/v2.2/gpstracking?count=1&vehicle_vrn=".$immat."".$ti."", "5a35101a-62ae-4cba-b70a-b1efd5cd75f0", true));

            $opts2 = array(
                'http' => array(
                    'method'=>'GET',
                    'header' => "x-tonl-client-id:  apa-aps-t39-c1\r\n".
                        "x-tonl-timestamp:  ".$ti."\r\n".
                        "x-tonl-signature: ".$hache2.""
                )
            );
            $context2 = stream_context_create($opts2);
            $kil2 = file_get_contents("https://ws.truckonline.pro/apis/rest/v2.2/gpstracking?count=1&vehicle_vrn=".$immat."", false, $context2);
            $result2 = json_decode($kil2, true);


            if($result2[0])
            {
                $geoc_lat = $result2[0]["gpsInfo"]["latitude"];
                $geoc_long = $result2[0]["gpsInfo"]["longitude"];
            }


            $form = $this->createFormBuilder()
                ->add('lieu', TextType::class)
                ->add('calcul', SubmitType::class)
                ->getForm();

            return $this->render('front/geoloc.html.twig', array(
                'form' => $form->createView(),
                'geoc_lat'  => $geoc_lat,
                'geoc_long' => $geoc_long,
            ));
        }
    }

}
