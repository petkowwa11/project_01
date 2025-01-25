<?php
include "login.php";
include "functions.php";
include "header.php";
?>
  <main> <!-- Основно съдържание на страницата -->

  <div class="container px-4 py-5" id="icon-grid">
    <h2 class="pb-2 border-bottom">Ученици</h2> <!-- Заглавие на секцията -->

    <!-- Контейнер за учениците с динамично генерирано съдържание -->
    <div class="d-flex justify-content-center row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-3 g-4 py-5">
      <?php
        echo students(); // Извикване на функцията students() за показване на учениците
      ?>
    </div>
  </div>

  </main>

  <!-- Футър на страницата -->
  <div class="container">
    <footer class="d-flex flex-wrap justify-content-between align-items-center py-3 my-4 border-top">
      <p class="col-md-4 mb-0 text-body-secondary">&copy; by Toni</p> <!-- Авторско право -->
    </footer>
  </div>
  
  <!-- Линк към Bootstrap JS библиотеката -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>

</body>

</html>
