<?php
global $conn;
include 'database.php';
// SQL запрос для получения данных о клиентах, задачах и статусах
$sql = "SELECT c.id_client, c.first_name, c.middle_name, c.last_name, i.name AS task_name, s.status_name
        FROM clients c
        LEFT JOIN issues i ON c.id_client = i.id_client
        LEFT JOIN status s ON i.id_status = s.id_status";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap">
    <link rel="stylesheet" href="CSS/clientsStyle.css">
    <link rel="stylesheet" href="CSS/clientsEdit.css">
    <link rel="stylesheet" href="CSS/addClientModal.css">
    <title>Клиенты</title>
    <style>
        /* Скрываем модальное окно по умолчанию */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
        }
    </style>
</head>
<body>
<div id="addClientModal" class="modal">
    <div class="modal-content">
        <div class="container">
            <div class="icons">
                <img class="cross-icon" src="IMG/cross.svg" alt="Cross Icon" onclick="closeAddClientModal()">
            </div>
            <div class="content">
                <h2>Добавить клиента</h2>
            </div>
            <div class="contact-info">
                <div class="input-group">
                    <label for="first_name">Имя:</label>
                    <input type="text" id="first_name">
                </div>
                <div class="input-group">
                    <label for="middle_name">Фамилия:</label>
                    <input type="text" id="middle_name">
                </div>
                <div class="input-group">
                    <label for="last_name">Отчество:</label>
                    <input type="text" id="last_name">
                </div>
                <div class="input-group">
                    <label for="company_name">Название компании:</label>
                    <input type="text" id="company_name">
                </div>
                <div class="input-group">
                    <label for="contact_phone">Телефон:</label>
                    <input type="text" id="contact_phone">
                </div>
                <div class="input-group">
                    <label for="c_email">E-mail Адресс:</label>
                    <input type="text" id="c_email">
                </div>
                <div class="input-group">
                    <label for="c_inn">ИНН:</label>
                    <input type="text" id="c_inn">
                </div>
                <button class="save-button" onclick="addClient()">Добавить</button>
            </div>
        </div>
    </div>
</div>
<div id="myModal" class="modal">
    <div class="modal-content">
        <div class="container">
            <div class="icons">
                <img class="cross-icon" src="IMG/cross.svg" alt="Cross Icon" onclick="closeModal()">
            </div>
            <div class="content">
                <img class="logo" src="IMG/image-wind-user.svg" alt="Logo">
                <div class="chat-info">
                    <img class="chat-icon" src="IMG/image-chat.svg" alt="Chat Icon">
                    <div class="header">Открыть чат</div>
                </div>
            </div>
            <div class="contact-info">
                <div class="input-group">
                    <label for="company">Название компании:</label>
                    <input type="text" id="company">
                </div>

                <div class="input-group">
                    <label for="contact">Контактное лицо:</label>
                    <input type="text" id="contact" readonly>
                </div>

                <div class="input-group">
                    <label for="phone">Телефон:</label>
                    <input type="text" id="phone">
                </div>

                <div class="input-group">
                    <label for="email">E-mail Адрес:</label>
                    <input type="text" id="email">
                </div>

                <div class="input-group">
                    <label for="inn">ИНН:</label>
                    <input type="text" id="inn">
                </div>
                <button class="save-button" id="saveButton">Сохранить</button>
            </div>
        </div>
    </div>
