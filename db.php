<?php
// This script provides the db connection.

define('DB_DSN', 'mysql:host=localhost;dbname=art');
define('DB_USER', 'testuser');
define('DB_PASS', 'mypassword');

$db = null;
try {
    $db = new PDO(DB_DSN, DB_USER, DB_PASS);
} catch (PDOException $e) {
    print "Error: " . $e->getMessage();
    die();
}

function getPaintings($ArtistID, $GalleryID, $ShapeID)
{
    global $db;
    $query = "SELECT * FROM paintings LEFT JOIN artists ON paintings.ArtistID = artists.ArtistID";
    if ($ArtistID != null) {
        $query .= " WHERE paintings.ArtistID=?";
    } else if ($GalleryID != null) {
        $query .= " WHERE paintings.GalleryID=?";
    } else if ($ShapeID != null) {
        $query .= " WHERE paintings.ShapeID=?";
    }
    $query .= " LIMIT 20";

    // echo $query;

    $statement = $db->prepare($query);
    if ($ArtistID != null) {
        $statement->bindParam(1, $ArtistID);
    } else if ($GalleryID != null) {
        $statement->bindParam(1, $GalleryID);
    } else if ($ShapeID != null) {
        $statement->bindParam(1, $ShapeID);
    }

    $statement->execute();

    return $statement->fetchAll();
}

function getArtists()
{
    global $db;
    $query = "SELECT * FROM artists ORDER BY LastName";
    $statement = $db->prepare($query);
    $statement->execute();
    return $statement->fetchAll();
}

function getMuseums()
{
    global $db;
    $query = "SELECT * FROM galleries ORDER BY GalleryName";
    $statement = $db->prepare($query);
    $statement->execute();
    return $statement->fetchAll();
}

function getShapes()
{
    global $db;
    $query = "SELECT * FROM shapes ORDER BY ShapeName";
    $statement = $db->prepare($query);
    $statement->execute();
    return $statement->fetchAll();
}

function getFrames()
{
    global $db;
    $query = "SELECT * FROM typesframes";
    $statement = $db->prepare($query);
    $statement->execute();
    return $statement->fetchAll();
}

function getGlass()
{
    global $db;
    $query = "SELECT * FROM typesglass";
    $statement = $db->prepare($query);
    $statement->execute();
    return $statement->fetchAll();
}

function getMatt()
{
    global $db;
    $query = "SELECT * FROM typesmatt";
    $statement = $db->prepare($query);
    $statement->execute();
    return $statement->fetchAll();
}

function getPaintingAvgRating($PaintingID)
{
    global $db;
    $query = "SELECT AVG(Rating) AS AvgRating FROM reviews WHERE PaintingID=?";
    $statement = $db->prepare($query);
    $statement->bindParam(1, $PaintingID);
    $statement->execute();
    $AvgRating = $statement->fetch();
    return $AvgRating[0];
}

function getPaintingGenres($PaintingID)
{
    global $db;
    $query = "SELECT * FROM paintinggenres LEFT JOIN genres g on paintinggenres.GenreID = g.GenreID WHERE PaintingID=?";
    $statement = $db->prepare($query);
    $statement->bindParam(1, $PaintingID);
    $statement->execute();
    return $statement->fetchAll();
}

function getPaintingSubjects($PaintingID)
{
    global $db;
    $query = "SELECT * FROM paintingsubjects LEFT JOIN subjects s on paintingsubjects.SubjectID = s.SubjectID WHERE PaintingID=?";
    $statement = $db->prepare($query);
    $statement->bindParam(1, $PaintingID);
    $statement->execute();
    return $statement->fetchAll();
}

function getPaintingReviews($PaintingID)
{
    global $db;
    $query = "SELECT * FROM reviews WHERE PaintingID=?";
    $statement = $db->prepare($query);
    $statement->bindParam(1, $PaintingID);
    $statement->execute();
    return $statement->fetchAll();
}

function getSinglePainting($PaintingID)
{
    global $db;
    $query = "SELECT * FROM paintings LEFT JOIN artists a ON paintings.ArtistID = a.ArtistID LEFT JOIN galleries g on paintings.GalleryID = g.GalleryID WHERE paintings.PaintingID=? LIMIT 1";
    // echo $query;
    $statement = $db->prepare($query);
    $statement->bindParam(1, $PaintingID);
    $statement->execute();
    $paintings = $statement->fetchAll();
    $painting = $paintings[0];
    $painting['AvgRating'] = getPaintingAvgRating($PaintingID);
    $painting['Genres'] = getPaintingGenres($PaintingID);
    $painting['Subjects'] = getPaintingSubjects($PaintingID);
    $painting['Reviews'] = getPaintingReviews($PaintingID);
    // print_r($painting['Genres']);
    return $painting;
}