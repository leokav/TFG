<?php
ob_start();
session_start();
if(!isset($_SESSION["admin"]) || $_SESSION["admin"]==false) header("location: index.php");
include "header.php";


// Array con todos los posibles errores
$alert = array(
	"",
	"Proceso realizado satisfactoriamente",
	"Error: Se produjo un error en la consulta",
	"Error: No existe ningun registro con esa ID",
	"Error: El campo esta vacio",
	"Error: Seleccione un usuario",
	"Error: Titulo vacio",
	);
    
    if (isset($_GET))
    
{
	$v = 0;
	if (isset($_GET["id"])) $id = (int)$_GET["id"];
    if (isset($_GET["do"])) $do = $_GET["do"];

    $e = 0;
	
//Borrar
	if (isset($_GET["do"]) && ($do == "delete")) 
    {
	
			$result = mysqli_query($link, "SELECT * FROM profesor WHERE id_usuario = '$id' ");
	
			if(mysqli_num_rows($result)>0){
	
				$result = mysqli_query($link, "DELETE FROM profesor WHERE id_usuario = '$id'");
	
				// Si se ha eliminado correctamente se hace una redireccion a la pagina para que se actualice.
				if($result) $e=1;	 			
				else $e=2;	
			} else {	
				$e=3;			
			}			
			header("location: profesor.php?e=$e");
	
	}

	
//Insertar

	else if (isset($_GET["do"]) && ($do == "insert") && isset($_POST)){
	   
		
		$id = (int)$_POST["id_usuario"];		
		$titulo = $_POST["titulo"];

		if ($id == 0) $e=5;
	
		else if($titulo == '') $e=6;
	
		else {
	
			$result = mysqli_query($link, "INSERT INTO `profesor` (`id_usuario`,`titulo`) VALUES ('$id', '$titulo') ");
            
            foreach($_POST['asig'] as $asigsel)
            {
                $result = mysqli_query($link, "INSERT INTO `profesor_asignaturas` (`id_usuario`,`id_asignatura`) VALUES ('$id','$asigsel') ");
            };
	
			if($result) $e=1; else $e=2; 
	
		}
		
		header("location: profesor.php?e=$e");	
		
	}
	
//Edicion

	else if(isset($_GET["do"]) && ($do == "edit") && isset($_POST)){
		
        $v = 0;
        $id = $_GET["id"];
		$titulo = $_POST["titulo"];

        $result = mysqli_query($link, "UPDATE `profesor` SET  `titulo` =  '$titulo' WHERE  `id_usuario` ='$id' LIMIT 1 ");
        
        if (!$_POST['asig'])
        {
            $result = mysqli_query($link, "DELETE FROM `profesor_asignaturas` WHERE `id_usuario` = '$id'");             
        }
        
        if ($_POST['asig'])
        {
            $result = mysqli_query($link, "DELETE FROM `profesor_asignaturas` WHERE `id_usuario` = '$id'");
        
        foreach ($_POST['asig'] as $asigsel)
        {  
            $result = mysqli_query($link,"INSERT INTO `profesor_asignaturas` (`id_usuario`, `id_asignatura`) VALUES ('$id','$asigsel')");
        } 
        
        }      
        

		if($result) header("location: profesor.php?e=1");

		else  header("location: profesor.php?e=2");
	
		
	}

}


