<?php
declare(strict_types=1);
session_start();

if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../public/login.php");
    exit;
}

require_once __DIR__ . '/../datos/conexion.php';


if (isset($_GET['del'])) {
    $id = (int)$_GET['del'];
    $stmt = $pdo->prepare("DELETE FROM articulos WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: articulos.php?msg=deleted");
    exit;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');
    $precio = (float)($_POST['precio'] ?? 0);

    if ($nombre === '' || $precio <= 0) {
        header("Location: articulos.php?msg=missing");
        exit;
    }

    $stmt = $pdo->prepare(
        "INSERT INTO articulos (nombre, descripcion, precio) VALUES (?, ?, ?)"
    );
    $stmt->execute([$nombre, $descripcion, $precio]);

    header("Location: articulos.php?msg=created");
    exit;
}


$stmt = $pdo->query("SELECT id, nombre, descripcion, precio, creado_en FROM articulos ORDER BY id DESC");
$articulos = $stmt->fetchAll();

function h(string $s): string {
    return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Admin - Artículos</title>
</head>
<body>
  <h1>Gestión de Artículos</h1>

  <p>
    <a href="panel.php">⬅ Volver al panel</a> |
    <a href="../public/logout.php">Cerrar sesión</a>
  </p>

  <?php if (isset($_GET['msg'])): ?>
    <p style="color:green;">
      <?php
        if ($_GET['msg'] === 'created') echo 'Artículo creado.';
        if ($_GET['msg'] === 'deleted') echo 'Artículo eliminado.';
        if ($_GET['msg'] === 'missing') echo 'Datos incorrectos.';
      ?>
    </p>
  <?php endif; ?>

  <h2>Crear artículo</h2>
  <form method="post">
    <label>Nombre</label><br>
    <input type="text" name="nombre" required><br><br>

    <label>Descripción</label><br>
    <textarea name="descripcion"></textarea><br><br>

    <label>Precio (€)</label><br>
    <input type="number" step="0.01" name="precio" required><br><br>

    <button type="submit">Crear</button>
  </form>

  <h2>Lista de artículos</h2>
  <table border="1" cellpadding="6">
    <tr>
      <th>ID</th>
      <th>Nombre</th>
      <th>Descripción</th>
      <th>Precio</th>
      <th>Creado</th>
      <th>Acción</th>
    </tr>

    <?php foreach ($articulos as $a): ?>
      <tr>
        <td><?php echo (int)$a['id']; ?></td>
        <td><?php echo h($a['nombre']); ?></td>
        <td><?php echo h($a['descripcion']); ?></td>
        <td><?php echo number_format((float)$a['precio'], 2); ?> €</td>
        <td><?php echo h((string)$a['creado_en']); ?></td>
        <td>
          <a href="articulos.php?del=<?php echo (int)$a['id']; ?>"
             onclick="return confirm('¿Eliminar este artículo?');">Eliminar</a>
        </td>
      </tr>
    <?php endforeach; ?>
  </table>
</body>
</html>
