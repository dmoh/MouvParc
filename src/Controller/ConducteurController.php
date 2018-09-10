<?php

namespace App\Controller;

use App\Entity\Conducteur;
use App\Entity\DemandeAccompte;
use App\Entity\DemandeConges;
use App\Entity\DemandesConducteurs;
use App\Entity\Notifications;
use App\Entity\RapportHebdo;
use App\Entity\User;
use App\Form\DemandeAccompteType;
use App\Form\DemandeCongesType;
use App\Form\DemandesConducteursType;
use App\Form\RapportHebdoType;
use App\Repository\RapportHebdoRepository;
use App\Service\RedirectAfterLogin;
use http\Env\Response;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Security;

class ConducteurController extends Controller
{
    /**
     * @Route("/conducteur/accueil/{user_id}", name="conducteur")
     * @ParamConverter("user", options={"id" = "user_id"})
     */
    public function index(Security $security, Request $request, User $user, RedirectAfterLogin $redirection)
    {
        $usrCurrent= $security->getUser();
        $userId = $usrCurrent->getId();
        if($usrCurrent->getId() != $user->getId() && $usrCurrent->getRoles() != ["ROLE_SUPER_MASTER"])
        {
            return $this->redirectToRoute('logout');
            //throw new AccessDeniedException("Espace personnel défense d'entrer !");
        }

        // TODO mes demandes en cours Traités ou non
        $conducteur = $security->getUser();
        $conducteur1 = $conducteur->getConducteur();
        $nom = $conducteur->getConducteur()->getNomConducteur();
        $prenom = $conducteur->getConducteur()->getPrenomConducteur();
        $idConducteur =$conducteur->getConducteur()->getId();
        $em = $this->getDoctrine()->getManager();
        $demandeAttente = $em->getRepository(RapportHebdo::class)->demandesEnAttente($idConducteur);
        $nbreDemandeAttente = count($demandeAttente);
        $rapportHebdo= $em->getRepository(RapportHebdo::class)->rapportsHebdo($idConducteur);
        $demandeDeCongeEnAtttente = $em->getRepository(DemandeConges::class)->demandeCongeEnAttente($idConducteur);
        $nbCongeEnAttente = count($demandeDeCongeEnAtttente);
        $demandeAccompte = $em->getRepository(DemandeAccompte::class)->demandeAccompteEnAttente($idConducteur);
        $nbdemandeAccompteAttente = count($demandeAccompte);

        //LES NOUVELLES NOTIFS
        //TODO Méthode de recup des notif
        $notifs = array();
        $notifs = $em->getRepository(Notifications::class)->recupNotifAttente($idConducteur);


        $em =$this->getDoctrine()->getManager();
        $demande  = new RapportHebdo();
        $data = array();

        // TODO Si pas de conducteur alors role SUPER MASTER

        //$conducteur->getConducteur()->getMatriculeConducteur()




        $form1= $this->createFormBuilder($data)
            ->add('rapportHebdoConducteur', CollectionType::class, array(
                'entry_type' => RapportHebdoType::class,
                'allow_add'=> true,
                'allow_delete' =>true,
                'prototype' => true,
                'by_reference' => false,
            ))
            ->add('Envoyer', SubmitType::class)
            ->getForm()
        ;


        $demandeConge = new DemandeConges();

        $demandeConge->setNom($conducteur->getConducteur()->getNomConducteur())
                    ->setPrenom(ucfirst($conducteur->getConducteur()->getPrenomConducteur()));

       $form2 = $this->createForm(DemandeCongesType::class, $demandeConge)
                        ->add('Envoyer', SubmitType::class)
       ;

        //$form->handleRequest($request);
        $form1->handleRequest($request);
        $form2->handleRequest($request);

        if($request->isXmlHttpRequest())
        {   if(isset($request->request->all()["notifID"]) )
            {
                $lesNotifslus = $request->request->all()["notifID"];
                $notif = explode(",", $lesNotifslus);

                foreach ($notif as $item) {
                    $notStatu = (int)$item;
                    $lanotif = $em->getRepository(Notifications::class)->find($notStatu);
                    $lanotif->setStatueNotif(0);
                    $em->flush();
                }

                return new JsonResponse(array('statueNotif' => 'OK'));

            }elseif (isset($request->request->all()["demande_conges"]))
            {


                $formulaireDemandeConge = $request->request->all()["demande_conges"];

                $dateDuDateConge = $formulaireDemandeConge['duDateConge'];
                $dateAuDateConge = $formulaireDemandeConge['auDateConge'];
                $dateDemandeConge = $formulaireDemandeConge['dateDemande'];

                //Formatage date pour enregistrement BDD
                $dateDu = \DateTime::createFromFormat('d/m/Y', $dateDuDateConge);
                $dateAu = \DateTime::createFromFormat('d/m/Y', $dateAuDateConge);
                $dateDemande = \DateTime::createFromFormat('d/m/Y', $dateDemandeConge);


                $demandeConge->setDuDateConge($dateDu);
                $demandeConge->setAuDateConge($dateAu);
                $demandeConge->setDateDemande($dateDemande);
                $demandeConge->setTypeDeConge($formulaireDemandeConge['typeDeConge']);
                $demandeConge->setStatueDemande(1);

                $demandeConge->setDemandeCongeConducteur($usrCurrent->getConducteur());
                $em->persist($demandeConge);
                $nouvelleNotif = new Notifications();
                $nouvelleNotif->setSujetNotif("Nouvelle demande d'accompte de ".$nom." ".$prenom." ");
                $nouvelleNotif->setNotifDirection(1);
                $nouvelleNotif->setNotifConducteur($conducteur1);
                $nouvelleNotif->setStatueNotif(0);
                $em->persist($nouvelleNotif);
                $em->flush();
                $this->addFlash('info', 'Votre demande de congé a bien été enregistré');
                return $this->redirectToRoute("conducteur", ['user_id' => $userId ]);
            }

        }


        if($request->isMethod('POST'))
        {
        }

        return $this->render('conducteur/index.html.twig', [
            'controller_name'   => 'ConducteurController',
            'user'              => $user,
            'userId'            => $userId,
            'nbreDemandeAttente'=> $nbreDemandeAttente,
            'nbdemandeAccompteAttente' => $nbdemandeAccompteAttente,
            'nbCongeEnAttente'  => $nbCongeEnAttente,
            'demandesEnAttente' => $demandeAttente,
            'rapportsHebdo'     => $rapportHebdo,
            'form2'             => $form2->createView(),
            'form1'             => $form1->createView(),
            'notifs'            => $notifs,
        ]);
    }

