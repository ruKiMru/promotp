<?php
include 'database.php';

mysqli_set_charset($conn, "utf8");

// Получение параметров из URL
$item = isset($_GET['item']) ? $_GET['item'] : null;
$id = isset($_GET['id']) ? $_GET['id'] : null;
$operation = isset($_GET['operation']) ? $_GET['operation'] : null;

// Проверка, чтобы избежать SQL-инъекций
$allowedItems = array("clients", "issues", "messages", "users");

if ($item === null) {
    // Возвращаем ошибку в формате JSON, если не указан параметр item
    $error = array("error" => "Не указан параметр item");
    echo json_encode($error, JSON_UNESCAPED_UNICODE);
} elseif (!in_array($item, $allowedItems)) {
    $error = array("error" => "Недопустимый параметр item");
    echo json_encode($error, JSON_UNESCAPED_UNICODE);
} elseif ($operation === 'insert') {
    $json_data = json_decode($_GET['data'], true);

    $sql = "";

    switch ($item) {
        case "clients":
            $sql = "INSERT INTO clients (first_name, middle_name, last_name, deleted) VALUES ({$json_data['first_name']}', '{$json_data['middle_name']}', '{$json_data['last_name']}', 0)";
            break;
        case "issues":
            $sql = "INSERT INTO issues (status, id_client, mark, deleted, name) VALUES ('{$json_data['status']}', {$json_data['id_client']}, {$json_data['mark']}, 0, '{$json_data['name']}')";
            break;
        case "messages":
            $sql = "INSERT INTO messages (contact_id, sender_id, message, deleted) VALUES ({$json_data['contact_id']}, {$json_data['sender_id']}, '{$json_data['message']}', 0)";
            break;
        case "users":
            $sql = "INSERT INTO users (login, first_name, last_name, middle_name, email, password, session_id) 
            VALUES ('{$json_data['login']}', '{$json_data['first_name']}', '{$json_data['last_name']}', '{$json_data['middle_name']}', '{$json_data['email']}', '{$json_data['password']}', '{$json_data['session_id']}')";
            break;
        default:
            // Возвращаем ошибку в формате JSON, если недопустимый параметр item
            $error = array("error" => "Недопустимый параметр item");
            echo json_encode($error, JSON_UNESCAPED_UNICODE);
            break;
    }

    if ($sql !== "") {
        $result = $conn->query($sql);

        if ($result) {
            // Возвращаем успешный ответ в формате JSON
            $success = array("success" => "Элемент успешно создан");
            echo json_encode($success, JSON_UNESCAPED_UNICODE);
        } else {
            // Возвращаем ошибку в формате JSON, если запрос не удался
            $error = array("error" => "Ошибка при создании элемента: " . $conn->error);
            echo json_encode($error, JSON_UNESCAPED_UNICODE);
        }
    }
} elseif ($operation === 'update') {
    // Разбор данных из параметра data
    $json_data = json_decode($_GET['data'], true);

    $sql = "";

    switch ($item) {
        case "clients":
            $sql = "UPDATE clients SET first_name = '{$json_data['first_name']}', middle_name = '{$json_data['middle_name']}', last_name = '{$json_data['last_name']}' WHERE id_client = $id";
            break;
        case "issues":
            $sql = "UPDATE issues SET status = '{$json_data['status']}', id_client = {$json_data['id_client']}, mark = {$json_data['mark']}, name = '{$json_data['name']}' WHERE id_issue = $id";
            break;
        case "messages":
            $sql = "UPDATE messages SET contact_id = {$json_data['contact_id']},  sender_id = {$json_data['sender_id']}, message = '{$json_data['message']}' WHERE id_messages = $id";
            break;
        case "users":
            $sql = "UPDATE users SET login = '{$json_data['login']}', first_name = '{$json_data['first_name']}', last_name = '{$json_data['last_name']}', middle_name = '{$json_data['middle_name']}', email = '{$json_data['email']}', password = '{$json_data['password']}', session_id = '{$json_data['session_id']}' WHERE ID = $id";
            break;
        default:
            // Возвращаем ошибку в формате JSON, если недопустимый параметр item
            $error = array("error" => "Недопустимый параметр item");
            echo json_encode($error, JSON_UNESCAPED_UNICODE);
            break;
    }

    if ($sql !== "") {
        $result = $conn->query($sql);

        if ($result) {
            // Возвращаем успешный ответ в формате JSON
            $success = array("success" => "Данные успешно обновлены");
            echo json_encode($success, JSON_UNESCAPED_UNICODE);
        } else {
            // Возвращаем ошибку в формате JSON, если запрос не удался
            $error = array("error" => "Ошибка при обновлении данных: " . $conn->error);
            echo json_encode($error, JSON_UNESCAPED_UNICODE);
        }
    }
} elseif ($operation === 'search') {
    $sql = "";

    switch ($item) {
        case "clients":
            $sql = "SELECT * FROM clients WHERE id_client = $id AND deleted = false";
            break;
        case "issues":
            $sql = "SELECT * FROM issues WHERE id_issue = $id AND deleted = false";
            break;
        case "messages":
            $sql = "SELECT * FROM messages WHERE id_messages = $id AND deleted = false";
            break;
        case "users":
            $sql = "SELECT * FROM users WHERE ID = $id AND deleted = false";
            break;
        default:
            // Возвращаем ошибку в формате JSON, если недопустимый параметр item
            $error = array("error" => "Недопустимый параметр item");
            echo json_encode($error, JSON_UNESCAPED_UNICODE);
            break;
    }

    if ($sql !== "") {
        $result = $conn->query($sql);

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();

            // Вывод JSON-представления элемента
            echo json_encode($row, JSON_UNESCAPED_UNICODE);
        } else {
            // В случае отсутствия элемента или если deleted=true, возвращаем ошибку
            $error = array("error" => "Item not found or deleted");
            echo json_encode($error, JSON_UNESCAPED_UNICODE);
        }
    }
} else {
    // Возвращаем ошибку в формате JSON, если операция не поддерживается
    $error = array("error" => "Неподдерживаемая операция");
    echo json_encode($error, JSON_UNESCAPED_UNICODE);
}

$conn->close();
?>
