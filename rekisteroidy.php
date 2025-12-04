<?php
$yhteys = new mysqli("localhost", "root", "", "pizzatilaus");
if ($yhteys->connect_error) {
    die("Yhteys epäonnistui: " . $yhteys->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nimi = $_POST["nimi"];
    $email = $_POST["email"];
    $salasana = password_hash($_POST["salasana"], PASSWORD_DEFAULT);

    $stmt = $yhteys->prepare("INSERT INTO asiakkaat (nimi, email, salasana) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $nimi, $email, $salasana);
    $stmt->execute();

    echo "<p>Rekisteröinti onnistui!</p>";
}
?>

<form method="post">
    <label>Nimi:</label><br>
    <input type="text" name="nimi" required><br>
    <label>Email:</label><br>
    <input type="email" name="email" required><br>
    <label>Salasana:</label><br>
    <input type="password" name="salasana" required><br><br>
    <input type="submit" value="Rekisteröidy">
</form>
