<?php
session_start();
if (!isset($_SESSION['rol'])) {
    header("Location: login.php");
    exit;
}

require_once __DIR__ . '/../datos/conexion.php';


if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}


if (isset($_GET['add'])) {
    $id = (int)$_GET['add'];
    $_SESSION['carrito'][] = $id;
    header("Location: productos.php");
    exit;
}


$stmt = $pdo->query("SELECT id, nombre, descripcion, precio FROM articulos");
$articulos = $stmt->fetchAll();

function h(string $s): string {
    return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Tienda</title>
</head>
<body>
  <h1>Tienda de Zapatillas</h1>

  <p>
    Hola, <?php echo h($_SESSION['nombre']); ?> |
    <a href="carrito.php">🛒 Ver carrito (<?php echo count($_SESSION['carrito']); ?>)</a> |
    <a href="logout.php">Cerrar sesión</a>
  </p>

  <table border="1" cellpadding="6">
    <tr>
      <th>Nombre</th>
      <th>Descripción</th>
      <th>Precio</th>
      <th></th>
    </tr>

    <?php foreach ($articulos as $a): ?>
      <tr>
        <td><?php echo h($a['nombre']); ?></td>
        <td><?php echo h($a['descripcion']); ?></td>
        <td><?php echo number_format((float)$a['precio'], 2); ?> €</td>
        <td>
          <a href="productos.php?add=<?php echo (int)$a['id']; ?>">
            Añadir al carrito
          </a>
        </td>
      </tr>
    <?php endforeach; ?>
  </table>
</body>
</html>
