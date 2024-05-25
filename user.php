<?php
$adminKey = "ed2f1a7b93c4d8e7a6b1d5f2c8e3d4b9a7c8e2d3b4a9c6d5f2c8e3d4b9a7c8e2d3b4a9c6d5f2c8e3d4b9a7c8e2d3b4a9c6d5f2c8e3d4b9a7c8e2d3b4a9c6d5f2c8e3d4b9a7c8e2d3b4a9c6d5f2c8e3d4b9a7c8e2d3b484359345934958349583498593485934853948593485934859gjdfgd;kfjgd;fkjg34534645645645644";

// Получаем параметр key из URL
$keyFromURL = isset($_GET['key']) ? $_GET['key'] : '';

// Проверяем, соответствует ли ключ ожидаемому ключу и имеет ли длину 256 или более символов
if ($keyFromURL === $adminKey && strlen($keyFromURL) >= 256) {

} else {
    echo "Ошибка доступа: Неверный ключ!";
    exit();
}

include 'database.php';

// Запрос к базе данных
$sql = "SELECT ID, first_name, last_name, middle_name, email, login FROM users";
$result = $conn->query($sql);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap">
    <link rel="stylesheet" href="CSS/viewUsersStyle.css">
    <style>
        #userTable td a {
            text-decoration: none;
            color: inherit;
        }
    </style>
    <title>Администратор: Просмотр пользователей</title>
</head>
<body>
    <header>
        <div class="header-container">
            <div class="login-avatar-container">
                <div class="login-text">Логин</div>
                <div class="avatar">
                    <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 26 26" fill="none">
                        <circle cx="13" cy="13" r="13" fill="#C7A5A5" />
                    </svg>
                </div>
            </div>
        </div>
    </header>
    <div class="header-content">
    <div class="search-panel">
    <img src="IMG/Polygon2.svg" alt="Polygon2" />
    <input type="text" id="searchInput" oninput="searchUsers()" placeholder="Поиск по пользователям"/>
</div>

    </div>
    <div class="task-list-container">
        <table class="task-table" id="userTable">
            <thead>
                <tr>
                    <th>ФИО</th>
                    <th>Email адрес</th>
                    <th>Логин</th>
                    <th>Операции</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Вывод данных из базы данных
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row["last_name"] . " " . $row["first_name"] . " " . $row["middle_name"] . "</td>";
                        echo "<td>" . $row["email"] . "</td>";
                        echo "<td>" . $row["login"] . "</td>";
                        echo "<td>";
                        echo "<a href='#' onclick='resetPasswordDialog(" . $row["ID"] . ")'>Сбросить пароль</a> ㅤ";
                        echo "<a href='editUser.php?userId=" . $row["ID"] . "'>Изменить</a>  ㅤ";
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
</body>
<script>
    function resetPasswordDialog(userId) {
        var newPassword = prompt("Введите новый пароль:");
        var confirmPassword = prompt("Подтвердите пароль:");

        if (newPassword !== null && confirmPassword !== null && newPassword === confirmPassword) {
            // Отправка AJAX-запроса на сервер для сброса пароля
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    alert("Пароль успешно сброшен, новый пароль: " + newPassword);
                } else if (this.readyState == 4 && this.status != 200) {
                    alert("Ошибка при сбросе пароля: " + this.responseText);
                }
            };
            xmlhttp.open("POST", "resetPasswordHandler.php", true);
            xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xmlhttp.send("userId=" + userId + "&newPassword=" + newPassword);
        } else {
            alert("Пароли не совпадают или действие отменено");
        }
    }
</script>
<script>
    function deleteUser(userId, login) {
        var confirmDelete = confirm("Вы действительно хотите удалить пользователя " + login + "?");

        if (confirmDelete) {
            // Отправка AJAX-запроса на сервер для удаления пользователя
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    alert("Пользователь " + login + " успешно удален");
                    location.reload();
                } else if (this.readyState == 4 && this.status != 200) {
                    alert("Ошибка при удалении пользователя: " + this.responseText);
                }
            };
            xmlhttp.open("POST", "deleteUser.php", true);
            xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xmlhttp.send("userId=" + userId);
        }
    }
</script>
<script>
    function searchUsers() {
        var input, filter, table, tr, td, i, txtValue;
        input = document.getElementById("searchInput");
        filter = input.value.toUpperCase();
        table = document.getElementById("userTable");
        tr = table.getElementsByTagName("tr");

        for (i = 0; i < tr.length; i++) {
            var found = false;

            // Перебираем все ячейки в каждой строке
            for (var j = 0; j < tr[i].cells.length; j++) {
                td = tr[i].getElementsByTagName("td")[j];
                if (td) {
                    txtValue = td.textContent || td.innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        found = true;
                        break;
                    }
                }
            }

            // Показываем или скрываем строку в зависимости от наличия совпадения
            if (found) {
                tr[i].style.display = "";
            } else {
                tr[i].style.display = "none";
            }
        }
    }
</script>


</html>
<?php
$conn->close();
?>
