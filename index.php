<?php
global $conn;
include 'database.php';

try {
    // Проверка кук и авторизации
    if (!isset($_COOKIE['login']) || !isset($_COOKIE['SessionID'])) {
        $redirect_url = "pages/auth.html?" . http_build_query(['auth' => 'failed']);
        header("Location: $redirect_url"); // Перенаправление на страницу auth.html с GET-параметром
        exit();
    }

    // Проверка пользователя в базе данных
    $login = $_COOKIE['login'];
    $session_id = $_COOKIE['SessionID'];

    $sql_check_user = "SELECT * FROM Users WHERE login = '$login' AND session_id = '$session_id'";
    $result_check_user = $conn->query($sql_check_user);

    if ($result_check_user->num_rows === 0) {
        $redirect_url = "pages/auth.html?" . http_build_query(['auth' => 'failed']);
        header("Location: $redirect_url"); // Перенаправление на страницу auth.html с GET-параметром
        exit();
    } else {
        $redirect_url = "Tasks.php";
        header("Location: $redirect_url");
        exit();
    }

    // Обработка запроса на выдачу ресурсов
    if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['res'])) {
        $resource_path = $_GET['res'];

        // Проверка на отсутствие двойных точек "../"
        if (strpos($resource_path, '..') !== false) {
            echo json_encode(["Status" => "Fail", "Message" => "Invalid resource path"]);
            exit();
        }

        // Путь к ресурсу в папке "res"
        $full_path = "res/" . $resource_path;

        // Проверка наличия файла
        if (file_exists($full_path)) {
            // Определение MIME-типа
            $mime_type = mime_content_type($full_path);

            // Проверка на наличие MIME-типа
            if ($mime_type) {
                echo "\nТип найден\n";
                header("Content-Type: $mime_type");
                readfile($full_path);
                exit();
            } else {
                echo json_encode(["Status" => "Fail", "Message" => "Cannot determine MIME type"]);
                exit();
            }
        } else {
            echo json_encode(["Status" => "Fail", "Message" => "Resource not found"]);
            exit();
        }
    }
} finally {
    
    header("Location: https://promotp.ru/pages/auth.html?auth=failed");
    if (isset($conn)) {
        $conn->close();
    }
}
?>
