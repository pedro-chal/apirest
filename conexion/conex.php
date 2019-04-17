<?php

function Conectar(){
    try {
        //Conexion
        $con = new PDO ("mysql:local=localhost;dbname=api2","root","");
        return $con;
    } catch (Exception $e) {
        print "¡Error!: " . $e->getMessage() . "<br/>";
            die();
    }
}