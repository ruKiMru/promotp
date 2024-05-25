<?php
include '../database.php';
global $conn;
$sqlGetModels = "SELECT 
    m.id AS model_id, 
    m.name AS model_name, 
    equip.equipment_name,
    config.configuration_name
FROM models m
LEFT JOIN (
    SELECT 
        me.model_id,
        GROUP_CONCAT(e.name SEPARATOR ', ') AS equipment_name
    FROM `model&equip` me
    INNER JOIN equipments e ON me.equipment_id = e.id
    GROUP BY me.model_id
) AS equip ON m.id = equip.model_id
LEFT JOIN (
    SELECT 
        mc.model_id,
        GROUP_CONCAT(c.name SEPARATOR ', ') AS configuration_name
    FROM `model&config` mc
    INNER JOIN configurations c ON mc.configuration_id = c.id
    GROUP BY mc.model_id
) AS config ON m.id = config.model_id;";
$result = $conn->query($sqlGetModels);
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
                    <label for="model">Модель</label>
                    <input type="text" id="model" name="model" required>
                </div>
                <div class="select-multiple-group_c">
                    <label for="equipment">Оборудование</label>
                    <select multiple id="equipment" name="equipment[]" required>
                        <?php
                        // Вывод статусов из базы данных
                        $equipmentsSql = "SELECT * FROM equipments";
                        $statusResult = $conn->query($equipmentsSql);

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
                <div class="select-multiple-group_c">
                    <label for="configuration">Конфигурации</label>
                    <select multiple id="configuration" name="configuration[]" required>
                        <?php
                        // Вывод статусов из базы данных
                        $configurationsSql = "SELECT * FROM configurations";
                        $statusResult = $conn->query($configurationsSql);

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
                <button type="submit" class="save-button_c add-models">Добавить</button>
            </form>
        </div>
    </div>
</div>

