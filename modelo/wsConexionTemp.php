<?php

/*
 * Clase Singleton para efectuar la conexion a la base de datos
 * 
 */


//require_once 'config.php';

class Conexion {

    // Contenedor Instancia de la clase
    private static $instance = NULL;
    private $conexion = NULL;

    // Constructor privado, previene la creación de objetos vía new
    private function __construct() {

        
        
        $this->conexion = new mysqli(HOST, USERNAME, PASSWD, DBNAME);

        if ($this->conexion->connect_errno) {
            echo "Error de conexion" . $this->conexion->connect_errno;
            exit();
        }
        $this->conexion->set_charset(CHARSET);
    }

    // Método singleton 
    public static function getInstance() {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    // Magic method clone is empty to prevent duplication of connection
    private function __clone() { }

    public function cerrar() {
                
        if(self::$instance!=null){            
            $this->conexion->close();
            self::$instance = NULL;
        }       
    }

    public function getConnection() {
        return $this->conexion;
    }

}
