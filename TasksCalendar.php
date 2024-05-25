<?php
include 'database.php';

// Выбор данных о задачах из базы данных
$sql = "SELECT name, start_time, completion_time FROM issues";
$result = $conn->query($sql);

// Создание массива для хранения данных о задачах
$tasks = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $tasks[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap">
    <link rel="stylesheet" href="CSS/tasksStyleCalendar.css">
    <link rel="stylesheet" href="CSS/calendarStyle.css">
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
            <div class="panel-item">
                <span>Календарь</span>
            </div>
            <div class="panel-item" id="gant">
                <span>Гант</span>
            </div>
        </div>
        <div class="spacer"></div>
        <div class="search-panel">
  <img src="IMG/Polygon2.svg" alt="Polygon2" id="polygon2"/>
  <div class="divider"></div>
  <img src="IMG/Search.svg" alt="Search" />
  <input type="text" id="searchInput"/>
</div>
          <div class="add-button" onclick="openAddIssuePage()"><span>Добавить</span></div>
    </div>
    <div class="task-list-container">
        <div id="calendar" class="calendar">
            <!-- Календарь будет отображаться здесь -->
            <?php
            // Вставляем данные о задачах в календарь
            echo "<script>";
            echo "const tasks = " . json_encode($tasks) . ";"; // Передача данных о задачах в JavaScript
            echo "</script>";
            ?>
        </div>
    </div>
</body>
<script>
  // Получаем ссылку на элементы
  var polygon2 = document.getElementById('polygon2');
  var searchInput = document.getElementById('searchInput');

  // Добавляем обработчик события клика на изображение Polygon2
  polygon2.addEventListener('click', function(event) {
    // Останавливаем всплытие события, чтобы оно не действовало на внешние обработчики
    event.stopPropagation();
    
    // Устанавливаем текст в поле ввода
    searchInput.value = "Фильтрация работает в списке!";

    // Через 3 секунды очищаем поле ввода
    setTimeout(function() {
      searchInput.value = '';
    }, 3000);
  });

  // Добавляем обработчик события клика для всего документа
  document.addEventListener('click', function(event) {
    // Если клик не на поле ввода, очищаем его
    if (event.target !== searchInput) {
      searchInput.value = '';
    }
  });

  // Добавляем обработчик события ввода в поле ввода
  searchInput.addEventListener('input', function() {
    // Устанавливаем текст в поле ввода при любом вводе
    searchInput.value = "Фильтрация работает в списке!";
  });
</script>
<script>
        // Получение ссылки на элемент календаря
        const calendarElement = document.getElementById('calendar');

        // Функция для генерации календаря для указанного месяца и года
function generateCalendar(year, month) {
    const daysInMonth = new Date(year, month + 1, 0).getDate();
    const firstDayOfMonth = new Date(year, month, 1).getDay();

    let html = '<div class="month-header">' +
        '<button onclick="prevMonth()">Предыдущий</button>' +
        '<h2>' + new Date(year, month).toLocaleString('default', {
            month: 'long',
            year: 'numeric'
        }) + '</h2>' +
        '<button onclick="nextMonth()">Следующий</button>' +
        '</div>';

    html += '<div class="days">';
    for (let i = 0; i < firstDayOfMonth; i++) {
        html += '<div class="empty"></div>';
    }
    for (let day = 1; day <= daysInMonth; day++) {
        const dayOfWeek = new Date(year, month, day).toLocaleString('default', {
            weekday: 'short'
        });

        // Проверяем, есть ли задачи на этот день
        const tasksForDay = tasks.filter(function(task) {
            const taskDate = new Date(task.start_time);
            return taskDate.getFullYear() === year && taskDate.getMonth() === month && taskDate.getDate() === day;
        });

        // Присваиваем класс task-day, если есть задачи на этот день
        const dayClass = tasksForDay.length > 0 ? 'day task-day' : 'day';

        html += '<div class="' + dayClass + '">' + day + '(' + dayOfWeek + ')';
        // Вставляем данные о задачах для текущего дня
        tasksForDay.forEach(function(task) {
            html += '<div class="task">' + 'Название: ' + task.name + ', Старт: ' + task.start_time + ', Финиш: ' + task.completion_time + '</div>';
        });
        html += '</div>';
    }
    html += '</div>';

    calendarElement.innerHTML = html;
}
        // Функция для отображения предыдущего месяца
        function prevMonth() {
            currentMonth--;
            if (currentMonth < 0) {
                currentYear--;
                currentMonth = 11;
            }
            generateCalendar(currentYear, currentMonth);
        }

        // Функция для отображения следующего месяца
        function nextMonth() {
            currentMonth++;
            if (currentMonth > 11) {
                currentYear++;
                currentMonth = 0;
            }
            generateCalendar(currentYear, currentMonth);
        }

        // Получение текущей даты
        const currentDate = new Date();
        let currentYear = currentDate.getFullYear();
        let currentMonth = currentDate.getMonth();

        // Генерация календаря для текущего месяца
        generateCalendar(currentYear, currentMonth);
    </script>
    <script>
  function openAddIssuePage() {
    var left = (screen.width - 450) / 2; // Вычисляем положение по горизонтали
    var top = (screen.height - 510) / 2; // Вычисляем положение по вертикали
    var params = 'width=450,height=510,scrollbars=yes,left=' + left + ',top=' + top;
    window.open('addIssue.php', '_blank', params);
  }
</script>
<script>
  document.addEventListener("DOMContentLoaded", function() {
    // Получаем ссылки на все панели
    var kanbanPanel = document.getElementById("list");
    var calendarPanel = document.getElementById("kanban");
    var ganttPanel = document.getElementById("gant");

    // Добавляем обработчики событий клика для каждой панели
    kanbanPanel.addEventListener("click", function() {
        window.location.href = "Tasks.php";
    });

    calendarPanel.addEventListener("click", function() {
        window.location.href = "TasksKanban.php";
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
</html>
