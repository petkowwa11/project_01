<?php

    $class_id = 0;

    function logout(){
        if(isset($_POST['logout'])){
          unset($_SESSION['loggedIn']);
      
          header('location:index.php');
        }
        if(!isset($_SESSION['loggedIn'])){
          header('location:index.php');
        }
      }

      function str_replace_first($search, $replace, $subject){
        $search = '/'.preg_quote($search, '/').'/';
        return preg_replace($search, $replace, $subject, 1);
      }
  
    // за учители

function cards() {
    require "connection.php";

    // Проверка за валидност на school_id
    if (!isset($_SESSION['school_id']) || !is_numeric($_SESSION['school_id'])) {
        return "Невалиден идентификатор на училище!";
    }
    $school_id = intval($_SESSION['school_id']); // Превръщаме го в цяло число

    // Подготвена SQL заявка
    $stmt = $conn->prepare("SELECT * FROM classes WHERE school_id = ?");
    $stmt->bind_param("i", $school_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $res = '';
    while ($row = $result->fetch_assoc()) {
        // Генериране на случаен цвят
        $rgb = [
            'r' => mt_rand(125, 175),
            'g' => mt_rand(125, 175),
            'b' => mt_rand(125, 175),
        ];

        // Създаване на HTML за класа
        $res .= '<a href="classes.php?class_id=' . $row['id'] . '" class="text-decoration-none">
                    <div class="card shadow-sm" style="background-color:rgb(' . $rgb['r'] . ',' . $rgb['g'] . ',' . $rgb['b'] . ');">
                        <strong>
                            <svg class="bd-placeholder-img card-img-top" width="100%" height="225" xmlns="http://www.w3.org/2000/svg"
                                role="img" aria-label="Placeholder: Thumbnail" preserveAspectRatio="xMidYMid slice" focusable="false">
                                <text x="50%" y="50%" fill="#eceeef" dy=".3em">' . htmlspecialchars($row['name']) . '</text>
                            </svg>
                        </strong>
                    </div>
                </a>';
    }

    return $res;
}

    function students() {
    require "connection.php";

    // Валидиране на class_id
    if (!isset($_GET['class_id']) || !is_numeric($_GET['class_id'])) {
        return "Невалиден клас!";
    }
    $class_id = intval($_GET['class_id']); // Превръщаме го в цяло число

    // Подготвена заявка за избягване на SQL инжекции
    $stmt = $conn->prepare('SELECT * FROM students WHERE class_id = ?');
    $stmt->bind_param('i', $class_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $res = '';
    while ($row = $result->fetch_assoc()) {
        // Генериране на случаен цвят
        $rgb = [
            'r' => mt_rand(125, 175),
            'g' => mt_rand(125, 175),
            'b' => mt_rand(125, 175),
        ];

        // Създаване на HTML за ученик
        $res .= '<a href="students.php?id=' . $row['id'] . '&class_id=' . $class_id . '" class="text-decoration-none">
                    <div class="card shadow-sm px-2 py-5" style="background-color:rgb(' . $rgb['r'] . ',' . $rgb['g'] . ',' . $rgb['b'] . ');">
                        <div class="col d-flex align-items-start mx-auto">
                            <div class="text-center">
                                <h3 class="fw-bold mb-0 fs-4 text-white">' . htmlspecialchars($row['name']) . '</h3>
                                <h4 class="fw-bold mb-0 fs-4 text-white">' . htmlspecialchars($row['num']) . ' номер</h4>
                            </div>
                        </div>
                    </div>
                </a>';
    }

    return $res;
}

function grades_and_skips() {
    require "connection.php";

    // Проверка дали параметрите са зададени
    if (!isset($_GET["id"]) || !isset($_GET["class_id"])) {
        return '<tr><td colspan="6">Липсва информация за ученика или класа.</td></tr>';
    }

    // Извличане на данни за конкретния ученик
    $student_id = intval($_GET["id"]);
    $class_id = intval($_GET["class_id"]);
    $query = "SELECT * FROM students WHERE id=$student_id AND class_id=$class_id";

    $result = mysqli_query($conn, $query);

    // Проверка дали заявката връща резултати
    if (!$result || mysqli_num_rows($result) === 0) {
        return '<tr><td colspan="6">Няма данни за този ученик.</td></tr>';
    }

    $columns = ["bel", "maths", "english"];
    $cols_in_bg = [
        'bel' => "БЕЛ",
        'maths' => "МАТЕМАТИКА",
        'english' => "АНГЛИЙСКИ"
    ];

    $res = '';
    $row = mysqli_fetch_assoc($result);

    foreach ($columns as $column) {
        $grades = array_filter(explode(" ", $row[$column]), 'is_numeric'); // Само числови оценки
        $final_grade = !empty($grades) ? number_format(array_sum($grades) / count($grades), 2) : null; // Закръгляне до 2 знака

        // Създаване на ред в таблицата
        $res .= '<tr>
                    <td><em>' . $cols_in_bg[$column] . '</em></td>
                    <td>' . (!empty($grades) ? implode(", ", $grades) : '-') . '</td>
                    <td>' . intval($row['classes_skipped_' . $column]) . '</td>
                    <td>' . (!is_null($final_grade) ? $final_grade : '-') . '</td>
                    <td class="td-class d-flex flex-row align-middle justify-content-center">
                        <form action="" method="post" class="d-flex flex-row">
                            <input type="hidden" value="' . $column . '" name="subject">
                            <select class="form-select border-secondary" name="grades" id="grades">
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                                <option value="6">6</option>
                            </select>
                            <button class="btn btn-outline-secondary" type="submit" name="add_grade">+</button>
                        </form>
                    </td>
                    <td>
                        <form action="" method="post" class="d-flex flex-row align-middle justify-content-center">
                            <input type="hidden" value="' . $column . '" name="skips_subject">
                            <button class="btn btn-outline-secondary" type="submit" name="remove_skip">-</button>
                            <button class="btn btn-outline-secondary" type="submit" name="add_skip">+</button>
                        </form>
                    </td>
                </tr>';
    }

    return $res;
}


function leaderboard() {
    require "connection.php";

    if ($_GET['class_id'] == 1 || $_GET['class_id'] == 2 || $_GET['class_id'] == 5) {
        $school_id = 1;
    } else if ($_GET['class_id'] == 3 || $_GET['class_id'] == 4 || $_GET['class_id'] == 6) {
        $school_id = 2;
    } else if ($_GET['class_id'] == 7 || $_GET['class_id'] == 8 || $_GET['class_id'] == 9) {
        $school_id = 3;
    } else if ($_GET['class_id'] == 10 || $_GET['class_id'] == 11 || $_GET['class_id'] == 12) {
        $school_id = 4;
    } else if ($_GET['class_id'] == 13 || $_GET['class_id'] == 14 || $_GET['class_id'] == 15) {
        $school_id = 5;
    }

    $res = '';
    
    $classes = mysqli_query($conn, 'SELECT * FROM classes WHERE school_id=' . $school_id);
    $select = 'SELECT * FROM students WHERE class_id=' . $_GET['class_id'];

    while ($row = mysqli_fetch_assoc($classes)) {
        $select .= ' OR class_id=' . $row['id'];
    }

    $result = mysqli_query($conn, $select);

    $bel = [];
    $maths = [];
    $english = [];

    while ($row = mysqli_fetch_assoc($result)) {
        array_push($bel, $row);
        array_push($maths, $row);
        array_push($english, $row);
    }

    // Сортиране с bubbleSort, което включва азбучен ред
    bubbleSort($bel, 'bel');
    bubbleSort($maths, 'maths');
    bubbleSort($english, 'english');

    for ($i = 0; $i < min(30, count($bel)); $i++) {
        $b_avg = calculate_average($bel[$i]['bel']);
        $m_avg = calculate_average($maths[$i]['maths']);
        $e_avg = calculate_average($english[$i]['english']);

        $res .= '<tr>
                    <td>#' . ($i + 1) . '</td>
                    <td>' . htmlspecialchars($bel[$i]['name']) . ' - ' . number_format($b_avg, 2) . ' | ' . $bel[$i]['num'] . ' номер в ' . htmlspecialchars(mysqli_fetch_assoc(mysqli_query($conn, "SELECT name FROM classes WHERE id=" . $bel[$i]['class_id']))['name']) . '</td>
                    <td>' . htmlspecialchars($maths[$i]['name']) . ' - ' . number_format($m_avg, 2) . ' | ' . $maths[$i]['num'] . ' номер в ' . htmlspecialchars(mysqli_fetch_assoc(mysqli_query($conn, "SELECT name FROM classes WHERE id=" . $maths[$i]['class_id']))['name']) . '</td>
                    <td>' . htmlspecialchars($english[$i]['name']) . ' - ' . number_format($e_avg, 2) . ' | ' . $english[$i]['num'] . ' номер в ' . htmlspecialchars(mysqli_fetch_assoc(mysqli_query($conn, "SELECT name FROM classes WHERE id=" . $english[$i]['class_id']))['name']) . '</td>
                 </tr>';
    }

    echo $res;
}

function bubbleSort(&$array, $subject) {
    $n = count($array);
    for ($i = 0; $i < $n - 1; $i++) {
        for ($j = 0; $j < $n - $i - 1; $j++) {
            $a_avg = calculate_average($array[$j][$subject]);
            $b_avg = calculate_average($array[$j + 1][$subject]);

            // Сравняване по оценка, ако са равни, сортиране по азбучен ред
            if ($a_avg < $b_avg || ($a_avg === $b_avg && strcmp($array[$j]['name'], $array[$j + 1]['name']) > 0)) {
                // Размяна на елементите
                $temp = $array[$j];
                $array[$j] = $array[$j + 1];
                $array[$j + 1] = $temp;
            }
        }
    }
}

function calculate_average($grades) {
    if (empty($grades)) return 0;
    $grades_array = explode(" ", $grades);
    $grades_numeric = array_map('intval', $grades_array);
    return array_sum($grades_numeric) / count($grades_numeric);
}

    // за ученици

    function grades_and_skips_students() {
    require "connection.php";

    $student_id = $_SESSION['student_id']; // Идентификатор на логнатия ученик
    $class_id = $_SESSION['class_id']; // Клас на логнатия ученик

    // Подготвена заявка за безопасност
    $stmt = $conn->prepare('SELECT * FROM students WHERE id = ? AND class_id = ?');
    $stmt->bind_param('ii', $student_id, $class_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        return "Няма намерени данни за ученика.";
    }

    $columns = ["bel", "maths", "english"];
    $cols_in_bg = [
        'bel' => "БЕЛ",
        'maths' => "МАТЕМАТИКА",
        'english' => "АНГЛИЙСКИ"
    ];

    $res = '';
    $row = $result->fetch_assoc();

    foreach ($columns as $column) {
        $grades = [];
        if (!empty($row[$column])) {
            foreach (explode(" ", $row[$column]) as $grade) {
                $grades[] = intval($grade);
            }
        }

        if (!empty($grades)) {
            $final_grade = array_sum($grades) / count($grades);
            $res .= '<tr>
                        <td><em>' . htmlspecialchars($cols_in_bg[$column]) . '</em></td>
                        <td>' . htmlspecialchars(str_replace(" ", ", ", $row[$column])) . '</td>
                        <td>' . htmlspecialchars($row['classes_skipped_' . $column]) . '</td>
                        <td>' . round($final_grade, 2) . '</td>
                     </tr>';
        } else {
            $res .= '<tr>
                        <td><em>' . htmlspecialchars($cols_in_bg[$column]) . '</em></td>
                        <td></td>
                        <td>' . htmlspecialchars($row['classes_skipped_' . $column]) . '</td>
                        <td></td>
                     </tr>';
        }
    }

    return $res;
}
    
    function leaderboard_students() {
    require "connection.php";

    $class_id = $_SESSION['class_id']; // Класът на логнатия ученик

    // Извличане на учениците от текущия клас
    $stmt = $conn->prepare("SELECT s.*, c.name AS class_name FROM students s 
                            JOIN classes c ON s.class_id = c.id 
                            WHERE s.class_id = ?");
    $stmt->bind_param('i', $class_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        return "Няма ученици в този клас.";
    }

    $students = [];
    while ($row = $result->fetch_assoc()) {
        // Групиране по уникално име и клас, използвайки комбинация от name и class_id като ключ
        $key = $row['name'] . '|' . $row['class_id'];
        if (!isset($students[$key])) {
            $students[$key] = $row; // Добавяне само ако няма дублиране
        }
    }

    // Превръщаме отново в индексен масив след премахване на дублиращите се записи
    $students = array_values($students);

    // Сортиране по предмети
    $columns = ['bel', 'maths', 'english'];
    $sorted = [];

    foreach ($columns as $column) {
        $sorted[$column] = $students;
        usort($sorted[$column], function ($a, $b) use ($column) {
            $a_avg = calculate_average($a[$column]);
            $b_avg = calculate_average($b[$column]);

            if ($a_avg === $b_avg) {
                // Вторично сортиране по азбучен ред (име)
                return strcmp($a['name'], $b['name']);
            }
            return $b_avg <=> $a_avg; // Сортиране по оценка (обратно, най-високата първа)
        });
    }

    // Генериране на HTML таблица
    $res = '';
    for ($i = 0; $i < count($students); $i++) {
        $b_avg = calculate_average($sorted['bel'][$i]['bel']);
        $m_avg = calculate_average($sorted['maths'][$i]['maths']);
        $e_avg = calculate_average($sorted['english'][$i]['english']);

        $res .= '<tr>
                    <td>#' . ($i + 1) . '</td>
                    <td>' . htmlspecialchars($sorted['bel'][$i]['name']) . ' - ' . number_format($b_avg, 2) . ' | ' . $sorted['bel'][$i]['num'] . ' номер в ' . htmlspecialchars($sorted['bel'][$i]['class_name']) . '</td>
                    <td>' . htmlspecialchars($sorted['maths'][$i]['name']) . ' - ' . number_format($m_avg, 2) . ' | ' . $sorted['maths'][$i]['num'] . ' номер в ' . htmlspecialchars($sorted['maths'][$i]['class_name']) . '</td>
                    <td>' . htmlspecialchars($sorted['english'][$i]['name']) . ' - ' . number_format($e_avg, 2) . ' | ' . $sorted['english'][$i]['num'] . ' номер в ' . htmlspecialchars($sorted['english'][$i]['class_name']) . '</td>
                 </tr>';
    }

    echo $res;
}

 // за администратор - „Ученици в риск“
function countStudentsAtRisk() {
    require "connection.php";
    $result = $conn->query("SELECT bel, maths, english FROM students");
    $students_at_risk = 0;

    while ($row = $result->fetch_assoc()) {
        $bel = is_numeric($row['bel']) ? $row['bel'] : 0;
        $maths = is_numeric($row['maths']) ? $row['maths'] : 0;
        $english = is_numeric($row['english']) ? $row['english'] : 0;

        $average = ($bel + $maths + $english) / 3;
        if ($average < 3.5) {
            $students_at_risk++;
        }
    }
    return $students_at_risk;
}

// Функция за извличане на училища
function getSchools($conn) {
    $stmt = $conn->prepare("SELECT * FROM schools ORDER BY name ASC");
    $stmt->execute();
    return $stmt->get_result();
}

// Функция за извличане на класове по училище, подредени по нарастващ ред
function getClassesBySchool($conn, $school_id) {
    $stmt = $conn->prepare("SELECT * FROM classes WHERE school_id = ? ORDER BY CAST(SUBSTRING_INDEX(name, ' ', 1) AS UNSIGNED) ASC");
    $stmt->bind_param("i", $school_id);
    $stmt->execute();
    return $stmt->get_result();
}

// Функция за извличане на ученици в клас
function getStudentsInClass($conn, $class_id) {
    $stmt = $conn->prepare("SELECT id, name, email, password, bel, maths, english, classes_skipped_bel, classes_skipped_maths, classes_skipped_english, class_id, num 
                           FROM students 
                           WHERE class_id = ?");
    $stmt->bind_param("i", $class_id);
    $stmt->execute();
    return $stmt->get_result();
}

function formatClassName($class_name) {
    // Премахване на вече съществуващи суфикси
    $class_name = trim($class_name);
    $class_name = preg_replace('/(-ти клас|-ви клас|-ри клас|-ми клас)$/', '', $class_name);

    // Преобразуване на името на класа в число
    $number = intval($class_name);
    switch ($number) {
        case 1:
            return '1-ви клас';
        case 2:
            return '2-ри клас';
        case 7:
            return '7-ми клас';
        case 8:
            return '8-ми клас';
        default:
            return $number . '-ти клас';
    }
}


// Функция за извличане на класен ръководител
function getClassTeacher($conn, $teacher_id) {
    $stmt = $conn->prepare("SELECT name FROM teachers WHERE id = ?");
    $stmt->bind_param("i", $teacher_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result && $result->num_rows > 0) {
        return $result->fetch_assoc()['name'];
    } else {
        return "";
    }
}

// Функция за извличане на всички учители
function getTeachers($conn) {
    $stmt = $conn->prepare("SELECT id, name FROM teachers ORDER BY name ASC");
    $stmt->execute();
    return $stmt->get_result();
}

// Връща school_id за даден class_id (или 0, ако не е намерен)
function getSchoolIdByClass($conn, $class_id) {
    $stmt = $conn->prepare("SELECT school_id FROM classes WHERE id = ?");
    $stmt->bind_param("i", $class_id);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    if (!$row) {
        return 0;
    }
    return (int)$row['school_id'];
}

// Връща school_id за даден student_id (или 0, ако не е намерен)
function getSchoolIdByStudent($conn, $student_id) {
    // Първо намираме class_id
    $stmt = $conn->prepare("SELECT class_id FROM students WHERE id = ?");
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    $resStudent = $stmt->get_result()->fetch_assoc();
    if (!$resStudent) {
        return 0;
    }
    $class_id = (int)$resStudent['class_id'];
    // После намираме school_id
    return getSchoolIdByClass($conn, $class_id);
}

// Връща всички класове + училище (JOIN), за да покажем "Училище | Клас | Класен ръководител"
function getAllClassesWithSchool($conn) {
    $sql = "
        SELECT c.id AS class_id,
               c.name AS class_name,
               c.teacher_id,
               s.id AS school_id,
               s.name AS school_name
        FROM classes AS c
        JOIN schools AS s ON c.school_id = s.id
        ORDER BY s.name, c.name
    ";
    return $conn->query($sql);
}

function getClassIdByStudent($conn, $student_id) {
    $stmt = $conn->prepare("SELECT class_id FROM students WHERE id = ?");
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    return $row ? (int)$row['class_id'] : 0;
}

// Функция за валидация на оценките
function validate_grades_optional($grades) {
    if (empty(trim($grades))) {
        // Празното поле е валидно
        return true;
    }

    // Разделяне на оценките със запетая и/или интервал
    $grades_array = preg_split('/[\s,]+/', trim($grades));

    foreach ($grades_array as $grade) {
        if (!is_numeric($grade)) {
            return false;
        }
        $num = (int)$grade;
        if ($num < 2 || $num > 6) {
            return false;
        }
    }
    return true;
}
?>