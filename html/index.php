<?php
require_once '../dbConf/config.php';

$success_message = '';
if (isset($_GET['deleted']) && $_GET['deleted'] == '1') {
    $success_message = 'Подарок успешно удален!';
} elseif (isset($_GET['status_updated']) && $_GET['status_updated'] == '1' && isset($_GET['message'])) {
    $success_message = htmlspecialchars($_GET['message']);
}

$error = '';
if (isset($_GET['error'])) {
    $error = htmlspecialchars($_GET['error']);
}

try {
    $stmt = $pdo->query("SELECT * FROM gifts ORDER BY created_at DESC");
    $gifts = $stmt->fetchAll();
} catch(PDOException $e) {
    $error = "Ошибка при загрузке подарков: " . $e->getMessage();
    $gifts = [];
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Список подарков</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../src/css/style.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-purple-custom fixed-top shadow-sm">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-gift me-2"></i>СПИСОК ПОДАРКОВ
            </a>
            <div class="d-flex align-items-center me-auto ms-3">
                <span class="navbar-text text-muted d-none d-md-block">
                    Сегодня: <?= date('d.m.Y') ?>
                </span>
            </div>
            <a href="add.php" class="btn btn-yellow-custom ms-auto">
                <i class="fas fa-plus me-2"></i>Добавить подарок
            </a>
        </div>
    </nav>
    <div class="container mt-4">
        <div class="row mb-5">
            <div class="col-12 text-center">
                <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-indicators">
                        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
                        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
                    </div>
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <img class="d-block w-100" src="https://placehold.co/800x200/FFD700/white?text=Уже" alt="First slide">
                        </div>
                        <div class="carousel-item">
                            <img class="d-block w-100" src="https://placehold.co/800x200/96c561/white?text=купил" alt="Second slide">
                        </div>
                        <div class="carousel-item">
                            <img class="d-block w-100" src="https://placehold.co/800x200/775494/white?text=подарок?" alt="Third slide">
                        </div>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <?php if ($success_message): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i><?= $success_message ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if ($error): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i><?= $error ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if (empty($gifts)): ?>
                    <div class="text-center py-5">
                        <i class="fas fa-gift fa-5x text-muted mb-3"></i>
                        <h3 class="text-muted">Пока нет подарков</h3>
                        <p class="text-muted">Добавьте первый подарок, нажав на кнопку выше</p>
                    </div>
                <?php else: ?>
                    <div class="row">
                        <?php foreach ($gifts as $gift): ?>
                            <div class="col-md-6 col-lg-4 mb-4">
                                <div class="card gift-card h-100 shadow-sm">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <span class="badge status-badge <?= $gift['status'] === 'куплен' ? 'bg-success' : 'bg-warning' ?>">
                                            <?= htmlspecialchars($gift['status']) ?>
                                        </span>
                                        <small class="text-muted">
                                            <i class="fas fa-calendar me-1"></i>
                                            <?= date('d.m.Y', strtotime($gift['created_at'])) ?>
                                        </small>
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title"><?= htmlspecialchars($gift['title']) ?></h5>
                                        <p class="card-text text-muted">
                                            <i class="fas fa-user me-1"></i>
                                            <span class="for-whom-text"><?= htmlspecialchars($gift['for_whom']) ?></span>
                                        </p>
                                        <?php if (!empty($gift['description'])): ?>
                                            <p class="card-text"><?= htmlspecialchars($gift['description']) ?></p>
                                        <?php endif; ?>
                                        <p class="card-text">
                                            <i class="fas fa-money-bill-wave me-1"></i>
                                            <span class="budget-text"><?= number_format($gift['budget'], 0, ',', ' ') ?> BYN</span>
                                        </p>
                                    </div>
                                    <div class="card-footer bg-transparent d-flex flex-wrap">
                                        <a href="edit.php?id=<?= $gift['id'] ?>" class="btn btn-outline-primary btn-sm me-1 mb-1 flex-grow-1">
                                            <i class="fas fa-edit me-1"></i>Редактировать
                                        </a>
                                        <?php if ($gift['status'] === 'не куплен'): ?>
                                            <a href="update_status.php?id=<?= $gift['id'] ?>&status=куплен"
                                               class="btn btn-outline-success btn-sm me-1 mb-1 flex-grow-1 confirmation-link"
                                               data-message="Отметить как купленный?">
                                                <i class="fas fa-check me-1"></i>Куплен
                                            </a>
                                        <?php else: ?>
                                            <a href="update_status.php?id=<?= $gift['id'] ?>&status=не куплен"
                                               class="btn btn-outline-warning btn-sm me-1 mb-1 flex-grow-1 confirmation-link"
                                               data-message="Отметить как не купленный?">
                                                <i class="fas fa-undo me-1"></i>Не куплен
                                            </a>
                                        <?php endif; ?>
                                        <a href="delete.php?id=<?= $gift['id'] ?>"
                                           class="btn btn-outline-danger btn-sm mb-1 flex-grow-1 confirmation-link"
                                           data-message="Удалить этот подарок?">
                                            <i class="fas fa-trash me-1"></i>Удалить
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmationModalLabel">Подтверждение</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p id="confirmationMessage"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                    <a href="#" class="btn btn-danger" id="confirmAction">Подтвердить</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const modal = document.getElementById('confirmationModal');
            const messageElement = document.getElementById('confirmationMessage');
            const confirmButton = document.getElementById('confirmAction');

            document.querySelectorAll('.confirmation-link').forEach(link => {
                link.addEventListener('click', (e) => {
                    e.preventDefault();
                    const url = e.currentTarget.href;
                    const message = e.currentTarget.dataset.message;

                    messageElement.textContent = message;
                    confirmButton.href = url;

                    const modalInstance = new bootstrap.Modal(modal);
                    modalInstance.show();
                });
            });
        });
    </script>
</body>
</html>