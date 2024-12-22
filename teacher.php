<?php
include 'db.php';
session_start();

// Проверка, что пользователь — администратор
if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] != 0) {
    header('Location: login.php');
    exit;
}

// Обработка фильтров
$cafedraFilter = isset($_GET['cafedra']) ? $_GET['cafedra'] : '';
$nameFilter = isset($_GET['name']) ? $_GET['name'] : '';

$whereClauses = array();
if ($cafedraFilter) {
    $whereClauses[] = "caf.CafedraCipher LIKE :cafedra";
}
if ($nameFilter) {
    $whereClauses[] = "CONCAT(t.LastName, ' ', t.Name, ' ', COALESCE(t.ThirdName, '')) LIKE :name";
}

$sql = '
    SELECT 
        t.TeacherID, 
        t.Name, 
        t.LastName, 
        t.ThirdName, 
        caf.CafedraCipher 
    FROM Teacher t
    JOIN Cafedra caf ON t.Cafedra = caf.CafedraID
';

if (count($whereClauses) > 0) {
    $sql .= ' WHERE ' . implode(' AND ', $whereClauses);
}

$stmt = $pdo->prepare($sql);

if ($cafedraFilter) {
    $stmt->bindValue(':cafedra', '%' . $cafedraFilter . '%');
}
if ($nameFilter) {
    $stmt->bindValue(':name', '%' . $nameFilter . '%');
}

$stmt->execute();
$teachers = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Управление преподавателями</title>
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
        <h1>Список преподавателей</h1>

        <!-- Фильтры -->
        <form method="GET" action="teacher.php">
            <label for="cafedra">Кафедра:</label>
            <input type="text" id="cafedra" name="cafedra" value="<?= htmlspecialchars($cafedraFilter) ?>">
            
            <label for="name">ФИО:</label>
            <input type="text" id="name" name="name" value="<?= htmlspecialchars($nameFilter) ?>">

            <button type="submit">Применить фильтры</button>
        </form>

        <!-- Таблица -->
        <table>
            <tr>
                <th>ID</th>
                <th>Фамилия</th>
                <th>Имя</th>
                <th>Отчество</th>
                <th>Кафедра</th>
                <th>Действия</th>
            </tr>
            <?php foreach ($teachers as $teacher): ?>
                <tr>
                    <td><?= htmlspecialchars($teacher['TeacherID']) ?></td>
                    <td><?= htmlspecialchars($teacher['LastName']) ?></td>
                    <td><?= htmlspecialchars($teacher['Name']) ?></td>
                    <td><?= htmlspecialchars($teacher['ThirdName']) ?></td>
                    <td><?= htmlspecialchars($teacher['CafedraCipher']) ?></td>
                    <td>
                        <a href="edit_teacher.php?id=<?= $teacher['TeacherID'] ?>">Изменить</a>
                        <a href="delete_teacher.php?id=<?= $teacher['TeacherID'] ?>" onclick="return confirm('Вы уверены?')">Удалить</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>

        <!-- Кнопка добавления -->
        <a href="add_teacher.php" class="add-button">Добавить преподавателя</a>
    </div>
</body>

</html>
