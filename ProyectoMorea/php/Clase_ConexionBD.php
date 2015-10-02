<?php

Class ConexionBD
{ 
  private $bd;
  private $enlace;
  
  
  function __construct($bd) {
     $this->bd = $bd;
     if (!isset($this->enlace))
       {
		   error_reporting(0);
		   $this->enlace = mysqli_connect("localhost","root","sa_aeg1",$this->bd) or die("No se ha podido conectar con la base de datos " . $bd);  
		   mysqli_set_charset($this->enlace,"utf8");
		   error_reporting(-1);		   
		}
  }
	
  public function ejecutarConsultaSql($sql)	
  {
     error_reporting(0);
     $resultados=mysqli_query($this->enlace,$sql) or die ("Error en la consulta");
     error_reporting(-1);
	 return $resultados;
  }
  
  public function devuelveFilaSql($resultados)
  {
     $fila=mysqli_fetch_array($resultados) ;
     return $fila;  
  }
	
  	
public function numeroFilasSql($resultados)
 {
   error_reporting(0); 
   $numFilas= mysqli_num_rows($resultados) or die ("Error al comprobar el número de filas");
   error_reporting(-1);
   return $numFilas;
 }

public function insertarBorrarModificarSql($sql)
{
	$this->ejecutarConsultaSql($sql);
	return mysqli_affected_rows($this->enlace);	
}


public function cerrarBD()
  {
    error_reporting(0);
	mysqli_close($this->enlace) or die("Error al cerrar la base de datos");  
    error_reporting(-1);
  }	
	
	
public function CrearListaDespegableSql($sql)
{
  $resultados=mysqli_query($this->enlace,$sql);
  $izenburuak=array();
  $numCampos=0;  
  while ($campo=mysqli_fetch_field($resultados))
    {
		$nombreCampo=$campo->name;
		$izenburuak[$numCampos]=$nombreCampo;
		$numCampos++;
	}

  echo '<select name="ld' . $izenburuak[$numCampos-1] . '" class="form-control">';
  echo '<option value=0 selected> Elija '. $nombreCampo  .'</option>';
  while ($fila=$this->devuelveFilaSql($resultados))
    {	      
        echo '<option value="'  .  $fila[0]  .'"> ' . $fila[$numCampos-1] . '</option>';
    }
  echo '</select> ';
}

  
public function listadoConsultaSql($sql,$titulo1,$titulo2)
{	
  $resultados=$this->ejecutarConsultaSql($sql);
  $numRegistros = $this->numeroFilasSql($resultados);

  echo '<div class="container">';
  if ($numRegistros>0)
    {
      echo "<h1 class='bg-primary text-center'>". $titulo1 ."</h1>";		
	  echo '<table class="table table-striped table-condensed">';
  
	  $cadena="<tr><thead>";
      while ($campo=mysqli_fetch_field($resultados))
	    {$cadena=$cadena . "<th>" . $campo->name . "</th>";}
	  $cadena= $cadena . "</thead></tr>";	
	  
	  echo $cadena;	
  
      $cadena="";
	  while ($fila=$this->devuelveFilaSql($resultados))
       {
		   $cadena="<tr> ";
		   for ($i=0;$i<(count($fila)/2);$i++)
		     {
				 $cadena= $cadena . "<td>" . $fila[$i]."</td>";
			 }
		   
		   $cadena=$cadena . "</tr>";
		   echo $cadena;		   
	   }
  	echo "</table>";
  	echo "</div>";
  }
  else
  {echo "<h3 class='bg-success text-center'>". $titulo2 ."</h3>";		}
}


public function listadoConsultaSqlConCambios($sql,$titulo1,$titulo2,$ficheroPhp,$columnaAcambiar)
{	
  $resultados=$this->ejecutarConsultaSql($sql);
  $numRegistros = $this->numeroFilasSql($resultados);

  echo '<div class="container">';
  if ($numRegistros>0)
    {
      echo "<h1 class='bg-primary text-center'>". $titulo1 ."</h1>";		
	  echo '<table class="table table-striped table-condensed">';
  
	  $cadena="<tr><thead>";
  	  $izenburuak=array();
  	  $i=0;

      while ($campo=mysqli_fetch_field($resultados))
	    {
			$cadena=$cadena . "<th>" . $campo->name . "</th>";
   		    $izenburuak[$i]=$campo->name;
		    $i++;
		}
	  $cadena= $cadena . "</thead></tr>";	
	  
	  echo $cadena;	
  
      $cadena="";
	  while ($fila=$this->devuelveFilaSql($resultados))
       {
		   $cadena="<tr> ";
		   for ($i=0;$i<(count($fila)/2);$i++)
		     {
				 $cadena= $cadena . "<td>" . $fila[$i]."</td>";
			 }
		   
		   if ($columnaAcambiar>=0)
			 {
				$parametro=$ficheroPhp . "?" .  $izenburuak[$columnaAcambiar]  . "=" . $fila[$columnaAcambiar]; 	 
		   		$cadena=$cadena . "<td> <a href='". $parametro  . "'>  Modificar </a> </td>";	 
			 }
		   $cadena=$cadena . "</tr>";
		   echo $cadena;		   
	   }
  	echo "</table>";
  	echo "</div>";
  }
  else
  {echo "<h3 class='bg-success text-center'>". $titulo2 ."</h3>";		}
}
  

public function crearFormularioSql($sql,$numCol,$titulo,$conValores)
{
  $resultados=$this->ejecutarConsultaSql($sql);
  $izenburuak=array();
  $i=0;
  while ($campo=mysqli_fetch_field($resultados))
   {
	   $izenburuak[$i]=$campo->name;
       $i++;
   }
   //Zenbat zutabe nahi ditugun arabera klase desberdinak jarriko ditugu
  switch ($numCol) 
    {
		case 1:
		       $lblKlasea="col-md-3";
			   $txtKlasea="col-md-6";
			   break;
		case 2:
		       $lblKlasea="col-md-2";
			   $txtKlasea="col-md-4";		
			   break;
		case 3:
		       $lblKlasea="col-md-1";
			   $txtKlasea="col-md-3";		
			   break;			   			   	
		default:
		       $lblKlasea="col-md-1";
			   $txtKlasea="col-md-2";		
				
	}
     
  if ($fila=$this->devuelveFilaSql($resultados))
    {
	   echo "<div class='container'>";	
       echo "<h1 class='bg-info text-center'>". $titulo ."</h1>";		

	   echo "<form class='form-horizontal'>";
	   for ($i=0;$i<count($izenburuak);$i++)
	     {
		   if (($numCol==1)  || (($i+1)% $numCol==0))
		     {echo '<div class="form-group">';}

           echo '<label  class="control-label '.$lblKlasea .'">' .$izenburuak[$i].  '</label>';
	       echo '<div class="'. $txtKlasea.'">';
		   if ($conValores)
		     { echo	'<input type="text" name="txt'. $izenburuak[$i]  . '" class="form-control" value="'. $fila[$i] .'">';}
		   else
		     { echo	'<input type="text" name="txt'. $izenburuak[$i]  . '" class="form-control" value="">';}
		   echo '</div>';
		   if (($numCol==1)  || (($i+1)% $numCol==0))
		     { echo '</div>';}
			 
		 }
	   echo "</form>";
	   echo '</div>';	   
	}
}
  
  
  
}

?>