<?php
require_once '../dbConf/config.php';

$message = '';
$message_type = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $for_whom = trim($_POST['for_whom'] ?? '');
    $budget = floatval($_POST['budget'] ?? 0);

    $errors = [];

    if (empty($title)) {
        $errors[] = 'Название подарка обязательно для заполнения';
    }

    if (empty($for_whom)) {
        $errors[] = 'Поле "Для кого" обязательно для заполнения';
    }

    if ($budget < 0) {
        $errors[] = 'Бюджет не может быть отрицательным';
    }

    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO gifts (title, description, for_whom, budget) VALUES (?, ?, ?, ?)");
            $stmt->execute([$title, $description, $for_whom, $budget]);

            $message = 'Подарок успешно добавлен!';
            $message_type = 'success';

            $title = $description = $for_whom = '';
            $budget = 0;
        } catch(PDOException $e) {
            $message = 'Ошибка при добавлении подарка: ' . $e->getMessage();
            $message_type = 'danger';
        }
    } else {
        $message = implode('<br>', $errors);
        $message_type = 'danger';
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Добавить подарок</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../src/css/style.css">
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-light bg-purple-custom fixed-top shadow-sm">
        <div class="container-fluid">
            <a class="navbar-brand" href="add.php">
                <i class="fas fa-plus-circle me-2"></i>ДОБАВИТЬ ПОДАРОК
            </a>
            <div class="d-flex align-items-center me-auto ms-3">
                <span class="navbar-text text-muted d-none d-md-block">
                    Сегодня: <?= date('d.m.Y') ?>
                </span>
            </div>
            <a href="index.php" class="btn btn-yellow-custom ms-auto">
                <i class="fas fa-arrow-left me-2"></i>Назад к списку
            </a>
        </div>
    </nav>
    
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="form-container">
                    <?php if ($message): ?>
                        <div class="alert alert-<?= $message_type ?> alert-dismissible fade show" role="alert">
                            <i class="fas fa-<?= $message_type === 'success' ? 'check-circle' : 'exclamation-triangle' ?> me-2"></i>
                            <?= $message ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <div class="card shadow">
                        <div class="card-body">
                            <form method="POST" action="">
                                <div class="mb-3">
                                    <label for="title" class="form-label">
                                        Название подарка <span class="required">*</span>
                                    </label>
                                    <input type="text"
                                           class="form-control"
                                           id="title"
                                           name="title"
                                           value="<?= htmlspecialchars($title ?? '') ?>"
                                           placeholder="Введите название подарка"
                                           required>
                                </div>

                                <div class="mb-3">
                                    <label for="for_whom" class="form-label">
                                        Для кого <span class="required">*</span>
                                    </label>
                                    <input type="text"
                                           class="form-control"
                                           id="for_whom"
                                           name="for_whom"
                                           value="<?= htmlspecialchars($for_whom ?? '') ?>"
                                           placeholder="Например: брат, друг, коллега"
                                           required>
                                </div>

                                <div class="mb-3">
                                    <label for="budget" class="form-label">
                                        Бюджет (BYN)
                                    </label>
                                    <div class="input-group">
                                        <input type="number"
                                               class="form-control"
                                               id="budget"
                                               name="budget"
                                               value="<?= $budget ?? 0 ?>"
                                               min="0"
                                               step="0.01"
                                               placeholder="0.00">
                                        <span class="input-group-text">BYN</span>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label">
                                        Описание
                                    </label>
                                    <textarea class="form-control"
                                              id="description"
                                              name="description"
                                              rows="4"
                                              placeholder="Дополнительная информация о подарке (необязательно)"><?= htmlspecialchars($description ?? '') ?></textarea>
                                </div>

                                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                    <a href="index.php" class="btn btn-secondary me-md-2">
                                        <i class="fas fa-times me-2"></i>Отмена
                                    </a>
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-save me-2"></i>Добавить подарок
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>