<?php
include 'database.php';

// Получение userId из параметра запроса
$userId = $_GET['userId'];

// Запрос к базе данных для получения данных пользователя по userId
$sql = "SELECT first_name, last_name, middle_name, email, login FROM users WHERE ID = $userId";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $userData = $result->fetch_assoc();
} else {
    echo "Пользователь не найден";
    exit;
}

$conn->close();

// Обработка POST-запроса после отправки формы
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Получение обновленных данных из формы
    $updatedLogin = $_POST["login"];
    $updatedFirstName = $_POST["first_name"];
    $updatedLastName = $_POST["last_name"];
    $updatedMiddleName = $_POST["middle_name"];
    $updatedEmail = $_POST["email"];


    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Обновление данных пользователя в базе
    $updateUserSql = "UPDATE users SET login = '$updatedLogin', first_name = '$updatedFirstName', last_name = '$updatedLastName', middle_name = '$updatedMiddleName', email = '$updatedEmail' WHERE ID = $userId";

    if ($conn->query($updateUserSql) === TRUE) {
        echo "Данные пользователя успешно обновлены";
    } else {
        echo "Ошибка при обновлении данных пользователя: " . $conn->error;
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Contact Information</title>
  <link rel="stylesheet" href="CSS/userEdit.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap">
</head>
<body>
  <div class="container">
    <div class="icons">
      <a href="NewClientAdmin.php?admin_key=ed2f1a7b93c4d8e7a6b1d5f2c8e3d4b9a7c8e2d3b4a9c6d5f2c8e3d4b9a7c8e2d3b4a9c6d5f2c8e3d4b9a7c8e2d3b4a9c6d5f2c8e3d4b9a7c8e2d3b4a9c6d5f2c8e3d4b9a7c8e2d3b4a9c6d5f2c8e3d4b9a7c8e2d3b484359345934958349583498593485934853948593485934859gjdfgd;kfjgd;fkjg34534645645645644"><img class="cross-icon" src="IMG/cross.svg" alt="Cross Icon"></a>
    </div>
    <div class="content">
      <img class="logo" src="IMG/image-wind-user.svg" alt="Logo">
      <div class="chat-info">
      </div>
    </div>
    <div class="contact-info">
      <form method="post" action="">
      <div class="input-group">
          <label for="">Логин</label>
          <input type="text" id="login" name="login" value="<?php echo $userData['login']; ?>">
        </div>
        <div class="input-group">
          <label for="company">Имя</label>
          <input type="text" id="first_name" name="first_name" value="<?php echo $userData['first_name']; ?>">
        </div>

        <div class="input-group">
          <label for="contact">Фамилия</label>
          <input type="text" id="last_name" name="last_name" value="<?php echo $userData['last_name']; ?>">
        </div>

        <div class="input-group">
          <label for="phone">Отчество </label>
          <input type="text" id="middle_name" name="middle_name" value="<?php echo $userData['middle_name']; ?>">
        </div>

        <div class="input-group">
          <label for="email">E-mail Адресс:</label>
          <input type="text" id="email" name="email" value="<?php echo $userData['email']; ?>">
        </div>
        <button type="submit" class="save-button">Сохранить</button>
      </form>
    </div>
  </div>
  <script>
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        echo "alert('Данные пользователя успешно обновлены');";
        echo "window.location = 'NewClientAdmin.php?admin_key=ed2f1a7b93c4d8e7a6b1d5f2c8e3d4b9a7c8e2d3b4a9c6d5f2c8e3d4b9a7c8e2d3b4a9c6d5f2c8e3d4b9a7c8e2d3b4a9c6d5f2c8e3d4b9a7c8e2d3b4a9c6d5f2c8e3d4b9a7c8e2d3b4a9c6d5f2c8e3d4b9a7c8e2d3b484359345934958349583498593485934853948593485934859gjdfgd;kfjgd;fkjg34534645645645644';";
    }
    ?>
</script>
</body>
</html>
