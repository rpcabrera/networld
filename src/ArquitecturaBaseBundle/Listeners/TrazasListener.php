<?php
/**
 * Created by PhpStorm.
 * User: cronos
 * Date: 18/10/16
 * Time: 13:29
 */

namespace ArquitecturaBaseBundle\Listeners;

use ArquitecturaBaseBundle\Entity\Usuario;
use ArquitecturaBaseBundle\Servicios\TrazasService;
use ArquitecturaBaseBundle\Utiles\Util;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Symfony\Component\Security\Core\Event\AuthenticationFailureEvent;


use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;


class TrazasListener
{
    /**
     * @var TrazasService
     */
    private $servicioTraza;

    /**
     * @var AuthorizationChecker
     */
    private $authorization_checker;

    /**
     * @var string
     */
    private $trazaNivel;

    /**
     * @var TokenStorage
     */
    private $tokenStorage;


    /**
     * TrazasListener constructor.
     * @param TrazasService $servicioTraza
     * @param AuthorizationChecker $authorization_checker
     * @param TokenStorage $tokenStorage
     */
    public function __construct($servicioTraza, $authorization_checker, $trazaNivel, $tokenStorage)
    {
        $this->servicioTraza = $servicioTraza;
        $this->authorization_checker = $authorization_checker;
        $this->trazaNivel = $trazaNivel;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @return AuthorizationChecker
     */
    public function getAuthorizationChecker()
    {
        return $this->authorization_checker;
    }

    /**
     * @return string
     */
    public function getTrazaNivel()
    {
        return $this->trazaNivel;
    }

    /**
     * @return TokenStorage
     */
    public function getTokenStorage()
    {
        return $this->tokenStorage;
    }


    /**
     * @return TrazasService
     */
    public function getServicioTraza()
    {
        return $this->servicioTraza;
    }


    public function onSecurityAuthenticationFailure(AuthenticationFailureEvent $event)
    {
        $traza = $this->getServicioTraza()->obtenerTraza(null);
        $fechaActual = new \DateTime();
        $usuario = $event->getAuthenticationToken()->getUsername();
        $accion = "La autenticación falló.";
        $this->getServicioTraza()->adicionarTraza($traza, $fechaActual, $accion, $usuario);
    }

    public function onSecurityInteractivelogin(InteractiveLoginEvent $event)
    {

        if ($this->authorization_checker->isGranted('IS_AUTHENTICATED_FULLY')) {
            $traza = $this->getServicioTraza()->obtenerTraza(null);
            $fechaActual = new \DateTime();
            $usuario = $event->getAuthenticationToken()->getUsername();
            $accion = "Autenticación correcta en el sistema.";
            $this->getServicioTraza()->adicionarTraza($traza, $fechaActual, $accion, $usuario);
        } elseif ($this->authorization_checker->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            $traza = $this->getServicioTraza()->obtenerTraza(null);
            $fechaActual = new \DateTime();
            $usuario = $event->getAuthenticationToken()->getUsername();
            $accion = "Autenticación correcta en el sistema mediante la cookie.";
            $this->getServicioTraza()->adicionarTraza($traza, $fechaActual, $accion, $usuario);
        }

    }

    public function onKernelResponse(FilterResponseEvent $event)
    {
        $request = $event->getRequest();
        if (!is_null($this->tokenStorage->getToken()))
            if ($this->tokenStorage->getToken()->getUser() instanceof Usuario) {
                $usuario = $this->tokenStorage->getToken()->getUser();

                $p_ruta = $request->attributes->get('_route_params');
                if ($this->getTrazaNivel() == Util::alto && !is_null($usuario) && (!is_null($p_ruta) && array_key_exists('etiqueta', $p_ruta)) &&
                    $this->authorization_checker->isGranted('IS_AUTHENTICATED_FULLY')
                ) {
                    $url = $request->getRequestUri();
                    $parts = parse_url($url);
                    $qs = array();
                    if (isset($parts['query'])) {
                        parse_str($parts['query'], $qs);
                        if (isset($qs['_ajax'])) {
                            unset($qs['_ajax']);
                        }
                        $path = $parts['path'];
                        $query = http_build_query($qs);
                        $url = "$path?$query";
                    }
                    $traza = $this->getServicioTraza()->obtenerTraza(null);
                    $accion = 'Funcionalidad visitada en el sistema: '.$p_ruta['etiqueta'];
                    $this->getServicioTraza()->adicionarTraza($traza, new \DateTime(), $accion, $usuario->getUsername(), $url);
                }

            }
    }

}