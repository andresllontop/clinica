<?php
if ($peticionAJAX) {
    require_once '../Modelos/loginModelo.php';
} else {
    require_once './Modelos/loginModelo.php';
}

class loginControlador extends loginModelo
{

    public function agregar_login_controlador()
    {
        $Nombre = mainModel::limpiar_cadena($_POST['nombre-reg']);
        $Dni = mainModel::limpiar_cadena($_POST['DNI-reg']);
        $Apellido = mainModel::limpiar_cadena($_POST['apellido-reg']);
        $Telefono = mainModel::limpiar_cadena($_POST['telefono-reg']);

        $usuario = mainModel::limpiar_cadena($_POST['usuario-reg']);
        $password1 = mainModel::limpiar_cadena($_POST['password1-reg']);
        $password2 = mainModel::limpiar_cadena($_POST['password2-reg']);
        $email = mainModel::limpiar_cadena($_POST['email-reg']);
        $privilegio = mainModel::limpiar_cadena($_POST['privilegio-reg']);

        if ($password1 != $password2) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrio un error inesperado",
                "Texto" => "las contraseñas que acabas de ingresar no coinciden",
                "Tipo" => "error",

            ];
        } else {
            $consulta1 = mainModel::ejecutar_consulta_simple("SELECT AdminDNI FROM login
                                 WHERE AdminDNI='$Dni'");
            if (count($consulta1) >= 1) {
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrio un error inesperado",
                    "Texto" => "el DNI que acaba de ingresar ya se encuentra registrado en el sistema",
                    "Tipo" => "error",

                ];
            } else {
                if ($email != "") {
                    $consulta2 = mainModel::ejecutar_consulta_simple("SELECT email FROM cuenta
                    WHERE email=' $email'");
                    $ec = count($consulta2);
                } else {
                    $ec = 0;
                }
                if ($ec >= 1) {
                    $alerta = [
                        "Alerta" => "simple",
                        "Titulo" => "Ocurrio un error inesperado",
                        "Texto" => "el EMAIL que acaba de ingresar ya se encuentra registrado en el sistema",
                        "Tipo" => "error",

                    ];
                } else {
                    $consulta3 = mainModel::ejecutar_consulta_simple("SELECT usuario FROM cuenta
                    WHERE usuario=' $usuario'");

                    if (count($consulta3) >= 1) {
                        $alerta = [
                            "Alerta" => "simple",
                            "Titulo" => "Ocurrio un error inesperado",
                            "Texto" => "el Usuario que acaba de ingresar ya se encuentra registrado
                                en el sistema",
                            "Tipo" => "error",

                        ];
                    } else {
                        $consulta4 = mainModel::ejecutar_consulta_simple("SELECT idcuenta FROM cuenta");
                        $numero = count($consulta4) + 1;

                        $codigo = mainModel::generar_codigo_aleatorio("AC", 7, $numero);

                        $clave = mainModel::encryption($password1);
                        $dataAC = [
                            "Codigo" => $codigo,
                            "Privilegio" => $privilegio,
                            "Usuario" => $usuario,
                            "Clave" => $clave,
                            "Email" => $email,
                            "Estado" => "Activo",
                            "Tipo" => "login",
                            "Foto" => "foto",
                        ];
                        $guardarCuenta = mainModel::agregar_cuenta($dataAC);
                        if ($guardarCuenta >= 1) {
                            $dataAD = [
                                "Dni" => $Dni,
                                "Nombre" => $Nombre,
                                "Apellido" => $Apellido,
                                "Telefono" => $Telefono,
                                "Codigo" => $codigo,
                            ];

                            $guardarAdmin = loginModelo::agregar_login_modelo($dataAD);
                            if ($guardarAdmin >= 1) {
                                $alerta = [
                                    "Alerta" => "limpiar",
                                    "Titulo" => "Adminitrador Registrado",
                                    "Texto" => "El login se registro con exito en el sistema",
                                    "Tipo" => "success",
                                ];

                            } else {
                                mainModel::eliminar_cuenta($codigo);
                                $alerta = [
                                    "Alerta" => "simple",
                                    "Titulo" => "Ocurrio un error inesperado",
                                    "Texto" => "No hemos podido registrar el login",
                                    "Tipo" => "error",

                                ];
                            }

                        } else {
                            $alerta = [
                                "Alerta" => "simple",
                                "Titulo" => "Ocurrio un error inesperado",
                                "Texto" => "No hemos podido registrar el login",
                                "Tipo" => "error",

                            ];
                        }

                    }
                }
            }
        }

        return json_encode($alerta);

    }
    public function datos_login_controlador()
    {
        $data = [
            "Email" => mainModel::limpiar_cadena($_POST['Email-reg']),
            "Clave" => mainModel::encryption(mainModel::limpiar_cadena($_POST['Password-reg']))
        ];
   
        return loginModelo::datos_login_modelo($data);

    }
    public function paginador_login_controlador($pagina, $registros, $privilegio, $codigo)
    {
        $pagina = mainModel::limpiar_cadena($pagina);
        $registros = mainModel::limpiar_cadena($registros);
        $codigo = mainModel::limpiar_cadena($codigo);
        $tabla = "";
        $pagina = (isset($pagina) && $pagina > 0) ? (int) $pagina : 1;
        $inicio = ($pagina) ? (($pagina * $registros) - $registros) : 0;
        $conexion = mainModel::__construct();

        $datos = $conexion->query("SELECT SQL_CALC_FOUND_ROWS *
                        FROM login WHERE id!='1' ORDER BY AdminNombre
                        ASC LIMIT $inicio,$registros
                        ");
        $datos = $datos->fetchAll();
        $total = $conexion->query("SELECT  FOUND_ROWS()");

        $total = (int) $total->fetchColumn();
        $Npaginas = ceil($total / $registros);
        if ($total >= 1 && $pagina <= $Npaginas) {
            return json_encode($datos);
        } else {
            $tabla = 'ninguno';
            return $tabla;
        }

    }
    public function eliminar_login_controlador()
    {
        $codigo = mainModel::limpiar_cadena($_POST['codigo-del']);
        $guardarAdmin = loginModelo::eliminar_login_modelo($codigo);

        if ($guardarAdmin >= 1) {
            $alerta = [
                "Alerta" => "limpiar",
                "Titulo" => "Adminitrador Eliminado",
                "Texto" => "El login se Elimino con éxito en el sistema",
                "Tipo" => "success",
            ];

        } else {

            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrio un error inesperado",
                "Texto" => "No hemos podido Eliminar el login",
                "Tipo" => "error",

            ];
        }
        return json_encode($alerta);

    }
}
