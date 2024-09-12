<?php

    include "connection.php";
    session_start();

    if(isset($_POST['submit_login'])){
        $email = $_POST['email'];
        $pswd = $_POST['password'];

        $result = mysqli_query($conn, "select * from teachers where email='$email' and password='$pswd';");

        if(mysqli_num_rows($result) > 0){
            $row = mysqli_fetch_assoc($result);
            $_SESSION["loggedIn"] = true;
            $_SESSION["school_id"] = $row['school_id'];
            $_SESSION['name'] = $row['name'];
            header('location:home.php');
        } else {
            header('location:index.php');
        }
    }

?>