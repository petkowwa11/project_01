<?php

  include "login.php";
  include "functions.php";
  include "header.php";

  if (isset($_POST['add_grade'])) {
    require "connection.php";

    // Извличане на параметрите от формуляра
    $student_id = intval($_GET['id']);
    $class_id = intval($_GET['class_id']);
    $subject = $_POST['subject']; // bel, maths или english
    $new_grade = intval($_POST['grades']); // Новата оценка

    // Извличане на текущите оценки
    $query = "SELECT $subject FROM students WHERE id=$student_id AND class_id=$class_id";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $current_grades = $row[$subject];

        // Добавяне на новата оценка
        if (!empty($current_grades)) {
            $updated_grades = $current_grades . " " . $new_grade;
        } else {
            $updated_grades = (string)$new_grade;
        }

        // Обновяване на базата данни
        $update_query = "UPDATE students SET $subject = '$updated_grades' WHERE id=$student_id AND class_id=$class_id";
        if (mysqli_query($conn, $update_query)) {
            header("Location: ?id=$student_id&class_id=$class_id"); // Презареждане на страницата
            exit;
        } else {
            echo "Грешка при обновяване: " . mysqli_error($conn);
        }
    } else {
        echo "Грешка: Ученикът не беше намерен.";
    }
}

  if(isset($_POST['add_skip'])){
    $skips = mysqli_fetch_assoc(mysqli_query($conn, "select * from students where id=".$_GET['id']." and class_id=".$_GET['class_id'].""))["classes_skipped_".$_POST['skips_subject']];
    $skips++;
    $update = "update students set "."classes_skipped_".$_POST['skips_subject']."=".$skips." where id=".$_GET['id']." and class_id=".$_GET['class_id']."";
    $conn->query($update);
    header("location:?id=".$_GET['id']."&class_id=".$_GET['class_id']."");
  }

  if(isset($_POST['remove_skip'])){
    $skips = mysqli_fetch_assoc(mysqli_query($conn, "select * from students where id=".$_GET['id']." and class_id=".$_GET['class_id'].""))["classes_skipped_".$_POST['skips_subject']];
    if($skips == 0){
      header("location:?id=".$_GET['id']."&class_id=".$_GET['class_id']."");
    } else {
    $skips--;
    $update = "update students set "."classes_skipped_".$_POST['skips_subject']."=".$skips." where id=".$_GET['id']." and class_id=".$_GET['class_id']."";
    $conn->query($update);
    header("location:?id=".$_GET['id']."&class_id=".$_GET['class_id']."");
    }
  }
?>

  <div class="container-fluid">
    <main class="d-flex flex-column text-center mx-5">
      <br>
      <h2 class="">Разглеждате оценките на: <em>
          <?php
            require "connection.php";
            $res = mysqli_query($conn, 'select * from students where id='.$_GET['id'].' and class_id='.$_GET['class_id'].'');
            $row = mysqli_fetch_assoc($res);
            echo $row['name'];
          ?>
        </em>,
        <?php
            require "connection.php";
            $res = mysqli_query($conn, 'select * from students where id='.$_GET['id'].' and class_id='.$_GET['class_id'].'');
            $row = mysqli_fetch_assoc($res);
            echo "<em>" . $row['id'] . "</em>" . " номер";
          ?>
      </h2>
      <br>
      <div class="table-responsive">
        <table class="table table-striped table-sm align-middle text-center">
          <thead>
            <tr>
              <th scope="col">ПРЕДМЕТ</th>
              <th scope="col">ОЦЕНКИ</th>
              <th scope="col">ОТСЪСТВИЯ</th>
              <th scope="col">ГОДИШНА</th>
              <th scope="col">ДОБАВИ ОЦЕНКА</th>
              <th scope="col">ДОБАВИ ОТСЪСТВИЕ</th>
            </tr>
          </thead>
          <tbody>
            <?php
                echo grades_and_skips();
              ?>
          </tbody>
        </table>
      </div>

  <br>
  <h2 class="">Класация</h2>
  <br>
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
        <?php leaderboard(); ?>
      </tbody>
    </table>
  </div>
  </main>
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