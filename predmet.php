<?php
include 'db.php';
session_start();

// Проверка, что пользователь — администратор
if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] != 0) {
    header('Location: login.php');
    exit;
}

// Обработка фильтров
$nameFilter = isset($_GET['predmetName']) ? $_GET['predmetName'] : '';

$whereClauses = array();
if ($nameFilter) {
    $whereClauses[] = "PredmetName LIKE :predmetName";
}

$sql = 'SELECT PredmetID, PredmetName FROM Predmet';

if (count($whereClauses) > 0) {
    $sql .= ' WHERE ' . implode(' AND ', $whereClauses);
}

$stmt = $pdo->prepare($sql);

if ($nameFilter) {
    $stmt->bindValue(':predmetName', '%' . $nameFilter . '%');
}

$stmt->execute();
$predmets = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Управление предметами</title>
    <link rel="stylesheet" href="styles_table.css">
</head>

<body>
    <!-- Toolbar Section -->
    <div class="toolbar">
        <h2>Навигация</h2>
        <a href="admin.php">Расписание</a>
        <a href="facultet.php">Факультеты</a>
        <a href="speciality.php">Специальности</a>
        <a href="cabinet.php">Аудитории</a>
        <a href="cafedra.php">Кафедры</a>
        <a href="predmet.php">Предметы</a>
        <a href="teacher.php">Преподаватели</a>
        <a href="study_group.php">Учебные группы</a>
        <a href="study_plane.php">Учебные планы</a>
    </div>

    <!-- Content Section -->
    <div class="content">
        <h1>Список предметов</h1>

        <!-- Фильтры -->
        <form method="GET" action="predmet.php">
            <label for="predmetName">Название предмета:</label>
            <input type="text" id="predmetName" name="predmetName" value="<?= htmlspecialchars($nameFilter) ?>">
            <button type="submit">Применить фильтры</button>
        </form>

        <!-- Таблица -->
        <table>
            <tr>
                <th>ID</th>
                <th>Название предмета</th>
                <th>Действия</th>
            </tr>
            <?php foreach ($predmets as $predmet): ?>
                <tr>
                    <td><?= htmlspecialchars($predmet['PredmetID']) ?></td>
                    <td><?= htmlspecialchars($predmet['PredmetName']) ?></td>
                    <td>
                        <a href="edit_predmet.php?id=<?= $predmet['PredmetID'] ?>">Изменить</a>
                        <a href="delete_predmet.php?id=<?= $predmet['PredmetID'] ?>" onclick="return confirm('Вы уверены?')">Удалить</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>

        <!-- Кнопка добавления -->
        <a href="add_predmet.php" class="add-button">Добавить предмет</a>
    </div>
</body>

</html>
