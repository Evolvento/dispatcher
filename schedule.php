<?php
include 'db.php';
session_start();

// Проверка, что пользователь авторизован
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Обработка выбора группы или преподавателя
$selectedGroup = isset($_GET['group']) ? $_GET['group'] : '';
$selectedTeacher = isset($_GET['teacher']) ? $_GET['teacher'] : '';

// Получение списка групп и преподавателей для выпадающих списков
$groupsQuery = 'SELECT GroupID, Cipher FROM StudyGroup ORDER BY Cipher';
$groupsStmt = $pdo->query($groupsQuery);
$groups = $groupsStmt->fetchAll();

$teachersQuery = 'SELECT TeacherID, CONCAT(LastName, " ", Name, " ", ThirdName) AS FullName FROM Teacher ORDER BY LastName';
$teachersStmt = $pdo->query($teachersQuery);
$teachers = $teachersStmt->fetchAll();

// Запрос расписания
$schedule = array();
if ($selectedGroup || $selectedTeacher) {
    $whereClause = '';
    if ($selectedGroup) {
        $whereClause = 's.StudyGroup = :group';
    } elseif ($selectedTeacher) {
        $whereClause = 's.Teacher = :teacher';
    }

    $scheduleQuery = "
        SELECT 
            s.DayOfWeek, 
            s.LessonOrder, 
            p.PredmetName, 
            CONCAT(t.LastName, ' ', t.Name, ' ', t.ThirdName) AS TeacherName, 
            CONCAT(c.Corpus, '-', c.CabinetNumber) AS Cabinet
        FROM Schedule s
        JOIN StudyPlane sp ON s.Plane = sp.PlaneID
        JOIN Predmet p ON sp.Predmet = p.PredmetID
        JOIN Teacher t ON s.Teacher = t.TeacherID
        JOIN Cabinet c ON s.Cabinet = c.CabinetID
        WHERE $whereClause
        ORDER BY FIELD(s.DayOfWeek, 'Понедельник', 'Вторник', 'Среда', 'Четверг', 'Пятница', 'Суббота'), s.LessonOrder
    ";
    $stmt = $pdo->prepare($scheduleQuery);
    if ($selectedGroup) {
        $stmt->bindValue(':group', $selectedGroup, PDO::PARAM_INT);
    } elseif ($selectedTeacher) {
        $stmt->bindValue(':teacher', $selectedTeacher, PDO::PARAM_INT);
    }
    $stmt->execute();
    $schedule = $stmt->fetchAll();
}

// Функция для группировки расписания по дням недели
function groupScheduleByDay($schedule) {
    $days = array('Понедельник', 'Вторник', 'Среда', 'Четверг', 'Пятница', 'Суббота');
    $grouped = array_fill_keys($days, array());
    foreach ($schedule as $lesson) {
        $grouped[$lesson['DayOfWeek']][] = $lesson;
    }
    return $grouped;
}

$groupedSchedule = groupScheduleByDay($schedule);
?>
<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Расписание</title>
    <link rel="stylesheet" href="styles_schedule.css">
</head>

<body>
    <div class="content">
        <h1>Расписание</h1>

        <!-- Форма выбора группы или преподавателя -->
        <form method="GET" action="schedule.php">
            <label for="group">Учебная группа:</label>
            <select id="group" name="group">
                <option value="">-- Выберите группу --</option>
                <?php foreach ($groups as $group): ?>
                    <option value="<?= $group['GroupID'] ?>" <?= $selectedGroup == $group['GroupID'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($group['Cipher']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="teacher">Преподаватель:</label>
            <select id="teacher" name="teacher">
                <option value="">-- Выберите преподавателя --</option>
                <?php foreach ($teachers as $teacher): ?>
                    <option value="<?= $teacher['TeacherID'] ?>" <?= $selectedTeacher == $teacher['TeacherID'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($teacher['FullName']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <button type="submit">Показать расписание</button>
        </form>

        <!-- Расписание -->
        <div class="schedule-container">
            <?php foreach ($groupedSchedule as $day => $lessons): ?>
                <div class="day-block">
                    <h2><?= htmlspecialchars($day) ?></h2>
                    <?php if (count($lessons) > 0): ?>
                        <ul>
                            <?php foreach ($lessons as $lesson): ?>
                                <li>
                                    <strong>Пара <?= htmlspecialchars($lesson['LessonOrder']) ?>:</strong> 
                                    <?= htmlspecialchars($lesson['PredmetName']) ?>, 
                                    <?= htmlspecialchars($lesson['Cabinet']) ?>, 
                                    <?= htmlspecialchars($lesson['TeacherName']) ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p>Нет занятий</p>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>

</html>