</div>
<header>
    <div class="header-container">
        <div class="nav-item">
            <a href="Tasks.php" class="rectangle-9 tasks" style="text-decoration: none;">
                <img src="IMG/image-70.svg" alt="Задачи" width="30" height="30"/>
                <span class="nav-text">Задачи</span>
            </a>
        </div>
        <div class="nav-item active">
            <div class="rectangle-9 client">
                <img src="IMG/image-80active.svg" alt="Клиенты" width="21" height="21"/>
                <span class="nav-text">Клиенты</span>
            </div>
        </div>
        <div class="nav-item">
            <a href="Chats.php" class="rectangle-9 chat" style="text-decoration: none;">
                <img src="IMG/image-90.svg" alt="Чаты" width="28" height="24"/>
                <span class="nav-text">Чаты</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="product/products.php" class="rectangle-9 chat" style="text-decoration: none;">
                <img src="IMG/image30.png" alt="Чаты" width="28" height="24"/>
                <span class="nav-text">Продукты</span>
            </a>
        </div>
    </div>
    <div class="login-avatar-container">
        <div class="login-text">Логин</div>
        <div class="avatar">
            <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 26 26" fill="none">
                <circle cx="13" cy="13" r="13" fill="#C7A5A5"/>
            </svg>
        </div>
    </div>
</header>
<div class="header-content">
    <div class="search-panel">
        <img src="IMG/Polygon2.svg" alt="Polygon2"/>
        <div class="divider"></div>
        <img src="IMG/Search.svg" alt="Search"/>
        <input type="text" id="searchInput" onkeyup="searchTable()">
    </div>
    <div class="add-button" onclick="openAddClientModal()"><span>Добавить</span></div>
</div>
<div class="task-list-container">
    <table class="task-table">
        <thead>
        <tr>
            <th>Клиент</th>
            <th>Задача</th>
            <th>Статус</th>
            <th></th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        <?php
        // Вывод данных из базы данных
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                // Заменяем тег <a> на <span> или <div>
                echo "<td> <span style='cursor: pointer;' onclick='openModal(" . $row['id_client'] . ")'>" . $row["first_name"] . " " . $row["middle_name"] . " " . $row["last_name"] . "</span></td>";
                echo "<td>" . $row["task_name"] . "</td>";
                echo "<td>" . $row["status_name"] . "</td>";
                echo "<td><img src='IMG/chat.svg' alt='chat' /> Открыть чат</td>";
                echo "<td><img src='IMG/delete.svg' alt='delete' style='cursor: pointer;' onclick='deleteClient(" . $row['id_client'] . ")'></td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='4'>Нет данных</td></tr>";
        }
        $conn->close();
        ?>
        </tbody>
    </table>
</div>
</body>
<script>
    // Функция для удаления клиента
    function deleteClient(clientId) {
        var confirmation = confirm("Вы уверены, что хотите удалить этого клиента?");
        if (confirmation) {
            // Отправляем AJAX-запрос для удаления клиента
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    // Обработка ответа сервера
                    alert(this.responseText); // Вывод сообщения об успешном удалении или ошибке
                    // Добавьте здесь код для обновления списка клиентов, если необходимо
                }
            };
            xmlhttp.open("POST", "deleteClient.php", true);
            xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xmlhttp.send("clientId=" + clientId);
        }
    }
</script>
<script>
    // Открытие модального окна для добавления клиента
    function openAddClientModal() {
        document.getElementById('addClientModal').style.display = 'block';
    }

    // Закрытие модального окна для добавления клиента
    function closeAddClientModal() {
        document.getElementById('addClientModal').style.display = 'none';
        document.getElementById('myModal').style.display = 'none';
    }

    // Добавление клиента
    function addClient() {
        var first_name = document.getElementById('first_name').value;
        var middle_name = document.getElementById('middle_name').value;
        var last_name = document.getElementById('last_name').value;
        var company_name = document.getElementById('company_name').value;
        var contact_phone = document.getElementById('contact_phone').value;
        var email = document.getElementById('c_email').value;
        var inn = document.getElementById('c_inn').value;

        // Проверяем, все ли поля заполнены
        if (first_name === "" || middle_name === "" || last_name === "" || company_name === "" || contact_phone === "" || email === "" || inn === "") {
            alert("Пожалуйста, заполните все поля ввода.");
            return; // Прекращаем выполнение функции, если не все поля заполнены
        }

        // Запрос на подтверждение, если не все поля заполнены
        if (!confirm("Вы уверены, что хотите добавить клиента? Информация не будет сохранена в случае отмены.")) {
            return; // Прекращаем выполнение функции, если пользователь отменил действие
        }

        // Отправка AJAX-запроса для добавления клиента
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                // Обработка ответа сервера
                closeModal();
                closeAddClientModal(); // Закрываем модальное окно после добавления
                alert(this.responseText); // Вывод сообщения об успешном добавлении или ошибке
                // Добавьте здесь код для обновления списка клиентов, если необходимо
            }
        };
        xmlhttp.open("POST", "addClientData.php", true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send("first_name=" + first_name + "&middle_name=" + middle_name + "&last_name=" + last_name +
            "&company_name=" + company_name + "&contact_phone=" + contact_phone +
            "&c_email=" + email + "&c_inn=" + inn);

    }

