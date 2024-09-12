<?php

  include "login.php";
  include "functions.php";

  if(isset($_POST['add_grade'])){
    $grades = mysqli_fetch_assoc(mysqli_query($conn, "select * from students where id=".$_GET['id']." and class_id=".$_GET['class_id'].""))[$_POST['subject']];
    if($grades != '' || $grades != null){
      $grades .= " " . $_POST['grades'];
      $update = "update students set ".$_POST['subject']."="."'"."".$grades.""."'"." where id=".$_GET['id']." and class_id=".$_GET['class_id']."";
      $conn->query($update);
      header("location:?id=".$_GET['id']."&class_id=".$_GET['class_id']."");
    } else {
      $update = "update students set ".$_POST['subject']."="."'"."".$_POST['grades'].""."'"." where id=".$_GET['id']." and class_id=".$_GET['class_id']."";
      $conn->query($update);
      header("location:?id=".$_GET['id']."&class_id=".$_GET['class_id']."");
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

<!doctype html>
<html lang="en" data-bs-theme="auto">

<head>
  <script src="../assets/js/color-modes.js"></script>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="">
  <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
  <meta name="generator" content="Hugo 0.111.3">
  <title>Електронен дневник</title>

  <link rel="canonical" href="https://getbootstrap.com/docs/5.3/examples/album/">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
  <link href="../assets/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.4/font/bootstrap-icons.css">

  <style>
    table,
    th,
    td {
      border: 1px solid lightgray;
    }
  </style>

</head>

<body>

  <?php include "header.php"; ?>

  <div class="container-fluid">
    <main class="d-flex flex-column text-center mx-5">
      <br>
      <h2 class="">Разглеждате оценките на : <em>
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
          <!-- <th scope="col">ОТСЪСТВИЯ</th>
          <th scope="col">УСПЕХ</th> -->
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