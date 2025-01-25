<?php
// Включване на файла за управление на входната логика
include "login.php";

// Проверка и стартиране на сесия, ако все още не е стартирана
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Проверка дали е натиснат бутонът за изход
if (isset($_POST['logout'])) {
    session_unset(); // Премахване на всички сесийни данни
    session_destroy(); // Унищожаване на сесията
    header("Location: index.php"); // Пренасочване към началната страница
    exit(); // Прекратяване на изпълнението на кода след пренасочване
}
?>

<!DOCTYPE html>
<html lang="bg"> <!-- Езикът на страницата е зададен като български -->
<head>
    <meta charset="UTF-8"> <!-- Задаване на UTF-8 като стандарт за кодиране -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge"> <!-- Съвместимост с Internet Explorer -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Адаптивност за мобилни устройства -->
    <title>Вход</title> <!-- Заглавие на страницата -->
    <link rel="stylesheet" href="login.css"> <!-- Линк към CSS файл за стилизиране -->
</head>
<body>
    <div class="wrapper"> <!-- Основен контейнер за формата -->
        <div class="form-box login"> <!-- Контейнер за формата за вход -->
            <h2>Вход</h2> <!-- Заглавие на формата -->
            <form action="login.php" method="post"> <!-- Форма за изпращане на данни за вход -->
                <!-- Поле за и-мейл -->
                <div class="input-box">
                    <span class="icon"><ion-icon name="mail"></ion-icon></span> <!-- Икона за е-мейл -->
                    <input type="email" id="email" name="email" required> <!-- Поле за въвеждане на е-мейл -->
                    <label>И-мейл</label> <!-- Надпис за полето -->
                </div>
                <!-- Поле за парола -->
                <div class="input-box">
                    <span class="icon"><ion-icon name="lock-closed"></ion-icon></span> <!-- Икона за парола -->
                    <input type="password" id="password" name="password" required> <!-- Поле за въвеждане на парола -->
                    <label>Парола</label> <!-- Надпис за полето -->
                </div>
                <!-- Бутон за изпращане на формата -->
                <button type="submit" class="btn" name="submit_login">Вход</button>
            </form>
        </div>
    </div>

    <!-- Включване на Ionicons за икони -->
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>
</html>
