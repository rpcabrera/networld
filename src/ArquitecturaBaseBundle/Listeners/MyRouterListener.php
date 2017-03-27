<?php
/**
 * Created by PhpStorm.
 * User: rigoberto
 * Date: 5/09/16
 * Time: 10:28
 */

namespace ArquitecturaBaseBundle\Listeners;


use ArquitecturaBaseBundle\Entity\Usuario;
use ArquitecturaBaseBundle\Servicios\RouterFirewall;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class MyRouterListener implements EventSubscriberInterface
{
    /**
     * @var RouterFirewall
     */
    private $firewall;

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * MyRouterListener constructor.
     * @param RouterFirewall $firewall
     * @param ContainerInterface $container
     */
    public function __construct(RouterFirewall $firewall, ContainerInterface $container)
    {
        $this->firewall = $firewall;
        $this->container= $container;
    }

    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::CONTROLLER => array(
                array('ControlarRutaPorRol', 10)
            )
        );
    }

    public function ControlarRutaPorRol(FilterControllerEvent $event)
    {
        $usarControlAcceso = $this->container->getParameter('usar_control_acceso');
        if (!$usarControlAcceso) return;

        $tokenStorage = $this->container->get('security.token_storage')->getToken();
        if (is_null($tokenStorage)){
            //No ha sido autenticado
            return;
        }
        //Si la peticion es AJAX debe permitirse
        if ($event->getRequest()->isXmlHttpRequest())
            return;

        $usuario = $this->container->get('security.token_storage')->getToken()->getUser();
        $ruta = $event->getRequest()->get('_route');
        //chequear que la ruta (buscada en el router) contenga el tag de seguimiento
        $router = $this->container->get('router');
        $routingPeticion = $router->matchRequest($event->getRequest());

        if ($usuario instanceof Usuario){
            $tienePermiso = $this->firewall->isRouteGrantedForUser($usuario,$ruta);
            if (!$tienePermiso && $event->isMasterRequest() && array_key_exists('mostrarEnMenu',$routingPeticion))
                throw new AccessDeniedException("No puede acceder a la ruta solicitada");
        }
    }

}