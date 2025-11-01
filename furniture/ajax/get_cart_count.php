<?php
require_once '../includes/functions.php';

header('Content-Type: application/json');

$count = isLoggedIn() ? getCartCount() : 0;

echo json_encode(['count' => $count]);
?>