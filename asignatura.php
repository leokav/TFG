<?php
ob_start();
session_start();
if(!isset($_SESSION["user_id"]) || !isset($_SESSION["admin"])) header("location: index.php");
include "header.php";


$alert = array(
    "",
    "Proceso realizado correctamente",
    "Error: Se produjo un error en las consulta",
    "Error: No existe ningun registro con esa ID",
    "Error: Algun campo esta vacio",
    );

if (isset($_GET))
{
        $v = 0;
        if (isset($_GET["id"]))
        $id = (int)$_GET["id"];
        if (isset($_GET["do"]))
        $do = $_GET["do"];

    $e = 0;

    //BORRAR

    if (isset($_GET["do"]) && ($do == "delete")) 
    {


        $result = mysqli_query($link,"SELECT * FROM asignatura WHERE id_asignatura = '$id' ");

        if (mysqli_num_rows($result) > 0)
        {

            $result = mysqli_query($link,"DELETE FROM asignatura WHERE id_asignatura = '$id'");

            if ($result)
                $e = 1;
            else
                $e = 2;

        } else
        {
            $e = 3;
        }
        header("location: asignatura.php?e=$e");

    }

    //Insertar

    else if (isset($_GET["do"]) && ($do == "insert") && isset($_POST))
        {

            $nombre = $_POST["nombre"];
            $descripcion = $_POST["descripcion"];
            $creditos = $_POST["creditos"];

            if (($nombre == '') || ($descripcion == '')){
                $e = 4;
            } else 
            
            {
                $result = mysqli_query($link, "INSERT INTO `asignatura` (`id_asignatura` ,`nombre` ,`descripcion`,`creditos`) VALUES (NULL , '$nombre', '$descripcion','$creditos')");
            
            if ($result) $id = mysqli_insert_id($link);

            foreach($_POST['estudiantes'] as $estudeleg)
            {
                $result3 = mysqli_query($link,"INSERT INTO `asignatura_estudiantes` (`id_asignatura`, `id_estudiante`) VALUES ('$id','$estudeleg')");
            };
            
            if (($result) && ($result3))  $e = 1;
                else $e = 2;

            }
            header("location: asignatura.php?e=$e");
        }
        
//Edicion
	else if(isset($_GET["do"]) && ($do == "edit") && isset($_POST)){
		
		$nombre = $_POST["nombre"];
        $descripcion = $_POST["descripcion"];
        $creditos = $_POST["creditos"];

		$result = mysqli_query($link, "UPDATE `asignatura` SET `nombre` = '$nombre', `descripcion` = '$descripcion', `creditos` = '$creditos' WHERE `id_asignatura` ='$id' LIMIT 1");
		if (!$_POST['estudiantes'])
        {
            $result = mysqli_query($link, "DELETE FROM `asignatura_estudiantes` WHERE `id_asignatura` = '$id'");             
        }
        
        if ($_POST['estudiantes'])
        {
            $result = mysqli_query($link, "DELETE FROM `asignatura_estudiantes` WHERE `id_asignatura` = '$id'");
        
            foreach ($_POST['estudiantes'] as $estudeleg)
            {
                $result = mysqli_query($link,"INSERT INTO `asignatura_estudiantes` (`id_asignatura`, `id_estudiante`) VALUES ('$id','$estudeleg')");
            }         
        }
        
        
        if($result)
  		  {
  		    $v = 0;
		  header("location: asignatura.php?e=1");
          }

		else  header("location: asignatura.php?e=2");
	
		
	}
    
}

    // Mostrar el formulario de edicion
    if (isset($_GET) && isset($_GET["do"]) && ($do == "showEdit"))
    {
        $v = 1;
        $id = (int)$_GET["id"];
        $result = mysqli_query($link, "SELECT * FROM asignatura WHERE id_asignatura = '$id' ");

        if (mysqli_num_rows($result) > 0)
        {

            $row = mysqli_fetch_assoc($result);

?>
<div class="bs-docs-section">

        <div class="row">
          <div class="col-lg-12">
            <div class="page-header">
              <h1 id="forms">Formulario de edicion de Asignatura</h1>
            </div>
          </div>
        </div>
        
        
        <div class="row">
        <div class="col-lg-12">
          <div class="well bs-component">
              <form class="form-horizontal" action="asignatura.php?do=edit&id= <?php echo $id;?> "method="post">
                <fieldset>
                  <legend>Edicion</legend>
                  
                  <div class="form-group">
                    <label for="inputEmail" class="col-lg-2 control-label">Nombre</label>
                    <div class="col-lg-10">
                      <input type="text" value="<?php echo $row["nombre"];?>"
            <?php if ($_SESSION["admin"]==false) echo "readonly=\"readonly\"";?> name="nombre" id="nombre" class="form-control" placeholder="nombre">
                    </div>
                  </div>
                  
                  <div class="form-group">
                    <label for="textArea" class="col-lg-2 control-label">Descripcion</label>
                    <div class="col-lg-10">
                      <textarea class="form-control" rows="3" id="descripcion" name="descripcion" <?php if ($_SESSION["admin"]==false) echo "readonly=\"readonly\"";?>><?php echo $row["descripcion"];?></textarea>
                      <span class="help-block">Introduzca una descripcion de la nueva asignatura</span>
                    </div>
                  </div>
                  
                  <div class="form-group">
                    <label for="inputEmail" class="col-lg-2 control-label">Creditos</label>
                    <div class="col-lg-10">
                      <input type="text" value="<?php echo $row["creditos"];?>" <?php if ($_SESSION["admin"]==false) echo "readonly=\"readonly\"";?> name="creditos" id="creditos" class="form-control" placeholder="creditos"/>
                    </div>
                  </div>
                  
        
        <?php

//Comprobar si existen Estudiantes y mostrarlos

    $result = mysqli_query($link, "SELECT * FROM estudiante ORDER BY id_estudiante ASC");
    $num = mysqli_num_rows($result);

    if ($num > 0)
    {
?>
    <div class="row">
        <div class="page-header span12 text-center">
              <h3 id="tables">Estudiantes matriculados</h3>
            </div>

            <div class="bs-component">
            <div class="table-responsive">
              <table class="table table2 table-striped table-hover sortable">
                <thead>
                  <tr>
                    <th>Check</th>
                    <!--<th>ID</th>-->
                    <th>DNI</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    </tr>
                    </thead>
    <tbody>
    
    <?php
    
    while ($row = mysqli_fetch_assoc($result))
        {
        $result5 = mysqli_query($link, "SELECT * FROM asignatura_estudiantes WHERE id_asignatura = $id");        
                
        echo 
        "<tr>
        	<td><input type=\"checkbox\" name=\"estudiantes[]\" value=\"$row[id_estudiante]\"";
                while ($row5 = mysqli_fetch_assoc($result5))
                {
                if ($row["id_estudiante"] == $row5["id_estudiante"] ){
                    echo "checked=\"checked\"";
                }
                }
                echo "/></td>";
            echo "<td>$row[dni]</td>
            <td>$row[nombre]</td>
            <td>$row[email]</td>
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

        } else
        {
            echo "No existe ningun registro con esa ID";
        }
    } 
    
    if (isset($_GET) && isset($_GET["do"]) && ($do == "alta"))
    { 

//ALTA
?>

<div class="bs-docs-section">

        <div class="row">
          <div class="col-lg-12">
            <div class="page-header">
              <h1 id="forms">Formulario de alta de Asignatura</h1>
            </div>
          </div>
        </div>
        
        <div class="row">
        <div class="col-lg-12">
          <div class="well bs-component">
              <form class="form-horizontal" action="asignatura.php?do=insert"method="post">
                <fieldset>
                  <legend>Alta</legend>
                  
                  <div class="form-group">
                    <label for="inputEmail" class="col-lg-2 control-label">Nombre</label>
                    <div class="col-lg-10">
                      <input type="text" name="nombre" id="nombre" class="form-control" placeholder="nombre"/>
                    </div>
                  </div>
                  
                  <div class="form-group">
                    <label for="textArea" class="col-lg-2 control-label">Descripcion</label>
                    <div class="col-lg-10">
                      <textarea class="form-control" rows="3" id="descripcion" name="descripcion"></textarea>
                      <span class="help-block">Introduzca una descripcion de la nueva asignatura</span>
                    </div>
                  </div>
                  
                  <div class="form-group">
                    <label for="inputEmail" class="col-lg-2 control-label">Creditos</label>
                    <div class="col-lg-10">
                      <input type="text" name="creditos" id="creditos" class="form-control" placeholder="creditos"/>
                    </div>
                  </div>
        
        <?php

//Comprobar si existen Asignaturas y mostrarlas

    $result = mysqli_query($link, "SELECT * FROM estudiante ORDER BY id_estudiante ASC");
    $num = mysqli_num_rows($result);

    if ($num > 0)
    {
?>
    <div class="row">
        <div class="page-header span12 text-center">
              <h3 id="tables">Estudiantes matriculados</h3>
            </div>

            <div class="bs-component">
            <div class="table-responsive">
              <table class="table table2 table-striped table-hover sortable">
                <thead>
                  <tr>
                    <th>Check</th>
                    <!--<th>ID</th>-->
                    <th>DNI</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    </tr>
                    </thead>
    <tbody>
    
    <?php
    
    while ($row = mysqli_fetch_assoc($result))
        {
        echo 
        "<tr>
        	<td><input type=\"checkbox\" name=\"estudiantes[]\" value=\"$row[id_estudiante]\" /></td>";
            echo "<td>$row[dni]</td>
            <td>$row[nombre]</td>
            <td>$row[email]</td>
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
    if ($_SESSION["admin"]==true)
    {    
        $result = mysqli_query($link, "SELECT * FROM asignatura");
        
    }else 
    {    
        $id_usuario = $_SESSION["user_id"];
        $result = mysqli_query($link, "SELECT * FROM `profesor_asignaturas` WHERE `id_usuario` = '$id_usuario'");
    }
    
    $num = mysqli_num_rows($result);

    if ($num>0)
    {
?>

    <div class="bs-docs-section">
        <div class="row">
          <div class="col-lg-12">
            <div class="page-header">
              <h1 id="tables">Listado de asignaturas<?php if ($_SESSION["admin"]==false) echo " que imparte $_SESSION[user_nombre]";?></h1>
            </div>
            
             <div class="bs-component">
             <div class="table-responsive">
              <table class="table table1 table-striped table-hover sortable">
                <thead>
                  <tr>
                    <th></th>
                    <!--<th>ID</th>-->
                    <th>Nombre</th>
                    <th>Descripcion</th>
                    <th>Creditos</th>
                    <th><?php if ($_SESSION["admin"]==true) echo "Edit/ver Estudiantes"; else echo "Estudiantes";?></th>
                    <?php if ($_SESSION["admin"]==true)
                    echo "<th>Borrar</th>";?>
                  </tr>
                </thead>
    <tbody>
    
    <?php
    
    while ($row = mysqli_fetch_assoc($result))
        { 
          $result4 = mysqli_query($link, "SELECT * FROM `asignatura_estudiantes` WHERE `id_asignatura` = '$row[id_asignatura]'");
            $num4 = mysqli_num_rows($result4);  
            
        if ($_SESSION["admin"]==true)
        {
        if ($j == 6) $j = -1;
            $j++;
        echo "<tr class=\"$array[$j]\">
        	<td></td>";
            echo 
            "<td>$row[nombre]</td>
            <td>$row[descripcion]</td>
            <td>$row[creditos]
            <td><a href=\"asignatura.php?do=showEdit&id=$row[id_asignatura]\"><img src=\"images/user_edit.png\" >  ($num4)</a></td>
            <td><a href=\"asignatura.php?do=delete&id=$row[id_asignatura]\" class=\"ask\"><img src=\"images/trash.png\" /></a></td>
        </tr>"; 
        }else 
        {
            $result3 = mysqli_query ($link, "SELECT * FROM asignatura WHERE id_asignatura = $row[id_asignatura]");
            $row3 = mysqli_fetch_assoc($result3);            
            
        if ($j == 6) $j = -1;
            $j++;
        echo "<tr class=\"$array[$j]\">
        	<td></td>
            <td>$row3[id_asignatura]</td>
            <td>$row3[nombre]</td>
            <td>$row3[descripcion]</td>
            <td>$row3[creditos]</td>
            <td><a href=\"asignatura.php?do=showEdit&id=$row3[id_asignatura]\"><img src=\"images/info.png\" >  ($num4)</a></td>";
        echo "</tr>";
        } 
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
            <?php if ($_SESSION["admin"]==true) echo "<a type=\"button\" class=\"btn btn-primary\"  href=\"asignatura.php?do=alta\">Nueva Asignatura</a>";?>
            </p>
            </div>
            </div>
            </div>
<?php
    include "footer.php";
?>