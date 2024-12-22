<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] != 0) {
    header('Location: login.php');
    exit;
}

$stmt = $pdo->query('
    SELECT 
        s.ScheduleID,
        s.DayOfWeek,
        s.LessonOrder,
        caf.CafedraCipher AS cafedra_cipher,
        CONCAT(t.LastName, \' \', 
               COALESCE(SUBSTRING(t.Name FROM 1 FOR 1), \'\'), \'.\', 
               COALESCE(SUBSTRING(t.ThirdName FROM 1 FOR 1), \'\')) AS teacher_name,
        CONCAT(cab.Corpus, \'-\', cab.CabinetNumber) AS cabinet,
        sg.Cipher AS study_group,
        CONCAT(p.PredmetName, \' (\', sp.LessonType, \')\') AS predmet
    FROM schedule s
    JOIN Cafedra caf ON s.Cafedra = caf.CafedraID
    JOIN Teacher t ON s.Teacher = t.TeacherID
    JOIN Cabinet cab ON s.Cabinet = cab.CabinetID
    JOIN StudyGroup sg ON s.StudyGroup = sg.GroupID
    JOIN StudyPlane sp ON s.Plane = sp.PlaneID
    JOIN Predmet p ON sp.Predmet = p.PredmetID
');
$schedules = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Управление расписанием</title>
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
        <h1>Расписание занятий</h1>
        <table>
            <tr>
                <th>ID</th>
                <th>День недели</th>
                <th>Порядок пары</th>
                <th>Кафедра</th>
                <th>Преподаватель</th>
                <th>Аудитория</th>
                <th>Учебная группа</th>
                <th>Учебный план</th>
                <th>Действия</th>
            </tr>
            <?php foreach ($schedules as $schedule): ?>
                <tr>
                    <td><?= $schedule['ScheduleID'] ?></td>
                    <td><?= $schedule['DayOfWeek'] ?></td>
                    <td><?= $schedule['LessonOrder'] ?></td>
                    <td><?= $schedule['cafedra_cipher'] ?></td>
                    <td><?= $schedule['teacher_name'] ?></td>
                    <td><?= $schedule['cabinet'] ?></td>
                    <td><?= $schedule['study_group'] ?></td>
                    <td><?= $schedule['predmet'] ?></td>
                    <td>
                        <a href="edit_facultet.php?id=<?= $facultet['FacultetID'] ?>">Изменить</a>
                        <a href="delete_facultet.php?id=<?= $facultet['FacultetID'] ?>" onclick="return confirm('Вы уверены?')">Удалить</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
        <a href="add_schedule.php" class="add-button">Добавить расписание</a>
    </div>
</body>

</html>