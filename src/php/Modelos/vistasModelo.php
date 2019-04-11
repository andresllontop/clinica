<?php
class vistasModelo
{
    protected function obtener_vistas_modelo($vistas)
    {
        $listaBlanca = ["registrate","forget","login"];
        if (in_array($vistas, $listaBlanca)) {
            if (file_exists("./src/webapp/views/contenidos/$vistas-view.html")) {
                $contenido = $vistas;
            } else {
                $contenido = "404";
            }
        } else {
            $contenido = "404";
        }
        return $contenido;

    }
    protected function obtener_vistas_modelo_publico($vistas)
    {
        $listaBlanca = ["login","registrate","forget"];
        if (in_array($vistas, $listaBlanca)) {
            if (is_file("./src/webapp/views/contenidos/$vistas-view.html")) {
                $data = [
                    "Vista" => "Publico",
                    "URL" => $vistas
                ];
            } else {
                $data = [
                    "Vista" => "Publico",
                    "URL" => "home"
                ];
            }
            return $data;
        } else {
            $data = [
                "Vista" => "NO"
            ];
            return $data;
        }

    }
    protected function obtener_vistas_modelo_admin($vistas)
    {
        $listaBlanca = ["home"];
        if (in_array($vistas, $listaBlanca)) {
            if (is_file("./src/webapp/views/contenidos/$vistas-view.html")) {
                return $vistas;
            } else {
                return "404";
            }
        } else {
            return "404";
        }
    }
    protected function obtener_vistas_modelo_cliente($vistas)
    {
        $listaBlanca = [];
        if (in_array($vistas, $listaBlanca)) {

            if (is_file("./vistas/contenidos/" . $vistas . "-view.php")) {
                $data = [
                    "Vista" => "Cliente",
                    "URL" => $vistas,
                ];

            } else {
                $data = [
                    "Vista" => "Cliente",
                    "URL" => "catalog",
                ];

            }
            return $data;
        } else {
            $data = [
                "Vista" => "NO",
            ];
            return $data;

        }

    }
}
