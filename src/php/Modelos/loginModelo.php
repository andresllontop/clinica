<?php
if ($peticionAJAX) {
    require_once '../Core/mainModel.php';
} else {
    require_once './Core/mainModel.php';
}

class loginModelo extends mainModel
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function agregar_login_modelo($datos)
    {
        $sql = parent::__construct()->prepare("INSERT INTO `cuenta`
        (AdminDNI,AdminNombre,AdminApellido,AdminTelefono,CuentaCodigo)
         VALUES(:DNI,:Nombre,:Apellido,:Telefono,:Codigo)");
        $sql->bindParam(":DNI", $datos['Dni']);
        $sql->bindParam(":Nombre", $datos['Nombre']);
        $sql->bindParam(":Apellido", $datos['Apellido']);
        $sql->bindParam(":Telefono", $datos['Telefono']);
        $sql->bindParam(":Codigo", $datos['Codigo']);

        $sql->execute();
        $resultado = $sql->rowCount();
        $sql->closeCursor(); //cerrar tabla virtual
        $this->conexion_db = null; //cerrar la conexion
        return $resultado;

    }
    protected function eliminar_login_modelo($codigo)
    {
        $sql = parent::__construct()->prepare("DELETE FROM `cuenta`
        WHERE  CuentaCodigo=:Codigo ");
        $sql->bindParam(":Codigo", $codigo);
        $sql->execute();
        $resultado = $sql->rowCount();
        $sql->closeCursor(); //cerrar tabla virtual
        return $resultado;
        $this->conexion_db = null; //cerrar la conexion
    }
    protected function datos_login_modelo($datos)
    {
        $sql = parent::__construct()->prepare("SELECT * FROM `cuenta` 
        WHERE email=:Email and clave=:Clave");
        $sql->bindParam(":Email", $datos['Email']);
        $sql->bindParam(":Clave", $datos['Clave']);
        $sql->execute();
        $contador = $sql->rowCount();
        if ($contador >= 1) {
            $resultado = $sql->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $resultado = "ninguno";
        }
        $sql->closeCursor(); //cerrar tabla virtual
        return $resultado;
    }
}
