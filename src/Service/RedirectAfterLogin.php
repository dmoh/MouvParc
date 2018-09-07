<?php
/**
 * Created by PhpStorm.
 * User: UTILISATEUR
 * Date: 25/07/2018
 * Time: 16:54
 */

namespace App\Service;


use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;

class RedirectAfterLogin implements AuthenticationSuccessHandlerInterface
{
    private $router;



    public function __construct(RouterInterface $router){
        $this->router = $router;
    }


    public function onAuthenticationSuccess(Request $request, TokenInterface $token){

        $usrRoles = $token->getRoles()[0]->getRole();
        $userJustLogged = $token->getUser()->getId();



        if($usrRoles == 'ROLE_SUPER_MASTER')
        {
            $redirection = new RedirectResponse($this->router->generate('dashboard_users'));
        }
        elseif ($usrRoles == 'ROLE_USER')
        {
            $redirection = new RedirectResponse($this->router->generate('conducteur',array('user_id' => $userJustLogged )));
        }
        elseif($usrRoles == 'ROLE_RH')
        {
            $redirection = new RedirectResponse($this->router->generate('admin_rh'));
        }

        return $redirection;

    }
}