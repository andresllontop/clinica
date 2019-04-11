<?php
if ($peticionAJAX) {
    require_once '../core/configAPP.php';
} else {
    require_once './core/configAPP.php';
}

class mainModel
{
    private $conexion_db;
    protected function __construct()
    {  
        try {
            $this->conexion_db = new PDO(SGDB, USER, PASS);
            $this->conexion_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conexion_db->exec(" SET CHARACTER SET 'utf8' ");
             //para tildes y ñ
            return $this->conexion_db;
        } catch (Exception $th) {
            echo ("la linea de error es:" . $th->getLine());
        }

    }
    protected function ejecutar_consulta_simple($consulta)
    {
        $respuesta = $this->conexion_db->prepare($consulta);
        $respuesta->execute();
        $resultado = $respuesta->fetchAll(PDO::FETCH_ASSOC);
        $respuesta->closeCursor(); //cerrar tabla virtual
        return $resultado;
        $this->conexion_db = null; //cerrar la conexion
    }
    protected function agregar_cuenta($datos)
    {
        $sql = $this->conexion_db->prepare("INSERT INTO `cuenta`
        (CuentaCodigo,usuario,clave,email,estado,tipo,foto,voucher) 
            VALUES(:Codigo,:Usuario,:Clave,:Email,:Estado,:Tipo,:Foto,:Voucher)");
        $sql->bindParam(":Codigo", $datos['Codigo']);
        $sql->bindParam(":Usuario", $datos['Usuario']);
        $sql->bindParam(":Clave", $datos['Clave']);
        $sql->bindParam(":Email", $datos['Email']);
        $sql->bindParam(":Estado", $datos['Estado']);
        $sql->bindParam("Tipo", $datos['Tipo']);
        $sql->bindParam(":Foto", $datos['Foto']);
        $sql->bindParam(":Voucher", $datos['Voucher']);

        $sql->execute();
        $resultado = $sql->rowCount();
        $sql->closeCursor(); //cerrar tabla virtual
        $this->conexion_db = null; //cerrar la conexion

        return $resultado;

    }
    protected function datos_cuenta($tipo, $codigo)
    {
        if ($tipo == "unico") {
            $sql = $this->conexion_db->prepare("SELECT * FROM `cuenta` WHERE idcuenta=:IDcliente");
            $sql->bindParam(":IDcliente", $codigo);
        } elseif ($tipo == "conteo") {
            $sql = $this->conexion_db->prepare("SELECT * FROM `cuenta` ");
        } elseif ($tipo == "clave") {
            $sql = $this->conexion_db->prepare("SELECT * FROM `cuenta` WHERE clave=:IDcliente");
            $sql->bindParam(":IDcliente", $codigo);
        } elseif ($tipo == "codigo") {
            $sql = $this->conexion_db->prepare("SELECT * FROM `cuenta` WHERE CuentaCodigo=:IDcliente");
            $sql->bindParam(":IDcliente", $codigo);
        } elseif ($tipo == "verificacion") {
            $sql = $this->conexion_db->prepare("SELECT idcuenta FROM `cuenta` WHERE
                usuario=:Usuario AND clave=:Clave AND email=:Email ");
            $sql->bindParam(":Usuario", $codigo['Usuario']);
            $sql->bindParam(":Clave", $codigo['Clave']);
            $sql->bindParam(":Email", $codigo['Email']);
        }

        $sql->execute();
        $resultado = $sql->fetchAll(PDO::FETCH_ASSOC);
        $sql->closeCursor(); //cerrar tabla virtual
        return $resultado;
        $this->conexion_db = null;
    }
    protected function eliminar_cuenta($codigo)
    {
        $sql = $this->conexion_db->prepare("DELETE FROM `cuenta` WHERE
         idcuenta=:Codigo ");
        $sql->bindParam(":Codigo", $codigo);
        $sql->execute();
        $resultado = $sql->rowCount();
        $sql->closeCursor(); //cerrar tabla virtual
        return $resultado;
        $this->conexion_db = null; //cerrar la conexion
    }
    
    protected function actualizar_cuenta($datos)
    {
       
        $sql = $this->conexion_db->prepare("UPDATE `cuenta` 
        SET usuario=:Usuario,foto=:Foto,email=:Email,clave=:Clave   WHERE idcuenta=:ID");
        $sql->bindParam(":Usuario", $datos['Usuario']);
        $sql->bindParam(":Foto", $datos['Foto']);
        $sql->bindParam(":Email", $datos['Email']);
        $sql->bindParam(":ID", $datos['ID']);
        $sql->bindParam(":Clave", $datos['Clave']);
        $sql->execute();
        $resultado = $sql->rowCount($sql);
        $sql->closeCursor(); //cerrar tabla virtual
        return $resultado;
        $this->conexion_db = null; //cerrar la conexion
    }
    protected function encryption($string)
    {
        $output = false;
        $key = hash('sha256', SECRET_KEY);
        $iv = substr(hash('sha256', SECRET_IV), 0, 16);
        $output = openssl_encrypt($string, METHOD, $key, 0, $iv);
        $output = base64_encode($output);
        return $output;
    }
    protected function decryption($string)
    {
        $key = hash('sha256', SECRET_KEY);
        $iv = substr(hash('sha256', SECRET_IV), 0, 16);
        $output = openssl_decrypt(base64_decode($string), METHOD, $key, 0, $iv);
        return $output;
    }
    protected function generar_codigo_aleatorio($letra, $longitud, $num)
    {
        for ($i = 1; $i <= $longitud; $i++) {
            $numero = rand(0, 9);
            $letra .= $numero;
        }
        return $letra . $num;
    }
    protected function limpiar_cadena($cadena)
    {
        $cadena = trim($cadena);
        $cadena = stripcslashes($cadena); //quitar las barrar invertidas
        $cadena = str_ireplace("<script>", "", $cadena);
        $cadena = str_ireplace("</script>", "", $cadena);
        $cadena = str_ireplace("<script src", "", $cadena);
        $cadena = str_ireplace("<script type", "", $cadena);
        $cadena = str_ireplace("SELECT * FROM", "", $cadena);
        $cadena = str_ireplace("DELETE FROM", "", $cadena);
        $cadena = str_ireplace("INSERT FROM", "", $cadena);
        $cadena = str_ireplace("--", "", $cadena);
        $cadena = str_ireplace("^", "", $cadena);
        $cadena = str_ireplace("[", "", $cadena);
        $cadena = str_ireplace("]", "", $cadena);
        $cadena = str_ireplace("==", "", $cadena);
        return $cadena;

    }
    protected function sweet_alert($datos)
    {
        if ($datos['Alerta']) {
            $alerta = "
            <script>
            swal(
                '" . $datos['Titulo'] . "',
                '" . $datos['Texto'] . "',
                '" . $datos['Tipo'] . "'
            );
            </script>
           ";
        } elseif ($datos['Alerta'] == "recargar") {
            $alerta = "
            <script>
            swal({
                title: '" . $datos['Titulo'] . "',
                text: '" . $datos['Texto'] . "',
                type: '" . $datos['Tipo'] . "',
                confirmButtonText: 'Aceptar'
            },function(){
                location.reload(true);
                }
              );
            </script>
           ";
        } elseif ($datos['Alerta'] == "limpiar") {
            $alerta = "
            <script>
            swal({
            title: '" . $datos['Titulo'] . "',
            text: '" . $datos['Texto'] . "',
            type: '" . $datos['Tipo'] . "',
            confirmButtonText: 'Aceptar'
              },function() {
                document.getElementById('miForm').reset();
              });
            </script>
           ";
        }
        return $alerta;

    }
    protected function archivo($permitidos,$limite_MB,$original, $nombre,$destino){

        if (in_array($original['type'], $permitidos)&& ($original['size']<=$limite_MB*1024)){
            $array_nombre=explode('.',$nombre);
            $extension=array_pop($array_nombre);
            $array=glob($destino.$array_nombre[0]."*.".$extension);
            $cantidad=count($array);
            $nombreImagen=$array_nombre[0].$cantidad.".".$extension;
            $resultado_guardado=move_uploaded_file($original['tmp_name'],$destino.$nombreImagen);
            if ($resultado_guardado) {
               return  $nombreImagen;
            } else {
                return "";
            }
            
        } else {
            return "";
        }
        
}

}
