<?php
session_start();
require "connection.php";
include "functions.php";
include "header.php";

// Получаване на информация за текущия ученик
$student_id = $_SESSION['student_id'] ?? null;
$class_id = $_SESSION['class_id'] ?? null;

if (!$student_id || !$class_id) {
    echo "Липсваща информация за ученика.";
    exit;
}
?>

<div class="container-fluid">
    <main class="d-flex flex-column text-center mx-5">
        <br>
        <h2 class="">Вашите оценки: <em>
            <?php
                // Вземане на името на ученика
                $stmt = $conn->prepare("SELECT * FROM students WHERE id = ? AND class_id = ?");
                $stmt->bind_param("ii", $student_id, $class_id);
                $stmt->execute();
                $result = $stmt->get_result();
                $row = $result->fetch_assoc();
                echo htmlspecialchars($row['name']);
            ?>
        </em>,
        <?php
            // Показване на номера на ученика в класа
            echo "<em>" . htmlspecialchars($row['num']) . "</em>" . " номер";
        ?>
        </h2>
        <br>
        <style>
            /* Специфичен стил само за student_profile */
            .student-profile-table thead th {
                height: 20px; /* Фиксирана височина на заглавния ред */
                vertical-align: middle; /* Центриране на текста по вертикала */
            }

            .student-profile-table tbody tr {
                height: 50px; /* Увеличена височина за редовете в тялото */
            }
        </style>
        <div class="table-responsive">
            <table class="table table-striped table-sm align-middle text-center student-profile-table">
                <thead>
                    <tr>
                        <th scope="col">ПРЕДМЕТ</th>
                        <th scope="col">ОЦЕНКИ</th>
                        <th scope="col">ОТСЪСТВИЯ</th>
                        <th scope="col">ГОДИШНА</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        // Функция за показване на оценки и отсъствия
                        echo grades_and_skips_students();
                    ?>
                </tbody>
            </table>
        </div>
    </main>
</div>


<div class="container mt-4" style="max-width: 1420px;">
    <h2 class="text-center mb-4">Класация</h2> <!-- Добавен клас mb-4 -->
    <div class="table-responsive">
        <table class="table table-striped table-sm align-middle text-center">
            <thead>
                <tr>
                    <th scope="col">КЛАСАЦИЯ</th>
                    <th scope="col">БЕЛ</th>
                    <th scope="col">МАТЕМАТИКА</th>
                    <th scope="col">АНГЛИЙСКИ</th>
                </tr>
            </thead>
            <tbody>
                <?php leaderboard_students(); ?>
            </tbody>
        </table>
    </div>
</div>

  <div class="container">
    <footer class="d-flex flex-wrap justify-content-between align-items-center py-3 my-4 border-top">
      <p class="col-md-4 mb-0 text-body-secondary">&copy; by Toni</p>
    </footer>
  </div>


  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe"
    crossorigin="anonymous"></script>

</body>

</html>