// Mostrar el formulario de edicion
if (isset($_GET) && isset($_GET["do"]) && ($do == "showEdit")){
    
    $v = 1;
	$id = (int)$_GET["id"];

	$result = mysqli_query($link, "SELECT * FROM profesor WHERE id_usuario = '$id' ");
    $result2 = mysqli_query($link, "SELECT * FROM usuario WHERE id_usuario = '$id' ");

	if(mysqli_num_rows($result)>0){

		$row = mysqli_fetch_assoc($result);
        $row2 = mysqli_fetch_assoc($result2);

		?>
		
  <div class="bs-docs-section">

        <div class="row">
          <div class="col-lg-12">
            <div class="page-header">
              <h1 id="forms">Formulario de edicion de Profesor</h1>
            </div>
          </div>
        </div>        
        
        <div class="row">
        <div class="col-lg-12">
          <div class="well bs-component">    
        <form action="profesor.php?do=edit&id=<?php echo $id;?>"method="post" class="form-horizontal">
    <fieldset>
    <legend>Edicion</legend>
    
    <div class="form-group">
                    <label for="inputEmail" class="col-lg-2 control-label">DNI</label>
                    <div class="col-lg-10">
                      <input type="text" value="<?php echo $row2["dni"];?>" name="id" id="id" class="form-control" placeholder="dni" readonly="readonly">
                    </div>
                  </div>
                  
                  
                  <div class="form-group">
                    <label for="textArea" class="col-lg-2 control-label">Titulo</label>
                    <div class="col-lg-10">
                      <textarea class="form-control" rows="3" id="titulo" name="titulo"><?php echo $row["titulo"];?></textarea>
                      <span class="help-block">Introduzca su titulacion/es</span>
                    </div>
                  </div>
        
        <?php

//Comprobar si existen Asignaturas y mostrarlas

    $result = mysqli_query($link, "SELECT * FROM asignatura ORDER BY id_asignatura ASC");
    $num = mysqli_num_rows($result);
    if ($num > 0)
    {
?>
    <div class="row">
        <div class="page-header span12 text-center">
              <h3 id="tables">Asignaturas de imparte</h3>
            </div>

            <div class="bs-component">
            <div class="table-responsive">
              <table class="table table2 table-striped table-hover sortable">
                <thead>
    	<tr>
        	<th></th>
            <th>ID_Asignatura</th>
            <th>Nombre</th>
            <th>Creditos</th>
        </tr>        
    </thead>
    <tbody>
    
    <?php
    
    while ($row = mysqli_fetch_assoc($result))
        {
        $result5 = mysqli_query($link, "SELECT * FROM profesor_asignaturas WHERE id_usuario = $id");
        
                
        echo 
        "<tr>
        	<td><input type=\"checkbox\" name=\"asig[]\" value=\"$row[id_asignatura]\"";
                while ($row5 = mysqli_fetch_assoc($result5)){
                if ($row["id_asignatura"] == $row5["id_asignatura"] ){
                    echo "checked=\"checked\"";
                }
                }
                echo "         
            /></td>
            <td>$row[id_asignatura]</td>
            <td>$row[nombre]</td>
            <td>$row[creditos]</td>
        </tr>";    
    }
        echo 
        "</tbody>
        </table>";
        
    }
?>        
        </div>
            </div><!-- /example -->
          </div>

        
                  <div class="form-group text-center ">
                    <div class="row">
                    <div class="col-lg-12">
                      <input type="submit" class="btn btn-primary" name="submit" id="submit" value="Aceptar" />
                      <a type="button" class="btn btn-default" onclick="history.back()">Cancel</a>                    
                    </div>
                    </div>
                  </div>
                </fieldset>
              </form>
            </div>
            </div>
          </div>
</div>		       
        
        <?php	

	} else {

		echo "No existe ningun registro con esa ID";
	}

}

    if (isset($_GET) && isset($_GET["do"]) && ($do == "alta"))
    { 
?>

<div class="bs-docs-section">

        <div class="row">
          <div class="col-lg-12">
            <div class="page-header">
              <h1 id="forms">Formulario de alta de Profesor</h1>
            </div>
          </div>
        </div>
        
        <div class="row">
        <div class="col-lg-12">
          <div class="well bs-component">
<form action="profesor.php?do=insert&a=<?php echo $a?>" method="post" class="form-horizontal">
    <fieldset>
    <legend>Alta</legend>
    <div class="form-group">
                    <label for="select" class="col-lg-2 control-label">Usuario</label>
                    <div class="col-lg-10">
                      <select class="form-control" id="id_usuario" name="id_usuario">
                        <option value="0" onchange="this.form.submit()">Seleccione Usuario</option>
                        <?php
		$result = mysqli_query($link, "SELECT * FROM usuario ORDER BY dni ASC");
		while($row = mysqli_fetch_assoc($result)){
            $result2 = mysqli_query($link, "SELECT * FROM profesor WHERE `id_usuario` = $row[id_usuario]");
            $row2 = mysqli_fetch_assoc($result2);
            if (!$row2)
            {

			echo "<option value=\"$row[id_usuario]\">$row[id_usuario] - $row[dni] - $row[nombre]</option>";
            }

		}
	?>
                      </select>
                    </div>
                  </div>          
    
        <div class="form-group">
                    <label for="textArea" class="col-lg-2 control-label">Titulo</label>
                    <div class="col-lg-10">
                      <textarea class="form-control" rows="3" id="titulo" name="titulo"></textarea>
                      <span class="help-block">Introduzca su titulacion/es</span>
                    </div>
                  </div>

<?php

//Comprobar si existen Asignaturas y mostrarlas

    $result = mysqli_query($link, "SELECT * FROM asignatura ORDER BY id_asignatura ASC");
    $num = mysqli_num_rows($result);
    if ($num > 0)
    {
?>
        <div class="row">
        <div class="page-header span12 text-center">
              <h3 id="tables">Asignaturas de Imparte</h3>
            </div>

            <div class="bs-component">
            <div class="table-responsive">
              <table class="table table2 table-striped table-hover sortable">
                <thead>
                  <tr>
        	<th></th>
            <th>ID_Asignatura</th>
            <th>Nombre</th>
            <th>Creditos</th>
            </tr>
    </thead>
    <tbody>
    
    <?php
    
    while ($row = mysqli_fetch_assoc($result))
        {
        echo 
        "<tr>
        	<td><input type=\"checkbox\" name=\"asig[]\" value=\"$row[id_asignatura]\" /></td>
            <td>$row[id_asignatura]</td>
            <td>$row[nombre]</td>
            <td>$row[creditos]</td>
        </tr>";    
    }
        echo 
        "</tbody>
        </table>";
        
    }
?>
        </div>
            </div><!-- /example -->
          </div>

        
                  <div class="form-group text-center ">
                    <div class="row">
                    <div class="col-lg-12">
                      <input type="submit" class="btn btn-primary" name="submit" id="submit" value="Aceptar" />
                      <a type="button" class="btn btn-default" onclick="history.back()">Cancel</a>                    
                    </div>
                    </div>
                  </div>
                </fieldset>
              </form>
            </div>
            </div>
          </div>
</div>

<?php

}

