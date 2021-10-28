<?php
	session_start();
	if($_SESSION['rol'] != 1)
	{
		header("location:../");
	}
	include "../conexion.php";

?>


<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php include "includes/scripts.php"; ?>
	<title>Lista de usuarios</title>
</head>
<body>
	<?php include "includes/header.php"; ?>
	<section id="container">
		
		<h1><i class="fas fa-user-secret"></i> Lista de Administradores</h1>
		<a href="registro_usuario.php" class="btn_new"><i class="fas fa-user-plus"></i> Crear Administrador</a>

		<form action="buscar_usuario.php" method="get" class="form_search">
			<input type="text" name="busqueda" id="busqueda" placeholder="Buscar">
			<button type="submit" class="btn_search">Buscar <i class="fas fa-search-plus"></i></button>
		</form>

		<table>
			<tr>
				<th>ID <i class="fas fa-fingerprint"></i> </th>
				<th>Nombre</th>
				<th>Correo <i class="fas fa-at"></i></th>
				<th>Usuario</th>
				<th>Rol</th>
				<th>Acciones</th>
			</tr>
			<?php
				//Paginador
				$sql_registe = mysqli_query($conection, "SELECT COUNT(*) AS total_registro FROM `usuario` WHERE estatus = 1");
				$result_resgister = mysqli_fetch_array($sql_registe);
				$total_registro = $result_resgister['total_registro'];

				$por_pagina = 10;

				if(empty($_GET['pagina']))
				{
					$pagina = 1;
				}else{
					$pagina = $_GET['pagina'];
				}

				$desde = ($pagina-1) * $por_pagina;
				$total_paginas = ceil($total_registro / $por_pagina);

				$query = mysqli_query($conection, "SELECT u.idusuario, u.nombre, u.correo, u.usuario, r.rol FROM usuario u INNER JOIN rol r ON u.rol = r.idrol WHERE estatus = 1
					ORDER BY u.idusuario 
					ASC LIMIT $desde,$por_pagina"); //ASC = acendente  DSC = desendente.
				
				mysqli_close($conection);
				$result = mysqli_num_rows($query);

				if($result > 0){

					while ($data = mysqli_fetch_array($query)) {
				?>
				<tr>
					<td><?php echo $data['idusuario']; ?></td>
					<td><?php echo $data['nombre']; ?></td>
					<td><?php echo $data['correo']; ?></td>
					<td><?php echo $data['usuario']; ?></td>
					<td><?php echo $data['rol']; ?></td>
					<td>
						<a class="link_edit" href="editar_usuario2.php?id=<?php echo $data['idusuario']; ?>"><i class="far fa-edit"></i> Editar</a>
						<?php if($data["idusuario"] != 1){ ?>
							|
							<a class="link_delete" href="eliminar_usuario.php?id=<?php echo $data['idusuario']; ?>"><i class="far fa-trash-alt"></i> Eliminar</a>
							|
							<a class="link_detalle" href="detalle_usuario.php?id=<?php echo $data['idusuario']; ?>"><i class="fas fa-info"></i> Detalle</a>

						<?php } ?>
						
					</td>
				</tr> 
			<?php	
					 }
				}

			?>
		</table>
		<div class="paginador">
			<ul>
				<?php
					if($pagina != 1)
					{
				 ?>
				<li><a href="?pagina=<?php echo 1; ?>"><i class="fas fa-backward"></i></a></li>
				<li><a href="?pagina=<?php echo $pagina-1; ?>"><i class="fas fa-caret-left"></i></a></li>
				<?php
					}
					for ($i=1; $i <= $total_paginas; $i++) { 
						# code...
						if($i == $pagina)
						{
							echo '<li class="pageSelected">'.$i.'</li>';
						}else{
						echo '<li><a href="?pagina='.$i.'">'.$i.'</a></li>';
						}
					}

					if($pagina != $total_paginas)
					{
				?>
				<li><a href="?pagina=<?php echo $pagina+1; ?>"><i class="fas fa-caret-right"></i></a></li>
				<li><a href="?pagina=<?php echo $total_paginas; ?>"><i class="fas fa-forward"></i></a></li>
				<?php } ?>
			</ul>
			<a href="index.php" class="btn_regresar"><i class="fas fa-undo-alt"></i> </a>
		</div>

	</section>
	<?php include "includes/footer.php"; ?>

</body>
</html>