<?php
include 'db.php';
session_start();

// Проверка, что пользователь — администратор
if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] != 0) {
    header('Location: login.php');
    exit;
}

// Обработка фильтров
$cipherFilter = isset($_GET['cafedraCipher']) ? $_GET['cafedraCipher'] : '';
$facultetFilter = isset($_GET['facultet']) ? $_GET['facultet'] : '';

$whereClauses = array();
if ($cipherFilter) {
    $whereClauses[] = "CafedraCipher LIKE :cafedraCipher";
}
if ($facultetFilter) {
    $whereClauses[] = "FacultetID = :facultet";
}

$sql = '
    SELECT 
        CafedraID, 
        CafedraCipher, 
        Login, 
        Password, 
        FacultetID 
    FROM Cafedra';

if (count($whereClauses) > 0) {
    $sql .= ' WHERE ' . implode(' AND ', $whereClauses);
}

$stmt = $pdo->prepare($sql);

if ($cipherFilter) {
    $stmt->bindValue(':cafedraCipher', '%' . $cipherFilter . '%');
}
if ($facultetFilter) {
    $stmt->bindValue(':facultet', $facultetFilter, PDO::PARAM_INT);
}

$stmt->execute();
$cafedras = $stmt->fetchAll();

// Получение списка факультетов для фильтров
$facultetsStmt = $pdo->query('SELECT FacultetID, FacultetCipher FROM Facultet');
$facultets = $facultetsStmt->fetchAll();
?>
<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Управление кафедрами</title>
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
        <h1>Список кафедр</h1>

        <!-- Фильтры -->
        <form method="GET" action="cafedra.php">
            <label for="cafedraCipher">Шифр кафедры:</label>
            <input type="text" id="cafedraCipher" name="cafedraCipher" value="<?= htmlspecialchars($cipherFilter) ?>">

            <label for="facultet">Факультет:</label>
            <select id="facultet" name="facultet">
                <option value="">Все</option>
                <?php foreach ($facultets as $facultet): ?>
                    <option value="<?= htmlspecialchars($facultet['FacultetID']) ?>" <?= ($facultetFilter == $facultet['FacultetID']) ? 'selected' : '' ?>>
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
                <th>Шифр кафедры</th>
                <th>Логин</th>
                <th>Пароль</th>
                <th>ID Факультета</th>
                <th>Действия</th>
            </tr>
            <?php foreach ($cafedras as $cafedra): ?>
                <tr>
                    <td><?= htmlspecialchars($cafedra['CafedraID']) ?></td>
                    <td><?= htmlspecialchars($cafedra['CafedraCipher']) ?></td>
                    <td><?= htmlspecialchars($cafedra['Login']) ?></td>
                    <td><?= htmlspecialchars($cafedra['Password']) ?></td>
                    <td><?= htmlspecialchars($cafedra['FacultetID']) ?></td>
                    <td>
                        <a href="edit_cafedra.php?id=<?= $cafedra['CafedraID'] ?>">Изменить</a>
                        <a href="delete_cafedra.php?id=<?= $cafedra['CafedraID'] ?>" onclick="return confirm('Вы уверены?')">Удалить</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>

        <!-- Кнопка добавления -->
        <a href="add_cafedra.php" class="add-button">Добавить кафедру</a>
    </div>
</body>

</html>
