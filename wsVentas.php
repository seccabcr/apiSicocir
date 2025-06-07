<?php

require_once conf_get('coreInstall', 'modules') . 'apiSicocir/modelo/wsConexion.php';



function consultaFechasPicking()
{

    $conexion = Conexion::getInstance()->getConnection();

    $fecha_ini = params_get('fecha_ini');
    $fecha_fin = params_get('fecha_fin');
    $cod_distri = params_get('cod_distri');

    $consulta = "SELECT fec_picking, can_picking FROM usuarios_pic WHERE cod_usuario=$cod_distri AND cod_item=1 AND fec_picking BETWEEN '$fecha_ini' AND '$fecha_fin'";


    $response = [];

    $resultado = $conexion->query($consulta);

    if ($resultado->num_rows > 0) {

        while ($registro = $resultado->fetch_assoc()) {

            $response[] = $registro;
        }
    }

    return $response;
}

/**********************************************************************************************************
 * 
 * 
 */

function actualizaPickingDiario()
{
    $conexion = Conexion::getInstance()->getConnection();

    $cod_usuario = params_get('cod_usuario');
    $fec_picking = params_get('fec_picking');
    $can_picking = params_get('can_picking');
    $cod_item = 1;
    $id_usu_reg = params_get('id_usu_reg');


    $fec_reg = date("Y-m-d H:i:s");

    $sql = "SELECT can_picking FROM usuarios_pic WHERE cod_usuario=$cod_usuario AND fec_picking='$fec_picking' AND cod_item=$cod_item";

    $resultado = $conexion->query($sql);

    if ($resultado->num_rows == 0) {

        $insert = "INSERT INTO usuarios_pic (cod_usuario, fec_picking, cod_item, can_picking, fecha_reg, id_usu_reg) VALUES($cod_usuario, '$fec_picking', $cod_item, $can_picking, '$fec_reg', '$id_usu_reg')";
        $conexion->query($insert);
    } else {

        $update = "UPDATE usuarios_pic SET can_picking=$can_picking, fecha_reg='$fec_reg', id_usu_reg='$id_usu_reg' WHERE cod_usuario=$cod_usuario AND cod_item=$cod_item AND fec_picking='$fec_picking'";
        $conexion->query($update);
    }

    return array(
        'estadoRes' => 'success',
        'msg' => 'Picking Actualizado'
    );
}


/**********************************************************************************************************
 * 
 * 
 */

function listaEntregaDiariaPdvs()
{

    $conexion = Conexion::getInstance()->getConnection();

    $fecha_ent = params_get('fec_entrega');
    $cod_distri = params_get('cod_distri');
    $cod_item = params_get('cod_item');

    $consulta = "SELECT c.cod_cliente, nom_cliente, IFNULL(can_entrega,0) AS can_entrega FROM clientes AS c
                    LEFT JOIN clientes_mov AS mov ON mov.cod_cliente=c.cod_cliente AND cod_item=$cod_item AND fec_entrega='$fecha_ent'
                    WHERE c.cod_distri=$cod_distri AND estado_cli=1";


    $response = [];

    $resultado = $conexion->query($consulta);

    if ($resultado->num_rows > 0) {

        while ($registro = $resultado->fetch_assoc()) {

            $response[] = $registro;
        }
    }

    return $response;
}

/**********************************************************************************************************
 * 
 * 
 */

function actualizaEntregaDiaria()
{
    $conexion = Conexion::getInstance()->getConnection();

    $cod_cliente = params_get('cod_cliente');
    $fec_entrega = params_get('fec_entrega');
    $can_entrega = params_get('can_entrega');
    $cod_item = 1;
    $id_usu_reg = params_get('id_usu_reg');


    $fec_reg = date("Y-m-d H:i:s");

    $sql = "SELECT can_entrega FROM clientes_mov WHERE cod_cliente=$cod_cliente AND fec_entrega='$fec_entrega' AND cod_item=$cod_item";

    $resultado = $conexion->query($sql);

    if ($resultado->num_rows == 0) {

        $insert = "INSERT INTO clientes_mov (cod_cliente, fec_entrega, cod_item, can_entrega, fec_reg, id_usu_reg) VALUES($cod_cliente, '$fec_entrega', $cod_item, $can_entrega, '$fec_reg', '$id_usu_reg')";
        $conexion->query($insert);
    } else {

        $update = "UPDATE clientes_mov SET can_entrega=$can_entrega, fec_reg='$fec_reg', id_usu_reg='$id_usu_reg' WHERE cod_cliente=$cod_cliente AND cod_item=$cod_item AND fec_entrega='$fec_entrega'";
        $conexion->query($update);
    }

    return array(
        'estadoRes' => 'success',
        'msg' => 'Entrega Actualizada'
    );
}


/**********************************************************************************************************
 * 
 * 
 */

function listaLiqDiariaPdv()
{

    $conexion = Conexion::getInstance()->getConnection();


    $cod_cliente = params_get('cod_cliente');
    $cod_item = params_get('cod_item');
    $fecha_ini = params_get('fecha_ini');
    $fecha_fin = params_get('fecha_fin');

    $consulta = "SELECT fec_entrega, can_entrega, can_dev, est_mov FROM clientes_mov AS mov
                INNER JOIN clientes AS c ON c.cod_cliente=mov.cod_cliente
                WHERE c.cod_cliente=$cod_cliente AND cod_item=$cod_item AND fec_entrega BETWEEN '$fecha_ini' AND '$fecha_fin'";

    $response = [];

    $resultado = $conexion->query($consulta);

    if ($resultado->num_rows > 0) {

        while ($registro = $resultado->fetch_assoc()) {

            $response[] = $registro;
        }
    }

    return $response;
}
