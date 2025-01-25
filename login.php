<?php
// Включване на файла за връзка с базата данни
include "connection.php";

// Стартиране на сесията
session_start();

// Проверка дали формата за вход е изпратена
if (isset($_POST['submit_login'])) {
    // Получаване на въведените имейл и парола
    $email = $_POST['email'];
    $pswd = $_POST['password'];

    // Проверка за администратор
    $adminResult = mysqli_query($conn, "SELECT * FROM administrators WHERE email='$email' AND password='$pswd'");
    if (mysqli_num_rows($adminResult) > 0) {
        // Ако има съвпадение, настройва сесийните променливи за администратора
        $row = mysqli_fetch_assoc($adminResult);
        $_SESSION["loggedIn"] = true;
        $_SESSION["role"] = "admin"; // Роля: администратор
        $_SESSION["name"] = $row["name"]; // Име на потребителя
        $_SESSION['user_id'] = $row["id"]; // Идентификатор на потребителя
        header('Location: admin_dashboard.php'); // Пренасочване към административното табло
        exit();
    }

    // Проверка за учител
    $teacherResult = mysqli_query($conn, "SELECT * FROM teachers WHERE email='$email' AND password='$pswd'");
    if (mysqli_num_rows($teacherResult) > 0) {
        // Ако има съвпадение, настройва сесийните променливи за учителя
        $row = mysqli_fetch_assoc($teacherResult);
        $_SESSION["loggedIn"] = true;
        $_SESSION["role"] = "teacher"; // Роля: учител
        $_SESSION["name"] = $row["name"]; // Име на потребителя
        $_SESSION["school_id"] = $row["school_id"]; // Идентификатор на училището
        header('Location: home.php'); // Пренасочване към началната страница
        exit();
    }

    // Проверка за ученик
    $stmt = $conn->prepare("SELECT * FROM students WHERE email = ? AND password = ?"); // Подготвена заявка за защита от SQL инжекции
    $stmt->bind_param("ss", $email, $pswd); // Свързване на параметрите (имейл и парола)
    $stmt->execute();
    $studentResult = $stmt->get_result();

    if ($studentResult->num_rows > 0) {
        // Ако има съвпадение, настройва сесийните променливи за ученика
        $row = $studentResult->fetch_assoc();
        $_SESSION["loggedIn"] = true;
        $_SESSION["role"] = "student"; // Роля: ученик
        $_SESSION["name"] = $row["name"]; // Име на ученика
        $_SESSION["class_id"] = $row["class_id"]; // Идентификатор на класа
        $_SESSION["student_id"] = $row["id"]; // Идентификатор на ученика
        header('Location: student_profile.php'); // Пренасочване към профила на ученика
        exit();
    }

    // Ако няма съвпадение за никоя роля
    header('Location: index.php'); // Пренасочване обратно към началната страница (вероятно с грешка за неуспешен вход)
    exit();
} // Край на проверката за изпратена форма
?>
