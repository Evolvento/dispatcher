<?php
include 'db.php';
session_start();

// Проверка, что пользователь — администратор
if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] != 0) {
    header('Location: login.php');
    exit;
}

// Обработка фильтров
$specialityFilter = isset($_GET['speciality']) ? $_GET['speciality'] : '';
$predmetFilter = isset($_GET['predmet']) ? $_GET['predmet'] : '';

$whereClauses = array();
if ($specialityFilter) {
    $whereClauses[] = "s.SpecialityCipher LIKE :speciality";
}
if ($predmetFilter) {
    $whereClauses[] = "p.PredmetName LIKE :predmet";
}

$sql = '
    SELECT 
        sp.PlaneID, 
        s.SpecialityCipher, 
        p.PredmetName, 
        sp.LessonType, 
        sp.Hours 
    FROM StudyPlane sp
    JOIN Speciality s ON sp.Speciality = s.SpecialityID
    JOIN Predmet p ON sp.Predmet = p.PredmetID
';

if (count($whereClauses) > 0) {
    $sql .= ' WHERE ' . implode(' AND ', $whereClauses);
}

$stmt = $pdo->prepare($sql);

if ($specialityFilter) {
    $stmt->bindValue(':speciality', '%' . $specialityFilter . '%');
}
if ($predmetFilter) {
    $stmt->bindValue(':predmet', '%' . $predmetFilter . '%');
}

$stmt->execute();
$studyPlanes = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Учебные планы</title>
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
        <h1>Учебные планы</h1>

        <form method="GET" action="study_plane.php">
            <label for="speciality">Шифр специальности:</label>
            <input type="text" id="speciality" name="speciality" value="<?= htmlspecialchars($specialityFilter) ?>">

            <label for="predmet">Название предмета:</label>
            <input type="text" id="predmet" name="predmet" value="<?= htmlspecialchars($predmetFilter) ?>">

            <button type="submit">Применить фильтры</button>
        </form>

        <table>
            <tr>
                <th>ID</th>
                <th>Шифр специальности</th>
                <th>Название предмета</th>
                <th>Тип занятия</th>
                <th>Часы</th>
                <th>Действия</th>
            </tr>
            <?php foreach ($studyPlanes as $plane): ?>
                <tr>
                    <td><?= htmlspecialchars($plane['PlaneID']) ?></td>
                    <td><?= htmlspecialchars($plane['SpecialityCipher']) ?></td>
                    <td><?= htmlspecialchars($plane['PredmetName']) ?></td>
                    <td><?= htmlspecialchars($plane['LessonType']) ?></td>
                    <td><?= htmlspecialchars($plane['Hours']) ?></td>
                    <td>
                        <a href="edit_study_plane.php?id=<?= $plane['PlaneID'] ?>">Изменить</a>
                        <a href="delete_study_plane.php?id=<?= $plane['PlaneID'] ?>" onclick="return confirm('Вы уверены?')">Удалить</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>

        <a href="add_study_plane.php" class="add-button">Добавить учебный план</a>
    </div>
</body>

</html>
