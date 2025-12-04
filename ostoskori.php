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

$pizza_id = $_POST["pizza_id"];
$maara = $_POST["maara"];
$taytteet = $_POST["taytteet"] ?? [];

$pizza = $yhteys->query("SELECT * FROM pizzat WHERE id = $pizza_id")->fetch_assoc();
$pizzahinta = $pizza["pohjahinta"];
$taytteiden_hinta = 0;
$taytteiden_nimet = [];

foreach ($taytteet as $tayte_id) {
    $tayte = $yhteys->query("SELECT * FROM taytteet WHERE id = $tayte_id")->fetch_assoc();
    $taytteiden_hinta += $tayte["hinta"];
    $taytteiden_nimet[] = $tayte["nimi"];
}

$yhteishinta = ($pizzahinta + $taytteiden_hinta) * $maara;

echo "<h3>Ostoskori</h3>";
echo "<p>Pizzakoko: " . ucfirst($pizza["koko"]) . "</p>";
echo "<p>Määrä: $maara</p>";
echo "<p>Täytteet: " . implode(", ", $taytteiden_nimet) . "</p>";
echo "<p>Yhteishinta: " . number_format($yhteishinta, 2) . " €</p>";

$_SESSION["ostoskori"] = [
    "pizza_id" => $pizza_id,
    "maara" => $maara,
    "taytteet" => $taytteet,
    "hinta" => $yhteishinta
];
?>

<form method="post" action="vahvista_tilaus.php">
    <input type="submit" value="Vahvista tilaus">
</form>
