<?php

namespace ArquitecturaBaseBundle\Controller;

use ArquitecturaBaseBundle\Entity\Visual\BuscarTraza;
use ArquitecturaBaseBundle\Form\BuscarTrazaType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class TrazaController extends BaseController
{

    public function buscarTrazaAction()
    {
        $buscarTrazaEV = new BuscarTraza();
        $form = $this->createForm(BuscarTrazaType::class , $buscarTrazaEV);
        return $this->render(
            '@ArquitecturaBase/Administracion/listar_trazas.html.twig',array(
                'form' => $form->createView()
            )
        );

    }

    public function ejecutarBuscarTrazaAction(Request $request)
    {
        $columnas = array(
            'getId',
            'getIndexGrid',
            'getNameToStringGrid',
            'getDescriptionToStringGrid',
            'getAcciones'
        );
        return new JsonResponse($this->Mostrar(
            $request,
            $columnas,
            'UserBundle:Action',
            'findActionsListGrid',
            false,
            false,
            array()
        ));
    }
}
