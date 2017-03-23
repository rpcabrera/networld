<?php

namespace ArquitecturaBaseBundle\Controller;

use Doctrine\ORM\PersistentCollection;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bundle\FrameworkBundle\Translation\Translator;
use Symfony\Component\BrowserKit\Request;

class BaseController extends Controller
{
    public function inicioAction(){
        return $this->render('ArquitecturaBaseBundle:Default:index.html.twig');
    }

    //<editor-fold defaultstate="collapsed" desc="Métodos auxiliares">
    const Error = 'error';
    const Satisfactorio = 'satisfactorio';
    const Informacion = 'informacion';
    const Advertencia = 'advertencia';

    /**
     * Este método permite adicionar a los errores FlashBag
     * un tipo de alerta  y traducir el mensaje según el el filtro que está
     * activado en el sistema.
     * @param $key
     * @param $msgs
     */
    public function Alerta($key, $msgs) {
        foreach ($msgs as $msg) {
            $msg = $this->get('translator')->trans($msg);
            $this->get('session')->getFlashBag()->add($key, $msg);
        }
    }

    /**
     * Este Método retorna el Entity Manager
     * @return \Doctrine\Common\Persistence\ObjectManager|object
     */
    public function getEM()
    {
        return $this->getDoctrine()->getManager();
    }

    /**
     * Este Método permite traducir el el mensaje según
     * el filtro que está activado en el sistema.
     * @param $msg
     * @return string
     */
    protected function traduceMensaje($msg) {
        $trans = $this->get('translator');
        /* @var $trans Translator */
        $msg = $trans->trans($msg);
        return $msg;
    }

    //</editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Grid">

    protected function Mostrar(Request $peticion, $columnas, $consultar, $metodo, $isArray, $loadOnce, $parametros = null) {
        $draw = $peticion->get('draw', 0);
        $columns = $peticion->get('columns');
        $order = $peticion->get('order');
        $start = intval($peticion->get('start', 0));
        $length = intval($peticion->get('length', 5));
        $search = $peticion->get('search');
        $end = $length;

        if(!$parametros) {
            $parametros = array();
        }

        if(!is_array($parametros)) {
            $arr = array($parametros);
            $parametros = $arr;
        }

        $count = 0;
        $list = array();

        if ($loadOnce) {
            $list = $this->Resolver($consultar, $metodo, $parametros);
            $count = count($list);
        } else {
            $orderFirst = isset($order[0]) ? $order[0] : array('column' => 0, 'dir' => 'asc');
            $column = $orderFirst['column'];
            $dir = $orderFirst['dir'];

            $searchValue = $search['value'];
            $searchColumns = array();

            for($i = 0; $i < count($columns); $i++) {
                $col = $columns[$i];
                $dataIndex = $col['data'];

                if($col['searchable'] == 'true') {
                    $searchColumns[] = $columnas[$dataIndex];
                }
            }

            $parametros['count'] = true;
            $parametros[] = $start;
            $parametros[] = $end;
            $parametros[] = $columnas[$column];
            $parametros[] = $dir;
            $parametros[] = array(
                'value' => $searchValue,
                'fields' => $searchColumns,
            );

            $count = $this->Resolver($consultar, $metodo, $parametros);
            $parametros['count'] = null;

            if ($count > 0) {
                $list = $this->Resolver($consultar, $metodo, $parametros);
            }
        }

        $records["data"] = array();
        $records["draw"] = $draw;
        $records["recordsTotal"] = $count;
        $records["recordsFiltered"] = $count;

        $cant_columnas = count($columnas);
        if (!$isArray) {
            foreach ($list as $item) {
                $arr = array();
                for ($j = 0; $j < $cant_columnas; $j++) {
                    $srt = 'return ' . '$item->' . $columnas[$j] . '();';
                    $val = eval($srt);
                    $arr[] = $this->parseData($val);
                }
                $records["data"][] = $arr;
            }
        } else {
            foreach ($list as $item) {
                $arr = array();
                for ($j = 0; $j < $cant_columnas; $j++) {
                    if(is_callable($columnas[$j])) {
                        $arr[] = call_user_func($columnas[$j], $item);
                    } else {
                        $srt = 'return ' . '$item["' . $columnas[$j] . '"];';
                        $val = eval($srt);
                        $arr[] = $this->parseData($val);
                    }
                }
                $records["data"][] = $arr;
            }
        }

        return $records;
    }

    private function Resolver($obj, $metodo, array $prm = null) {
        $objeto = null;

        if (!$this->container->has($obj)) {
            $type = "repositorio";
            $objeto = $this->getDoctrine()->getEntityManager()->getRepository($obj);
        } else {
            $type = "servicio";
            $objeto = $this->container->get($obj);
        }

        $result = null;
        $reflectionObject = new \ReflectionObject($objeto);
        $encontrado = false;

        foreach ($reflectionObject->getMethods() as $reflectionMethod) {
            if ($reflectionMethod->getName() === $metodo) {
                $encontrado = true;

                if ($prm === null) {
                    $result = $reflectionMethod->invoke($objeto);
                } else {
                    $result = $reflectionMethod->invokeArgs($objeto, $prm);
                }
            }
        }

        if (!$encontrado) {
            throw new \Exception("El metodo $metodo no se encuentra en el $type $obj");
        }

        return $result;
    }

    private function parseData($data) {
        $result = "";

        if($data instanceof PersistentCollection) {
            foreach ($data as $d) {
                if($result == "") {
                    $result .= $this->parseData($d);
                } else {
                    $result .= ", " . $this->parseData($d);
                }
            }
        } else if(is_object($data) ) {
            $result .= $data->__toString();
        } else {
            $result .= $data;
        }

        return $result;
    }

    // </editor-fold>

}
