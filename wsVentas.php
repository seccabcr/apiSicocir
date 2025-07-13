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
                    WHERE c.cod_distri=$cod_distri AND estado_cli=1
                    ORDER BY nom_cliente";


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
    $pre_item = params_get('pre_item');
    $id_usu_reg = params_get('id_usu_reg');
    

    $fec_reg = date("Y-m-d H:i:s");

    $sql = "SELECT * FROM clientes_mov WHERE cod_cliente=$cod_cliente AND fec_entrega='$fec_entrega' AND cod_item=$cod_item";

    $can_ent_ant = 0;

    $resultado = $conexion->query($sql);

    if ($resultado->num_rows == 0) {

        $insert = "INSERT INTO clientes_mov (cod_cliente, fec_entrega, cod_item, pre_item, can_entrega, fec_reg, id_usu_reg) VALUES($cod_cliente, '$fec_entrega', $cod_item, $pre_item, $can_entrega, '$fec_reg', '$id_usu_reg')";
        $conexion->query($insert);
    } else {

        $registro = $resultado->fetch_assoc();
        $est_mov = $registro['est_mov'];

        if ($registro['est_mov'] == 2) {

            return array(
                'estadoRes' => 'error',
                'msg' => 'Fecha Entrega NO puede ser modificada. Liquidación Cerrada'
            );
        }

        $can_ent_ant = $registro['can_entrega'];

        $update = "UPDATE clientes_mov SET pre_item=$pre_item, can_entrega=$can_entrega, fec_reg='$fec_reg', id_usu_reg='$id_usu_reg' WHERE cod_cliente=$cod_cliente AND cod_item=$cod_item AND fec_entrega='$fec_entrega'";
        $conexion->query($update);
    }

    // Rutina para actualiza el saldo en consignacion del PDV

    $sql = "SELECT sal_consigna FROM clientes_con WHERE cod_cliente=$cod_cliente AND cod_item=$cod_item";

    $res_saldo =  $conexion->query($sql);

    if ($res_saldo->num_rows == 0) {

        $insert = "INSERT INTO clientes_con (cod_cliente, cod_item, sal_consigna) VALUES($cod_cliente, $cod_item, $can_entrega)";
        $conexion->query($insert);
    } else if ($est_mov == 0) {

        $regMov = $res_saldo->fetch_assoc();

        $nuevoSaldo = $regMov['sal_consigna'] + $can_entrega - $can_ent_ant;

        $update = "UPDATE clientes_con SET sal_consigna=$nuevoSaldo WHERE cod_cliente=$cod_cliente AND cod_item=$cod_item";
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
                WHERE c.cod_cliente=$cod_cliente AND cod_item=$cod_item AND fec_entrega BETWEEN '$fecha_ini' AND '$fecha_fin'
                ORDER BY fec_entrega DESC";

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

function actualizaLiqDiaria()
{
    $conexion = Conexion::getInstance()->getConnection();

    $cod_cliente = params_get('cod_cliente');
    $fec_entrega = params_get('fec_entrega');
    $can_dev = params_get('can_dev');
    $cod_item = 1;
    $id_usu_reg = params_get('id_usu_reg');

    $fec_reg = date("Y-m-d H:i:s");

    $sql = "SELECT * FROM clientes_mov WHERE cod_cliente=$cod_cliente AND fec_entrega='$fec_entrega' AND cod_item=$cod_item";

    $resultado = $conexion->query($sql);

    $response = array(
        'estadoRes' => 'error',
        'msg' => 'Fecha Liquidación NO puede ser actualizada'
    );

    if ($resultado->num_rows > 0) {

        $registro = $resultado->fetch_assoc();

        $can_ent = $registro['can_entrega'];
        $est_mov = $registro['est_mov'];

        // Verifica si estado de movimiento esta liquidado y cerrado

        if ($registro['est_mov'] == 2) {

            $response = array(
                'estadoRes' => 'error',
                'msg' => 'Fecha Liquidación NO puede ser modificada. Liquidación Cerrada'
            );
        } else {

            $update = "UPDATE clientes_mov SET can_dev=$can_dev, est_mov=1, fec_liq='$fec_reg', fec_reg='$fec_reg', id_usu_reg='$id_usu_reg' WHERE cod_cliente=$cod_cliente AND cod_item=$cod_item AND fec_entrega='$fec_entrega'";
            $conexion->query($update);


            // Rutina para actualiza el saldo en consignacion del PDV

            if ($est_mov == 0) {

                $sql = "SELECT sal_consigna FROM clientes_con WHERE cod_cliente=$cod_cliente AND cod_item=$cod_item";

                $res_saldo =  $conexion->query($sql);

                if ($res_saldo->num_rows > 0) {

                    $reg_saldo = $res_saldo->fetch_assoc();

                    $nuevoSaldo = $reg_saldo['sal_consigna'] - $can_ent;

                    $update = "UPDATE clientes_con SET sal_consigna=$nuevoSaldo WHERE cod_cliente=$cod_cliente AND cod_item=$cod_item";
                    $conexion->query($update);
                }
            }


            $response = array(
                'estadoRes' => 'success',
                'msg' => 'Liquidación Actualizada'
            );
        }
    }

    return $response;
}


/**********************************************************************************************************
 * 
 * 
 */

function actualizaCierre()
{
    $conexion = Conexion::getInstance()->getConnection();

    $cod_dis = params_get('cod_dis');
    $fec_ini = params_get('fec_ini');
    $fec_fin = params_get('fec_fin');
    $est_mov = params_get('est_mov');

    $update = "UPDATE clientes_mov AS mov
                INNER JOIN clientes AS c ON mov.cod_cliente=c.cod_cliente
                SET mov.est_mov=$est_mov
                WHERE c.cod_distri=$cod_dis AND est_mov > 0 AND fec_entrega BETWEEN '$fec_ini' AND '$fec_fin'";


    $conexion->query($update);

    return array(
        'estadoRes' => 'success',
        'msg' => 'Cambio estado cierre aplicado'
    );
}


/**********************************************************************************************************
 * 
 * 
 */

function resumenEntregasxDSD()
{

    $conexion = Conexion::getInstance()->getConnection();

    $fec_ini = params_get('fec_ini');
    $fec_fin = params_get('fec_fin');
    $cod_item = params_get('cod_item');

    $consulta = "SELECT cod_usuario, nom_usuario, SUM(can_entrega) AS can_entrega FROM clientes_mov AS mov
                    INNER JOIN clientes AS c ON c.cod_cliente=mov.cod_cliente
                    INNER JOIN usuarios AS u ON u.cod_usuario=c.cod_distri
                    WHERE cod_item=$cod_item AND fec_entrega BETWEEN '$fec_ini' AND '$fec_fin'
                    GROUP BY nom_usuario
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


/**********************************************************************************************************
 * 
 * 
 */

function resumenEntregasxPDV()
{

    $conexion = Conexion::getInstance()->getConnection();

    $cod_dis = params_get('cod_distri');
    $fec_ini = params_get('fec_ini');
    $fec_fin = params_get('fec_fin');
    $cod_item = params_get('cod_item');

    $consulta = "SELECT mov.cod_cliente, nom_cliente, SUM(can_entrega) AS can_entrega FROM clientes_mov AS mov
                    INNER JOIN clientes AS c ON c.cod_cliente=mov.cod_cliente
                    INNER JOIN usuarios AS u ON u.cod_usuario=c.cod_distri
                    WHERE cod_usuario= $cod_dis AND cod_item=$cod_item AND fec_entrega BETWEEN '$fec_ini' AND '$fec_fin'
                    GROUP BY cod_cliente
                    ORDER BY nom_cliente";


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

function resumenLiquidacionxDSD()
{

    $conexion = Conexion::getInstance()->getConnection();

    $fec_ini = params_get('fec_ini');
    $fec_fin = params_get('fec_fin');
    $cod_item = params_get('cod_item');

    $consulta = "SELECT cod_usuario, nom_usuario, SUM(can_entrega) AS can_entrega, SUM(can_dev) AS can_dev FROM clientes_mov AS mov
                    INNER JOIN clientes AS c ON c.cod_cliente=mov.cod_cliente
                    INNER JOIN usuarios AS u ON u.cod_usuario=c.cod_distri
                    WHERE cod_item=$cod_item AND fec_entrega BETWEEN '$fec_ini' AND '$fec_fin'
                    GROUP BY cod_usuario
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


/**********************************************************************************************************
 * 
 * 
 */

function resumenLiquidacionxPDV()
{

    $conexion = Conexion::getInstance()->getConnection();

    $cod_dis = params_get('cod_dis');
    $fec_ini = params_get('fec_ini');
    $fec_fin = params_get('fec_fin');
    $cod_item = params_get('cod_item');

    $consulta = "SELECT mov.cod_cliente, nom_cliente, SUM(can_entrega) AS can_entrega, SUM(can_dev) AS can_dev FROM clientes_mov AS mov
                    INNER JOIN clientes AS c ON c.cod_cliente=mov.cod_cliente
                    INNER JOIN usuarios AS u ON u.cod_usuario=c.cod_distri
                    WHERE cod_usuario =$cod_dis AND cod_item=$cod_item AND fec_entrega BETWEEN '$fec_ini' AND '$fec_fin'
                    GROUP BY cod_cliente
                    ORDER BY nom_cliente";


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

function listaPreciosGen()
{

    $conexion = Conexion::getInstance()->getConnection();

    $cod_item = params_get('cod_item');

   
    $consulta = "SELECT fec_ini, pre_item FROM precios WHERE cod_item=$cod_item";

    $response = [];

    $resultado = $conexion->query($consulta);

    if ($resultado->num_rows > 0) {

        while ($registro = $resultado->fetch_assoc()) {

            $response[] = $registro;
        }
    }

    return $response;
}