<div id="editModal" class="modalIssue">
    <div class="container_c">
        <div class="icons_c">
            <img class="cross-icon-third_c" src="../IMG/cross.svg" alt="Cross Icon"
                 onclick="closeTaskAddIssueWindow()">
        </div>
        <div class="content_c">
            <div class="chat-info_c"></div>
        </div>
        <div class="contact-info_c">
            <form id="editIssueForm_c" class="form-edit">
                <input type="hidden" id="idModel" name="idModel">
                <div class="input-group_c">
                    <label for="edit-model">Модель</label>
                    <input type="text" id="edit-model" name="model" required>
                </div>
                <div class="select-multiple-group_c">
                    <label for="edit-equipment">Оборудование</label>
                    <select multiple id="edit-equipment" name="equipment[]" required>
                        <?php
                        // Вывод статусов из базы данных
                        $equipmentsSql = "SELECT * FROM equipments";
                        $statusResult = $conn->query($equipmentsSql);

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
                <div class="select-multiple-group_c">
                    <label for="edit-configuration">Конфигурации</label>
                    <select multiple id="edit-configuration" name="configuration[]" required>
                        <?php
                        // Вывод статусов из базы данных
                        $configurationsSql = "SELECT * FROM configurations";
                        $statusResult = $conn->query($configurationsSql);

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
                <button type="submit" class="save-button_c edit-models">Сохранить</button>
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
        <div class="panel-item" style="border-radius: 30px 0 0 30px; background:#FFF">
            <a href="./products.php" style="text-decoration: none;">
                <span>Продукты</span>
            </a>
        </div>
        <div class="panel-item active" style=" border-radius:0;" id="kanban">
            <div style="text-decoration: none">
                <span>Модели</span>
            </div>
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
            <th>Имя модели</th>
            <th>Оборудование</th>
            <th>Конфигурация</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                // Преобразуем строки с названиями оборудования и конфигураций в массивы их ID
                $equipmentIds = [];
                $configurationIds = [];

                // Получение ID оборудования
                if (!empty($row['equipment_name'])) {
                    $equipmentNames = explode(', ', $row['equipment_name']);
                    $equipmentsSql = "SELECT id, name FROM equipments WHERE name IN ('" . implode("','", $equipmentNames) . "')";
                    $equipmentsResult = $conn->query($equipmentsSql);
                    if ($equipmentsResult->num_rows > 0) {
                        while ($equipmentRow = $equipmentsResult->fetch_assoc()) {
                            $equipmentIds[] = $equipmentRow['id'];
                        }
                    }
                }

                // Получение ID конфигураций
                if (!empty($row['configuration_name'])) {
                    $configurationNames = explode(', ', $row['configuration_name']);
                    $configurationsSql = "SELECT id, name FROM configurations WHERE name IN ('" . implode("','", $configurationNames) . "')";
                    $configurationsResult = $conn->query($configurationsSql);
                    if ($configurationsResult->num_rows > 0) {
                        while ($configurationRow = $configurationsResult->fetch_assoc()) {
                            $configurationIds[] = $configurationRow['id'];
                        }
                    }
                }

                echo "<tr>";
                echo "<td onclick='openEditModal(" . $row['model_id'] . ", \"" . htmlspecialchars($row['model_name'], ENT_QUOTES) . "\", [" . implode(',', $equipmentIds) . "], [" . implode(',', $configurationIds) . "])'>" . $row['model_name'] . "</td>";
                echo "<td onclick='openEditModal(" . $row['model_id'] . ", \"" . htmlspecialchars($row['model_name'], ENT_QUOTES) . "\", [" . implode(',', $equipmentIds) . "], [" . implode(',', $configurationIds) . "])'>" . $row['equipment_name'] . "</td>";
                echo "<td onclick='openEditModal(" . $row['model_id'] . ", \"" . htmlspecialchars($row['model_name'], ENT_QUOTES) . "\", [" . implode(',', $equipmentIds) . "], [" . implode(',', $configurationIds) . "])'>" . $row['configuration_name'] . "</td>";
                echo "<td><button class='delete-button' onclick='deleteRecord(" . $row['model_id'] . ")'>Удалить</button></td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='4'>Нет данных для отображения</td></tr>";
        }
        $conn->close();
        ?>
        </tbody>
    </table>
</div>
</body>
<script>
    document.addEventListener('DOMContentLoaded', function() {
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

    function openEditModal(modelId, modelName, selectedEquipments, selectedConfigurations) {
        // Открываем модальное окно
        document.getElementById('editModal').style.display = 'block';

        // Устанавливаем значение модели
        document.getElementById('idModel').value = modelId;
        document.getElementById('edit-model').value = modelName;

        // Сбрасываем предыдущие выбранные значения оборудования и конфигураций
        const equipmentSelect = document.getElementById('edit-equipment');
        const configurationSelect = document.getElementById('edit-configuration');

        Array.from(equipmentSelect.options).forEach(option => {
            option.selected = false;
        });

        Array.from(configurationSelect.options).forEach(option => {
            option.selected = false;
        });

        // Устанавливаем новые выбранные значения оборудования
        selectedEquipments.forEach(equipmentId => {
            let option = equipmentSelect.querySelector(`option[value="${equipmentId}"]`);
            if (option) {
                option.selected = true;
            }
        });

        // Устанавливаем новые выбранные значения конфигураций
        selectedConfigurations.forEach(configurationId => {
            let option = configurationSelect.querySelector(`option[value="${configurationId}"]`);
            if (option) {
                option.selected = true;
            }
        });
    }

    function closeEditModal() {
        document.getElementById('editModal').style.display = 'none';
        window.location.reload()
    }
</script>
<script>
    function deleteRecord(id) {
        if (confirm('Вы уверены, что хотите удалить эту запись?')) {
            fetch('./requests/delete/model.php', {
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
<script src="js/add/ajax-models.js"></script>
<script src="js/edit/ajax-models.js"></script>
</html>