if (isset($_GET['e']))
    {

        $e = (int)$_GET['e'];
        if ($e == 1)
            echo "<h2 style=\"color: black;\" align=\"center\">$alert[$e]</h2>";
        else
            echo "<h2 style=\"color: black;\" align=\"center\">$alert[$e]</h2>";
            
    }

// Comprobar si existen registros y mostrarlos

    $result = mysqli_query($link, "SELECT * FROM profesor");
    $result2 = mysqli_query($link, "SELECT * FROM usuario");
    $num = mysqli_num_rows($result);
    $num2 = mysqli_num_rows($result2);

    if ($num>0)
    {
?>
        <div class="bs-docs-section">
        <div class="row">
          <div class="col-lg-12">
            <div class="page-header">
              <h1 id="tables">Listado de Profesores</h1>
            </div>
    
            <div class="bs-component">
             <div class="table-responsive">
              <table class="table table1 table-striped table-hover sortable">
                <thead>
                  <tr> 
        	<th></th>
            <!--<th>ID</th>-->
            <th>DNI</th>
            <th>Nombre</th>
            <th>Titulo</th>
            <th>Editar/Ver Asignaturas</th>
            <th>Borrar</th>
        </tr>
    </thead>
    <tbody>
    
    <?php
    
    while ($row = mysqli_fetch_assoc($result))
        {
        
        $rr = mysqli_query($link, "SELECT * FROM usuario WHERE `id_usuario`= $row[id_usuario]");
        $row2 = mysqli_fetch_assoc($rr);    
        
        if ($j == 6) $j = -1;
            $j++;
        echo "<tr class=\"$array[$j]\">
        	<td></td>";
            echo "<td>$row2[dni]</td>
            <td>$row2[nombre]</td>
            <td>$row[titulo]</td>
            ";
            echo "<td><a href=\"profesor.php?do=showEdit&id=$row[id_usuario]\"><img src=\"images/user_edit.png\" >"; 
            
            $result4 = mysqli_query($link, "SELECT * FROM profesor_asignaturas WHERE id_usuario = $row[id_usuario]");
            $p = 1;
            while ($row6 = mysqli_fetch_assoc($result4)){
                
                $result7 = mysqli_query($link,"SELECT * FROM asignatura WHERE id_asignatura = $row6[id_asignatura]");
                $row8 = mysqli_fetch_assoc($result7);
                $num9 = mysqli_num_rows($result4);
                
                echo $row8["nombre"];
                if ($p != $num9) 
                {
                    echo ", ";
                }
                else 
                {
                    echo ""; 
                }
                $p++;
            
            }; echo"</a></td>
            <td><a href=\"profesor.php?do=delete&id=$row[id_usuario]\" class=\"ask\"><img src=\"images/trash.png\" /></a></td>
        </tr>";     
    
    }
        echo 
        "</tbody>
        </table>";
    }
    if ($num>0)
        { 
        ?>
        </div>    
            </div><!-- /example -->
          </div>
        </div>
      </div>
      <?php } ?>
      
      <div class="bs-docs-section">
          <div class="row">
            <div class="col-lg-12 text-right">
            <p class="bs-component">
            <?php if ($_SESSION["admin"]==true) echo "<a type=\"button\" class=\"btn btn-primary\"  href=\"profesor.php?do=alta\">Nuevo Profesor</a>";?>
            </p>
            </div>
            </div>
            </div>
<?php
    include "footer.php";
?>