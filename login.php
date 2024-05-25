<?php
global $conn;
include 'database.php';
try {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $login = $_POST["login"];
        $password = $_POST["password"];
        // Поиск пользователя в базе данных
        $sql = "SELECT * FROM users WHERE login = '$login'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $hashed_password = $row['password'];
            if (password_verify($password, $hashed_password)) {
                // Пользователь найден, пароль верен
                $session_id = md5(uniqid(rand(), true));
                $cookie_expiration = isset($_POST['rememberme']) ? 86400 * 30 : 86400;
                setcookie("login", $login, time() + $cookie_expiration, "/");
                setcookie("ID", $row['ID'], time() + $cookie_expiration, "/"); // Сохраняем ID в куки
                setcookie("SessionID", $session_id, time() + $cookie_expiration, "/");
                // Сохранение SessionID в базе данных
                $sql_update_session = "UPDATE users SET session_id = '$session_id' WHERE login = '$login'";
                $conn->query($sql_update_session);
                date_default_timezone_set('Asia/Yekaterinburg'); // Установка часового пояса
                $last_login_time = date('Y-m-d H:i:s'); // Получаем текущее время с учетом установленного часового пояса
                // Обновление времени последней авторизации пользователя в базе данных
                $sql_update_last_login = "UPDATE users SET last_login_time = '$last_login_time' WHERE login = '$login'";
                $conn->query($sql_update_last_login);
                // Перенаправление на страницу авторизованного пользователя
                echo "success"; // Возвращаем строку, указывающую успешную аутентификацию
                exit();
            } else {
                // Пользователь найден, но пароль неверен
                echo "Неверный логин или пароль";
                exit();
            }            
        } else {
            // Пользователь не найден
            echo "Пользователь не найден";
        }
    }
} finally {
    if (isset($conn)) {
        $conn->close();
    }
}
?>
