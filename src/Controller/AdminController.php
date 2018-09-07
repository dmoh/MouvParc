<?php

namespace App\Controller;

use App\Entity\DemandeAccompte;
use App\Entity\DemandeConges;
use App\Entity\Notifications;
use App\Form\DemandeAccompteType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Security;
use App\Entity\User;



class AdminController extends Controller
{
    /**
     * @Route("/admin/rh-accompte", name="admin_rh")
     *
     */
    public function index(Security $security, Request $request)
    {

        //TODO SERVICE DE SECURITE
        $usrCurrent= $security->getUser();
        $userId = $usrCurrent->getId();


        if($security->isGranted('IS_AUTHENTICATED_ANONYMOUSLY'))
        {
            $this->redirectToRoute('login');

        }elseif( $usrCurrent->getRoles() != ["ROLE_SUPER_MASTER"] || $usrCurrent->getRoles() != ["ROLE_RH"])
        {
           $this->redirectToRoute('logout');
            //throw new AccessDeniedException("Espace personnel défense d'entrer !");
        }

        $em = $this->getDoctrine()->getManager();
        $listingDemandesAccompte = $em->getRepository(DemandeAccompte::class)->findAll();



        return $this->render('admin/indexAccompte.html.twig', [
            'controller_name'   => 'AdminController',
            'listingAccompte'   => $listingDemandesAccompte,
        ]);
    }

    /**
     * @Route("admin/rh/demande-accompte/{id}", name="reponse_accompte", requirements={"id"="\d+"})
     */
    public function reponseDemandeAccompte(Security $security, Request $request, $id)
    {
        //TODO SERVICE DE SECURITE
        $usrCurrent= $security->getUser();
        $userId = $usrCurrent->getId();
        if($security->isGranted('IS_AUTHENTICATED_ANONYMOUSLY'))
        {
            $this->redirectToRoute('login');

        }elseif( $usrCurrent->getRoles() != ["ROLE_SUPER_MASTER"] && $usrCurrent->getRoles() != ["ROLE_RH"])
        {
            $this->redirectToRoute('logout');
            //throw new AccessDeniedException("Espace personnel défense d'entrer !");
        }

        $em = $this->getDoctrine()->getManager();

        $accompte = $em ->getRepository(DemandeAccompte::class)->find($id);
         //ajout nom prenom
        $accompte->setPrenom($accompte->getDemandeAccompteConducteur()->getPrenomConducteur());
        $accompte->setNom($accompte->getDemandeAccompteConducteur()->getNomConducteur());

        $idConducteur = $accompte->getDemandeAccompteConducteur()->getId();
        $conducteur = $accompte->getDemandeAccompteConducteur();
        $montantAccompte = $accompte->getMontantAccompte();


        $data = array();

        $form = $this->createFormBuilder($data)
            ->add('ouiDir', ChoiceType::class, array(
                "choices" => [
                    "ACCORD"    => "accord",
                    "REFUS"     => "refus"
                ]
            ))
            ->add('obsDirection', TextareaType::class, ["required" => false])
            ->add('Envoyer', SubmitType::class)
            ->getForm();
        //$form = $this->createForm(DemandeAccompteType::class, $accompte);


        if($request->isMethod("POST"))
        {
            //$form->handleRequest($request);

            $repDirection = $request->request->all()["form"];
            $accordRefus = $repDirection["ouiDir"];
            $accordRefus = strtoupper($accordRefus);

            $obsDirection = $repDirection["obsDirection"];

            //la réponse a été validée par la direction
            $accompte->setStatueDemande(0);

            //TODO NOTIFIER AU CONDUCTEUR
            $nouvelleNotif = new Notifications();


            if ($accordRefus == "ACCORD")
            {
                $accompte->setReponseDirection("ACCORD");
                $nouvelleNotif->setSujetNotif("Votre demande d'accompte de ".$montantAccompte."€ a été ACCEPTE");
            }
            else
            {
                $accompte->setReponseDirection("REFUS");
                $nouvelleNotif->setSujetNotif("Votre demande d'accompte de ".$montantAccompte."€ a été REFUSE");
            }

            if($obsDirection !== "")
            {
                $accompte->setObsDirection($obsDirection);
            }
            //Lie au conducteur
            $nouvelleNotif->setNotifConducteur($conducteur);
            $em->persist($nouvelleNotif);
            $em->flush();
            return $this->redirectToRoute("admin_rh");
        }

        //$form->handleRequest($request);


        return $this->render('admin/reponseAccompte.html.twig', array(
            'id'            => $id,
            'accompte'      => $accompte,
            'form'          => $form->createView()
        ));
    }



