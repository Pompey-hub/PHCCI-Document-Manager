<?php
session_start();

/* Check if user is logged in */
if(isset($_SESSION['user'])){

    if($_SESSION['user']['role'] == 'admin'){
        header("Location: admin/index.php");
        exit;
    }

    if($_SESSION['user']['role'] == 'collector'){
        header("Location: collector/index.php");
        exit;
    }

    if($_SESSION['user']['role'] == 'superadmin'){
        header("Location: superadmin/index.php");
        exit;
    }

}

/* If not logged in */
header("Location: auth/login.php");
exit;