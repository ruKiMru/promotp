<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap">
    <link rel="stylesheet" href="CSS/chatsStyle.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <title>Чаты</title>
</head>
<body>
    <header>
        <div class="header-container">
            <div class="nav-item">
                <a href="Tasks.php" class="rectangle-9 tasks" style="text-decoration: none;">
                    <img src="IMG/image-70.svg" alt="Задачи" width="30" height="30" />
                    <span class="nav-text">Задачи</span>
                </a>
            </div>
            <div class="nav-item">
                <a href="Clients.php" class="rectangle-9 client" style="text-decoration: none;">
                    <img src="IMG/image-80.svg" alt="Клиенты" width="21" height="21" />
                    <span class="nav-text">Клиенты</span>
                </a>
            </div>
            <div class="nav-item active">
                <div class="rectangle-9 chat">
                    <img src="IMG/image-90active.svg" alt="Чаты" width="28" height="24" />
                    <span class="nav-text" style="color: #5FC3D0;">Чаты</span>
                </div>
            </div>
            <div class="nav-item">
                <a href="product/products.php" class="rectangle-9 chat" style="text-decoration: none;">
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
        <div class="search-panel">
            <img src="IMG/Search.svg" alt="Search" />
            <input type="text" id="searchInput"/>
        </div>
        <div class="add-button"><span>Добавить</span></div>
    </div>
    <div class="chats-container">
      <div class="contacts-panel">
          <div class="contacts-title">Контакты</div>
          <div class="contacts-line"></div>
          <div class="contacts-list">
          </div>
      </div>
      <div class="chat-panel">
        <div class="chat-window">
        </div>
        <div class="message-input-hidden">
          <input type="text" id="messageInput" placeholder="Введите сообщение" />
          <div class="emoji-button" onclick="toggleEmojiPopup()">
              <img src="IMG/Smile.svg" alt="Smile" />
              <div class="emoji-popup">
                  <!-- Здесь вы можете разместить ваши смайлики -->
                  <span class="emoji" onclick="insertEmoji('😊')">😊</span>
                  <span class="emoji" onclick="insertEmoji('😂')">😂</span>
                  <span class="emoji" onclick="insertEmoji('❤️')">❤️</span>
                  <!-- ... -->
              </div>
          </div>
          <div class="send-button">
              <img src="IMG/Send.svg" alt="Send" />
          </div>
        </div>
      
    <script>
    $(document).ready(function() {
        var contactId = null;
        var searchParametrs = "";

        $('#searchInput').on('input', function() {
            let query = $(this).val();

            searchParametrs = query;
            updateContacts();
        });

        function allEventContacts() {
            $('.contact-link').on('click', function(event) {
                event.preventDefault(); // Предотвращаем переход по ссылке

                // Удаляем класс active-contact у всех контактов
                $('.contact-link').removeClass('active-contact');
                // Добавляем класс active-contact для выбранного контакта
                $(this).addClass('active-contact');
                $('.message-input-hidden').addClass('message-input');
                $('.message-input').removeClass('message-input-hidden');

                contactId = $(this).data('contact-id');
                $('.send-button').off('click').on('click', function(event) {
                    event.preventDefault();
                    sendMessage(contactId);
                });

                updateMessages(contactId);
            });

        }

        function sendMessage(contactId) {
            var senderId = 36; 
            var input = document.getElementById("messageInput");
            var message = input.value.trim();

            if (message !== "") {
                $.ajax({
                    url: 'chat/telegram-bot/send_messages.php',
                    type: 'POST',
                    data: {
                        contactId: contactId,
                        senderId: senderId,
                        message: message
                    },
                    success: function(response) {
                        console.log(response);
                        input.value = "";
                        updateMessages(contactId);
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            }
        }

        // Функция для обновления сообщений
        function updateMessages(contactId) {
            $.ajax({
                url: 'chat/update_messages.php',
                type: 'GET',
                data: { contact_id: contactId },
                dataType: 'json',
                success: function(response) {
                    if (response.error) {
                        console.log(response.error);
                    } else {
                        var chatWindow = $('.chat-window');
                        chatWindow.empty(); // Очищаем текущие сообщения

                        $.each(response.result, function(index, message) {
                            var messageClass = message.contact_id !== contactId ? 'outgoing' : 'incoming';
                            var avatarFill = message.sender_id ? '#C7A5A5' : '#D9D9D9';

                            var messageHtml = `
                                <div class="message ${messageClass}">
                                    <div class="avatar">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="38" height="38" viewBox="0 0 38 38" fill="none">
                                            <circle cx="19" cy="19" r="19" fill="${avatarFill}"/>
                                        </svg>
                                    </div>
                                    <div class="message-content">
                                        <p>${message.message}</p>
                                    </div>
                                </div>
                            `;
                            chatWindow.append(messageHtml);
                        });
                    }
                },
                error: function() {
                    console.log('Произошла ошибка. Попробуйте еще раз.');
                }
            });
        }

        function updateContacts() {
            $.ajax({
                url: 'chat/update_contacts.php',
                type: 'GET',
                data: { search_parametrs: searchParametrs },
                dataType: 'json',
                success: function(response) {
                    if (response.error) {
                        console.log(response.error);
                    } else {
                        var contactWindow = $('.contacts-list');
                        contactWindow.empty(); // Очищаем текущие сообщения

                        $.each(response.result, function(index, contact) {

                            var messageHtml = `
                                <a class="contact contact-link ${contactId === contact.contact_id ? "active-contact" : ""}" data-contact-id="${contact.contact_id}">
                                    <div class="avatar">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="38" height="38" viewBox="0 0 38 38" fill="none">
                                            <circle cx="19" cy="19" r="19" fill="#D9D9D9"/>
                                        </svg>
                                    </div>
                                    <div class="contact-info">
                                        <span class="contact-name">${contact.first_name} ${contact.last_name} ${contact.middle_name}</span>
                                    </div>
                                </a>
                            `;
                            contactWindow.append(messageHtml);
                        });
                        allEventContacts();
                    }
                },
                error: function() {
                    console.log('Произошла ошибка. Попробуйте еще раз.');
                }
            });
        }

        function updateTelegramMessages() {
            $.ajax({
                url: 'chat/telegram-bot/update_all_messages.php',
                type: 'POST',
                dataType: 'json',
                success: function(response) {
                },
                error: function(jqXHR, textStatus, errorThrown) {
                }
            });
        }


        updateContacts();
        allEventContacts();
        // Обновление сообщений каждые 5 секунд
        setInterval(function() {
            if(contactId !== null) {
                updateMessages(contactId);
            }

            updateTelegramMessages();
            updateContacts();
        }, 2000); // 5000 миллисекунд = 5 секунд
    });
    </script>
    
      




  <script>
    function toggleEmojiPopup() {
    const emojiPopup = document.querySelector('.emoji-popup');
    emojiPopup.style.display = (emojiPopup.style.display === 'none' || emojiPopup.style.display === '') ? 'block' : 'none';
}
function insertEmoji(emoji) {
    const messageInput = document.getElementById('messageInput');
    const cursorPos = messageInput.selectionStart;
    const textBefore = messageInput.value.substring(0, cursorPos);
    const textAfter = messageInput.value.substring(cursorPos);
    messageInput.value = textBefore + emoji + textAfter;
}

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
</body>
</html>