</script>
<script>
    function openModal(clientId) {
        // Отправка AJAX-запроса для загрузки данных о клиенте по ID
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                // Получаем данные о клиенте из ответа на запрос
                var clientData = JSON.parse(this.responseText);

                // Обновляем содержимое модального окна данными о клиенте
                document.getElementById('company').value = clientData.company_name;
                document.getElementById('contact').value = clientData.first_name + ' ' + clientData.middle_name + ' ' + clientData.last_name;
                document.getElementById('phone').value = clientData.contact_phone;
                document.getElementById('email').value = clientData.email;
                document.getElementById('inn').value = clientData.inn;

                // Открываем модальное окно
                document.getElementById('myModal').style.display = 'block';
            }
        };
        // Отправляем запрос на сервер с указанием clientId
        xmlhttp.open("GET", "getClientData.php?clientId=" + clientId, true);
        xmlhttp.send();
    }

    function closeModal() {
        // Закрываем модальное окно
        document.getElementById('myModal').style.display = 'none';
    }

    document.getElementById('saveButton').addEventListener('click', function () {
        var company = document.getElementById('company').value;
        var contact = document.getElementById('contact').value;
        var phone = document.getElementById('phone').value;
        var email = document.getElementById('email').value;
        var inn = document.getElementById('inn').value;

        // Отправка AJAX-запроса для обновления данных клиента
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                // Обработка ответа сервера
                alert(this.responseText); // Вывод сообщения об успешном сохранении или ошибке
            }
        };
        xmlhttp.open("POST", "updateClientData.php", true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send("company=" + company + "&contact=" + contact + "&phone=" + phone + "&email=" + email + "&inn=" + inn);
        closeAddClientModal();
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var searchInput = document.getElementById('searchInput');
        var taskRows = document.querySelectorAll('.task-table tbody tr');

        // Функция для фильтрации строк таблицы по ключевому слову
        function filterRows() {
            var keyword = searchInput.value.trim().toLowerCase();

            taskRows.forEach(function (row) {
                var cells = row.querySelectorAll('td');
                var isVisible = false;

                // Проверяем каждую ячейку в строке на соответствие ключевому слову
                cells.forEach(function (cell) {
                    var cellText = cell.textContent.toLowerCase();

                    if (cellText.includes(keyword)) {
                        isVisible = true;
                    }
                });

                // Устанавливаем свойство отображения строки в соответствии с результатом фильтрации
                if (isVisible) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        // Обработчик события для ввода ключевого слова
        searchInput.addEventListener('input', filterRows);
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var loginContainer = document.querySelector('.login-avatar-container');
        var loginText = loginContainer.querySelector('.login-text');

        // Получаем значение куки с логином пользователя
        var loginCookie = document.cookie.replace(/(?:(?:^|.*;\s*)login\s*\=\s*([^;]*).*$)|^.*$/, "$1");

        // Проверяем, есть ли значение куки с логином
        if (loginCookie) {
            loginText.textContent = loginCookie; // Отображаем логин пользователя
        }
    });
</script>
</html>
