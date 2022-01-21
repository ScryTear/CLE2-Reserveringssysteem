<?php
session_start();

//May I even visit this page?
if (!isset($_SESSION['loggedInUser'])) {
    header("Location: login.php");
    exit;
}


//Get email from session
$email = $_SESSION['loggedInUser']['email'];
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Veilige pagina</title>
</head>
<body>
    <h2>Secure page</h2>
    <p>Dit is de beveiligde pagina. Hier mag je alleen komen als je ingelogd bent.</p>
    <p>Welkom, <?= $email ?></p>
    <p><a href="logout.php">Uitloggen</a></p>
</body>
</html>
