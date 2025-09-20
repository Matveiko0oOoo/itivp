<?php
require_once '../dbConf/config.php';

$id = $_GET['id'] ?? null;

if (!$id || !is_numeric($id)) {
    header('Location: index.php');
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT id FROM gifts WHERE id = ?");
    $stmt->execute([$id]);
    
    if (!$stmt->fetch()) {
        header('Location: index.php');
        exit;
    }
    
    $stmt = $pdo->prepare("DELETE FROM gifts WHERE id = ?");
    $stmt->execute([$id]);
    
    header('Location: index.php?deleted=1');
    exit;
    
} catch(PDOException $e) {
    header('Location: index.php?error=' . urlencode('Ошибка при удалении подарка: ' . $e->getMessage()));
    exit;
}
?>
