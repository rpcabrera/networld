<?php

namespace ArquitecturaBaseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AutenticacionController extends Controller
{

    public function loginAction(){
        $authenticationUtils = $this->get('security.authentication_utils');
        // Obtener el error de autenticacion si existe
        $error = $authenticationUtils->getLastAuthenticationError();
        // Coger el ultimo username para mandarlo para la vista
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render(
            'login.html.twig',
            array(
                'last_username' => $lastUsername,
                'error'         => $error,
            )
        );
    }

    public function inicioAction(){

        return $this->render('ArquitecturaBaseBundle:Default:index.html.twig');
    }


}
