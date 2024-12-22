<?php
include 'db.php';
session_start();

// Проверка, что пользователь — администратор
if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] != 0) {
    header('Location: login.php');
    exit;
}

// Обработка фильтров
$cipherFilter = isset($_GET['specialityCipher']) ? $_GET['specialityCipher'] : '';
$facultetFilter = isset($_GET['facultetID']) ? $_GET['facultetID'] : '';

$whereClauses = array();
if ($cipherFilter) {
    $whereClauses[] = "SpecialityCipher LIKE :specialityCipher";
}
if ($facultetFilter) {
    $whereClauses[] = "Facultet = :facultetID";
}

$sql = '
    SELECT 
        s.SpecialityID,
        s.SpecialityCipher,
        f.FacultetCipher
    FROM Speciality s
    JOIN Facultet f ON s.Facultet = f.FacultetID';

if (count($whereClauses) > 0) {
    $sql .= ' WHERE ' . implode(' AND ', $whereClauses);
}

$stmt = $pdo->prepare($sql);

if ($cipherFilter) {
    $stmt->bindValue(':specialityCipher', '%' . $cipherFilter . '%');
}
if ($facultetFilter) {
    $stmt->bindValue(':facultetID', $facultetFilter, PDO::PARAM_INT);
}

$stmt->execute();
$specialities = $stmt->fetchAll();

// Для фильтрации по факультетам
$facultetStmt = $pdo->query('SELECT FacultetID, FacultetCipher FROM Facultet');
$facultets = $facultetStmt->fetchAll();
?>
<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Управление специальностями</title>
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
        <h1>Список специальностей</h1>

        <!-- Фильтры -->
        <form method="GET" action="speciality.php">
            <label for="specialityCipher">Шифр специальности:</label>
            <input type="text" id="specialityCipher" name="specialityCipher" value="<?= htmlspecialchars($cipherFilter) ?>">

            <label for="facultetID">Факультет:</label>
            <select id="facultetID" name="facultetID">
                <option value="">Все</option>
                <?php foreach ($facultets as $facultet): ?>
                    <option value="<?= $facultet['FacultetID'] ?>" <?= ($facultetFilter == $facultet['FacultetID']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($facultet['FacultetCipher']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <button type="submit">Применить фильтры</button>
        </form>

        <!-- Таблица -->
        <table>
            <tr>
                <th>ID</th>
                <th>Шифр специальности</th>
                <th>Факультет</th>
                <th>Действия</th>
            </tr>
            <?php foreach ($specialities as $speciality): ?>
                <tr>
                    <td><?= htmlspecialchars($speciality['SpecialityID']) ?></td>
                    <td><?= htmlspecialchars($speciality['SpecialityCipher']) ?></td>
                    <td><?= htmlspecialchars($speciality['FacultetCipher']) ?></td>
                    <td>
                        <a href="edit_speciality.php?id=<?= $speciality['SpecialityID'] ?>">Изменить</a>
                        <a href="delete_speciality.php?id=<?= $speciality['SpecialityID'] ?>" onclick="return confirm('Вы уверены?')">Удалить</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>

        <!-- Кнопка добавления -->
        <a href="add_speciality.php" class="add-button">Добавить специальность</a>
    </div>
</body>

</html>
