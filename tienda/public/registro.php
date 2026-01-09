<?php
session_start();
if (isset($_SESSION['rol'])) {
    header("Location: productos.php");
    exit;
}
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Registro - Tienda</title>
</head>
<body>
  <h1>Registro (Cliente)</h1>

  <?php if (isset($_GET['e']) && $_GET['e'] === 'email'): ?>
    <p style="color:red;">Ese email ya está registrado.</p>
  <?php endif; ?>

  <?php if (isset($_GET['e']) && $_GET['e'] === 'data'): ?>
    <p style="color:red;">Faltan datos o no son válidos.</p>
  <?php endif; ?>

  <form method="post" action="registro_procesar.php">
    <label>Nombre</label><br>
    <input type="text" name="nombre" required maxlength="60"><br><br>

    <label>Email</label><br>
    <input type="email" name="email" required maxlength="120"><br><br>

    <label>Contraseña</label><br>
    <input type="password" name="password" required minlength="6"><br><br>

    <button type="submit">Crear cuenta</button>
  </form>

  <p><a href="login.php">Volver al login</a></p>
</body>
</html>
