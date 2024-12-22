<?php
include 'db.php';
session_start();

// Проверка, что пользователь — администратор
if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] != 0) {
    header('Location: login.php');
    exit;
}

// Обработка фильтров
$cipherFilter = isset($_GET['cipher']) ? $_GET['cipher'] : '';
$specialityFilter = isset($_GET['speciality']) ? $_GET['speciality'] : '';

$whereClauses = array();
if ($cipherFilter) {
    $whereClauses[] = "g.Cipher LIKE :cipher";
}
if ($specialityFilter) {
    $whereClauses[] = "s.SpecialityCipher LIKE :speciality";
}

$sql = '
    SELECT 
        g.GroupID, 
        g.Cipher, 
        g.GroupSize, 
        s.SpecialityCipher 
    FROM StudyGroup g
    JOIN Speciality s ON g.Speciality = s.SpecialityID
';

if (count($whereClauses) > 0) {
    $sql .= ' WHERE ' . implode(' AND ', $whereClauses);
}

$stmt = $pdo->prepare($sql);

if ($cipherFilter) {
    $stmt->bindValue(':cipher', '%' . $cipherFilter . '%');
}
if ($specialityFilter) {
    $stmt->bindValue(':speciality', '%' . $specialityFilter . '%');
}

$stmt->execute();
$groups = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Учебные группы</title>
    <link rel="stylesheet" href="styles_table.css">
</head>

<body>
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

    <div class="content">
        <h1>Учебные группы</h1>

        <form method="GET" action="study_group.php">
            <label for="cipher">Шифр группы:</label>
            <input type="text" id="cipher" name="cipher" value="<?= htmlspecialchars($cipherFilter) ?>">

            <label for="speciality">Шифр специальности:</label>
            <input type="text" id="speciality" name="speciality" value="<?= htmlspecialchars($specialityFilter) ?>">

            <button type="submit">Применить фильтры</button>
        </form>

        <table>
            <tr>
                <th>ID</th>
                <th>Шифр группы</th>
                <th>Шифр специальности</th>
                <th>Размер группы</th>
                <th>Действия</th>
            </tr>
            <?php foreach ($groups as $group): ?>
                <tr>
                    <td><?= htmlspecialchars($group['GroupID']) ?></td>
                    <td><?= htmlspecialchars($group['Cipher']) ?></td>
                    <td><?= htmlspecialchars($group['SpecialityCipher']) ?></td>
                    <td><?= htmlspecialchars($group['GroupSize']) ?></td>
                    <td>
                        <a href="edit_study_group.php?id=<?= $group['GroupID'] ?>">Изменить</a>
                        <a href="delete_study_group.php?id=<?= $group['GroupID'] ?>" onclick="return confirm('Вы уверены?')">Удалить</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>

        <a href="add_study_group.php" class="add-button">Добавить группу</a>
    </div>
</body>

</html>
