<?php
session_start();

// Проверка ключа в URL
if (!isset($_GET['admin_key']) || strlen($_GET['admin_key']) < 256 || $_GET['admin_key'] !== 'ed2f1a7b93c4d8e7a6b1d5f2c8e3d4b9a7c8e2d3b4a9c6d5f2c8e3d4b9a7c8e2d3b4a9c6d5f2c8e3d4b9a7c8e2d3b4a9c6d5f2c8e3d4b9a7c8e2d3b4a9c6d5f2c8e3d4b9a7c8e2d3b4a9c6d5f2c8e3d4b9a7c8e2d3b484359345934958349583498593485934853948593485934859gjdfgd;kfjgd;fkjg34534645645645644') {
    echo "Доступ запрещен!";
    exit();
}
// Функция для генерации пароля, удовлетворяющего требованиям
function generatePassword($length = 10) {
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZабвгдеёжзийклмнопрстуфхцчшщъыьэюя0123456789!@#$%^&*()-_+=';
    $password = '';
    $charsLength = strlen($chars) - 1;

    for ($i = 0; $i < $length; $i++) {
        $password .= $chars[rand(0, $charsLength)];
    }

    return $password;
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "my_database";


try {
    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    // Запрос к базе данных
    $sql = "SELECT ID, first_name, last_name, password, middle_name, email, creation_time, last_login_time, login FROM users";
    $result = $conn->query($sql);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $login = $_POST["login"];
        $password = $_POST["password"];
        $confirm_password = $_POST["confirm_password"];

        // Проверка пароля и его подтверждения
        if ($password != $confirm_password) {
            $_SESSION['password_message'] = "Пароли не совпадают";
        } else {
            // Проверка наличия пользователя с таким логином
            $check_user_sql = "SELECT * FROM Users WHERE login = '$login'";
            $result = $conn->query($check_user_sql);

            if ($result->num_rows > 0) {
                // Генерация нового пароля
                $new_password = generatePassword(8);

                // Хеширование нового пароля
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

                // Обновление пароля пользователя в базе данных
                $update_password_sql = "UPDATE Users SET password = '$hashed_password' WHERE login = '$login'";

                if ($conn->query($update_password_sql) === TRUE) {
                    $_SESSION['password_message'] = "Пароль пользователя успешно изменен. Новый пароль: " . mb_convert_encoding($new_password, "UTF-8", "auto");
                    // Перенаправление пользователя
                    header("Location: NewClientAdmin.php?admin_key=ed2f1a7b93c4d8e7a6b1d5f2c8e3d4b9a7c8e2d3b4a9c6d5f2c8e3d4b9a7c8e2d3b4a9c6d5f2c8e3d4b9a7c8e2d3b4a9c6d5f2c8e3d4b9a7c8e2d3b4a9c6d5f2c8e3d4b9a7c8e2d3b4a9c6d5f2c8e3d4b9a7c8e2d3b484359345934958349583498593485934853948593485934859gjdfgd;kfjgd;fkjg34534645645645644");
                    exit();
                } else {
                    $_SESSION['error_message'] = "Ошибка при изменении пароля пользователя: " . $conn->error;
                }
            } else {
                date_default_timezone_set('Asia/Yekaterinburg'); // Установка часового пояса
                
                // Проверка соответствия пароля требованиям
                if (!preg_match('/^(?=.*\d)(?=.*[a-zA-Zа-яА-ЯёЁ])(?=.*[\W_]).{6,}$/', $password)) {
                    $_SESSION['password_message'] = "Пароль должен содержать цифры, строчные или прописные буквы русского или латинского алфавита, а также специальный символ, длина пароля должна быть не менее 6 символов.";
                    // Возвращаемся к форме для ввода
                    header("Location: NewClientAdmin.php?admin_key=ed2f1a7b93c4d8e7a6b1d5f2c8e3d4b9a7c8e2d3b4a9c6d5f2c8e3d4b9a7c8e2d3b4a9c6d5f2c8e3d4b9a7c8e2d3b4a9c6d5f2c8e3d4b9a7c8e2d3b4a9c6d5f2c8e3d4b9a7c8e2d3b4a9c6d5f2c8e3d4b9a7c8e2d3b484359345934958349583498593485934853948593485934859gjdfgd;kfjgd;fkjg34534645645645644");
                    exit();
                }

                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $creation_time = date('Y-m-d H:i:s'); // Получаем текущее время с учетом установленного часового пояса
                // Добавление пользователя в базу данных
                $insert_user_sql = "INSERT INTO Users (login, password, creation_time) 
                VALUES ('$login', '$hashed_password', '$creation_time')";
                if ($conn->query($insert_user_sql) === TRUE) {
                    $_SESSION['user_added_message'] = "Пользователь успешно добавлен";
                    // Перенаправление пользователя
                    header("Location: NewClientAdmin.php?admin_key=ed2f1a7b93c4d8e7a6b1d5f2c8e3d4b9a7c8e2d3b4a9c6d5f2c8e3d4b9a7c8e2d3b4a9c6d5f2c8e3d4b9a7c8e2d3b4a9c6d5f2c8e3d4b9a7c8e2d3b4a9c6d5f2c8e3d4b9a7c8e2d3b4a9c6d5f2c8e3d4b9a7c8e2d3b484359345934958349583498593485934853948593485934859gjdfgd;kfjgd;fkjg34534645645645644");
                    exit();
                } else {
                    $_SESSION['error_message'] = "Ошибка при добавлении пользователя: " . $conn->error;
                }
            }
        }
    }
} catch (Exception $e) {
    $_SESSION['error_message'] = $e->getMessage();
} finally {
    if (isset($conn)) {
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/newClientAdmin.css"/>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500&display=swap"/>
</head>
<body>
    <div class="container">
        <div class="left-section">
            <div class="text">
                Добавление нового пользователя
            </div>
         <form action="NewClientAdmin.php?admin_key=ed2f1a7b93c4d8e7a6b1d5f2c8e3d4b9a7c8e2d3b4a9c6d5f2c8e3d4b9a7c8e2d3b4a9c6d5f2c8e3d4b9a7c8e2d3b4a9c6d5f2c8e3d4b9a7c8e2d3b4a9c6d5f2c8e3d4b9a7c8e2d3b4a9c6d5f2c8e3d4b9a7c8e2d3b484359345934958349583498593485934853948593485934859gjdfgd;kfjgd;fkjg34534645645645644" method="post">
         <div class="field">
                    <input type="text" name="login" placeholder="Логин" required>
                    <span class="fas fa-user"></span>
                </div>
                <div class="field">
                    <input type="password" name="password" placeholder="Пароль" required>
                    <span class="fas fa-lock"></span>
                </div>
                <div class="field">
                    <input type="password" name="confirm_password" placeholder="Повторите пароль" required>
                    <span class="fas fa-lock"></span>
                </div>
                <button type="submit" class="enter">Добавить пользователя</button>
            </form>
            <?php
            if (isset($_SESSION['password_message'])) {
                echo "<p>{$_SESSION['password_message']}</p>";
                unset($_SESSION['password_message']);
            }
            if (isset($_SESSION['user_added_message'])) {
                echo "<p>{$_SESSION['user_added_message']}</p>";
                unset($_SESSION['user_added_message']);
            }
            if (isset($_SESSION['error_message'])) {
                echo "<p>{$_SESSION['error_message']}</p>";
                unset($_SESSION['error_message']);
            }
            ?>
        </div>
        <div class="right-section">
            <table>
                <thead>
                    <tr>
                        <th>Логин</th>
                        <th>Пароль</th>
                        <th>Время создания</th>
                        <th>Время последней авторизации</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                // Вывод данных из базы данных
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row["login"] . "</td>";
                        echo "<td>" . $row["password"] . "</td>";
                        echo "<td>" . $row["creation_time"] . "</td>";
                        echo "<td>" . $row["last_login_time"] . "</td>";
                        echo "<td>";
                        echo "<a href='adminEditUser.php?userId=" . $row["ID"] . "'>Изменить</a>  ㅤ";
                        echo "<a href='#' onclick='deleteUser(" . $row["ID"] . ", \"" . $row["login"] . "\")'>Удалить</a>";
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='3'>Нет данных</td></tr>";
                }
                ?>
            </tbody>
            </table>
        </div>
    </div>
    <script src="JS/script.js"></script>
</body>
<script>
    function deleteUser(userId, login) {
        var confirmDelete = confirm("Вы действительно хотите удалить пользователя " + login + "?");

        if (confirmDelete) {
            // Отправка AJAX-запроса на сервер для удаления пользователя
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
                if (this.readyState == 4) {
                    if (this.status == 200) {
                        // Проверяем ответ сервера
                        if (this.responseText === "success") {
                            alert("Пользователь " + login + " успешно удален");
                            location.reload();
                        } else {
                            alert("Ошибка: " + this.responseText);
                        }
                    } else {
                        alert("Ошибка при выполнении запроса: " + this.statusText);
                    }
                }
            };
            xmlhttp.open("POST", "deleteUser.php", true);
            xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xmlhttp.send("userId=" + userId);
        }
    }
</script>

</html>
