<?php
include 'db.php';
session_start();
#password_verify($password, $user['password'])
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    if ($username == 'admin' && $password == 'admin') {
        $_SESSION['user_id'] = 0;
        header('Location: admin.php');
    } else {
        $stmt = $pdo->prepare('SELECT * FROM Cafedra WHERE Login = :username');
        $stmt->execute(array('username' => $username));
        $user = $stmt->fetch();
        if ($user) {
            if ($password == $user['Password']) {
                $_SESSION['user_id'] = $user['id'];
                header('Location: schedule.php');
            } else {
                $error = 'Неверный пароль';
            }
        } else {
            $error = 'Неверный логин';
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="stylesheet" href="styles_index.css">
    <title>Авторизация</title>
</head>
<body>
    <h1>Вход</h1>
    <?php if (!empty($error)) echo "<p>$error</p>"; ?>
    <form method="post">
        <label>Логин: <input type="text" name="username"></label><br>
        <label>Пароль: <input type="password" name="password"></label><br>
        <button type="submit">Войти</button>
    </form>
</body>
</html>