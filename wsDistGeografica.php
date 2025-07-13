<?php

require_once conf_get('coreInstall', 'modules') . '/apiSeccab/modelo/wsConexion.php';

function llenaProvincias()
{

    $conexion = Conexion::getInstance()->getConnection();

    $sql = "SELECT idProvincia, nomProvincia FROM codificacion_geo GROUP BY idProvincia";

    $resultado = $conexion->query($sql);
    $json['provincias'][] = array('idProvincia' => 0, 'nomProvincia' => 'Seleccione Provincia');

    if ($resultado->num_rows > 0) {

        while ($registro = $resultado->fetch_assoc()) {
            $json['provincias'][] = $registro;
        }

    } 

    return $json;

}

function llenaCantones()
{

    $conexion = Conexion::getInstance()->getConnection();

    $idProvincia = params_get("idProvincia");

    $sql = "SELECT idCanton,nomCanton FROM codificacion_geo WHERE idProvincia=$idProvincia AND idCanton>0 GROUP BY idCanton";

    $json['cantones'][] = array('idCanton' => 0, 'nomCanton' => 'Seleccione canton');

    $resultado = $conexion->query($sql);

    if ($resultado->num_rows > 0) {

        while ($registro = $resultado->fetch_assoc()) {
            $json['cantones'][] = $registro;
        }

    } 

    return $json;

}
function llenaDistritos()
{

    $conexion = Conexion::getInstance()->getConnection();

    $idProvincia = params_get("idProvincia");
    $idCanton= params_get('idCanton');

    $sql = "SELECT idDistrito,nomDistrito FROM codificacion_geo WHERE idProvincia=$idProvincia AND idCanton=$idCanton AND idDistrito >0 GROUP BY idDistrito";

    $json['distritos'][] = array('idDistrito' => 0, 'nomDistrito' => 'Seleccione distrito');

    $resultado = $conexion->query($sql);

    if ($resultado->num_rows > 0) {

        while ($registro = $resultado->fetch_assoc()) {
            $json['distritos'][] = $registro;
        }

    } 

    return $json;

}
