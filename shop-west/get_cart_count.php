<?php
session_start();
require_once 'config/database.php';

header('Content-Type: application/json');

$count = 0;

if (isset($_SESSION['user_id'])) {
    $stmt = $pdo->prepare("SELECT SUM(quantity) FROM cart WHERE user_id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $count = $stmt->fetchColumn() ?: 0;
}

echo json_encode(['count' => (int)$count]);
?>