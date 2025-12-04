<?php
session_start();
if (!isset($_SESSION["asiakas_id"])) {
    header("Location: kirjaudu.php");
    exit();
}

$yhteys = new mysqli("localhost", "root", "", "pizzatilaus");
if ($yhteys->connect_error) {
    die("Yhteys epäonnistui: " . $yhteys->connect_error);
}

$asiakas_id = $_SESSION["asiakas_id"];
$ostoskori = $_SESSION["ostoskori"];
$pizza_id = $ostoskori["pizza_id"];
$maara = $ostoskori["maara"];
$taytteet = $ostoskori["taytteet"];

$yhteys->query("INSERT INTO tilaukset (asiakas_id) VALUES ($asiakas_id)");
$tilaus_id = $yhteys->insert_id;

$yhteys->query("INSERT INTO tilausrivit (tilaus_id, pizza_id, maara) VALUES ($tilaus_id, $pizza_id, $maara)");
$tilausrivi_id = $yhteys->insert_id;

foreach ($taytteet as $tayte_id) {
    $yhteys->query("INSERT INTO tilaus_taytteet (tilausrivi_id, tayte_id) VALUES ($tilausrivi_id, $tayte_id)");
}

echo "<p>Tilaus tallennettu onnistuneesti!</p>";
?>

<a href="index.php">Takaisin tilaukseen</a>
