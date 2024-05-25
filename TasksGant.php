<?php
include 'database.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap">
    <link rel="stylesheet" href="CSS/tasksStyleGant.css">
    <link rel="stylesheet" href="CSS/gantStyle.css">
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
            <div class="panel-item" id="list">
                <span>Список</span>
            </div>
            <div class="panel-item" id="kanban">
                <span>Канбан</span>
            </div>
            <div class="panel-item" id="calendar">
                <span>Календарь</span>
            </div>
            <div class="panel-item">
                <span>Гант</span>
            </div>
        </div>
        <div class="spacer"></div>
        <div class="search-panel">
        <img src="IMG/Polygon2.svg" alt="Polygon2" id="polygon2"/>
        <div class="divider"></div>
        <img src="IMG/Search.svg" alt="Search" />
        <input type="text" id="searchInput">
    </div>
    <div class="add-button" onclick="openAddIssuePage()"><span>Добавить</span></div>
    </div>
    <div class="task-list-container">
    <div class="gantt-chart">
        <table class="gantt-table">
            <thead>
                <tr>
                    <th>Задача</th>
                    <th>Начало</th>
                    <th>Конец</th>
                    <th>Продолжительность</th>
                </tr>
            </thead>
            <tbody id="taskTableBody">
                <?php
                $sql = "SELECT * FROM issues WHERE start_time IS NOT NULL AND completion_time IS NOT NULL";
                $result = $conn->query($sql);

                // Найти максимальную продолжительность
                $maximum = 0;
                while($row = $result->fetch_assoc()) {
                    $start_time = strtotime($row["start_time"]);
                    $completion_time = strtotime($row["completion_time"]);
                    $duration = $completion_time - $start_time;
                    if ($duration > $maximum) {
                        $maximum = $duration;
                    }
                }

                // Вернуть указатель на начало результирующего набора данных
                $result->data_seek(0);

                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td class='task-name'>" . $row["name"] . "</td>";
                        echo "<td>" . $row["start_time"] . "</td>";
                        echo "<td>" . $row["completion_time"] . "</td>";
                        echo "<td>";
                        // Вычисляем ширину полосы задачи
                        $start_time = strtotime($row["start_time"]);
                        $completion_time = strtotime($row["completion_time"]);
                        $duration = $completion_time - $start_time;
                        $width = ($duration / $maximum) * 100; // Вычисляем ширину в процентах
                        // Ограничиваем ширину 100%
                        $width = min($width, 100);
                        echo "<div class='task-bar' style='width: " . $width . "%;'></div>";
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "0 результатов";
                }
                $conn->close();
                ?>
            </tbody>
        </table>
    </div>
</div>
</body>
<script>
  function openAddIssuePage() {
    var left = (screen.width - 450) / 2; // Вычисляем положение по горизонтали
    var top = (screen.height - 510) / 2; // Вычисляем положение по вертикали
    var params = 'width=450,height=510,scrollbars=yes,left=' + left + ',top=' + top;
    window.open('addIssue.php', '_blank', params);
  }
</script>
<script>
  // Получаем ссылку на элементы
  var polygon2 = document.getElementById('polygon2');
  
  // Добавляем обработчик события клика на изображение Polygon2
  polygon2.addEventListener('click', function() {
    // Устанавливаем текст в поле ввода
    searchInput.value = "Фильтрация работает в поле поиска";

    // Через 3 секунды очищаем поле ввода
    setTimeout(function() {
      searchInput.value = '';
    }, 2000);
  });
</script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const searchInput = document.getElementById('searchInput');
        const taskTableBody = document.getElementById('taskTableBody');
        const rows = taskTableBody.getElementsByTagName('tr');

        searchInput.addEventListener('input', function() {
            const searchText = searchInput.value.toLowerCase().trim();

            for (let i = 0; i < rows.length; i++) {
                const taskName = rows[i].querySelector('.task-name').textContent.toLowerCase();
                const startTime = rows[i].querySelectorAll('td')[1].textContent.toLowerCase();
                const endTime = rows[i].querySelectorAll('td')[2].textContent.toLowerCase();

                if (taskName.includes(searchText) || startTime.includes(searchText) || endTime.includes(searchText)) {
                    rows[i].style.display = '';
                } else {
                    rows[i].style.display = 'none';
                }
            }
        });
    });
</script>
<script>
  document.addEventListener("DOMContentLoaded", function() {
    // Получаем ссылки на все панели
    var kanbanPanel = document.getElementById("list");
    var calendarPanel = document.getElementById("kanban");
    var ganttPanel = document.getElementById("calendar");

    // Добавляем обработчики событий клика для каждой панели
    kanbanPanel.addEventListener("click", function() {
        window.location.href = "Tasks.php";
    });

    calendarPanel.addEventListener("click", function() {
        window.location.href = "TasksKanban.php";
    });

    ganttPanel.addEventListener("click", function() {
        window.location.href = "TasksCalendar.php";
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
  
</html>
