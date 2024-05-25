document.addEventListener('DOMContentLoaded', function() {
    var form = document.querySelector('form');
    form.addEventListener('submit', function(event) {
        event.preventDefault(); // Предотвращаем отправку формы по умолчанию

        var formData = new FormData(form); // Получаем данные формы

        // Отправляем AJAX запрос
        var xhr = new XMLHttpRequest();
        xhr.open('POST', '../login.php', true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    // Обработка ответа от сервера
                    var response = xhr.responseText;
                    if (response.trim() === 'success') {
                        window.location.href = '/Tasks.php'; // Перенаправление на страницу Tasks.html
                    } else if (response.includes('Неверный логин или пароль') || response.includes('Пользователь не найден')) {
                        alert(response); // Показываем сообщение об ошибке
                    } else {
                        alert('Произошла ошибка при обработке вашего запроса.');
                    }
                } else {
                    alert('Произошла ошибка при отправке запроса на сервер.');
                }
            }
        };
        xhr.send(formData); // Отправляем данные формы
    });
});
