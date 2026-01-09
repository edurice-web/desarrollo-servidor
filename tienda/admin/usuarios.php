<?php
declare(strict_types=1);
session_start();

if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../public/login.php");
    exit;
}

require_once __DIR__ . '/../datos/conexion.php';


if (isset($_GET['del'])) {
    $idDel = (int)$_GET['del'];

    
    if ($idDel === (int)($_SESSION['id_usuario'] ?? 0)) {
        header("Location: usuarios.php?msg=no_self_delete");
        exit;
    }

    $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id = ?");
    $stmt->execute([$idDel]);

    header("Location: usuarios.php?msg=deleted");
    exit;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $email  = trim($_POST['email'] ?? '');
    $pass   = $_POST['password'] ?? '';
    $rol    = $_POST['rol'] ?? 'cliente';

    if ($nombre === '' || $email === '' || $pass === '') {
        header("Location: usuarios.php?msg=missing");
        exit;
    }

    if (!in_array($rol, ['admin', 'cliente'], true)) {
        $rol = 'cliente';
    }

    
    $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        header("Location: usuarios.php?msg=exists");
        exit;
    }

    $hash = password_hash($pass, PASSWORD_BCRYPT);

    $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, email, password_hash, rol) VALUES (?, ?, ?, ?)");
    $stmt->execute([$nombre, $email, $hash, $rol]);

    header("Location: usuarios.php?msg=created");
    exit;
}


$stmt = $pdo->query("SELECT id, nombre, email, rol, creado_en FROM usuarios ORDER BY id DESC");
$usuarios = $stmt->fetchAll();

function h(string $s): string {
    return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Admin - Usuarios</title>
</head>
<body>
  <h1>Gestión de Usuarios (RF01)</h1>
  <p>
    <a href="panel.php">⬅ Volver al panel</a> |
    <a href="../public/logout.php">Cerrar sesión</a>
  </p>

  <?php if (isset($_GET['msg'])): ?>
    <p style="color:green;">
      <?php
        $m = $_GET['msg'];
        if ($m === 'created') echo 'Usuario creado.';
        elseif ($m === 'deleted') echo 'Usuario eliminado.';
        elseif ($m === 'exists') echo 'Ese email ya existe.';
        elseif ($m === 'missing') echo 'Faltan datos.';
        elseif ($m === 'no_self_delete') echo 'No puedes borrarte a ti mismo.';
      ?>
    </p>
  <?php endif; ?>

  <h2>Crear usuario</h2>
  <form method="post">
    <label>Nombre</label><br>
    <input type="text" name="nombre" required maxlength="60"><br><br>

    <label>Email</label><br>
    <input type="email" name="email" required maxlength="120"><br><br>

    <label>Contraseña</label><br>
    <input type="password" name="password" required minlength="6"><br><br>

    <label>Rol</label><br>
    <select name="rol">
      <option value="cliente">cliente</option>
      <option value="admin">admin</option>
    </select><br><br>

    <button type="submit">Crear</button>
  </form>

  <h2>Lista de usuarios</h2>
  <table border="1" cellpadding="6">
    <tr>
      <th>ID</th>
      <th>Nombre</th>
      <th>Email</th>
      <th>Rol</th>
      <th>Creado</th>
      <th>Acción</th>
    </tr>

    <?php foreach ($usuarios as $u): ?>
      <tr>
        <td><?php echo (int)$u['id']; ?></td>
        <td><?php echo h($u['nombre']); ?></td>
        <td><?php echo h($u['email']); ?></td>
        <td><?php echo h($u['rol']); ?></td>
        <td><?php echo h((string)$u['creado_en']); ?></td>
        <td>
          <?php if ((int)$u['id'] === (int)($_SESSION['id_usuario'] ?? 0)): ?>
            (tú)
          <?php else: ?>
            <a href="usuarios.php?del=<?php echo (int)$u['id']; ?>"
               onclick="return confirm('¿Eliminar este usuario?');">Eliminar</a>
          <?php endif; ?>
        </td>
      </tr>
    <?php endforeach; ?>
  </table>
</body>
</html>
