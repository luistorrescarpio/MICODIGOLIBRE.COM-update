<?php 
//Script para conexión con base de datos en Mysql
include("db_script/heasy_mysql.php");
// Get Params Client
$obj = (object)$_REQUEST;

//Ejecutar Consultas en MYSQL desde PHP
switch ($obj->action) {
  case 'getBookList':
    $r = query("SELECT id_libro, titulo FROM libro ORDER BY id_libro ASC");
    server_res($r);
    break;  

  case 'getDataBook':
    $r = query("SELECT * FROM libro WHERE id_libro='$obj->id_libro'");
    server_res($r);
    break;  
  // ******************* UPDATE ******************* //
  case 'update_book':
    $r = query("UPDATE libro SET
    		codigo='{$obj->codigo}'
    		,titulo='{$obj->titulo}'
    		,autor='{$obj->autor}'
    		,editorial='{$obj->editorial}'
    		,ejemplares='{$obj->ejemplares}'
    		,fech_registro='{$obj->fech_registro}'

    		WHERE id_libro = '{$obj->id_libro}'
    	");
    server_res($r);
    break;  
  // ******************* FIN - UPDATE ******************* //
}
?>
