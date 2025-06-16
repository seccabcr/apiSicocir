<?php

require_once conf_get('coreInstall', 'modules') . 'apiSicocir/modelo/wsConexion.php';

function actualizaCliente()
{

    $conexion = Conexion::getInstance()->getConnection();

    $nuevo = params_get('nuevo');
    $cod_cliente = params_get('cod_cliente');
    $nom_cliente = params_get('nom_cliente');
    $tipo_negocio = params_get('tipo_negocio');
    $nom_contacto = params_get('nom_contacto');
    $tel_contacto = params_get('tel_contacto');
    $email = params_get('email');
    $idProvincia = params_get('idProvincia');
    $provincia = params_get('provincia');
    $idCanton = params_get('idCanton');
    $canton = params_get('canton');
    $idDistrito = params_get('idDistrito');
    $distrito = params_get('distrito');
    $otras_sennas = params_get('otras_sennas');
    $cod_distri = params_get('cod_distri');
    $id_usu_reg = params_get('id_usu_reg');
    $latitud = params_get('latitud');
    $longitud = params_get('longitud');
    $est_cliente = params_get('est_cliente');


    $fec_reg = date("Y-m-d H:i:s");

    //return array($nuevo, $cod_cliente, $nom_cliente, $tipo_negocio, $nom_contacto, $tel_contacto, $email, $idProvincia, $provincia, $idCanton, $canton, $idDistrito, $distrito, $otras_sennas, $cod_distri, $id_usu_reg, $latitud, $longitud, $est_cliente);

    if ($nuevo) {

        $insert = "INSERT INTO clientes (nom_cliente, tipo_negocio, nom_contacto, tel_contacto, email, idProvincia, provincia, idCanton, canton, idDistrito, distrito, otras_sennas, cod_distri, fec_reg, id_usu_reg, latitud, longitud) 
        VALUES('$nom_cliente', $tipo_negocio, '$nom_contacto', '$tel_contacto', '$email', $idProvincia, '$provincia', $idCanton, '$canton', $idDistrito, '$distrito', '$otras_sennas', $cod_distri, '$fec_reg', '$id_usu_reg', $latitud, $longitud)";

        $conexion->query($insert);

        return array(
            '' => 'success',
            'msg' => 'Cliente incluido',
            'cod_cliente' => $conexion->insert_id
        );
    } else {

        $update = "UPDATE clientes 
                    SET nom_cliente='$nom_cliente',
                        tipo_negocio=$tipo_negocio,
                        nom_contacto='$nom_contacto',
                        tel_contacto='$tel_contacto',
                        email='$email',
                        idProvincia=$idProvincia,
                        provincia='$provincia',
                        idCanton=$idCanton,
                        canton='$canton',
                        idDistrito=$idDistrito,
                        distrito='$distrito',
                        otras_sennas='$otras_sennas',
                        cod_distri=$cod_distri,                        
                        latitud=$latitud,
                        longitud=$longitud,
                        estado_cli=$est_cliente 
                    WHERE cod_cliente=$cod_cliente";

        $conexion->query($update);

        return array(
            '' => 'success',
            'msg' => 'Cliente actualizado',
            'cod_cliente' => $cod_cliente
        );
    }
}


function consultaCliente()
{


    $conexion = Conexion::getInstance()->getConnection();

    $cod_cliente = params_get('cod_cliente');
    $cod_distri = params_get('cod_distri');

    $sql = "SELECT * FROM clientes WHERE cod_cliente=$cod_cliente limit 1";

    $resultado = $conexion->query($sql);

    if ($resultado->num_rows > 0) {

        $registro = $resultado->fetch_assoc();

        if ($registro['cod_distri'] != $cod_distri) {

            return array(
                'estadoRes' => 'error',
                'msg' => 'Cliente NO pertenece al distribuidor',
                'datos' => null
            );
        }


        return array(
            'estadoRes' => 'success',
            'msg' => '',
            'datos' => $registro
        );
    } else {

        return array(
            'estadoRes' => 'error',
            'msg' => 'Cliente NO existe',
            'datos' => null
        );
    }
}

