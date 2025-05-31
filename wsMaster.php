<?php

require_once conf_get('coreInstall', 'modules') . 'apiSicocir/modelo/wsConexion.php';

/**************************************************************************************************
 * 
 * Actualiza items merchandising
 */

function actualizaItem()
{
    $conexion = Conexion::getInstance()->getConnection();

    $nuevo = params_get('nuevo');
    $cod_item = params_get('cod_item');
    $nom_item = params_get('nom_item');
    $cat_item = params_get('cat_item');
    $est_item = params_get('est_item');


    if ($nuevo) {

        $insert = "INSERT INTO master_items (nom_item, cat_item, est_item) VALUES('$nom_item', $cat_item, $est_item)";
        $conexion->query($insert);

        return array(
            'estadoRes' => 'success',
            'msg' => 'Item Incluido'
        );
    } else {

        $update = "UPDATE master_items SET nom_item='$nom_item', cat_item=$cat_item, est_item=$est_item WHERE cod_item=$cod_item";
        $conexion->query($update);

        return array(
            'estadoRes' => 'success',
            'msg' => 'Item Modificado'
        );
    }
}

function listaItems()
{

    $conexion = Conexion::getInstance()->getConnection();

    $cat_item = params_get('cat_item');

    $consulta = "SELECT cod_item, nom_item, est_item 
                    FROM master_items 
                    WHERE cat_item=$cat_item
                    ORDER BY nom_item";

    $response = [];

    $resultado = $conexion->query($consulta);

    if ($resultado->num_rows > 0) {

        while ($registro = $resultado->fetch_assoc()) {

            $response[] = $registro;
        }
    }

    return $response;
}


function actualizaTipoNeg()
{
    $conexion = Conexion::getInstance()->getConnection();

    $nuevo = params_get('nuevo');
    $cod_tipo_neg = params_get('cod_tipo_neg');
    $nom_tipo_neg = params_get('nom_tipo_neg');
    $est_tipo_neg = params_get('est_tipo_neg');


    if ($nuevo) {

        $insert = "INSERT INTO master_tipo_neg (nom_tipo_neg, est_tipo_neg) VALUES('$nom_tipo_neg', $est_tipo_neg)";
        $conexion->query($insert);

        return array(
            'estadoRes' => 'success',
            'msg' => 'Tipo Negocio Incluido'
        );
    } else {

        $update = "UPDATE master_tipo_neg SET nom_tipo_neg='$nom_tipo_neg', est_tipo_neg=$est_tipo_neg WHERE cod_tipo_neg=$cod_tipo_neg";
        $conexion->query($update);

        return array(
            'estadoRes' => 'success',
            'msg' => 'Tipo Negocio Modificado'
        );
    }
}

function listaTipoNeg()
{

    global $params;

    $conexion = Conexion::getInstance()->getConnection();

    $filtro = isset($params['filtro']) ? params_get('filtro') : 0;

    if ($filtro == 1) {
        $consulta = "SELECT cod_tipo_neg, nom_tipo_neg, est_tipo_neg 
                    FROM master_tipo_neg 
                    WHERE est_tipo_neg=1
                    ORDER BY nom_tipo_neg";
    } else {
        $consulta = "SELECT cod_tipo_neg, nom_tipo_neg, est_tipo_neg 
                    FROM master_tipo_neg 
                    WHERE 1
                    ORDER BY nom_tipo_neg";
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
