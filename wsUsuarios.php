<?php

require_once conf_get('coreInstall', 'modules') . 'apiSicocir/modelo/wsConexion.php';

/**************************************************************************************************** */


function actualizaUsuario()
{
    $conexion = Conexion::getInstance()->getConnection();

    $nuevo = params_get('nuevo');
    $cod_usu = params_get('cod_usu');
    $id_usu = params_get('id_usu');
    $nom_usu = params_get('nom_usu');
    $nom_comercial = params_get('nom_comercial');
    $tipo_usu = params_get('tipo_usu');
    $est_usu = params_get('est_usu');

    $fec_reg = date("Y-m-d H:i:s");


    if ($nuevo) {

        // verifica que el ID no este usado

        $sql = "SELECT cod_usuario FROM usuarios WHERE id_usuario='$id_usu'";

        $resultado = $conexion->query($sql);

        if ($resultado->num_rows > 0) {

            return array(
                'estadoRes' => 'error',
                'msg' => 'ID ya esta siendo utilizado'
            );
        }



        $insert = "INSERT INTO usuarios (nom_usuario, id_usuario, nom_comercial, tipo_usuario, fec_reg) VALUES('$nom_usu','$id_usu','$nom_comercial', $tipo_usu, '$fec_reg')";
        $conexion->query($insert);

        return array(
            'estadoRes' => 'success',
            'msg' => 'Usuario Incluido'
        );
    } else {

        $update = "UPDATE usuarios SET nom_usuario='$nom_usu', nom_comercial='$nom_comercial', tipo_usuario=$tipo_usu, est_usuario=$est_usu WHERE cod_usuario=$cod_usu";
        $conexion->query($update);

        return array(
            'estadoRes' => 'success',
            'msg' => 'Usuario Modificado'
        );
    }
}

/************************************************************************************************ */


function reseteaPin()
{
    $conexion = Conexion::getInstance()->getConnection();


    $cod_usu = params_get('cod_usu');

    $update = "UPDATE usuarios SET pin_pass='1234' WHERE cod_usuario=$cod_usu";
    $conexion->query($update);

    return array(
        'estadoRes' => 'success',
        'msg' => 'PIN reseteado'
    );
}

/************************************************************************************************ */


function listaUsuarios()
{

    $conexion = Conexion::getInstance()->getConnection();


    $consulta = "SELECT cod_usuario, nom_usuario, id_usuario, tipo_usuario, est_usuario 
                    FROM usuarios 
                    WHERE tipo_usuario > 1
                    ORDER BY nom_usuario";

    $response = [];

    $resultado = $conexion->query($consulta);

    if ($resultado->num_rows > 0) {

        while ($registro = $resultado->fetch_assoc()) {

            $response[] = $registro;
        }
    }

    return $response;
}


function actualizaDistribuidor()
{
    $conexion = Conexion::getInstance()->getConnection();

    $nuevo = params_get('nuevo');
    $cod_usu = params_get('cod_usu');
    $id_usu = params_get('id_usu');
    $nom_usu = params_get('nom_usu');
    $cod_eje = params_get('cod_eje');
    $tipo_usu = 1;
    $est_usu = params_get('est_usu');

    $fec_reg = date("Y-m-d H:i:s");


    if ($nuevo) {

        // verifica que el ID no este usado

        $sql = "SELECT cod_usuario FROM usuarios WHERE id_usuario='$id_usu'";

        $resultado = $conexion->query($sql);

        if ($resultado->num_rows > 0) {

            return array(
                'estadoRes' => 'error',
                'msg' => 'ID ya esta siendo utilizado'
            );
        }



        $insert = "INSERT INTO usuarios (nom_usuario, id_usuario, cod_ejecutivo, tipo_usuario, fec_reg) VALUES('$nom_usu','$id_usu', $cod_eje, $tipo_usu, '$fec_reg')";
        $conexion->query($insert);

        return array(
            'estadoRes' => 'success',
            'msg' => 'Distribuidor Incluido'
        );
    } else {

        $update = "UPDATE usuarios SET nom_usuario='$nom_usu', cod_ejecutivo=$cod_eje, tipo_usuario=$tipo_usu, est_usuario=$est_usu WHERE cod_usuario=$cod_usu";
        $conexion->query($update);

        return array(
            'estadoRes' => 'success',
            'msg' => 'Distribuidor Modificado'
        );
    }
}

/************************************************************************************************ */

function listaDistribuidores()
{

    $conexion = Conexion::getInstance()->getConnection();

    $filtro = params_get('filtro');
    $cod_ejecutivo = params_get('cod_ejecutivo');
    $tipo_usu = params_get('tipo_usu');

    if ($filtro == 1) {

        if ($tipo_usu < 3) {

            $consulta = "SELECT cod_usuario, nom_usuario, id_usuario, tipo_usuario, est_usuario 
                    FROM usuarios 
                    WHERE tipo_usuario = 1 AND est_usuario=1 AND cod_ejecutivo=$cod_ejecutivo
                    ORDER BY nom_usuario";
        } else {

            $consulta = "SELECT cod_usuario, nom_usuario, id_usuario, tipo_usuario, est_usuario 
                    FROM usuarios 
                    WHERE tipo_usuario = 1 AND est_usuario=1
                    ORDER BY nom_usuario";
        }
    } else {
        if ($tipo_usu > 3) {

            $consulta = "SELECT cod_usuario, nom_usuario, id_usuario, tipo_usuario, est_usuario 
                    FROM usuarios 
                    WHERE tipo_usuario = 1 AND cod_ejecutivo=$cod_ejecutivo
                    ORDER BY nom_usuario";
        } else {
            $consulta = "SELECT cod_usuario, nom_usuario, id_usuario, tipo_usuario, est_usuario 
                    FROM usuarios 
                    WHERE tipo_usuario = 1
                    ORDER BY nom_usuario";
        }
    }

    $response = [];

    $resultado = $conexion->query($consulta);

    if ($resultado->num_rows > 0) {

        while ($registro = $resultado->fetch_assoc()) {

            $response[] = $registro;
        }
    }

    return $response;
}

