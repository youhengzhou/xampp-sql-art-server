<?php
require "db.php";

$ArtistID = null;
if (isset($_POST['ArtistID']) && $_POST['ArtistID'] != '') {
    $ArtistID = $_POST['ArtistID'];
}

$GalleryID = null;
if (isset($_POST['GalleryID']) && $_POST['GalleryID'] != '') {
    $GalleryID = $_POST['GalleryID'];
}

$ShapeID = null;
if (isset($_POST['ShapeID']) && $_POST['ShapeID'] != '') {
    $ShapeID = $_POST['ShapeID'];
}

$memcached = new Memcached();
$memcached->addServer('localhost', 11211);

$paintings = null;
$artists = null;
$museums = null;
$shapes = null;

$filterJSON = json_encode($_POST);
if ($memcached->get('paintings' . $filterJSON) === false && $memcached->getResultCode() == Memcached::RES_NOTFOUND) {
    $paintings = getPaintings($ArtistID, $GalleryID, $ShapeID);
    $memcached->set('paintings' . $filterJSON, $paintings);
    // echo "paintings from db <br/>";
} else {
    $paintings = $memcached->get('paintings' . $filterJSON);
    // echo "paintings from memcached <br/>";
}

if ($memcached->get('artists') === false && $memcached->getResultCode() == Memcached::RES_NOTFOUND) {
    $artists = getArtists();
    $memcached->set('artists', $artists);
    // echo "artists from db <br/>";
} else {
    $artists = $memcached->get('artists');
    // echo "artists from memcached <br/>";
}

if ($memcached->get('museums') === false && $memcached->getResultCode() == Memcached::RES_NOTFOUND) {
    $museums = getMuseums();
    $memcached->set('museums', $museums);
    // echo "museums from db <br/>";
} else {
    $museums = $memcached->get('museums');
    // echo "museums from memcached <br/>";
}

if ($memcached->get('shapes') === false && $memcached->getResultCode() == Memcached::RES_NOTFOUND) {
    $shapes = getShapes();
    $memcached->set('shapes', $shapes);
    // echo "shapes from db <br/>";
} else {
    $shapes = $memcached->get('shapes');
    // echo "shapes from memcached <br/>";
}

// print_r($paintings)

?>

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

<main class="ui segment doubling stackable grid container">

    <section class="five wide column">
        <form class="ui form" action="browse-paintings.php" method="post">
            <h4 class="ui dividing header">Filters</h4>

            <div class="field">
                <label>Artist</label>
                <select class="ui fluid dropdown" name="ArtistID">
                    <option value="">Select Artist</option>
                    <?php foreach ($artists as $i => $value) { ?>
                        <option value="<?= $value['ArtistID'] ?>"><?= $value['FirstName'] . ' ' . $value['LastName'] ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="field">
                <label>Museum</label>
                <select class="ui fluid dropdown" name="GalleryID">
                    <option value="">Select Museum</option>
                    <?php foreach ($museums as $i => $value) { ?>
                        <option value="<?= $value['GalleryID'] ?>"><?= $value['GalleryName'] ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="field">
                <label>Shape</label>
                <select class="ui fluid dropdown" name="ShapeID">
                    <option value="">Select Shape</option>
                    <?php foreach ($shapes as $i => $value) { ?>
                        <option value="<?= $value['ShapeID'] ?>"><?= $value['ShapeName'] ?></option>
                    <?php } ?>
                </select>
            </div>

            <button class="small ui orange button" type="submit">
                <i class="filter icon"></i> Filter
            </button>

        </form>
    </section>


    <section class="eleven wide column">
        <h1 class="ui header">Paintings</h1>
        <p><?= ($ArtistID == null && $GalleryID == null && $ShapeID == null) ? 'ALL' : 'FILTERED' ?> PAINTINGS [TOP
            20]</p>
        <ul class="ui divided items" id="paintingsList">
            <?php foreach ($paintings as $i => $value) { ?>
                <li class="item">
                    <a class="ui small image" href="single-painting.php?id=<?= $value['PaintingID'] ?>">
                        <img src="images/art/works/square-medium/<?= $value['ImageFileName'] ?>.jpg">
                    </a>
                    <div class="content">
                        <a class="header"
                           href="single-painting.php?id=<?= $value['PaintingID'] ?>"><?= $value['Title'] ?></a>
                        <div class="meta"><span
                                    class="cinema"><?= $value['FirstName'] ?> <?= $value['LastName'] ?></span></div>
                        <div class="description">
                            <p><?= $value['Excerpt'] ?></p>
                        </div>
                        <div class="meta">
                            <strong>$<?= number_format($value['Cost'], 0, '.', ',') ?></strong>
                        </div>
                        <div class="extra">
                            <a class="ui icon orange button" href="cart.php?id=<?= $value['PaintingID'] ?>"><i
                                        class="add to cart icon"></i></a>
                            <a class="ui icon button"
                               href="addToFavorites.php?PaintingID=<?= $value['PaintingID'] ?>&ImageFileName=<?= $value['ImageFileName'] ?>&Title=<?= urlencode($value['Title']) ?>"><i
                                        class="heart icon"></i></a>
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