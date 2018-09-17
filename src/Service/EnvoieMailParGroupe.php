<?php
/**
 * Created by PhpStorm.
 * User: UTILISATEUR
 * Date: 17/09/2018
 * Time: 10:50
 */

namespace App\Service;


class EnvoieMailParGroupe
{
    const MAIL_DU_PORTAIL = 'info@portail-aps.com';

    public function envoieDeMail($sujetDuMail, $nomDuGroupe, \Swift_Mailer $mailer, $infosDuMail)
    {
        $message = (new \Swift_Message($sujetDuMail))
                    ->setFrom(EnvoieMailParGroupe::MAIL_DU_PORTAIL)
                    ->setTo($nomDuGroupe)
                    ->setBody(
                        $this->renderView(
                        'emails/portailAps.html.twig',
                        array(
                            'infos' => $infosDuMail
                        )

                    ), 'text/html')
        ;

        $mailer->send($message);
    }
}