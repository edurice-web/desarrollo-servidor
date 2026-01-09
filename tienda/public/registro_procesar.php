<?php
declare(strict_types=1);
session_start();
require_once __DIR__ . '/../datos/conexion.php';

$nombre = trim($_POST['nombre'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if ($nombre === '' || $email === '' || $password === '' || strlen($password) < 6) {
    header("Location: registro.php?e=data");
    exit;
}


$stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = ?");
$stmt->execute([$email]);
if ($stmt->fetch()) {
    header("Location: registro.php?e=email");
    exit;
}

$hash = password_hash($password, PASSWORD_BCRYPT);

$stmt = $pdo->prepare("INSERT INTO usuarios (nombre, email, password_hash, rol) VALUES (?, ?, ?, 'cliente')");
$stmt->execute([$nombre, $email, $hash]);

header("Location: login.php?ok=1");
exit;
