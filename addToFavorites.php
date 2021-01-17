<?php
require "functions.php";

session_start();

if (isset($_GET['PaintingID']) && isset($_GET['ImageFileName']) && isset($_GET['Title'])) {
    $fav = [];
    if (isset($_SESSION['fav'])) {
        $fav = $_SESSION['fav'];
    }

    array_push($fav, [$_GET['PaintingID'], $_GET['ImageFileName'], $_GET['Title']]);

    $_SESSION['fav'] = array_map("unserialize", array_unique(array_map("serialize", $fav)));;
    redirect('view-favorites.php');
    // print_r($_SESSION['fav']);
}
