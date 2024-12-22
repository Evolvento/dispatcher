<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] != 0) {
    header('Location: login.php');
    exit;
}

$cipherFilter = isset($_GET['facultetCipher']) ? $_GET['facultetCipher'] : '';
$whereClauses = array();

if ($cipherFilter) {
    $whereClauses[] = "FacultetCipher LIKE :facultetCipher";
}

$sql = 'SELECT FacultetID, FacultetCipher FROM Facultet';

if (count($whereClauses) > 0) {
    $sql .= ' WHERE ' . implode(' AND ', $whereClauses);
}

$stmt = $pdo->prepare($sql);

if ($cipherFilter) {
    $stmt->bindValue(':facultetCipher', '%' . $cipherFilter . '%');
}

$stmt->execute();
$facultets = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Управление факультетами</title>
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
        <h1>Список факультетов</h1>

        <!-- Фильтры -->
        <form method="GET" action="facultet.php">
            <label for="facultetCipher">Шифр факультета:</label>
            <input type="text" id="facultetCipher" name="facultetCipher" value="<?= htmlspecialchars($cipherFilter) ?>">
            <button type="submit">Применить фильтры</button>
        </form>

        <!-- Таблица -->
        <table>
            <tr>
                <th>ID</th>
                <th>Шифр факультета</th>
                <th>Действия</th>
            </tr>
            <?php foreach ($facultets as $facultet): ?>
                <tr>
                    <td><?= htmlspecialchars($facultet['FacultetID']) ?></td>
                    <td><?= htmlspecialchars($facultet['FacultetCipher']) ?></td>
                    <td>
                        <a href="edit_facultet.php?id=<?= $facultet['FacultetID'] ?>">Изменить</a>
                        <a href="delete_facultet.php?id=<?= $facultet['FacultetID'] ?>" onclick="return confirm('Вы уверены?')">Удалить</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>

        <!-- Кнопка добавления -->
        <a href="add_facultet.php" class="add-button">Добавить факультет</a>
    </div>
</body>

</html>