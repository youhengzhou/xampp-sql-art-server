<!DOCTYPE html>
<html lang=en>
<head>
    <meta charset=utf-8>
    <link href='http://fonts.googleapis.com/css?family=Merriweather' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>

    <script src="css/semantic.js"></script>
    <script src="js/misc.js"></script>

    <link href="css/semantic.css" rel="stylesheet">
    <link href="css/icon.css" rel="stylesheet">
    <link href="css/styles.css" rel="stylesheet">

</head>
<body>

<?php include "header.php" ?>
<?php
$paintings = [];
if (isset($_SESSION['fav'])) {
    $paintings = $_SESSION['fav'];
}
?>
<main class="ui segment doubling stackable grid container">

    <section class="eleven wide column">
        <h1 class="ui header">Favorite Paintings</h1>

        <a class="ui icon button"
           href="remove-favorites.php">Remove all</a>

        <ul class="ui divided items" id="paintingsList">
            <?php foreach ($paintings as $i => $value) { ?>
                <li class="item">
                    <a class="ui small image" href="single-painting.php?id=<?= $value[0] ?>" style="width: 70px">
                        <img src="images/art/works/square-medium/<?= $value[1] ?>.jpg">
                    </a>
                    <div class="content">
                        <a class="header"
                           href="single-painting.php?id=<?= $value[0] ?>"><?= $value[2] ?></a>

                        <div class="extra">
                            <a class="ui icon button"
                               href="remove-favorites.php?PaintingID=<?= $value[0] ?>">Remove</a>
                        </div>
                    </div>


                </li>
            <?php } ?>


        </ul>
    </section>

</main>

<footer class="ui black inverted segment">
    <div class="ui container">footer for later</div>
</footer>
</body>
</html>