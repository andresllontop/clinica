<?php
require_once './src/php/Modelos/vistasModelo.php';
class vistasControlador extends vistasModelo
{

    public function obtener_plantilla_controlador()
    {
        return require_once './vistas/plantilla.php';
    }
    public function obtener_vistas_controlador()
    {
       
        if (isset($_GET['views'])) {
            $ruta = explode("/", $_GET['views']);
            if ($ruta[0]=='index') {
                $respuesta = "login";
            } else {
                $respuesta = vistasModelo::obtener_vistas_modelo($ruta[0]);
            }
        } else {
            $respuesta = "login";
        }
        return $respuesta;

    }
    public function obtener_vistas_controlador_publico($ruta)
    {
        $respuesta = vistasModelo::obtener_vistas_modelo_publico($ruta);
        return $respuesta;

    }
    public function obtener_vistas_controlador_admin()
    {
        if (isset($_GET['views'])) {
            $ruta = explode("/", $_GET['views']);
            if ($ruta[0]=="login") {
                $respuesta = 'login';
            } else {
                $respuesta = vistasModelo::obtener_vistas_modelo_admin($ruta[0]);
            }
        }else{
            $respuesta = 'login';
        }
        return $respuesta;
    }
    public function obtener_vistas_controlador_cliente($ruta)
    {
        $respuesta = vistasModelo::obtener_vistas_modelo_cliente($ruta);
        return $respuesta;

    }
    public function obtener_vistas_controlador_parametro($ruta)
    {
        $respuesta = self::obtener_vistas_controlador_publico($ruta);

        if ($respuesta['Vista'] == 'Publico') {
            return $respuesta;
        } else {
            $respuesta = self::obtener_vistas_controlador_cliente($ruta);

            if ($respuesta['Vista'] == 'Cliente') {
                return $respuesta;
            } else {
                $respuesta = self::obtener_vistas_controlador_admin($ruta);
                if ($respuesta['Vista'] == 'Admin') {
                    return $respuesta;
                } else {
                    $data = [
                        "Vista" => "Publico",
                        "URL" => "home",
                    ];
                    return $data;
                }

            }

        }

    }

}
