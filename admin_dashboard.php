<?php
session_start();
require 'connection.php';  // Връзка към MySQL
include 'functions.php';
include 'header.php';

// Активиране на показване на грешки (за дебъгинг)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Добавяне на училище
if (isset($_POST['add_school'])) {
    $school_name = trim($_POST['school_name']);
    if (!empty($school_name)) {
        $stmt = $conn->prepare("INSERT INTO schools (name) VALUES (?)");
        $stmt->bind_param("s", $school_name);
        if ($stmt->execute()) {
            $_SESSION['message'] = "Училището беше добавено успешно.";
        } else {
            $_SESSION['error'] = "Грешка при добавяне на училище: " . $stmt->error;
        }
        $stmt->close();
    }
    header("Location: ".$_SERVER['PHP_SELF']);
    exit();
}

// Изтриване на училище
if (isset($_POST['delete_school'])) {
    $school_id = intval($_POST['school_id']);

    // Трием учениците, класовете, накрая училището
    $stmt1 = $conn->prepare("
        DELETE students
        FROM students
        JOIN classes ON students.class_id = classes.id
        WHERE classes.school_id = ?
    ");
    $stmt1->bind_param("i", $school_id);
    $stmt1->execute();
    $stmt1->close();

    $stmt2 = $conn->prepare("DELETE FROM classes WHERE school_id=?");
    $stmt2->bind_param("i", $school_id);
    $stmt2->execute();
    $stmt2->close();

    $stmt3 = $conn->prepare("DELETE FROM schools WHERE id=?");
    $stmt3->bind_param("i", $school_id);
    if ($stmt3->execute()) {
        $_SESSION['message'] = "Училището беше успешно изтрито.";
    } else {
        $_SESSION['error'] = "Грешка при изтриване на училище: " . $stmt3->error;
    }
    $stmt3->close();

    header("Location: ".$_SERVER['PHP_SELF']);
    exit();
}

// Добавяне на клас
if (isset($_POST['add_class'])) {
    $class_name_input = $_POST['class_name'];
    $formatted_class_name = formatClassName($class_name_input);
    $school_id = intval($_POST['school_id']);
    
    // Вмъкване в базата данни
    $stmt = $conn->prepare("INSERT INTO classes (name, school_id) VALUES (?, ?)");
    $stmt->bind_param("si", $formatted_class_name, $school_id);
    if ($stmt->execute()) {
        $_SESSION['message'] = "Класът е успешно добавен.";
    } else {
        $_SESSION['error'] = "Грешка при добавяне на клас: " . $stmt->error;
    }
    $stmt->close();
    
    header("Location: admin_dashboard.php");
    exit();
}

// Редактиране на клас
if (isset($_POST['edit_class'])) {
    $class_id = intval($_POST['class_id']);
    $class_name = trim($_POST['class_name']);
    $teacher_id = intval($_POST['teacher_id']);

    // Форматиране на името на класа
    $class_name = formatClassName($class_name);

    if ($class_id > 0 && !empty($class_name) && $teacher_id > 0) {
        // Проверка дали teacher_id съществува
        $stmt = $conn->prepare("SELECT id FROM teachers WHERE id = ?");
        $stmt->bind_param("i", $teacher_id);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows == 0) {
            $_SESSION['error'] = "Избраният класен ръководител не съществува.";
            $stmt->close();
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        }
        $stmt->close();

        // Актуализиране на класа с teacher_id
        $stmt = $conn->prepare("UPDATE classes SET name = ?, teacher_id = ? WHERE id = ?");
        $stmt->bind_param("sii", $class_name, $teacher_id, $class_id);

        if ($stmt->execute()) {
            $_SESSION['message'] = "Класът беше редактиран успешно.";
        } else {
            $_SESSION['error'] = "Грешка при редактиране на клас: " . $stmt->error;
        }
        $stmt->close();
    } else {
        // Ако полетата са празни или teacher_id не е валиден
        $_SESSION['error'] = "Моля, въведете име на класа и изберете класен ръководител.";
    }

    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Изтриване на клас
if (isset($_POST['delete_class'])) {
    $class_id = intval($_POST['class_id']);

    // Първо трием учениците от този клас
    $stmtDelStudents = $conn->prepare("DELETE FROM students WHERE class_id = ?");
    $stmtDelStudents->bind_param("i", $class_id);
    $stmtDelStudents->execute();
    $stmtDelStudents->close();

    // Сега изтриваме класа
    $stmt = $conn->prepare("DELETE FROM classes WHERE id=?");
    $stmt->bind_param("i", $class_id);
    if ($stmt->execute()) {
        $_SESSION['message'] = "Класът беше изтрит успешно.";
    } else {
        $_SESSION['error'] = "Грешка при изтриване на клас: " . $stmt->error;
    }
    $stmt->close();

    $school_id = getSchoolIdByClass($conn, $class_id);
    header("Location: " . $_SERVER['PHP_SELF'] . "?open_school=$school_id");
    exit();
}

// Добавяне на ученик
if (isset($_POST['add_student'])) {
    $class_id     = intval($_POST['class_id']);
    $student_name = trim($_POST['student_name']);
    $email        = trim($_POST['email']);
    $password     = trim($_POST['password']);

    if ($class_id > 0 && !empty($student_name) && !empty($email)) {
        $stmt = $conn->prepare("
            INSERT INTO students (name, email, password, class_id, classes_skipped_bel, classes_skipped_maths, classes_skipped_english)
            VALUES (?, ?, ?, ?, 0, 0, 0)
        ");
        $stmt->bind_param("sssi", $student_name, $email, $password, $class_id);
        if ($stmt->execute()) {
            $_SESSION['message'] = "Ученикът беше добавен успешно.";
        } else {
            $_SESSION['error'] = "Грешка при добавяне на ученик: " . $stmt->error;
        }
        $stmt->close();
    }
    $school_id = getSchoolIdByClass($conn, $class_id);
    header("Location: ".$_SERVER['PHP_SELF']."?open_school=$school_id#class$class_id");
    exit();
}

// Редактиране на ученик
if (isset($_POST['edit_student'])) {
    $student_id = intval($_POST['student_id']);
    $name       = trim($_POST['student_name']);
    $email      = trim($_POST['email']);
    $password   = trim($_POST['password']);
    $bel        = trim($_POST['bel']);
    $maths      = trim($_POST['maths']);
    $english    = trim($_POST['english']);
    $skBel      = intval($_POST['classes_skipped_bel']);
    $skMaths    = intval($_POST['classes_skipped_maths']);
    $skEng      = intval($_POST['classes_skipped_english']);

    // Валидация на оценките (позволява празни стойности)
    if (!validate_grades_optional($bel) || !validate_grades_optional($maths) || !validate_grades_optional($english)) {
        $_SESSION['error'] = "Моля, въведете валидни оценки (от 2 до 6) за всички предмети, ако ги имате.";
    } else {
        if ($student_id > 0 && !empty($name) && !empty($email)) {
            // Подготвяне на оценките за съхранение: премахване на допълнителните интервали и замяна със запетаи
            $bel_clean = !empty($bel) ? implode(',', array_map('intval', preg_split('/[\s,]+/', $bel))) : '';
            $maths_clean = !empty($maths) ? implode(',', array_map('intval', preg_split('/[\s,]+/', $maths))) : '';
            $english_clean = !empty($english) ? implode(',', array_map('intval', preg_split('/[\s,]+/', $english))) : '';

        
            // Ако паролата е празна, запазете текущата
            if (!empty($password)) {
                $final_password = $password;
            } else {
                // Извлечете текущата парола от базата данни
                $stmt_current = $conn->prepare("SELECT password FROM students WHERE id = ?");
                $stmt_current->bind_param("i", $student_id);
                $stmt_current->execute();
                $stmt_current->bind_result($final_password);
                $stmt_current->fetch();
                $stmt_current->close();
            }

            // Подготвяне на заявката за актуализиране
            $stmt = $conn->prepare("
                UPDATE students 
                SET name=?, email=?, password=?, bel=?, maths=?, english=?, 
                    classes_skipped_bel=?, classes_skipped_maths=?, classes_skipped_english=?
                WHERE id=?
            ");
            $stmt->bind_param("ssssssiiii",
                $name,           // s
                $email,          // s
                $final_password, // s
                $bel_clean,      // s
                $maths_clean,    // s
                $english_clean,  // s
                $skBel,          // i
                $skMaths,        // i
                $skEng,          // i
                $student_id      // i
            );

            if ($stmt->execute()) {
                $_SESSION['message'] = "Ученикът беше редактиран успешно.";
            } else {
                $_SESSION['error'] = "Грешка при редактиране на ученик: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $_SESSION['error'] = "Моля, попълнете всички задължителни полета.";
        }
    }

    // Пренасочване обратно към таблото за управление
    $class_id  = getClassIdByStudent($conn, $student_id);
    $school_id = getSchoolIdByClass($conn, $class_id);
    header("Location: ".$_SERVER['PHP_SELF']."?open_school=$school_id#class$class_id");
    exit();
}

// Изтриване на ученик
if (isset($_POST['delete_student'])) {
    $student_id = intval($_POST['student_id']);
    $class_id   = getClassIdByStudent($conn, $student_id);
    $school_id  = getSchoolIdByClass($conn, $class_id);

    $stmt = $conn->prepare("DELETE FROM students WHERE id=?");
    $stmt->bind_param("i", $student_id);
    if ($stmt->execute()) {
        $_SESSION['message'] = "Ученикът беше изтрит успешно.";
    } else {
        $_SESSION['error'] = "Грешка при изтриване на ученик: " . $stmt->error;
    }
    $stmt->close();
    header("Location: ".$_SERVER['PHP_SELF']."?open_school=$school_id#class$class_id");
    exit();
}

// Обработка на формата за добавяне на нов преподавател
if (isset($_POST['add_teacher'])) {
    $teacher_name_input = trim($_POST['teacher_name']);
    $class_id = intval($_POST['class_id']);

    if (!empty($teacher_name_input)) {
        // Извличане на school_id от таблицата classes
        $stmt = $conn->prepare("SELECT school_id FROM classes WHERE id = ?");
        $stmt->bind_param("i", $class_id);
        $stmt->execute();
        $stmt->bind_result($school_id);
        if ($stmt->fetch()) {
            $stmt->close();

            // Проверка дали преподавателят вече съществува в това училище
            $stmt = $conn->prepare("SELECT id FROM teachers WHERE name = ? AND school_id = ?");
            $stmt->bind_param("si", $teacher_name_input, $school_id);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                // Преподавателят вече съществува
                $_SESSION['error'] = "Преподавателят \"$teacher_name_input\" вече съществува в това училище.";
            } else {
                // Добавяне на новия преподавател с school_id
                $stmt->close();
                $stmt = $conn->prepare("INSERT INTO teachers (name, school_id) VALUES (?, ?)");
                $stmt->bind_param("si", $teacher_name_input, $school_id);
                if ($stmt->execute()) {
                    $_SESSION['message'] = "Преподавателят \"$teacher_name_input\" беше успешно добавен.";

                    // Вземане на ID на новия преподавател
                    $new_teacher_id = $stmt->insert_id;

                    // Обновяване на класа с новия преподавател
                    $update_stmt = $conn->prepare("UPDATE classes SET teacher_id = ? WHERE id = ?");
                    $update_stmt->bind_param("ii", $new_teacher_id, $class_id);
                    if ($update_stmt->execute()) {
                        $_SESSION['message'] .= " и класа беше обновен с новия класен ръководител.";
                    } else {
                        $_SESSION['error'] = "Преподавателят беше добавен, но възникна грешка при обновяването на класа.";
                    }
                    $update_stmt->close();
                } else {
                    $_SESSION['error'] = "Възникна грешка при добавянето на преподавателя.";
                }
            }
            $stmt->close();
        } else {
            $stmt->close();
            $_SESSION['error'] = "Неуспешно извличане на училището за класа.";
        }
    } else {
        $_SESSION['error'] = "Името на преподавателя не може да бъде празно.";
    }

    // Пренасочване за предотвратяване на повторно изпращане на формата
    header("Location: admin_dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <title>Админ табло</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
    <style>
        .form-container {
            width: 100%;
            margin: 20px 0;
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .accordion-container {
            margin-top: 30px;
        }

        /* Персонализирани стилове за аккордеона */
        .accordion-button {
            background-color: #000000;
            color: #ffffff;
            border: none;
        }

        .accordion-button:not(.collapsed) {
            background-color: #000000;
            color: #ffffff;
        }

        .accordion-button::after {
            filter: invert(1);
        }

        /* Персонализирани стилове за черни рамки на таблиците */
        .table-bordered-black {
            border: 2px solid #000000;
        }

        .table-bordered-black th,
        .table-bordered-black td {
            border: 2px solid #000000;
        }

        /* Допълнителни стилове за по-добро разграфяване */
        .table-bordered-black thead th {
            background-color: #000000;
            color: #ffffff;
        }

        /* Персонализирани стилове за хоризонтална линия */
        .bordered-line {
            border: none;
            height: 2px;
            background-color: #000000;
            width: 100%;
            margin: 15px 0;
        }

        /* Персонализирани стилове за заглавието с черен кант */
        .bordered-title {
            border: 2px solid #000000;
            padding: 10px 20px;
            display: inline-block;
            border-radius: 5px;
            background-color: #f8f9fa;
        }

        /* Стил за подтаблици */
        .sub-table {
            margin-top: 10px;
        }

        .custom-row td {
        border: 2px solid black;
        text-align: center;
    }
    </style>
</head>
<body>
<?php
$open_school = isset($_GET['open_school']) ? intval($_GET['open_school']) : 0;
?>
<div class="container mt-5">
    <hr class="bordered-line">
    <h1 class="text-center">Табло за управление</h1>
    <hr class="bordered-line">

    <!-- Съобщения за успех/грешка -->
    <?php if (!empty($_SESSION['message'])): ?>
        <div class="alert alert-success alert-dismissible fade show auto-dismiss" role="alert">
            <?php echo $_SESSION['message']; unset($_SESSION['message']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Затвори"></button>
        </div>
    <?php endif; ?>
    <?php if (!empty($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show auto-dismiss" role="alert">
            <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Затвори"></button>
        </div>
    <?php endif; ?>

    <!-- Форма за добавяне на училище -->
    <div class="form-container">
        <hr class="bordered-line">
        <h2 class="text-center">Добавяне на училище</h2>
        <hr class="bordered-line">
        <table class="table table-bordered-black">
            <tr>
                <td>
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Име на училище</label>
                            <input type="text" class="form-control" name="school_name" placeholder="Например: СУ 'В. Левски'" required>
                        </div>
                        <button type="submit" class="btn btn-dark w-100" name="add_school">Добави училище</button>
                    </form>
                </td>
            </tr>
        </table>
    </div>

    <!-- Форма за добавяне на клас -->
    <div class="form-container">
        <hr class="bordered-line">
        <h2 class="text-center">Добавяне на клас</h2>
        <hr class="bordered-line">
        <table class="table table-bordered-black">
            <tr>
                <td>
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Име на клас</label>
                            <input type="text" class="form-control" name="class_name" placeholder="Например: 1" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Изберете училище</label>
                            <select class="form-select" name="school_id" required>
                                <option value="">-- Изберете --</option>
                                <?php
                                $schoolRes = getSchools($conn);
                                while ($sch = $schoolRes->fetch_assoc()) {
                                    echo '<option value="'.$sch['id'].'">'.htmlspecialchars($sch['name']).'</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-dark w-100" name="add_class">Добави клас</button>
                    </form>
                </td>
            </tr>
        </table>
    </div>

    <!-- Списък с училища -->
    <div class="accordion-container">
        <hr class="bordered-line">
        <h2 class="text-center">Списък с училища</h2>
        <hr class="bordered-line">
        <div class="accordion" id="schoolsAccordion">
            <?php
            $schools = getSchools($conn);
            if ($schools->num_rows > 0) {
                while ($school = $schools->fetch_assoc()) {
                    $school_id   = $school['id'];
                    $school_name = $school['name'];
                    ?>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingSchool<?php echo $school_id; ?>">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseSchool<?php echo $school_id; ?>" aria-expanded="false"
                                    aria-controls="collapseSchool<?php echo $school_id; ?>">
                                Училище: <?php echo htmlspecialchars($school_name); ?>
                            </button>
                        </h2>
                        <div id="collapseSchool<?php echo $school_id; ?>"
                             class="accordion-collapse collapse"
                             aria-labelledby="headingSchool<?php echo $school_id; ?>"
                             data-bs-parent="#schoolsAccordion">
                            <div class="accordion-body">
                                <form method="POST" class="mb-3">
                                    <input type="hidden" name="school_id" value="<?php echo $school_id; ?>">
                                    <button type="submit" name="delete_school" class="btn btn-danger btn-sm"
                                            onclick="return confirm('Сигурни ли сте, че искате да изтриете това училище и всички класове/ученици в него?')">
                                        Изтрий училище
                                    </button>
                                </form>
<?php
// Извличане на класовете за текущото училище
$classes = getClassesBySchool($conn, $school_id);
if ($classes->num_rows > 0) {
    while ($class = $classes->fetch_assoc()) {
        $class_id = $class['id'];
        $class_name = htmlspecialchars($class['name']);
        $teacher_id = (int)$class['teacher_id'];
        $teacher_name = getClassTeacher($conn, $teacher_id) ?: "—";
        ?>
        
        <!-- Таблица за класова информация -->
        <table class="table table-bordered-black text-center">
            <thead>
                <tr class="bg-dark text-white">
                    <th>Клас</th>
                    <th>Класен ръководител</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><?php echo $class_name; ?></td>
                    <td><?php echo htmlspecialchars($teacher_name); ?></td>
                    <td>
                        <button class="btn btn-success btn-sm" data-bs-toggle="modal"
                                data-bs-target="#addStudentModal<?php echo $class_id; ?>">
                            Добавяне на ученик
                        </button>
                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                data-bs-target="#editClassModal<?php echo $class_id; ?>">
                            Редактирай
                        </button>
                        <form method="POST" class="d-inline">
                            <input type="hidden" name="class_id" value="<?php echo $class_id; ?>">
                            <button type="submit" name="delete_class" class="btn btn-danger btn-sm"
                                    onclick="return confirm('Да изтрием ли този клас?')">
                                Изтрий
                            </button>
                        </form>
                    </td>
                </tr>
            </tbody>
        </table>

<!-- Таблица за учениците в класа -->
<table class="table table-sm table-bordered text-center">
    <thead>
        <tr class="bg-dark text-white" style="border-color: white;">
            <th colspan="3" style="border: 1px solid white;">Лични данни</th>
            <th colspan="3" style="border: 1px solid white;">Оценки</th>
            <th colspan="3" style="border: 1px solid white;">Отсъствия</th>
            <th colspan="3" style="border: 1px solid white;">Действия</th>
        </tr>
        <tr class="bg-white text-black text-center" custom-row style="border-color: black;">
            <th>Име</th>
            <th>Имейл</th>
            <th>Парола</th>
            <th>Български</th>
            <th>Математика</th>
            <th>Английски</th>
            <th>Български</th>
            <th>Математика</th>
            <th>Английски</th>
            <th>Редактирай/Изтрий</th>
        </tr>
    </thead>
    <tbody>
        <?php
        // Извличане на учениците за текущия клас
        $stmtStud = $conn->prepare("
            SELECT id, name, email, password,
                   bel, maths, english,
                   classes_skipped_bel, classes_skipped_maths, classes_skipped_english
            FROM students
            WHERE class_id = ?
            ORDER BY name ASC
        ");
        $stmtStud->bind_param("i", $class_id);
        $stmtStud->execute();
        $students = $stmtStud->get_result();

        if ($students->num_rows > 0) {
            while ($st = $students->fetch_assoc()) {
                $student_id = $st['id'];
                ?>
                <tr class = "custom-row">
                    <td><?php echo htmlspecialchars($st['name']); ?></td>
                    <td><?php echo htmlspecialchars($st['email']); ?></td>
                    <td><?php echo htmlspecialchars($st['password']); ?></td>
                    <td><?php echo isset($st['bel']) && !empty($st['bel']) ? htmlspecialchars(str_replace(" ", ", ", $st['bel'])) : " "; ?></td>
                    <td><?php echo isset($st['maths']) && !empty($st['maths']) ? htmlspecialchars(str_replace(" ", ", ", $st['maths'])) : " "; ?></td>
                    <td><?php echo isset($st['english']) && !empty($st['english']) ? htmlspecialchars(str_replace(" ", ", ", $st['english'])) : " "; ?></td>
                    <td><?php echo (int)$st['classes_skipped_bel']; ?></td>
                    <td><?php echo (int)$st['classes_skipped_maths']; ?></td>
                    <td><?php echo (int)$st['classes_skipped_english']; ?></td>
                    <td>
                        <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editStudentModal<?php echo $student_id; ?>">
                            Редактирай
                        </button>
                        <form method="POST" class="d-inline">
                            <input type="hidden" name="student_id" value="<?php echo $student_id; ?>">
                            <button type="submit" name="delete_student" class="btn btn-sm btn-danger" onclick="return confirm('Изтриване на ученик?')">
                                Изтрий
                            </button>
                        </form>
                    </td>
                </tr>

                <!-- Модал за редактиране на ученик -->
                <div class="modal fade" id="editStudentModal<?php echo $student_id; ?>" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Редактиране на ученик</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <form method="POST">
                                    <input type="hidden" name="student_id" value="<?php echo $student_id; ?>">

                                    <div class="mb-3">
                                        <label class="form-label">Име</label>
                                        <input type="text" class="form-control" name="student_name" value="<?php echo htmlspecialchars($st['name']); ?>" required>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Имейл</label>
                                        <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($st['email']); ?>" required>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Парола</label>
                                        <input type="text" class="form-control" name="password" value="<?php echo htmlspecialchars($st['password']); ?>">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Оценки (Български)</label>
                                        <input type="text" class="form-control" name="bel" 
                                               value="<?php echo htmlspecialchars(str_replace(', ', ' ', $st['bel'] ?? '')); ?>" 
                                               placeholder="Например: 2, 4, 6">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Оценки (Математика)</label>
                                        <input type="text" class="form-control" name="maths" 
                                               value="<?php echo htmlspecialchars(str_replace(', ', ' ', $st['maths'] ?? '')); ?>" 
                                               placeholder="Например: 3, 5">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Оценки (Английски)</label>
                                        <input type="text" class="form-control" name="english" 
                                               value="<?php echo htmlspecialchars(str_replace(', ', ' ', $st['english'] ?? '')); ?>" 
                                               placeholder="Например: 4, 6">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Отсъствия (Български)</label>
                                        <input type="number" class="form-control" name="classes_skipped_bel" 
                                               value="<?php echo (int)$st['classes_skipped_bel']; ?>">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Отсъствия (Математика)</label>
                                        <input type="number" class="form-control" name="classes_skipped_maths" 
                                               value="<?php echo (int)$st['classes_skipped_maths']; ?>">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Отсъствия (Английски)</label>
                                        <input type="number" class="form-control" name="classes_skipped_english" 
                                               value="<?php echo (int)$st['classes_skipped_english']; ?>">
                                    </div>

                                    <button type="submit" name="edit_student" class="btn btn-primary">Запази</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
            }
        } else {
            echo '<tr><td colspan="10" class="text-center">Няма ученици в този клас.</td></tr>';
        }
        ?>
    </tbody>
</table>


                                                                   

                                        <!-- Модал за добавяне на ученик -->
                                        <div class="modal fade" id="addStudentModal<?php echo $class_id; ?>" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Добавяне на ученик в <?php echo htmlspecialchars($class_name); ?></h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form method="POST">
                                                            <input type="hidden" name="class_id" value="<?php echo $class_id; ?>">
                                                            <div class="mb-3">
                                                                <label class="form-label">Име на ученик</label>
                                                                <input type="text" class="form-control" name="student_name" required>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label">Имейл</label>
                                                                <input type="email" class="form-control" name="email" required>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label">Парола</label>
                                                                <input type="password" class="form-control" name="password">
                                                            </div>
                                                            <button type="submit" name="add_student" class="btn btn-primary">Добави</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Модал за редактиране на клас -->
                                        <div class="modal fade" id="editClassModal<?php echo $class_id; ?>" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Редактиране на клас</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form method="POST">
                                                            <input type="hidden" name="class_id" value="<?php echo $class_id; ?>">
                                                            <div class="mb-3">
                                                                <label class="form-label">Име на клас</label>
                                                                <input type="text" class="form-control" name="class_name"
                                                                       value="<?php echo $class_name; ?>" required>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label">Класен ръководител</label>
                                                                <div class="d-flex align-items-center">
                                                                    <select class="form-select me-2" name="teacher_id" required>
                                                                        <?php
                                                                        // Извличане на всички преподаватели от системата
                                                                        $stmt = $conn->prepare("SELECT id, name FROM teachers ORDER BY name ASC");
                                                                        $stmt->execute();
                                                                        $teachers = $stmt->get_result();
                                                                        while ($t = $teachers->fetch_assoc()) {
                                                                            $sel = ($t['id'] == $teacher_id) ? 'selected' : '';
                                                                            echo '<option value="'.$t['id'].'" '.$sel.'>'.htmlspecialchars($t['name']).'</option>';
                                                                        }
                                                                        $stmt->close();
                                                                        ?>
                                                                    </select>
                                                                    <!-- Бутон за добавяне на нов преподавател -->
                                                                    <button type="button" class="btn btn-secondary btn-sm" data-bs-toggle="modal"
                                                                            data-bs-target="#addTeacherModal<?php echo $class_id; ?>">
                                                                        Добави нов класен ръководител
                                                                    </button>
                                                                </div>
                                                            </div>
                                                            <button type="submit" name="edit_class" class="btn btn-primary">Запази</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Модал за добавяне на нов преподавател -->
                                        <div class="modal fade" id="addTeacherModal<?php echo $class_id; ?>" tabindex="-1" aria-labelledby="addTeacherModalLabel<?php echo $class_id; ?>" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form method="POST">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="addTeacherModalLabel<?php echo $class_id; ?>">Добавяне на нов класен ръководител</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Затвори"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <input type="hidden" name="class_id" value="<?php echo $class_id; ?>">
                                                            <div class="mb-3">
                                                                <label for="teacher_name<?php echo $class_id; ?>" class="form-label">Име на преподавател</label>
                                                                <input type="text" class="form-control" id="teacher_name<?php echo $class_id; ?>" name="teacher_name" placeholder="Например: Георги Георгиев" required>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отказ</button>
                                                            <button type="submit" name="add_teacher" class="btn btn-primary">Добави преподавател</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <?php
                                    }
                                } else {
                                    echo '<p class="text-center">Няма добавени класове.</p>';
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            } else {
                echo '<p class="text-center">Все още няма добавени училища.</p>';
            }
            ?>
        </div>
    </div> 

    <!-- Модали за добавяне на преподаватели (ако е необходимо) -->
    <?php
    // Извличане на всички класове за създаване на модалите
    $allClasses = $conn->query("SELECT id FROM classes");
    while ($cls = $allClasses->fetch_assoc()) {
        $class_id = $cls['id'];
        ?>
        <div class="modal fade" id="addTeacherModal<?php echo $class_id; ?>" tabindex="-1" aria-labelledby="addTeacherModalLabel<?php echo $class_id; ?>" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="POST">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addTeacherModalLabel<?php echo $class_id; ?>">Добавяне на нов класен ръководител</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Затвори"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="class_id" value="<?php echo $class_id; ?>">
                            <div class="mb-3">
                                <label for="teacher_name<?php echo $class_id; ?>" class="form-label">Име на преподавател</label>
                                <input type="text" class="form-control" id="teacher_name<?php echo $class_id; ?>" name="teacher_name" placeholder="Например: Георги Георгиев" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отказ</button>
                            <button type="submit" name="add_teacher" class="btn btn-primary">Добави преподавател</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <?php
    }
    ?>

    <!-- Включване на Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- JavaScript за автоматично затваряне на съобщенията и валидация -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Автоматично затваряне на съобщенията за успех/грешка след 5 секунди
            var alerts = document.querySelectorAll('.auto-dismiss');

            alerts.forEach(function(alert) {
                setTimeout(function() {
                    var bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }, 5000); // Затваря след 5 секунди
            });

            // Клиентска валидация на оценките
            const gradeInputs = document.querySelectorAll('.grade-input');

            gradeInputs.forEach(input => {
                input.addEventListener('input', function() {
                    const value = this.value.trim();

                    if (value === '') {
                        // Ако полето е празно, премахнете грешката
                        this.setCustomValidity('');
                        return;
                    }

                    // Проверка дали оценките са във формат "число, интервал, число, интервал,..."
                    const pattern = /^(\d{1},\s*)*\d{1}$/;
                    const isPatternValid = pattern.test(value);

                    // Разделяне на оценките със запетая и интервал
                    const grades = value.split(/,\s*/);
                    let isValid = true;

                    for (let grade of grades) {
                        const num = parseInt(grade, 10);
                        if (isNaN(num) || num < 2 || num > 6) {
                            isValid = false;
                            break;
                        }
                    }

                    if (!isValid || !isPatternValid) {
                        this.setCustomValidity('Моля, въведете само числа от 2 до 6, разделени със запетаи и/или интервали.');
                    } else {
                        this.setCustomValidity('');
                    }
                });
            });
        });
    </script>
</body>
</html>
