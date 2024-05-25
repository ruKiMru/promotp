<?php
include 'database.php';

// Получение задач из базы данных
$sql = "SELECT * FROM issues WHERE deleted = 0";
$result = $conn->query($sql);

// Создаем массив для хранения задач
$tasks = array();

if ($result->num_rows > 0) {
    // Добавляем задачи в массив
    while ($row = $result->fetch_assoc()) {
        $tasks[] = $row;
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap">
    <link rel="stylesheet" href="CSS/tasksStyleKanban.css">
    <link rel="stylesheet" href="CSS/kanbanStyle.css">
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
            <div class="nav-item">
                <a href="product/products.php" class="rectangle-9 chat" style="text-decoration: none;padding:0 10px; 0 10px;">
                    <img src="IMG/image30.png" alt="Чаты" width="28" height="24" />
                    <span class="nav-text">Продукты</span>
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
            <div class="panel-item">
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
  <img src="IMG/Polygon2.svg" alt="Polygon2" id="polygon2"/>
  <div class="divider"></div>
  <img src="IMG/Search.svg" alt="Search" />
  <input type="text" id="searchInput"/>
</div>
          <div class="add-button" onclick="openAddIssuePage()"><span>Добавить</span></div>
    </div>
    <div class="task-list-container">
    <div class="task-column">
        <h3>Открытый</h3>
        <div class="tasks" id="open-tasks" ondrop="drop(event)" ondragover="allowDrop(event)">
            <?php
            // Вывод задач со статусом 1 (Открытый)
            foreach ($tasks as $task) {
                if ($task['id_status'] == 1) {
                    echo '<div class="task" id="task' . $task['id_issue'] . '" draggable="true" ondragstart="drag(event)">' . $task['name'] . '</div>';
                }
            }
            ?>
        </div>
    </div>

    <div class="task-column">
    <h3>Сделаю сегодня</h3>
    <div class="tasks" id="today-tasks" ondrop="dropToday(event)" ondragover="allowDrop(event)">
        <?php
        // Вывод задач со статусом 3 (Сегодня сделаю)
        foreach ($tasks as $task) {
            if ($task['id_status'] == 3 && $task['id_mark'] == 1) {
                echo '<div class="task" id="task' . $task['id_issue'] . '" draggable="true" ondragstart="drag(event)">' . $task['name'] . '</div>';
            }
        }
        ?>
    </div>
</div>

<div class="task-column">
    <h3>Сделаю завтра</h3>
    <div class="tasks" id="tomorrow-tasks" ondrop="dropTomorrow(event)" ondragover="allowDrop(event)">
        <?php
        // Вывод задач со статусом 3 (Сделаю завтра)
        foreach ($tasks as $task) {
            if ($task['id_status'] == 3 && $task['id_mark'] == 2) {
                echo '<div class="task" id="task' . $task['id_issue'] . '" draggable="true" ondragstart="drag(event)">' . $task['name'] . '</div>';
            }
        }
        ?>
    </div>
</div>

<div class="task-column">
    <h3>Сделаю на неделе</h3>
    <div class="tasks" id="week-tasks" ondrop="dropWeek(event)" ondragover="allowDrop(event)">
        <?php
        // Вывод задач со статусом 3 (Сделаю на неделе)
        foreach ($tasks as $task) {
            if ($task['id_status'] == 3 && $task['id_mark'] == 3) {
                echo '<div class="task" id="task' . $task['id_issue'] . '" draggable="true" ondragstart="drag(event)">' . $task['name'] . '</div>';
            }
        }
        ?>
    </div>
</div>

<div class="task-column">
    <h3>Закрытые</h3>
    <div class="tasks" id="closed-tasks" ondrop="dropClosed(event)" ondragover="allowDrop(event)">
        <?php
        // Вывод задач со статусом 2 (Закрытые)
        foreach ($tasks as $task) {
            if ($task['id_status'] == 2) {
                echo '<div class="task" id="task' . $task['id_issue'] . '" draggable="true" ondragstart="drag(event)">' . $task['name'] . '</div>';
            }
        }
        ?>
    </div>
</div>
<script>
    function allowDrop(ev) {
        // Проверяем, является ли элемент перетаскиваемым из столбца "Закрытые"
        if (ev.target.parentElement.id === 'closed-tasks') {
            ev.preventDefault(); // Отменяем перетаскивание
        } else {
            ev.preventDefault();
        }
    }

    function drag(ev) {
        ev.dataTransfer.setData("text", ev.target.id);
    }

    function dropToday(ev) {
        ev.preventDefault();
        var data = ev.dataTransfer.getData("text");
        ev.target.appendChild(document.getElementById(data));

        // AJAX запрос на обновление статуса и метки
        var taskId = data.substring(4); // Получаем id задачи из id элемента
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                console.log("Статус и метка обновлены");
            }
        };
        xhttp.open("GET", "update_task.php?task_id=" + taskId + "&status=3&mark=1", true);
        xhttp.send();
    }

    function dropTomorrow(ev) {
        ev.preventDefault();
        var data = ev.dataTransfer.getData("text");
        ev.target.appendChild(document.getElementById(data));

        // AJAX запрос на обновление статуса и метки
        var taskId = data.substring(4); // Получаем id задачи из id элемента
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                console.log("Статус и метка обновлены");
            }
        };
        xhttp.open("GET", "update_task.php?task_id=" + taskId + "&status=3&mark=2", true);
        xhttp.send();
    }

    function dropWeek(ev) {
        ev.preventDefault();
        var data = ev.dataTransfer.getData("text");
        ev.target.appendChild(document.getElementById(data));

        // AJAX запрос на обновление статуса и метки
        var taskId = data.substring(4); // Получаем id задачи из id элемента
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                console.log("Статус и метка обновлены");
            }
        };
        xhttp.open("GET", "update_task.php?task_id=" + taskId + "&status=3&mark=3", true);
        xhttp.send();
    }

    function dropClosed(ev) {
        ev.preventDefault();
        var data = ev.dataTransfer.getData("text");
        var taskElement = document.getElementById(data);
        ev.target.appendChild(taskElement);

        // Делаем задачу не перетаскиваемой
        taskElement.draggable = false;

        // AJAX запрос на обновление статуса и метки
        var taskId = data.substring(4); // Получаем id задачи из id элемента
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                console.log("Статус и метка обновлены");
            }
        };
        xhttp.open("GET", "update_task.php?task_id=" + taskId + "&status=2&mark=3", true);
        xhttp.send();
    }
</script>
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
    var kanbanPanel = document.getElementById("list");
    var calendarPanel = document.getElementById("calendar");
    var ganttPanel = document.getElementById("gant");

    // Добавляем обработчики событий клика для каждой панели
    kanbanPanel.addEventListener("click", function() {
        window.location.href = "Tasks.php";
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
  
</html>
