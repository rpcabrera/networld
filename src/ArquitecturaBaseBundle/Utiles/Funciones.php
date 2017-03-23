<?php
/**
 * Created by PhpStorm.
 * User: cronos
 * Date: 22/09/16
 * Time: 12:13
 */

namespace ArquitecturaBaseBundle\Utiles;


class Funciones
{

    /**
     * @return string (ip del cliente)
     */
    public static function IpAddress() {
        return $_SERVER['REMOTE_ADDR'];
    }

    /**
     * Determina la direccion MAC a traves de la IP cliente. Para ello se ejecuta
     * un comando dependiendo del Sistema Operativo para obtener mediante el protocolo
     * de TCP/IP (caracteristicas del sistema operativo) la direccion MAC del
     * cliente conectado al servidor utilizando un IP dado. Nota: Esta funcionalidad
     * no se encuentra soportada para todas las distribuciones de Linux.
     *
     * @param String $ip IP del cliente conectado
     * @return String Direccion MAC del cliente.
     */
    public static function getmac($ip) {

        if ($ip == 'localhost') {
            $ip = '127.0.0.1';
        }
        if ($ip == '::1') {
            $ip = '127.0.0.1';
        }
        $serversoftware = strtolower($_SERVER['SERVER_SOFTWARE']);
        $httpuseragent = strtolower($_SERVER['HTTP_USER_AGENT']);
        //Si la direccion IP es la propia PC del servidor (Para entornos de desarrollo)
        if ($ip == '127.0.0.1') {
            //Si el sistema operativo utilizado es de 32 o 64 bits, obtener del comando de consola ipconfig
            if (
                strpos($serversoftware, "win32") !== false ||
                strpos($serversoftware, "win64") !== false ||
                strpos($httpuseragent, "windows") !== false ||
                strpos($httpuseragent, "wow32") !== false ||
                strpos($httpuseragent, "wow64") !== false
            ) {
                $command = "ipconfig/all";
                $arpTable = shell_exec($command);
                $arpSplitted = preg_match_all("/((?:[0-9a-f]{2}[:-]){5}[0-9a-f]{2})/i", $arpTable, $mac);
                return $mac[0][0];
            }
            //Si es un sistema operativo de Linux
            else if (
                strpos($serversoftware, "debian") !== false ||
                strpos($serversoftware, "apache") !== false ||
                strpos($serversoftware, "development server") !== false ||
                strpos($serversoftware, "ubuntu") !== false
            )
            {
                $command = "/sbin/ifconfig";
                $arpTable = shell_exec($command);
                $arpSplitted = preg_match_all("/((?:[0-9a-f]{2}[:-]){5}[0-9a-f]{2})/i", $arpTable, $mac);
                return $mac[0][0];
            }
        }
        //Si la IP del cliente no es la PC local (Para entorno de despliegue)
        if (strpos($serversoftware, "win32") !== false || strpos($serversoftware, "win64") !== false) {
            $command = "arp -a " . $ip;
            $arpTable = shell_exec($command);
            $arpSplitted = preg_match_all("/((?:[0-9a-f]{2}[:-]){5}[0-9a-f]{2})/i", $arpTable, $mac);
            return $mac[0][0];
        }
        //Si es un sistema operativo de Linux


        else if (
            strpos($serversoftware, "debian") !== false ||
            strpos($serversoftware, "ubuntu") !== false ||
            strpos($serversoftware, "apache") !== false )
        {
            $command = "/usr/sbin/arp -a " . $ip . " | awk '{print $4}'";

            $mac = shell_exec($command);
            return $mac;
        }
    }

    /**
     * @return Container
     */
    public static function getContainer(){
        $app_cache = $GLOBALS['kernel'];
        if ($app_cache instanceof \AppCache) {
            $container = $app_cache->getKernel()->getContainer();
        } else {
            $container = $app_cache->getContainer();
        }

        return $container;
    }

}