<?php
include 'database.php';

if (isset($_GET['task_id']) && isset($_GET['status']) && isset($_GET['mark'])) {
    $task_id = $_GET['task_id'];
    $status = $_GET['status'];
    $mark = $_GET['mark'];

    $task_str = "";
    if (isset($_GET['task_name']))
        $task_str = ", name = '" . $_GET['task_name'] . "' ";

    $desc_str = "";
    if (isset($_GET['description']))
        $desc_str = ", description = '". $_GET['description'] . "' ";

    // Обновление статуса и метки задачи
    $update_query = "UPDATE issues SET id_status = $status, id_mark = $mark " .
        $task_str . $desc_str . " WHERE id_issue = $task_id";
    if ($conn->query($update_query) === TRUE) {
        // Установка часового пояса Екатеринбурга
        date_default_timezone_set('Asia/Yekaterinburg');
        
        // Получение текущего времени в формате MySQL
        $current_time = date('Y-m-d H:i:s');

        // Если задача была перенесена в столбец "Сегодня сделаю", "Сделаю завтра", "Сделаю на недели"
        if ($status == 3 && ($mark == 1 || $mark == 2 || $mark == 3)) {
            $time_update_query = "UPDATE issues SET start_time = '$current_time' WHERE id_issue = $task_id AND start_time IS NULL";
            $conn->query($time_update_query);
        }
        // Если задача была перенесена в столбец "Закрытые"
        elseif ($status == 2 && $mark == 3) {
            $time_update_query = "UPDATE issues SET completion_time = '$current_time' WHERE id_issue = $task_id AND completion_time IS NULL";
            $conn->query($time_update_query);
        }
        echo "Задача успешно обновлена.";
    } else {
        echo "Ошибка при обновлении статуса и метки: " . $conn->error;
    }

    $conn->close();
} else {
    echo "Некоторые параметры не были переданы.";
}
?>
