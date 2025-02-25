<?php 
include 'db.php'; 
session_start();
$_SESSION['user_id'] = 100;
?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="stylesheet" href="styles_index.css">
    <title>Главная страница</title>
</head>
<body>
    <h1>Добро пожаловать в систему "Диспетчер"</h1>
    <a href="login.php">Вход</a>
    <a href="schedule.php">Просмотреть расписание</a>
</body>
</html>