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

$pizzat = $yhteys->query("SELECT * FROM pizzat");
$taytteet = $yhteys->query("SELECT * FROM taytteet");
?>

<h2>Pizza-tilauslomake</h2>
<form method="post" action="ostoskori.php">
    <label>Pizzakoko:</label><br>
    <select name="pizza_id">
        <?php while ($pizza = $pizzat->fetch_assoc()): ?>
            <option value="<?= $pizza['id'] ?>">
                <?= ucfirst($pizza['koko']) ?> (<?= number_format($pizza['pohjahinta'], 2) ?> €)
            </option>
        <?php endwhile; ?>
    </select><br><br>

    <label>Määrä:</label><br>
    <input type="number" name="maara" min="1" required><br><br>

    <label>Täytteet:</label><br>
    <?php while ($tayte = $taytteet->fetch_assoc()): ?>
        <input type="checkbox" name="taytteet[]" value="<?= $tayte['id'] ?>">
        <?= ucfirst($tayte['nimi']) ?> (<?= number_format($tayte['hinta'], 2) ?> €)<br>
    <?php endwhile; ?><br>

    <input type="submit" value="Lisää ostoskoriin">
</form>
