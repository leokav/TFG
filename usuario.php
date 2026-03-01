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
	"Error: No existe ning�n registro con esa ID",
	"Error: El campo est� vacio",
	"Error: Nombre vacio",
	"Error: Dni Vacio",
	"Error: Error en el password",
    "Error: Debes cambiar el password",
    );
    
    
 if (isset($_GET))
    
{
	$v = 0;
	if (isset($_GET["id"])) $id = $_GET["id"];
    if (isset($_GET["do"])) $do = $_GET["do"];

    $e = 0;
	
//Borrar
	if (isset($_GET["do"]) && ($do == "delete")) 
    {
	
			$result = mysqli_query($link, "SELECT * FROM usuario WHERE id_usuario = '$id' ");
	
			if(mysqli_num_rows($result)>0){
	
				$result = mysqli_query($link, "DELETE FROM usuario WHERE id_usuario = '$id'");
	
				// Si se ha eliminado correctamente se hace una redireccion a la pagina para que se actualice.
				if($result) $e=1;	 			
				else $e=2;	
			} else {	
				$e=3;			
			}			
			header("location: usuario.php?e=$e");
	
	}

	
//Insertar

	else if (isset($_GET["do"]) && ($do == "insert") && isset($_POST)){
		
        $dni = $_POST["dni"];		
		$nombre = $_POST["nombre"];
        $seed = "unmonohaciendoelpinoalreves";
        $password1 = $_POST["password"];
        $password2 = $_POST["password1"];
        $password = sha1($password1.$seed,false);
        
        $admin = (int)$_POST["admin"];
	
		// Comprobaciones
	
		if($dni == '') $e=6;

		else if($nombre == '') $e=5;
	
		else if($password1 == '' || $password1!=$password2 ) $e=7;
	
		else {
	
			$result = mysqli_query($link, "INSERT INTO `usuario` (`id_usuario` ,`dni` ,`nombre` ,`password` ,`admin`)VALUES (NULL , '$dni', '$nombre', '$password', '$admin')");
	
			if($result) $e=1; else $e=2; 
	
		}
        
        if ($e==1){
            header ("location: usuario.php?e=$e");
            
        }else
		
		header("location: usuario.php?do=alta&e=$e");	
		
	}
    
    	
//Edicion

	else if(isset($_GET["do"]) && ($do == "edit") && isset($_POST)){

        $dni = $_POST["dni"];
		$nombre = $_POST["nombre"];
		$password1 = $_POST["password"];
        $seed = "unmonohaciendoelpinoalreves";
        $password = sha1($password1.$seed,false);
        $admin = $_POST["admin"];
        
        if ($password1 != "Introduce una nueva contrasena")
        {

		$result = mysqli_query($link, "UPDATE `rubrica`.`usuario` SET  `dni` =  '$dni', `nombre` =  '$nombre',	`password` =  '$password', `admin` = $admin 
		WHERE  `id_usuario` ='$id' LIMIT 1 ");

		if($result) {
        $v = 0;
        header("location: usuario.php?e=1");
        }
		else  header("location: usuario.php?e=2&admin=$admin");
        } else header ("location: usuario.php?e=8");
	
		
	}

}


// Mostrar el formulario de edicion
if (isset($_GET) && isset($_GET["do"]) && ($do == "showEdit"))
{
    $v = 1;
	$id = $_GET["id"];

	$result = mysqli_query($link, "SELECT * FROM usuario WHERE id_usuario = '$id' ");

	if(mysqli_num_rows($result)>0){

		$row = mysqli_fetch_assoc($result);

		?>
		
<div class="bs-docs-section">

        <div class="row">
          <div class="col-lg-12">
            <div class="page-header">
              <h1 id="forms">Formulario de edicion de Usuario</h1>
            </div>
          </div>
        </div>
        
        
        <div class="row">
        <div class="col-lg-12">
          <div class="well bs-component">
            <form action="usuario.php?do=edit&id=<?php echo $id;?>"method="post" class="form-horizontal">
            <fieldset>
            <legend>Edicion</legend>
            
            <div class="form-group">
                    <label for="inputEmail" class="col-lg-2 control-label">DNI</label>
                    <div class="col-lg-10">
                      <input type="text" value="<?php echo $row["dni"];?>" name="dni" id="dni" class="form-control" placeholder="dni">
                    </div>
                  </div>
            
            <div class="form-group">
                    <label for="inputEmail" class="col-lg-2 control-label">Nombre</label>
                    <div class="col-lg-10">
                      <input type="text" value="<?php echo $row["nombre"];?>" name="nombre" id="nombre" class="form-control" placeholder="nombre">
                    </div>
                  </div>
            
            <div class="form-group">
                    <label for="inputPassword" class="col-lg-2 control-label">Password</label>
                    <div class="col-lg-10">
                      <input type="password" value="Introduce una nueva contrasena" onclick="this.value = ''" name="password" id="password" class="form-control" placeholder="password">
                    </div>
                  </div>
    
            <div class="form-group">
                    <label class="col-lg-2 control-label">Admin</label>
                    <div class="col-lg-10">
                      <div class="radio">
                        <label>
                          <input type="radio" name="admin" id="admin" value="0" <?php if ($row["admin"]==0){ echo "checked=\"checked\""; } ?>/>
                          No
                        </label>
                      </div>
                      <div class="radio">
                        <label>
                          <input type="radio" name="admin" id="admin" value="1" <?php if ($row["admin"]==1){ echo "checked=\"checked\""; } ?>/>
                          Si
                        </label>
                      </div>
                    </div>
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
              <h1 id="forms">Formulario de alta de Usuario</h1>
            </div>
          </div>
        </div>
        
        <div class="row">
        <div class="col-lg-12">
          <div class="well bs-component">
        <form action="usuario.php?do=insert" method="post" class="form-horizontal">        
        <fieldset>
        <legend>Alta</legend>
        
        <div class="form-group">
                    <label for="inputEmail" class="col-lg-2 control-label">DNI</label>
                    <div class="col-lg-10">
                      <input type="text" name="dni" id="dni" class="form-control" placeholder="dni">
                    </div>
                  </div>
            
            <div class="form-group">
                    <label for="inputEmail" class="col-lg-2 control-label">Nombre</label>
                    <div class="col-lg-10">
                      <input type="text" name="nombre" id="nombre" class="form-control" placeholder="nombre">
                    </div>
                  </div>
            
            <div class="form-group">
                    <label for="inputPassword" class="col-lg-2 control-label">Password</label>
                    <div class="col-lg-10">
                      <input type="password" value="Introduce una nueva contrasena" onclick="this.value = ''" name="password" id="password" class="form-control" placeholder="password">
                    </div>
                  </div>
                  
                  <div class="form-group">
                    <label for="inputPassword" class="col-lg-2 control-label">Repite Password</label>
                    <div class="col-lg-10">
                      <input type="password" value="Introduce una nueva contrasena" onclick="this.value = ''" name="password1" id="password1" class="form-control" placeholder="password">
                    </div>
                  </div>
    
            <div class="form-group">
                    <label class="col-lg-2 control-label">Admin</label>
                    <div class="col-lg-10">
                      <div class="radio">
                        <label>
                          <input type="radio" name="admin" id="admin" value="0"/>
                          No
                        </label>
                      </div>
                      <div class="radio">
                        <label>
                          <input type="radio" name="admin" id="admin" value="1"/>
                          Si
                        </label>
                      </div>
                    </div>
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

    $result = mysqli_query($link, "SELECT * FROM usuario");
    $num = mysqli_num_rows($result);

    if ($num > 0)
    {
?>
    <div class="bs-docs-section">
        <div class="row">
          <div class="col-lg-12">
            <div class="page-header">
              <h1 id="tables">Listado de Usuarios</h1>
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
                    <th>Password</th>
                    <th>Admin</th>
                    <th>Editar</th>
                    <th>Borrar</th>
                    </tr>
                </thead>
    <tbody>
 
    <?php
    
    while ($row = mysqli_fetch_assoc($result))
        {            
        if ($j == 6) $j = -1;
            $j++;
        echo "<tr class=\"$array[$j]\">
        	<td></td>";
            echo "<td>$row[dni]</td>
            <td>$row[nombre]</td>
            <td>**********</td>
            <td>$row[admin]</td>
            <td><a href=\"usuario.php?do=showEdit&id=$row[id_usuario]\"><img src=\"images/user_edit.png\" ></a></td>";
            
            if ($_SESSION["user_id"] == $row["id_usuario"]) 
            {              
                echo "<td> No permitido </td>";
            }else echo "
            <td><a href=\"usuario.php?do=delete&id=$row[id_usuario]\" class=\"ask\"><img src=\"images/trash.png\" /></a></td>
            </tr>";;     
    
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
            <?php if ($_SESSION["admin"]==true) echo "<a type=\"button\" class=\"btn btn-primary\"  href=\"usuario.php?do=alta\">Nuevo Usuario</a>";?>
            </p>
            </div>
            </div>
            </div>

<?php
    include "footer.php";
?>