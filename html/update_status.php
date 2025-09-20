<?php
require_once '../dbConf/config.php';

$id = $_GET['id'] ?? null;
$status = $_GET['status'] ?? null;

if (!$id || !is_numeric($id) || !in_array($status, ['не куплен', 'куплен'])) {
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
    
    $stmt = $pdo->prepare("UPDATE gifts SET status = ? WHERE id = ?");
    $stmt->execute([$status, $id]);
    
    $message = $status === 'куплен' ? 'Подарок отмечен как купленный!' : 'Подарок отмечен как не купленный!';
    header('Location: index.php?status_updated=1&message=' . urlencode($message));
    exit;
    
} catch(PDOException $e) {
    header('Location: index.php?error=' . urlencode('Ошибка при обновлении статуса: ' . $e->getMessage()));
    exit;
}
?>
