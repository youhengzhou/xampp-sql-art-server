<?php
require "db.php";

$PaintingID = null;
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $PaintingID = $_GET['id'];
} else {
    $PaintingID = 5;
}

$painting = getSinglePainting($PaintingID);
$frames = getFrames();
$glasses = getGlass();
$matts = getMatt();

$paintings = getPaintings(null, null, null);

// print_r($painting);

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

<main>
    <!-- Main section about painting -->
    <section class="ui segment grey100">
        <div class="ui doubling stackable grid container">

            <div class="nine wide column">
                <img src="images/art/works/medium/<?= $painting['ImageFileName'] ?>.jpg" alt="..." class="ui big image"
                     id="artwork">

                <div class="ui fullscreen modal">
                    <div class="image content">
                        <img src="images/art/works/large/<?= $painting['ImageFileName'] ?>.jpg" alt="..." class="image">
                        <div class="description">
                            <p><?= $painting['Description'] ?></p>
                        </div>
                    </div>
                </div>

            </div>    <!-- END LEFT Picture Column -->

            <div class="seven wide column">

                <!-- Main Info -->
                <div class="item">
                    <h2 class="header"><?= $painting['Title'] ?></h2>
                    <h3><?= $painting['FirstName'] . ' ' . $painting['LastName'] ?></h3>
                    <div class="meta">
                        <p>
                            <i class="<?= $painting['AvgRating'] >= 1 ? 'orange' : 'empty' ?> star icon"></i>
                            <i class="<?= $painting['AvgRating'] >= 2 ? 'orange' : 'empty' ?> star icon"></i>
                            <i class="<?= $painting['AvgRating'] >= 3 ? 'orange' : 'empty' ?> star icon"></i>
                            <i class="<?= $painting['AvgRating'] >= 4 ? 'orange' : 'empty' ?> star icon"></i>
                            <i class="<?= $painting['AvgRating'] >= 5 ? 'orange' : 'empty' ?> star icon"></i>
                        </p>
                        <p><?= ($painting['Excerpt']) ?></p>
                    </div>
                </div>

                <!-- Tabs For Details, Museum, Genre, Subjects -->
                <div class="ui top attached tabular menu ">
                    <a class="active item" data-tab="details"><i class="image icon"></i>Details</a>
                    <a class="item" data-tab="museum"><i class="university icon"></i>Museum</a>
                    <a class="item" data-tab="genres"><i class="theme icon"></i>Genres</a>
                    <a class="item" data-tab="subjects"><i class="cube icon"></i>Subjects</a>
                </div>

                <div class="ui bottom attached active tab segment" data-tab="details">
                    <table class="ui definition very basic collapsing celled table">
                        <tbody>
                        <tr>
                            <td>
                                Artist
                            </td>
                            <td>
                                <a href="#"><?= $painting['FirstName'] . ' ' . $painting['LastName'] ?></a>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Year
                            </td>
                            <td>
                                <?= $painting['YearOfWork'] ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Medium
                            </td>
                            <td>
                                <?= $painting['Medium'] ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Dimensions
                            </td>
                            <td>
                                <?= $painting['Width'] ?>cm x <?= $painting['Height'] ?>cm
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>

                <div class="ui bottom attached tab segment" data-tab="museum">
                    <table class="ui definition very basic collapsing celled table">
                        <tbody>
                        <tr>
                            <td>
                                Museum
                            </td>
                            <td>
                                <?= $painting['GalleryName'] ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Assession #
                            </td>
                            <td>
                                <?= $painting['AccessionNumber'] ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Copyright
                            </td>
                            <td>
                                <?= $painting['CopyrightText'] ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                URL
                            </td>
                            <td>
                                <a href="<?= $painting['MuseumLink'] ?>">View
                                    painting at museum site</a>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="ui bottom attached tab segment" data-tab="genres">

                    <ul class="ui list">
                        <?php foreach ($painting['Genres'] as $i => $value) { ?>
                            <li class="item"><a href="<?= $value['Link'] ?>"><?= $value['GenreName'] ?></a></li>
                        <?php } ?>
                    </ul>

                </div>
                <div class="ui bottom attached tab segment" data-tab="subjects">
                    <ul class="ui list">
                        <?php foreach ($painting['Subjects'] as $i => $value) { ?>
                            <li class="item"><a href="#"><?= $value['SubjectName'] ?></a></li>
                        <?php } ?>
                    </ul>
                </div>

                <!-- Cart and Price -->
                <div class="ui segment">
                    <div class="ui form">
                        <div class="ui tiny statistic">
                            <div class="value">
                                $<?= number_format($painting['Cost'], 0, '.', ',') ?>
                            </div>
                        </div>
                        <div class="four fields">
                            <div class="three wide field">
                                <label>Quantity</label>
                                <input type="number">
                            </div>
                            <div class="four wide field">
                                <label>Frame</label>
                                <select id="frame" class="ui search dropdown">
                                    <?php foreach ($frames as $i => $value) { ?>
                                        <option value="<?= $value['FrameID'] ?>"><?= $value['Title'] ?>
                                            [$<?= number_format($value['Price'], 0, '.', ',') ?>]
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="four wide field">
                                <label>Glass</label>
                                <select id="glass" class="ui search dropdown">
                                    <?php foreach ($glasses as $i => $value) { ?>
                                        <option value="<?= $value['GrassID'] ?>"><?= $value['Title'] ?>
                                            [$<?= number_format($value['Price'], 0, '.', ',') ?>]
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="four wide field">
                                <label>Matt</label>
                                <select id="matt" class="ui search dropdown">
                                    <?php foreach ($matts as $i => $value) { ?>
                                        <option value="<?= $value['MattID'] ?>"><?= $value['Title'] ?>
                                            [<?= $value['ColorCode'] ?>]
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="ui divider"></div>

                    <button class="ui labeled icon orange button">
                        <i class="add to cart icon"></i>
                        Add to Cart
                    </button>
                    <a class="ui right labeled icon button" href="addToFavorites.php?PaintingID=<?= $painting['PaintingID'] ?>&ImageFileName=<?= $painting['ImageFileName'] ?>&Title=<?= urlencode($painting['Title']) ?>">
                        <i class="heart icon"></i>
                        Add to Favorites
                    </a>
                </div>     <!-- END Cart -->

            </div>    <!-- END RIGHT data Column -->
        </div>        <!-- END Grid -->
    </section>        <!-- END Main Section -->

    <!-- Tabs for Description, On the Web, Reviews -->
    <section class="ui doubling stackable grid container">
        <div class="sixteen wide column">

            <div class="ui top attached tabular menu ">
                <a class="active item" data-tab="first">Description</a>
                <a class="item" data-tab="second">On the Web</a>
                <a class="item" data-tab="third">Reviews</a>
            </div>

            <div class="ui bottom attached active tab segment" data-tab="first">
                <?= $painting['Description'] ?>
            </div>    <!-- END DescriptionTab -->

            <div class="ui bottom attached tab segment" data-tab="second">
                <table class="ui definition very basic collapsing celled table">
                    <tbody>
                    <tr>
                        <td>
                            Wikipedia Link
                        </td>
                        <td>
                            <a href="<?= $painting['WikiLink'] ?>">View painting on Wikipedia</a>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            Google Link
                        </td>
                        <td>
                            <a href="<?= $painting['GoogleLink'] ?>">View painting on Google Art Project</a>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            Google Text
                        </td>
                        <td>
                            <?= $painting['GoogleDescription'] ?>
                        </td>
                    </tr>


                    </tbody>
                </table>
            </div>   <!-- END On the Web Tab -->

            <div class="ui bottom attached tab segment" data-tab="third">
                <div class="ui feed">
                    <?php
                    $len = count($painting['Reviews']);
                    foreach ($painting['Reviews'] as $i => $value) { ?>
                        <div class="event">
                            <div class="content">
                                <div class="date"><?= date('m/d/Y', strtotime($value['ReviewDate'])) ?></div>
                                <div class="meta">
                                    <a class="like">
                                        <?= $value['Rating'] >= 1 ? '<i class="star icon"></i>' : '' ?>
                                        <?= $value['Rating'] >= 2 ? '<i class="star icon"></i>' : '' ?>
                                        <?= $value['Rating'] >= 3 ? '<i class="star icon"></i>' : '' ?>
                                        <?= $value['Rating'] >= 4 ? '<i class="star icon"></i>' : '' ?>
                                        <?= $value['Rating'] >= 5 ? '<i class="star icon"></i>' : '' ?>
                                    </a>
                                </div>
                                <div class="summary">
                                    <?= $value['Comment'] ?>
                                </div>
                            </div>
                        </div>


                        <?php if ($i != $len - 1) { ?>
                            <div class="ui divider"></div>
                        <?php }
                    } ?>
                </div>
            </div>   <!-- END Reviews Tab -->

        </div>
    </section> <!-- END Description, On the Web, Reviews Tabs -->

    <!-- Related Images ... will implement this in assignment 2 -->
    <section class="ui container">
        <h3 class="ui dividing header">Related Works</h3>

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
                    </div>
                </li>
            <?php } ?>


        </ul>
    </section>

</main>


<footer class="ui black inverted segment">
    <div class="ui container">footer</div>
</footer>
</body>
</html>