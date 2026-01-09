<?php
session_start();
if (!isset($_SESSION['rol'])) {
    header("Location: login.php");
    exit;
}

require_once __DIR__ . '/../datos/conexion.php';

$carrito = $_SESSION['carrito'] ?? [];


if (isset($_POST['confirmar'])) {
    $_SESSION['carrito'] = [];
    header("Location: carrito.php?ok=1");
    exit;
}

$articulos = [];
$total = 0;

if ($carrito) {
    $ids = implode(',', array_map('intval', $carrito));
    $stmt = $pdo->query("SELECT id, nombre, precio FROM articulos WHERE id IN ($ids)");
    $articulos = $stmt->fetchAll();

    foreach ($articulos as $a) {
        $total += (float)$a['precio'];
    }
}

function h(string $s): string {
    return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Carrito</title>
</head>
<body>
  <h1>Carrito</h1>

  <p>
    <a href="productos.php">⬅ Volver a la tienda</a> |
    <a href="logout.php">Cerrar sesión</a>
  </p>

  <?php if (isset($_GET['ok'])): ?>
    <p style="color:green;">Compra confirmada. ¡Gracias!</p>
  <?php endif; ?>

  <?php if (!$articulos): ?>
    <p>El carrito está vacío.</p>
  <?php else: ?>
    <table border="1" cellpadding="6">
      <tr>
        <th>Artículo</th>
        <th>Precio</th>
      </tr>

      <?php foreach ($articulos as $a): ?>
        <tr>
          <td><?php echo h($a['nombre']); ?></td>
          <td><?php echo number_format((float)$a['precio'], 2); ?> €</td>
        </tr>
      <?php endforeach; ?>

      <tr>
        <th>Total</th>
        <th><?php echo number_format($total, 2); ?> €</th>
      </tr>
    </table>

    <form method="post">
      <button type="submit" name="confirmar">Confirmar compra</button>
    </form>
  <?php endif; ?>
</body>
</html>
