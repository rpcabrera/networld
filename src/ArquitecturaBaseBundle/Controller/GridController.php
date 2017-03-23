<?php
/**
 * Este controlador es  para rederizar todos los ejemplos de grid con
 * los que se cuentan en el sistema.
 * Powered By Robin
 */

namespace ArquitecturaBaseBundle\Controller;


use Symfony\Component\BrowserKit\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class GridController extends BaseController
{
    public function gridAction()
    {
        return $this->render('ComponentBundle:Default:index.html.twig');
    }

    public function listAjax0Action(Request $request) {
        $user = $this->getUser();
        $columnas = array('getIndexGrid', 'getNameToStringGrid', 'getDescriptionToStringGrid', 'getAcciones');
        return new JsonResponse($this->Mostrar($request, $columnas, 'UserBundle:Action', 'findActionsListGrid', false, false, array($user)));
    }

    public function listAjax1Action(Request $request) {
        $user = $this->getUser();
        $columnas = array('getBlank', 'getIndexGrid', 'getNameToStringGrid', 'getDescriptionToStringGrid', 'getInfoGrid', 'getAcciones');
        return new JsonResponse($this->Mostrar($request, $columnas, 'UserBundle:Action', 'findActionsListGrid', false, false, array($user)));
    }

    public function listAjax2Action(Request $request) {
        $user = $this->getUser();
        $columnas = array('getId', 'getIndexGrid', 'getNameToStringGrid', 'getDescriptionToStringGrid', 'getAcciones');
        return new JsonResponse($this->Mostrar($request, $columnas, 'UserBundle:Action', 'findActionsListGrid', false, false, array($user)));
    }

    public function update2Action(Request $rq) {
        echo "<pre>";
        print_r($rq->get('gridSelectedIds'));
        die;
    }

    public function delete2Action(Request $rq) {
        echo "<pre>";
        print_r($rq->get('gridSelectedIds'));
        die;
    }

    public function submit2Action(Request $rq) {
        echo "<pre>";
        print_r($rq->get('gridSelectedIds'));
        die;
    }

    public function oneSelect2Action(Request $rq) {
        $arr = $rq->get('gridSelectedIds');
        $id = is_array($arr) ? array_pop($arr) : $arr;
        echo "<pre>";
        print_r($id);
        die;
    }
}
