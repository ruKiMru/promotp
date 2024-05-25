<?php
include 'database.php';
// Запрос к базе данных
$sql = "SELECT issues.name AS issue_name, status.id_status, status.status_name, clients.first_name, marks.name AS mark_name
        FROM issues
        LEFT JOIN status ON issues.id_status = status.id_status
        LEFT JOIN clients ON issues.id_client = clients.id_client
        LEFT JOIN marks ON issues.id_mark = marks.id_mark
        WHERE issues.deleted = 0";

$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap">
    <link rel="stylesheet" href="CSS/tasksStyle.css">
    <title>Задачи</title>
</head>
<body>
    <header>
        <div class="header-container">
            <div class="nav-item active">
                <div class="rectangle-9">
                    <img src="IMG/image-70active.svg" alt="Задачи" width="30" height="30" />
                    <span class="nav-text">Задачи</span>
                </div>
            </div>
            <div class="nav-item">
              <a href="Clients.php" class="rectangle-9 client" style="text-decoration: none;">
                    <img src="IMG/image-80.svg" alt="Клиенты" width="21" height="21" />
                    <span class="nav-text">Клиенты</span>
              </a>
            </div>
            <div class="nav-item">
              <a href="Chats.php" class="rectangle-9 chat" style="text-decoration: none;">
                    <img src="IMG/image-90.svg" alt="Чаты" width="28" height="24" />
                    <span class="nav-text">Чаты</span>
              </a>
            </div>
        </div>
        <div class="login-avatar-container">
            <div class="login-text">Логин</div>
            <div class="avatar">
                <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 26 26" fill="none">
                    <circle cx="13" cy="13" r="13" fill="#C7A5A5" />
                </svg>
            </div>
        </div>
    </header>
    <div class="header-content">
        <div class="second-panel">
            <div class="panel-item">
                <span>Список</span>
            </div>
            <div class="panel-item" id="kanban">
                <span>Канбан</span>
            </div>
            <div class="panel-item" id="calendar">
                <span>Календарь</span>
            </div>
            <div class="panel-item" id="gant">
                <span>Гант</span>
            </div>
        </div>
        <div class="spacer"></div>
        <div class="search-panel">
    <div class="custom-select-wrapper">
        <img src="IMG/Polygon2.svg" alt="Select" id="customSelect">
        <select id="statusFilter">
            <option value="all">Все статусы</option>
            <?php
                // Вывод статусов из базы данных
                $statusSql = "SELECT * FROM status";
                $statusResult = $conn->query($statusSql);

                if ($statusResult->num_rows > 0) {
                    while($statusRow = $statusResult->fetch_assoc()) {
                        echo "<option value='" . $statusRow['id_status'] . "'>" . $statusRow['status_name'] . "</option>";
                    }
                }
            ?>
        </select>
    </div>
    <div class="divider"></div>
    <img src="IMG/Search.svg" alt="Search" />
    <input type="text" id="keywordInput"/>
</div>

<style>
    /* Скрываем стандартный селект */
    #statusFilter {
        display: none;
    }
</style>

<script>
    // Получаем ссылки на элементы
    const customSelect = document.getElementById('customSelect');
    const statusFilter = document.getElementById('statusFilter');

    // Добавляем обработчик события при клике на изображение
    customSelect.addEventListener('click', function() {
        // Открываем или закрываем выпадающий список при клике на изображение
        statusFilter.style.display = statusFilter.style.display === 'none' ? 'block' : 'none';
    });
</script>

<div class="add-button" onclick="openAddIssuePage()"><span>Добавить</span></div>
    </div>
    <div class="task-list-container">
        <table class="task-table">
          <thead>
            <tr>
              <th>Имя задачи</th>
              <th>Статус</th>
              <th>Клиент</th>
              <th>Метки</th>
            </tr>
          </thead>
          <tbody>
            <?php
            if ($result->num_rows > 0) {
                // Вывод данных каждой строки в таблицу
                while($row = $result->fetch_assoc()) {
                  echo "<tr>";
                  echo "<td>" . $row['issue_name'] . "</td>";
                  echo "<td data-status-id='" . $row['id_status'] . "'>" . $row['status_name'] . "</td>";
                  echo "<td>" . $row['first_name'] . "</td>";
                  echo "<td>" . $row['mark_name'] . "</td>";
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
  // Функция для открытия страницы addIssue.php в новом окне или модальном окне
  function openAddIssuePage() {
    var left = (screen.width - 450) / 2; // Вычисляем положение по горизонтали
    var top = (screen.height - 510) / 2; // Вычисляем положение по вертикали
    var params = 'width=450,height=510,scrollbars=yes,left=' + left + ',top=' + top;
    window.open('addIssue.php', '_blank', params);
  }
    // Если вы хотите открыть в модальном окне, используйте библиотеку для модальных окон, например, Bootstrap Modal или другие
    // Пример открытия в Bootstrap Modal:
    // $('#addIssueModal').modal('show');
</script>

<script>
  document.addEventListener("DOMContentLoaded", function() {
    // Получаем ссылки на все панели
    var kanbanPanel = document.getElementById("kanban");
    var calendarPanel = document.getElementById("calendar");
    var ganttPanel = document.getElementById("gant");

    // Добавляем обработчики событий клика для каждой панели
    kanbanPanel.addEventListener("click", function() {
        window.location.href = "TasksKanban.php";
    });

    calendarPanel.addEventListener("click", function() {
        window.location.href = "TasksCalendar.php";
    });

    ganttPanel.addEventListener("click", function() {
        window.location.href = "TasksGant.php";
    });
});
</script>
<script>
  document.addEventListener('DOMContentLoaded', function() {
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
  <script>
    document.addEventListener('DOMContentLoaded', function() {
    var statusFilter = document.getElementById('statusFilter');
    var taskRows = document.querySelectorAll('.task-table tbody tr');

    statusFilter.addEventListener('change', function() {
        var selectedStatusId = statusFilter.value;

        // Показываем или скрываем строки таблицы в зависимости от выбранного статуса
        taskRows.forEach(function(row) {
            var statusCell = row.querySelector('td:nth-child(2)');
            var statusId = statusCell.dataset.statusId;

            if (selectedStatusId === 'all' || statusId === selectedStatusId) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
});
  </script>
  <script>
document.addEventListener('DOMContentLoaded', function() {
    var statusFilter = document.getElementById('statusFilter');
    var keywordInput = document.getElementById('keywordInput');
    var taskRows = document.querySelectorAll('.task-table tbody tr');

    // Функция для фильтрации строк таблицы по статусу и ключевому слову
    function filterRows() {
        var selectedStatusId = statusFilter.value;
        var keyword = keywordInput.value.trim().toLowerCase();

        taskRows.forEach(function(row) {
            var cells = row.querySelectorAll('td');
            var isVisible = false;

            // Проверяем каждую ячейку в строке на соответствие ключевому слову
            cells.forEach(function(cell) {
                var cellText = cell.textContent.toLowerCase();

                // Если хотя бы одно поле содержит ключевое слово и статус соответствует, делаем строку видимой
                if ((cellText.includes(keyword) || keyword === '') && (selectedStatusId === 'all' || cell.dataset.statusId === selectedStatusId)) {
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

    // Обработчики событий для изменения статуса фильтра и ввода ключевого слова
    statusFilter.addEventListener('change', filterRows);
    keywordInput.addEventListener('input', filterRows);
});
</script>
</html>