    /**
     * @Route("admin/rh-conges", name="rh_conges")
     */
    public function indexConge(Security $security, Request $request)
    {
        //TODO SERVICE DE SECURITE
        $usrCurrent= $security->getUser();
        $userId = $usrCurrent->getId();
        if($security->isGranted('IS_AUTHENTICATED_ANONYMOUSLY'))
        {
            $this->redirectToRoute('login');

        }elseif( $usrCurrent->getRoles() != ["ROLE_SUPER_MASTER"] && $usrCurrent->getRoles() != ["ROLE_RH"])
        {
            $this->redirectToRoute('logout');
            //throw new AccessDeniedException("Espace personnel défense d'entrer !");
        }

        $em = $this->getDoctrine()->getManager();

        $listingDemandesConges = $em->getRepository(DemandeConges::class)->findAll();

        return $this->render('admin/indexConges.html.twig',array(
           'listingConges' => $listingDemandesConges,
        ));

    }


    /**
     * @Route("admin/rh/reponse-conge/{id}", name="reponse_conge", requirements={"id"="\d+"})
     */
    public function reponseConge($id, Security $security, Request $request)
    {
        //TODO SERVICE DE SECURITE
        $usrCurrent= $security->getUser();
        $userId = $usrCurrent->getId();
        if($security->isGranted('IS_AUTHENTICATED_ANONYMOUSLY'))
        {
            $this->redirectToRoute('login');

        }elseif( $usrCurrent->getRoles() != ["ROLE_SUPER_MASTER"] && $usrCurrent->getRoles() != ["ROLE_RH"])
        {
            $this->redirectToRoute('logout');
            //throw new AccessDeniedException("Espace personnel défense d'entrer !");
        }

        $em = $this->getDoctrine()->getManager();

        $conge = $em->getRepository(DemandeConges::class)->find($id);

        //ajout nom prenom
        $conge->setPrenom($conge->getDemandeCongeConducteur()->getPrenomConducteur());
        $conge->setNom($conge->getDemandeCongeConducteur()->getNomConducteur());
        $conducteur = $conge->getDemandeCongeConducteur();

        //RECUP LES DATES DU CONGE ET ON FORMATE
        $duDate  = $conge->getDuDateConge();
        $auDate = $conge->getAuDateConge();
        $dd = $duDate->format('d/m/Y');
        $da = $auDate->format('d/m/Y');

        $data = array();
        $form = $this->createFormBuilder($data)
            ->add('ouiDir', ChoiceType::class, array(
                "choices" => [
                    "ACCORD"    => "accord",
                    "REFUS"     => "refus"
                ]
            ))
            ->add('obsDirection', TextareaType::class, ["required" => false])
            ->add('Envoyer', SubmitType::class)
            ->getForm();

        if($request->isMethod("POST"))
        {
            //$form->handleRequest($request);

            //TODO NOTIFIER AU CONDUCTEUR
            $nouvelleNotif = new Notifications();
            $nouvelleNotif->setNotifConducteur($conducteur);

            $repDirection = $request->request->all()["form"];
            $accordRefus = $repDirection["ouiDir"];
            $accordRefus = strtoupper($accordRefus);

            $obsDirection = $repDirection["obsDirection"];
            //la réponse a été validée par la direction
            $conge->setStatueDemande(0);
            $conge->setDemandeCloturer(true);

            if ($accordRefus == "ACCORD")
            {
                $conge->setAccordRefusDirection("ACCORD");
                $nouvelleNotif->setSujetNotif("Demande de congé du ".$dd." au ".$da." été ACCEPTE");
            }
            else
            {
                $conge->setAccordRefusDirection("REFUS");
                $nouvelleNotif->setSujetNotif("Demande de congé du ".$dd." au ".$da." été REFUSE");
            }

            if($obsDirection !== "")
            {
                $conge->setreponseDirection($obsDirection);
            }
            $em->persist($nouvelleNotif);
            $em->flush();
            return $this->redirectToRoute("rh_conges");
        }


        return $this->render('admin/reponseConge.html.twig', array(
            'conge'         => $conge,
            'form'          => $form->createView(),
        ));
    }



}
