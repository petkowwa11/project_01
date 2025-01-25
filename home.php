<?php
include "login.php";
include "functions.php";
include "header.php";
?>
  <main>
    <section class="py-5 text-center container">
      <div class="row py-lg-5">
        <div class="col-lg-6 col-md-8 mx-auto">
          <h1 class="fw-light">Класове</h1> <!-- Заглавие на секцията -->
          <p class="lead text-body-secondary">Тук можете да видите всички класове и информация за учениците.</p> <!-- Описание -->
        </div>
      </div>
    </section>

    <!-- Секция с класовете -->
    <div class="album py-5 bg-body-tertiary">
      <div class="container">
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-2 g-2">
            <?php
              echo cards(); // Извикване на функцията cards() за динамично генериране на информация за класовете
            ?>
        </div>
      </div>
    </div>

  </main>

  <!-- Футър на страницата -->
  <div class="container">
    <footer class="d-flex flex-wrap justify-content-between align-items-center py-3 my-4 border-top">
      <p class="col-md-4 mb-0 text-body-secondary">&copy; by Тoni</p> <!-- Авторско право -->
    </footer>
  </div>

  <!-- Линк към Bootstrap JS библиотеката -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>

</body>

</html>
