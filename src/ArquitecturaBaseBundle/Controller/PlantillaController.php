<?php

namespace ArquitecturaBaseBundle\Controller;

use ArquitecturaBaseBundle\Entity\Plantilla;
use ArquitecturaBaseBundle\Form\PlantillaType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class PlantillaController extends Controller
{
    public function agregarPlantillaAction(Request $peticion)
    {
        $plantilla = new Plantilla();
        $form = $this->createForm(PlantillaType::class, $plantilla, array(
            'method' => Request::METHOD_POST,
            'action' => $this->generateUrl('agregar_plantilla', array())
        ));

        $form->handleRequest($peticion);
        if ($form->isSubmitted() && $form->isValid()){
            $em = $this->get('doctrine.orm.entity_manager');
            $em->persist($plantilla);
            $em->flush();
            return $this->redirectToRoute('listar_plantillas');
        }
        return $this->render('ArquitecturaBaseBundle:Plantilla:agregar_plantilla.html.twig', array(
            'form' => $form->createView()
        ));
    }

    public function listarPlantillasAction()
    {
        return $this->render('ArquitecturaBaseBundle:Plantilla:mostrar_plantilla.html.twig', array(
            // ...
        ));
    }

    public function mostrarPlantillaAction()
    {
        return $this->render('ArquitecturaBaseBundle:Plantilla:mostrar_plantilla.html.twig', array(
            // ...
        ));
    }

    public function cargarPlantillasAjaxAction(){
        $em = $this->get('doctrine.orm.entity_manager');
        $plantillas = $em->getRepository('ArquitecturaBaseBundle:Plantilla')->findBy(array(
            'activa' => true
        ));
        $datosPlantilla = array();
        foreach ($plantillas as $plantilla){
            $datosPlantilla[] = array(
                'id' => $plantilla->getId(),
                'nombre' => $plantilla->getNombre(),
                'descripcion' => $plantilla->getDescripcion(),
                'activa' => $plantilla->isActiva()
            );
        }
        return new JsonResponse($datosPlantilla);
    }

}
