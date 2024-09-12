<header data-bs-theme="dark">
    <div class="collapse text-bg-dark" id="navbarHeader">
      <div class="container">
        <div class="row">
          <div class="col-sm-10 col-md-7 py-4">
            <h4>Електронен дневник</h4>
          </div>
          <div class="col-sm-2 offset-md-1 py-4">
            <ul class="list-unstyled">
              <form action ="", method = "post">
                <button name = "logout" onclick = "<?php logout();?>" class="btn btn-link text-white text-left">ИЗХОД</button>
              </form>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <div class="navbar navbar-dark bg-dark shadow-sm">
      <div class="container">
        <button class="btn text-secondary" id="hbtn"><span class="bi bi-arrow-left-square text-secondary"></span></button>
        <p class="navbar-brand d-flex align-items-center">
          <strong>Welcome, <?php echo $_SESSION['name']; ?></strong>
        </p>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarHeader"
          aria-controls="navbarHeader" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
      </div>
    </div>
  </header>

  <script>
    var btn = document.querySelector('#hbtn');
    btn.addEventListener('click', () => {
      window.history.back();
    })
  </script>