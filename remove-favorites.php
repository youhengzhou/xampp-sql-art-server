<?php
require "functions.php";

session_start();

if (isset($_GET['PaintingID'])) {
    $fav = [];
    if (isset($_SESSION['fav'])) {
        $fav = $_SESSION['fav'];
    }
    $index = searchForId(0, $_GET['PaintingID'], $fav);
    // print_r($index);
    unset($fav[$index]);

    $_SESSION['fav'] = $fav;;

    // print_r($_SESSION['fav']);
} else {
    $_SESSION['fav'] = [];
}

redirect('view-favorites.php');