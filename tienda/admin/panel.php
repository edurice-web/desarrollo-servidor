<?php
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../public/login.php");
    exit;
}
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Panel Admin</title>
</head>
<body>
  <h1>Panel de Administración</h1>
  <p>Bienvenido, <?php echo htmlspecialchars($_SESSION['nombre']); ?> (admin)</p>


<ul>
  <li><a href="usuarios.php">Gestionar usuarios</a></li>
  <li><a href="articulos.php">Gestionar artículos</a></li>
  <li><a href="../public/logout.php">Cerrar sesión</a></li>
</ul>


</body>
</html>
