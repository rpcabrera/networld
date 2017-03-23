<?php
/**
 * Created by PhpStorm.
 * User: cronos
 * Date: 19/09/16
 * Time: 9:16
 */

namespace ArquitecturaBaseBundle\Listeners;


use Symfony\Component\HttpKernel\Event\GetResponseEvent;

class MigaListener
{
   public function onKernelRequest(GetResponseEvent $event) {
            $request = $event->getRequest();
            $id_ruta =$request->attributes->get('_route');
            $p_ruta = $request->attributes->get('_route_params');
            $sesion = $request->getSession();
            $arr = array();
            if (!is_null($p_ruta) && array_key_exists('etiqueta', $p_ruta)) {
                $params_ruta['etiqueta'] = $p_ruta['etiqueta'];

                $url = $request->getRequestUri();
                $parts = parse_url($url);
                $qs = array();
                if (isset($parts['query'])) {
                    parse_str($parts['query'], $qs);
                    if(isset($qs['_ajax'])) {
                        unset($qs['_ajax']);
                    }
                    $path = $parts['path'];
                    $query = http_build_query($qs);
                    $url = "$path?$query";
                }

                $params_ruta['ruta'] = $url;

                if ($sesion->has('migas')) {
                    $miga = $sesion->get('migas');
                    foreach ($miga as $key => $item) {
                        $arr[$key] = $item;
                        if ($key == $id_ruta) {
                            break;
                        }
                    }
                    if (count($miga) == count($arr)) {
                        $flag = false;
                        $arr = array_reverse($arr);
                        foreach ($arr as $key => $item) {
                            if (array_key_exists('padre', $p_ruta) && is_array($p_ruta['padre']) && in_array($key, $p_ruta['padre'])) {
                                $arr = array_reverse($arr);
                                $arr[$id_ruta] = $params_ruta;
                                $flag = true;
                                break;
                            } else {
                                unset($arr[$key]);
                            }
                        }
                        if (!$flag) {
                            $arr = array();
                            $arr[$id_ruta] = $params_ruta;
                        }
                    }
                } else {
                    $arr[$id_ruta] = $params_ruta;
                }
                $sesion->set('migas', $arr);
            }
    }
}