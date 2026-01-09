<?php
session_start();

if (isset($_SESSION['rol'])) {
    if ($_SESSION['rol'] === 'admin') {
        header("Location: ../admin/panel.php");
        exit;
    }
    header("Location: productos.php");
    exit;
}
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Login - Tienda</title>
</head>
<body>
  <h1>Iniciar sesión</h1>

  <?php if (isset($_GET['e'])): ?>
    <p style="color:red;">Email o contraseña incorrectos.</p>
  <?php endif; ?>

  <?php if (isset($_GET['ok'])): ?>
    <p style="color:green;">Registro completado. Ya puedes iniciar sesión.</p>
  <?php endif; ?>

  <form method="post" action="login_procesar.php">
    <label>Email</label><br>
    <input type="email" name="email" required><br><br>

    <label>Contraseña</label><br>
    <input type="password" name="password" required><br><br>

    <button type="submit">Entrar</button>
  </form>

  <p>¿No tienes cuenta? <a href="registro.php">Regístrate</a></p>
</body>
</html>
