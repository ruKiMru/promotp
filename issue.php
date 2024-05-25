<?php
include 'database.php';

// Проверка наличия параметра id в URL-запросе
if(isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];
    $table = isset($_GET['entity']) ? $_GET['entity'] : '';

    $sql = "";

    switch ($table) {
        case 'clients':
            $sql = "UPDATE clients SET deleted=1 WHERE id_client = ?";
            break;
        case 'issues':
            $sql = "UPDATE issues SET deleted=1 WHERE id_issue = ?";
            break;
        case 'messages':
            $sql = "UPDATE messages SET deleted=1 WHERE contactId = ?";
            break;
        case 'users':
            $sql = "UPDATE users SET deleted=1 WHERE ID = ?";
            break;
        default:
            echo "Неизвестная сущность.";
            break;
    }

    if (!empty($sql)) {
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            echo "Запись успешно помечена как удаленная.";
        } else {
            echo "Ошибка при выполнении запроса: " . $stmt->error;
        }

        $stmt->close();
    }
} else {
    echo "Неверные параметры запроса.";
}

$conn->close();
?>
