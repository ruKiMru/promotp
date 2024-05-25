<?php
include '../database.php';
global $conn;

$sqlGetProducts = "SELECT
    products.id,
    models.id AS model_id,
    models.name AS model_name,
    products.series,
    products.ip,
    clients.id_client AS client_id,
    clients.first_name,
    clients.middle_name,
    clients.last_name,
    configurations.id AS configuration_id,
    configurations.name AS configuration_name,
    products.release_date
FROM products
INNER JOIN configurations ON products.configuration_id = configurations.id
INNER JOIN clients ON products.client_id = clients.id_client
INNER JOIN models ON products.model_id = models.id;";
$result = $conn->query($sqlGetProducts);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap">
    <link rel="stylesheet" href="../CSS/Issue_Styl.css">
    <link rel="stylesheet" href="../CSS/product.css">
    <link rel="stylesheet" href="../CSS/completerstylee.css">
    <title>Продукты</title>
</head>

<div id="TaskAddIssueWindow" class="modalIssue">
    <div class="container_c">
        <div class="icons_c">
            <img class="cross-icon-third_c" src="../IMG/cross.svg" alt="Cross Icon"
                 onclick="closeTaskAddIssueWindow()">
        </div>
        <div class="content_c">
            <div class="chat-info_c"></div>
        </div>
        <div class="contact-info_c">
            <form method="post" action="" id="addIssueForm_c">
                <div class="input-group_c">
                    <label for="client">Клиент</label>
                    <select id="client" name="client" required>
                        <option selected hidden="hidden">Выберите клиента</option>
                        <?php
                        // Вывод статусов из базы данных
                        $statusSql = "SELECT * FROM clients";
                        $statusResult = $conn->query($statusSql);

                        if ($statusResult->num_rows > 0) {
                            while ($statusRow = $statusResult->fetch_assoc()) {
                                echo "<option value='" . $statusRow['id_client'] . "'>
                                " . " " . $statusRow['first_name'] . " " . $statusRow['middle_name'] . " " . $statusRow['last_name'] . "
                                </option>";
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="input-group_c">
                    <label for="model">Модель</label>
                    <select id="model" name="model" required>
                        <option selected hidden="hidden">Выберите модель</option>
                        <?php
                        // Вывод статусов из базы данных
                        $statusSql = "SELECT * FROM models";
                        $statusResult = $conn->query($statusSql);

                        if ($statusResult->num_rows > 0) {
                            while ($statusRow = $statusResult->fetch_assoc()) {
                                echo "<option value='" . $statusRow['id'] . "'>
                                " . $statusRow['name'] . "
                                </option>";
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="input-group_c">
                    <label for="configuration">Конфигурация</label>
                    <select id="configuration" name="configuration" required>
                        <option selected hidden="hidden">Выберите конфигурацию</option>
                        <?php
                        // Вывод статусов из базы данных
                        $statusSql = "SELECT * FROM configurations";
                        $statusResult = $conn->query($statusSql);

                        if ($statusResult->num_rows > 0) {
                            while ($statusRow = $statusResult->fetch_assoc()) {
                                echo "<option value='" . $statusRow['id'] . "'>
                                " . $statusRow['name'] . "
                                </option>";
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="input-group_c">
                    <label for="series">Серия</label>
                    <input type="text" id="series" name="series" required>
                </div>
                <div class="input-group_c">
                    <label for="ipAddress">IP Адрес</label>
                    <input type="text" id="ipAddress" name="ipAddress" required>
                </div>
                <div class="input-group_c">
                    <label for="releaseDate">Дата выпуска</label>
                    <input type="date" id="releaseDate" name="releaseDate" required>
                </div>
                <button type="submit" class="save-button_c add-products">Добавить</button>
            </form>
        </div>
    </div>
</div>

<div id="editModal" class="modalIssue">
    <div class="container_c">
        <div class="icons_c">
            <img class="cross-icon-third_c" src="../IMG/cross.svg" alt="Cross Icon"
                 onclick="closeEditModal()">
        </div>
        <div class="content_c">
            <div class="chat-info_c"></div>
        </div>
        <div class="contact-info_c">
            <form id="addIssueForm_c" class="form-edit">
                <input type="hidden" name="idProduct" id="idProduct">
                <div class="input-group_c">
                    <label for="edit-client">Клиент</label>
                    <select id="edit-client" name="client" required>
                        <option selected hidden="hidden">Выберите клиента</option>
                        <?php
                        // Вывод статусов из базы данных
                        $statusSql = "SELECT * FROM clients";
                        $statusResult = $conn->query($statusSql);

                        if ($statusResult->num_rows > 0) {
                            while ($statusRow = $statusResult->fetch_assoc()) {
                                echo "<option value='" . $statusRow['id_client'] . "'>
                                " . " " . $statusRow['first_name'] . " " . $statusRow['middle_name'] . " " . $statusRow['last_name'] . "
                                </option>";
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="input-group_c">
                    <label for="edit-model">Модель</label>
                    <select id="edit-model" name="model" required>
                        <option selected hidden="hidden">Выберите модель</option>
                        <?php
                        // Вывод статусов из базы данных
                        $statusSql = "SELECT * FROM models";
                        $statusResult = $conn->query($statusSql);

                        if ($statusResult->num_rows > 0) {
                            while ($statusRow = $statusResult->fetch_assoc()) {
                                echo "<option value='" . $statusRow['id'] . "'>
                                " . $statusRow['name'] . "
                                </option>";
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="input-group_c">
                    <label for="edit-configuration">Конфигурация</label>
                    <select id="edit-configuration" name="configuration" required>
                        <option selected hidden="hidden">Выберите конфигурацию</option>
                        <?php
                        // Вывод статусов из базы данных
                        $statusSql = "SELECT * FROM configurations";
                        $statusResult = $conn->query($statusSql);

                        if ($statusResult->num_rows > 0) {
                            while ($statusRow = $statusResult->fetch_assoc()) {
                                echo "<option value='" . $statusRow['id'] . "'>
                                " . $statusRow['name'] . "
                                </option>";
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="input-group_c">
                    <label for="edit-series">Серия</label>
                    <input type="text" id="edit-series" name="series" required>
                </div>
                <div class="input-group_c">
                    <label for="edit-ipAddress">IP Адрес</label>
                    <input type="text" id="edit-ipAddress" name="ipAddress" required>
                </div>
                <div class="input-group_c">
                    <label for="edit-releaseDate">Дата выпуска</label>
                    <input type="date" id="edit-releaseDate" name="releaseDate" required>
                </div>
                <button type="submit" class="save-button_c edit-product">Сохранить</button>
            </form>
        </div>
    </div>
</div>

<body>
<header>
    <div class="header-container">
        <div class="nav-item">
            <a href="../Tasks.php" class="rectangle-9 client" style="text-decoration: none;">
                <img src="../IMG/image-70.svg" alt="Задачи" width="30" height="30"/>
                <span class="nav-text">Задачи</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="../Clients.php" class="rectangle-9 client" style="text-decoration: none;">
                <img src="../IMG/image-80.svg" alt="Клиенты" width="21" height="21"/>
                <span class="nav-text">Клиенты</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="../Chats.php" class="rectangle-9 chat" style="text-decoration: none;">
                <img src="../IMG/image-90.svg" alt="Чаты" width="28" height="24"/>
                <span class="nav-text">Чаты</span>
            </a>
        </div>
        <div class="nav-item active">
            <div class="rectangle-9" style="text-decoration: none;">
                <img src="../IMG/image30-active.svg" alt="Продукты" width="28" height="24"/>
                <span class="nav-text active">Продукты</span>
            </div>
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
    <div class="second-panel">
        <div class="panel-item active">
            <div style="text-decoration: none;">
                <span>Продукты</span>
            </div>
        </div>
        <div class="panel-item" id="kanban" style="background:#FFF">
            <a href="./models.php" style="text-decoration: none;">
                <span>Модели</span>
            </a>
        </div>
        <div class="panel-item" id="calendar" style="background:#FFF">
            <a href="./configuration.php" style="text-decoration: none;">
                <span>Конфигурация</span>
            </a>
        </div>
        <div class="panel-item" id="gant" style="border-radius: 0 30px 30px 0; background:#FFF">
            <a href="./equipment.php" style="text-decoration: none;">
                <span>Оборудование</span>
            </a>
        </div>
    </div>
    <div class="spacer"></div>
    <div class="search-panel">
        <div class="custom-select-wrapper">
            <div style="display: flex">
                <img src="../IMG/Polygon2.svg" alt="Select" id="customSelect">
                <select id="statusFilter">
                    <option value="all">Все статусы</option>
                    <?php
                    // Вывод статусов из базы данных
                    $statusSql = "SELECT * FROM status";
                    $statusResult = $conn->query($statusSql);

                    if ($statusResult->num_rows > 0) {
                        while ($statusRow = $statusResult->fetch_assoc()) {
                            echo "<option value='" . $statusRow['id_status'] . "'>" . $statusRow['status_name'] . "</option>";
                        }
                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="divider-main"></div>
        <img src="../IMG/Search.svg" alt="Search"/>
        <input type="text" id="keywordInput"/>
    </div>


    <div class="add-button" onclick="openTaskAddIssueWindow()"><span>Добавить</span></div>
</div>

<div class="task-list-container">
    <table class="task-table">
        <thead>
        <tr>
            <th>Модель</th>
            <th>Серия</th>
            <th>IP Адрес</th>
            <th>Клиент</th>
            <th>Конфигурация</th>
            <th>Дата выпуска</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td onclick='openEditModal(" . json_encode($row) . ")'>" . $row['model_name'] . "</td>";
                echo "<td onclick='openEditModal(" . json_encode($row) . ")'>" . $row['series'] . "</td>";
                echo "<td onclick='openEditModal(" . json_encode($row) . ")'>" . $row['ip'] . "</td>";
                echo "<td onclick='openEditModal(" . json_encode($row) . ")'>"
                    . $row['middle_name'] . " " .
                    mb_substr($row['first_name'], 0, 1) . "." .
                    mb_substr($row['last_name'], 0, 1) . "." .
                    "</td>";
                echo "<td onclick='openEditModal(" . json_encode($row) . ")'>" . $row['configuration_name'] . "</td>";
                echo "<td onclick='openEditModal(" . json_encode($row) . ")'>" . $row['release_date'] . "</td>";
                echo "<td><button class='delete-button' onclick='deleteRecord(" . $row['id'] . ")'>Удалить</button></td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='8'>Нет данных для отображения</td></tr>";
        }
        ?>
        </tbody>
    </table>
</div>
</body>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var loginContainer = document.querySelector('.login-avatar-container');
        var loginText = loginContainer.querySelector('.login-text');

        // Получаем значение куки с логином пользователя
        var loginCookie = document.cookie.replace(/(?:(?:^|.*;\s*)login\s*\=\s*([^;]*).*$)|^.*$/, "$1");

        // Проверяем, есть ли значение куки с логином
        if (loginCookie) {
            loginText.textContent = loginCookie; // Отображаем логин пользователя

            // Добавляем код для установки логина в другой элемент
            var loginTextWindow = document.querySelector('.login-text-window');
            loginTextWindow.textContent = loginCookie; // Устанавливаем логин в другой элемент
        }
    });
</script>
<script>
    function openTaskAddIssueWindow() {
        // Открываем модальное окно
        document.getElementById('TaskAddIssueWindow').style.display = 'block';
    }

    function closeTaskAddIssueWindow() {
        // Закрываем модальное окно
        document.getElementById('TaskAddIssueWindow').style.display = 'none';
        window.location.reload()
    }

    function openEditModal(rowData) {
        document.getElementById('idProduct').value = rowData.id;
        document.getElementById('edit-client').value = rowData.client_id;
        document.getElementById('edit-model').value = rowData.model_id;
        document.getElementById('edit-configuration').value = rowData.configuration_id;
        document.getElementById('edit-series').value = rowData.series;
        document.getElementById('edit-ipAddress').value = rowData.ip;
        document.getElementById('edit-releaseDate').value = rowData.release_date;
        document.getElementById('editModal').style.display = 'block';
    }

    function closeEditModal() {
        document.getElementById('editModal').style.display = 'none';
    }
</script>
<script>
    function deleteRecord(id) {
        if (confirm('Вы уверены, что хотите удалить эту запись?')) {
            fetch('./requests/delete/product.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ id: id })
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        alert('Запись успешно удалена');
                        location.reload();
                    } else {
                        alert('Ошибка при удалении записи: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Ошибка:', error);
                    alert('Ошибка при удалении записи: ' + error.message);
                });
        }
    }
</script>
<script>
    const customSelect = document.getElementById('customSelect');
    const statusFilter = document.getElementById('statusFilter');

    customSelect.addEventListener('click', function () {
        // Открываем или закрываем выпадающий список при клике на изображение
        statusFilter.style.display = statusFilter.style.display === 'none' ? 'block' : 'none';
    });
</script>
<script src="js/add/ajax-product.js"></script>
<script src="js/edit/ajax-product.js"></script>
</html>
