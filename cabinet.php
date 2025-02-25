<?php
include 'db.php';
session_start();

// Проверка, что пользователь — администратор
if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] != 0) {
    header('Location: login.php');
    exit;
}

// Обработка фильтров
$corpusFilter = isset($_GET['corpus']) ? $_GET['corpus'] : '';
$purposeFilter = isset($_GET['purpose']) ? $_GET['purpose'] : '';

$whereClauses = array();
if ($corpusFilter) {
    $whereClauses[] = "Corpus LIKE :corpus";
}
if ($purposeFilter) {
    $whereClauses[] = "Purpose = :purpose";
}

$sql = '
    SELECT 
        CabinetID, 
        CabinetNumber, 
        Corpus, 
        Purpose, 
        Capacity 
    FROM Cabinet';

if (count($whereClauses) > 0) {
    $sql .= ' WHERE ' . implode(' AND ', $whereClauses);
}

$stmt = $pdo->prepare($sql);

if ($corpusFilter) {
    $stmt->bindValue(':corpus', '%' . $corpusFilter . '%');
}
if ($purposeFilter) {
    $stmt->bindValue(':purpose', $purposeFilter);
}

$stmt->execute();
$cabinets = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Управление аудиториями</title>
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
        <h1>Список аудиторий</h1>

        <!-- Фильтры -->
        <form method="GET" action="cabinet.php">
            <label for="corpus">Корпус:</label>
            <input type="text" id="corpus" name="corpus" value="<?= htmlspecialchars($corpusFilter) ?>">

            <label for="purpose">Назначение:</label>
            <select id="purpose" name="purpose">
                <option value="">Все</option>
                <option value="Лабораторная работа" <?= ($purposeFilter == 'Лабораторная работа') ? 'selected' : '' ?>>Лабораторная работа</option>
                <option value="Лекция" <?= ($purposeFilter == 'Лекция') ? 'selected' : '' ?>>Лекция</option>
                <option value="Семинар" <?= ($purposeFilter == 'Семинар') ? 'selected' : '' ?>>Семинар</option>
            </select>

            <button type="submit">Применить фильтры</button>
        </form>

        <!-- Таблица -->
        <table>
            <tr>
                <th>ID</th>
                <th>Номер аудитории</th>
                <th>Корпус</th>
                <th>Назначение</th>
                <th>Вместимость</th>
                <th>Действия</th>
            </tr>
            <?php foreach ($cabinets as $cabinet): ?>
                <tr>
                    <td><?= htmlspecialchars($cabinet['CabinetID']) ?></td>
                    <td><?= htmlspecialchars($cabinet['CabinetNumber']) ?></td>
                    <td><?= htmlspecialchars($cabinet['Corpus']) ?></td>
                    <td><?= htmlspecialchars($cabinet['Purpose']) ?></td>
                    <td><?= htmlspecialchars($cabinet['Capacity']) ?></td>
                    <td>
                        <a href="edit_cabinet.php?id=<?= $cabinet['CabinetID'] ?>">Изменить</a>
                        <a href="delete_cabinet.php?id=<?= $cabinet['CabinetID'] ?>" onclick="return confirm('Вы уверены?')">Удалить</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>

        <!-- Кнопка добавления -->
        <a href="add_cabinet.php" class="add-button">Добавить аудиторию</a>
    </div>
</body>

</html>
