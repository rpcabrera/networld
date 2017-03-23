<?php
/**
 * Created by PhpStorm.
 * User: cronos
 * Date: 19/10/16
 * Time: 16:38
 */

namespace ArquitecturaBaseBundle\Utiles;



class Util
{
/** Tipo nivel de las trazas*/
    const bajo = 'bajo';
    const alto = 'alto';

    /** tipo de boton */
    const btn_siguiente = "_siguiente_";
    const btn_guardar = "_guardar_";
    const btn_finalizar = "_finalizar_";

    /** Tipo de Mensaje */
    const tipo_msg_error = "Error";
    const tipo_msg_informacion = "Información";
    const tipo_msg_advertencia = "Advertencia";

    public static function util($variable)
    {
        if (defined("self::$variable")) {
            return constant("self::$variable");
        }
    }

    public static function bindSearch($qb, $search, $fieldsName) {
        $arrs = explode(',', $search['value']);

        foreach ($fieldsName as $key1 => $field) {
            foreach ($arrs as $key2 => $it) {
                if($it != '') {
                    $str = strtolower($it);

                    $qb->orWhere($qb->expr()->like("LOWER($field)", ":key_$key1" . "_" . "$key2"))
                        ->setParameter(":key_$key1" . "_" . "$key2", "%$str%");
                }
            }
        }

        return $qb;
    }
}