function llenaTablaClientes()
{

    $conexion = Conexion::getInstance()->getConnection();

    $filtro = params_get('filtro');
    $cod_distri = params_get('cod_distri');

    if ($filtro == 1) {
        $consulta = "SELECT cod_cliente, nom_cliente 
                    FROM clientes
                    WHERE cod_distri=$cod_distri  AND estado_cli=1
                    ORDER BY nom_cliente";
    } else {


        $consulta = "SELECT cod_cliente, nom_cliente 
                    FROM clientes
                    WHERE cod_distri=$cod_distri
                    ORDER BY nom_cliente";
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

/************************************************************************************ */
function consultaItemMerchaPdv()
{

    $conexion = Conexion::getInstance()->getConnection();

    $cod_cliente = params_get('cod_cliente');
    $cod_item = params_get('cod_item');

    $consulta = "SELECT can_item FROM clientes_mer                
                WHERE cod_cliente=$cod_cliente AND cod_item=$cod_item";

    $resultado = $conexion->query($consulta);

    $response = null;

    if ($resultado->num_rows > 0) {

        $registro = $resultado->fetch_assoc();

        $response = $registro;
    }

    return $response;
}


/****************************************************************************************** */

function listaItemsMerchaPdv()
{

    $conexion = Conexion::getInstance()->getConnection();

    $cod_cliente = params_get('cod_cliente');

    $consulta = "SELECT cm.cod_item, nom_item, can_item 
                FROM clientes_mer AS cm
                INNER JOIN master_items as i ON i.cod_item=cm.cod_item
                WHERE cm.cod_cliente=$cod_cliente";

    $response = [];

    $resultado = $conexion->query($consulta);

    if ($resultado->num_rows > 0) {

        while ($registro = $resultado->fetch_assoc()) {

            $response[] = $registro;
        }
    }

    return $response;
}

/************************************************************************************ */
function actualizaItemMerchaPdv()
{

    $conexion = Conexion::getInstance()->getConnection();

    $cod_cliente = params_get('cod_cliente');
    $cod_item = params_get('cod_item');
    $can_item = params_get('can_item');

    $consulta = "SELECT * FROM clientes_mer WHERE cod_cliente=$cod_cliente AND cod_item=$cod_item";

    $resultado = $conexion->query($consulta);

    if ($resultado->num_rows == 0) {

        $insert = "INSERT INTO clientes_mer(cod_cliente, cod_item, can_item) VALUES($cod_cliente, $cod_item, $can_item)";
        $conexion->query($insert);
    } else {

        $update = "UPDATE clientes_mer SET can_item=$can_item WHERE cod_cliente=$cod_cliente AND cod_item=$cod_item";
        $conexion->query($update);
    }

    return array(
        'msg' => 'Item Actualizado'
    );
}


/************************************************************************************ */
function eliminaItemMerchaPdv()
{

    $conexion = Conexion::getInstance()->getConnection();

    $cod_cliente = params_get('cod_cliente');
    $cod_item = params_get('cod_item');


    $delete = "DELETE FROM clientes_mer WHERE cod_cliente=$cod_cliente AND cod_item=$cod_item";
    $conexion->query($delete);



    return array(
        'msg' => 'Item Eliminado'
    );
}


/****************************************************************************************** */

function listaClientes()
{

    $conexion = Conexion::getInstance()->getConnection();

    $cod_distri = params_get('cod_distri');

    $consulta = "SELECT cod_cliente, nom_cliente, nom_tipo_neg, fec_reg, estado_cli, latitud, longitud FROM clientes AS c
                INNER JOIN master_tipo_neg as tn ON tn.cod_tipo_neg=c.tipo_negocio
                WHERE cod_distri=$cod_distri";

    $response = [];

    $resultado = $conexion->query($consulta);

    if ($resultado->num_rows > 0) {

        while ($registro = $resultado->fetch_assoc()) {

            $response[] = $registro;
        }
    }

    return $response;
}