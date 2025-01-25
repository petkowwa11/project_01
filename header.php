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
<header data-bs-theme="dark">
    <div class="collapse text-bg-dark" id="navbarHeader">
        <div class="container">
            <div class="row">
                <div class="col-sm-10 col-md-7 py-4">
                    <h4>Електронен дневник</h4>
                </div>
                <div class="col-sm-2 offset-md-1 py-4">
                    <ul class="list-unstyled">
                        <form action="index.php" method="post">
                            <button name="logout" class="btn btn-link text-white text-left">ИЗХОД</button>
                        </form>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="navbar navbar-dark bg-dark shadow-sm">
        <div class="container">
            <button class="btn text-secondary" id="hbtn">
                <span class="bi bi-arrow-left-square text-secondary"></span>
            </button>
            <p class="navbar-brand d-flex align-items-center">
                <strong>Добре дошли, <?php echo isset($_SESSION['name']) ? htmlspecialchars($_SESSION['name']) : 'Гост'; ?></strong>
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
    if (btn) {
        btn.addEventListener('click', () => {
            window.history.back();
        });
    }
</script>