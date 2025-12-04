<?php
session_start();
$yhteys = new mysqli("localhost", "root", "", "pizzatilaus");
if ($yhteys->connect_error) {
    die("Yhteys epäonnistui: " . $yhteys->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $salasana = $_POST["salasana"];

    $stmt = $yhteys->prepare("SELECT id, nimi, salasana FROM asiakkaat WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id, $nimi, $hash);

    if ($stmt->num_rows > 0) {
        $stmt->fetch();
        if (password_verify($salasana, $hash)) {
            $_SESSION["asiakas_id"] = $id;
            $_SESSION["asiakas_nimi"] = $nimi;
            header("Location: index.php");
            exit();
        } else {
            echo "<p>Väärä salasana.</p>";
        }
    } else {
        echo "<p>Käyttäjää ei löytynyt.</p>";
    }
}
?>

<form method="post">
    <label>Email:</label><br>
    <input type="email" name="email" required><br>
    <label>Salasana:</label><br>
    <input type="password" name="salasana" required><br><br>
    <input type="submit" value="Kirjaudu">
</form>
