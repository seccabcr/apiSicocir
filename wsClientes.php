<?php

require_once conf_get('coreInstall', 'modules') . 'apiSicocir/modelo/wsConexion.php';

function actualizaCliente()
{

    $conexion = Conexion::getInstance()->getConnection();

    $nuevo = params_get('nuevo');
    $cod_cliente = params_get('cod_cliente');
$nom_cliente = params_get('nom_cliente');


}
