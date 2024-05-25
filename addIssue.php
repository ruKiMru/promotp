<?php
// Подключение к базе данных
include 'database.php';

// Переменная для хранения сообщения об успешном добавлении задачи
$success_message = "";

// Проверяем, была ли отправлена форма
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Получаем данные из формы
    $name = $_POST['name'];
    $id_client = $_POST['client'];

    // Получаем ID пользователя из куки
    if(isset($_COOKIE['ID'])) {
        $id_user = $_COOKIE['ID'];
    } else {
        // Если ID пользователя не найден в куки, выполнение скрипта прерывается
        exit("ID пользователя не найден");
    }
    
    // Устанавливаем значение статуса по умолчанию (1)
    $id_status = 1;

    // Проверяем, была ли уже добавлена такая задача
    $sql_check = "SELECT * FROM issues WHERE name = '$name' AND id_client = '$id_client'";
    $result_check = $conn->query($sql_check);

    if ($result_check->num_rows > 0) {
        $success_message = "Эта задача уже была добавлена ранее!";
    } else {
        // Подготавливаем запрос для вставки данных
        $sql = "INSERT INTO issues (name, id_client, id_status, id_user) VALUES ('$name', '$id_client', '$id_status', '$id_user')";

        if ($conn->query($sql) === TRUE) {
            $success_message = "Задача успешно добавлена!";
            // Добавляем JavaScript для закрытия страницы после 1 секунды
            echo "<script>
                    setTimeout(function(){
                        window.close();
                    }, 2000);
                  </script>";
        } else {
            echo "Ошибка: " . $sql . "<br>" . $conn->error;
        }
    }
    // Закрываем соединение с базой данных
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Новая задача</title>
  <link rel="stylesheet" href="CSS/addIssueStyle.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap">
  <style>
    /* Добавленные стили для алерта */
    .alert {
      display: <?php echo $success_message ? 'block' : 'none'; ?>;
      background-color: #4CAF50;
      color: white;
      text-align: center;
      padding: 14px;
      margin-bottom: 20px;
      border-radius: 5px;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="icons">
      <a href="viewUsersHandler.php"><img class="cross-icon" src="IMG/cross.svg" alt="Cross Icon"></a>
    </div>
    <div class="content">
      <img class="logo" src="IMG/task.svg" alt="Logo">
      <div class="chat-info">
      </div>
    </div>
    <div class="contact-info">
      <form method="post" action="">
        <div class="alert" id="successAlert"><?php echo $success_message; ?></div>
        <div class="input-group">
          <label for="name">Имя задачи</label>
          <input type="text" id="name" name="name">
        </div>
        <div class="input-group">
          <label for="client">Клиент</label>
          <select id="client" name="client">
            <?php
              // Запрос для получения списка клиентов
              $sql = "SELECT id_client, CONCAT(first_name, ' ', middle_name, ' ', last_name) AS full_name FROM clients WHERE deleted = 0";
              $result = $conn->query($sql);

              // Вывод списка клиентов в комбобокс
              if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                  echo "<option value='" . $row["id_client"] . "'>" . $row["full_name"] . "</option>";
                }
              }
              $conn->close();
            ?>
          </select>
        </div>
        <button type="submit" class="save-button">Добавить</button>
      </form>
    </div>
  </div>
</body>
</html>
