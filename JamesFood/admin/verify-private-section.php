<?php
if(!isset($_SESSION['jamesfood_admin_logged'])) {
    header('Location:sign-out.php');
    die();
}