    /**
     * @Route("/conducteur/demande-accompte/{user_id}", name="demande_accompte")
     * @ParamConverter("user", options={"id" = "user_id"})
     */
    public function demandeAccompte(Security $security, Request $request, User $user)
    {
        $usrCurrent= $security->getUser();
        $userId = $usrCurrent->getId();
        if($usrCurrent->getId() != $user->getId() && $usrCurrent->getRoles() != ["ROLE_SUPER_MASTER"])
        {
            throw new AccessDeniedException("Espace personnel défense d'entrer !");
        }

        $nom = $security->getUser()->getConducteur()->getNomConducteur();
        $prenom = $security->getUser()->getConducteur()->getPrenomConducteur();
        $demandeAccompte = new DemandeAccompte();
        //Entité conducteur
        $conducteur = $security->getUser()->getConducteur();

        $demandeAccompte->setNom(ucfirst($nom));
        $demandeAccompte->setPrenom(ucfirst($prenom));
        $form = $this->createForm(DemandeAccompteType::class, $demandeAccompte);
        $form->handleRequest($request);
        if($form->isSubmitted() &&  $form->isValid() )
        {
            $em = $this->getDoctrine()->getManager();
            $arrAccompte = $request->request->all()['demande_accompte'];
            $montantAccompte = $arrAccompte['montantAccompte'];
            $dateDemande =  $arrAccompte['dateDemande'];
            $dateD = \DateTime::createFromFormat('d/m/Y', $dateDemande);
            $observationConducteur = $arrAccompte['obsAccompteConducteur'];
            $demandeAccompte->setMontantAccompte($montantAccompte);
            $demandeAccompte->setStatueDemande(1);
            $demandeAccompte->setDateDemande($dateD);
            $demandeAccompte->setObsAccompteConducteur($observationConducteur);
            $demandeAccompte->setDemandeAccompteConducteur($conducteur);

            $em->persist($demandeAccompte);
            $nouvelleNotif = new Notifications();
            $nouvelleNotif->setSujetNotif("Nouvelle demande d'accompte de ".$nom." ".$prenom." ");
            $nouvelleNotif->setNotifDirection(1);
            $nouvelleNotif->setNotifConducteur($conducteur);
            $nouvelleNotif->setStatueNotif(0);
            $em->persist($nouvelleNotif);
            $em->flush();

            $this->addFlash('info', 'Votre demande d\'accompte de '.$montantAccompte.'€ a bien été enregistré');
            return $this->redirectToRoute("conducteur", ['user_id' => $userId ]);
        }



        return $this->render('conducteur/demandeAccompte.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("conducteur/envoi-rapport/{user_id}", name="rapport_hebdo_conducteur")
     * @ParamConverter("user", options={"id" = "user_id"})
     */
    public  function envoiRapportHebdoConducteur(Security $security, Request $request, User $user)
    {
        $usrCurrent= $security->getUser();
        $conducteur = $usrCurrent->getConducteur();
        $userId = $usrCurrent->getId();
        if($usrCurrent->getId() != $user->getId() && $usrCurrent->getRoles() != ["ROLE_SUPER_MASTER"])
        {
            throw new AccessDeniedException("Espace personnel défense d'entrer !");
        }

        $data = array();
        $nomConductuer = strtoupper($conducteur->getNomConducteur());
        $prenomConducteur = ucfirst($conducteur->getPrenomConducteur());

        // TODO Si pas de conducteur alors role SUPER MASTER

        //$conducteur->getConducteur()->getMatriculeConducteur()

        $em = $this->getDoctrine()->getManager();


        $form1= $this->createFormBuilder($data)
            ->add('rapportHebdoConducteur', CollectionType::class, array(
                'entry_type' => RapportHebdoType::class,
                'allow_add'=> true,
                'allow_delete' =>true,
                'prototype' => true,
                'by_reference' => false,
            ))
            ->add('Envoyer', SubmitType::class, array(
                'attr'=> ['class' => 'btn btn-block btn-primary']
            ))
            ->getForm()
        ;

        if($request->isMethod('POST'))
        {
            $dataRapport = $request->request->all()['form']['rapportHebdoConducteur'];
            $nbreJournee = count($dataRapport);


            for($i = 0; $i < $nbreJournee; $i++)
            {
                $nVeauRapport = new RapportHebdo();
                //$nVeauRapport->setDateReclame($dataRapport[$i]["dateReclame"]);
                $nVeauRapport->setDateReclame(new \DateTime());
                $nVeauRapport->setCompteurRapport($nbreJournee);
                $nVeauRapport->setTravailHorsTachy($dataRapport[$i]["travailHorsTachy"]);
                $nVeauRapport->setHeureRapport($dataRapport[$i]["heureRapport"]);
                $nVeauRapport->setMinRapport($dataRapport[$i]["minRapport"]);
                $nVeauRapport->setHeureFinRapport($dataRapport[$i]["heureFinRapport"]);
                $nVeauRapport->setMinFinRapport($dataRapport[$i]["minFinRapport"]);
                $nVeauRapport->setRepasMidi((bool)$dataRapport[$i]["repasMidi"]);
                $nVeauRapport->setRepasSoir((bool)$dataRapport[$i]["repasSoir"]);
                $nVeauRapport->setObservationsRapport($dataRapport[$i]["observationsRapport"]);
                $nVeauRapport->setStatuDemande(true);
                $nVeauRapport->setRapportConducteur($conducteur);


                $em->persist($nVeauRapport);
                $em->flush();

            }
            $nouvelleNotif = new Notifications();
            $nouvelleNotif->setSujetNotif("Dépos d'un rapport hebdo de ".$nomConductuer." ".$prenomConducteur." ");
            $nouvelleNotif->setNotifDirection(1);
            $nouvelleNotif->setNotifConducteur($conducteur);
            $nouvelleNotif->setStatueNotif(0);
            $em->persist($nouvelleNotif);
            $em->flush();
            $this->addFlash('info', 'Votre rapport hebdo de '.$nbreJournee.' jours a bien été enregistré ');
            return $this->redirectToRoute("conducteur", array("user_id" => $user->getId()));

        }


        return $this->render('conducteur/rapportHebdo.html.twig',['form' => $form1->createView()]);
    }




    /**
     * @Route("/conducteur/mes-demandes/{user_id}", name="mesdemandes")
     * @ParamConverter("user", options={"id" = "user_id"})
     */
    public function mesDemandes(Security $security, Request $request, User $user)
    {
        $usrCurrent= $security->getUser();
        $userId = $usrCurrent->getId();
        if($usrCurrent->getId() != $user->getId() && $usrCurrent->getRoles() != ["ROLE_SUPER_MASTER"])
        {
            return $this->redirectToRoute('logout');
            //throw new AccessDeniedException("Espace personnel défense d'entrer !");
        }

        $em= $this->getDoctrine()->getManager();

        $mesDemandesAccompte = null;
        $mesDemandesConges = null;
        $mesDemandesAccompte = $em->getRepository(DemandeAccompte::class)->findAll();
        $mesDemandesConges = $em->getRepository(DemandeConges::class)->findAll();
        return $this->render('conducteur/mesdemandes.html.twig', array(
            'userId'                    => $userId,
            'mesDemandesAccompte'       => $mesDemandesAccompte,
            'mesDemandesConges'         => $mesDemandesConges
        ));

    }




}
