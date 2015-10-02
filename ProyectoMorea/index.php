<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Floristeria morea</title>
	<link href="bootstrap-3.3.5-dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="css/estilos.css" rel="stylesheet">

</head>

<body>

<?php

	require("php/Clase_ConexionBD.php");
	$conBd=new ConexionBD("tiendaonline");
	
//Crea una lista despegable que como value tendra el id del producto y visualizara el nombre y como name ldnombre (ld+nombre campo tabla)
	$sql="select id,nombre from productos";
	$conBd->CrearListaDespegableSql($sql);
	
//Crea una formulario sin datos con 2 columnas (opcion de columnas 1,2,3,4) con todos los campos de la tabla productos	
	$sql="select * from productos";
	$conBd->crearFormularioSql($sql,2,"Formulario Productos vacio",false);
	
	
//Crea una formulario con datos con 4 columnas (opcion de columnas 1,2,3,4) con todos los campos de la tabla productos	
	$sql="select * from productos where id=2";
	$conBd->crearFormularioSql($sql,4,"Formulario Productos con datos",true);
	
//Crea un listado de los productos	
	$sql="select * from productos";
	$conBd->listadoConsultaSql($sql,"LISTADO DE PRODUCTOS","NO HAY PRODUCTOS");

//Crea un listado de los productos	con una columna para poder hacer modificaciones en cada producto (se le pasa la pagina php)
	$sql="select * from productos";
	$conBd->listadoConsultaSqlConCambios($sql,"LISTADO DE PRODUCTOS A MODIFICAR","NO HAY PRODUCTOS","BAJA.PHP",0);
	
	$conBd->cerrarBD();

?>
</body>
</html>