/************************************************************************************************ */

function listaEjecutivos()
{

    $conexion = Conexion::getInstance()->getConnection();

    $consulta = "SELECT cod_usuario, nom_usuario
                    FROM usuarios 
                    WHERE tipo_usuario = 2 AND est_usuario=1
                    ORDER BY nom_usuario";

    $response = [];

    $resultado = $conexion->query($consulta);

    if ($resultado->num_rows > 0) {

        while ($registro = $resultado->fetch_assoc()) {

            $response[] = $registro;
        }
    }

    return $response;
}



/*********************************************************************************************** */

function consultaDistribuidor()
{

    global $params;

    $conexion = Conexion::getInstance()->getConnection();

    $cod_usuario = params_get('cod_usuario');
    $cod_ejecutivo = isset($params['cod_ejecutivo']) ? params_get('cod_ejecutivo') : 0; // Recibo el codigo del usuario que consulta
    $tipo_usu = isset($params['tipo_usu']) ? params_get('tipo_usu') : 2; // Recibe el tipo de usuario que consulta

    $consulta = "SELECT id_usuario, nom_usuario, tipo_usuario, cod_ejecutivo, est_usuario FROM usuarios WHERE cod_usuario=$cod_usuario AND tipo_usuario=1";

    $resultado = $conexion->query($consulta);

    if ($resultado->num_rows > 0) {

        $registro = $resultado->fetch_assoc();

        if ($registro['est_usuario'] == 0) {

            return array(
                'estadoRes' => 'error',
                'msg' => 'Distribuidor Inactivo',
                'datos' => null
            );
        }
        if ($registro['cod_ejecutivo'] != $cod_ejecutivo && $tipo_usu < 3) {

            return array(
                'estadoRes' => 'error',
                'msg' => 'Distribuidor NO asignado a este usuario',
                'datos' => null
            );
        }

        return  array(
            'estadoRes' => 'success',
            'msg' => '',
            'datos' => $registro
        );
    } else {

        return array(
            'estadoRes' => 'error',
            'msg' => 'Distribuidor NO existe',
            'datos' => null
        );
    }
}


/*********************************************************************************************** */

function consultaPrecioDSD()
{


    $conexion = Conexion::getInstance()->getConnection();

    $cod_usuario = params_get('cod_usuario');
    $cod_item = params_get('cod_item');
    $fec_entrega = params_get('fec_entrega');

    $consulta = "SELECT pre_item FROM usuarios_pre WHERE cod_usuario=$cod_usuario AND cod_item=$cod_item AND fec_ini<'$fec_entrega' ORDER BY fec_ini DESC LIMIT 1";

    $response['pre_item'] = 0;

    $resultado = $conexion->query($consulta);

    if ($resultado->num_rows > 0) {

        $registro = $resultado->fetch_assoc();

        $response = $registro;
    }

    return $response;
}


/**************************************************************************************
 *    Login usuario
 */

function loginUsuario()
{

    $conexion = Conexion::getInstance()->getConnection();

    $id_usuario = params_get('id_usuario');
    $pin_pass = params_get('pin_pass');


    $consulta = "SELECT cod_usuario, nom_usuario, pin_pass, tipo_usuario, est_usuario FROM usuarios WHERE id_usuario='$id_usuario'";

    $response = array(
        'estadoRes' => 'error',
        'msg' => 'Usuario NO existe',
        'datos' => null
    );

    $resultado = $conexion->query($consulta);

    if ($resultado->num_rows > 0) {
        $registro = $resultado->fetch_assoc();

        if ($pin_pass != $registro['pin_pass']) {

            $response = array(
                'estadoRes' => 'error',
                'msg' => 'PIN incorrecto',
                'datos' => null
            );
        } else if ($registro['est_usuario'] == 0) {

            $response = array(
                'estadoRes' => 'error',
                'msg' => 'Usuario Inactivo',
                'datos' => null
            );
        } else {

            $datos = array(

                "cod_usuario" => $registro['cod_usuario'],
                "nom_usuario" => $registro['nom_usuario'],
                "tipo_usuario" => $registro['tipo_usuario']
            );


            $response = array(
                'estadoRes' => 'success',
                'msg' => '',
                'datos' => $datos
            );
        }
    }

    return $response;
}

function cambiaPin()
{


    $conexion = Conexion::getInstance()->getConnection();

    $id_usuario = params_get('id_usuario');
    $pinActual = params_get('pin_actual');
    $nuevopin = params_get('nuevo_pin');

    $consulta = "SELECT pin_pass FROM usuarios WHERE id_usuario='$id_usuario'";

    $resultado = $conexion->query($consulta);

    if ($resultado->num_rows > 0) {

        $registro = $resultado->fetch_assoc();
        if ($pinActual != $registro['pin_pass']) {

            return array(
                "estadoRes" => 'error',
                "msg" => "Pin Actual es Incorrecto"
            );
        }


        $sql = "UPDATE usuarios SET pin_pass='$nuevopin' WHERE id_usuario='$id_usuario'";
        $conexion->query($sql);

        return array(
            'estadoRes' => 'success',
            'msg' => 'Pin cambiado'
        );
    }
}
