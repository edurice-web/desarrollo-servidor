<?php
declare(strict_types=1);
session_start();
require_once __DIR__ . '/../datos/conexion.php';

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if ($email === '' || $password === '') {
    header("Location: login.php?e=1");
    exit;
}

$stmt = $pdo->prepare("SELECT id, nombre, email, password_hash, rol FROM usuarios WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch();

if (!$user || !password_verify($password, $user['password_hash'])) {
    header("Location: login.php?e=1");
    exit;
}


$_SESSION['id_usuario'] = (int)$user['id'];
$_SESSION['nombre'] = $user['nombre'];
$_SESSION['rol'] = $user['rol'];

if ($user['rol'] === 'admin') {
    header("Location: ../admin/panel.php");
    exit;
}
header("Location: productos.php");
exit;
