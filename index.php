<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Challenge PDO</title>
</head>
<body>
<h1>Challenge PDO</h1>

<?php
require_once 'connec.php';
$pdo = new \PDO(DSN, USER, PASS);

$query = "SELECT * FROM friend";
$statement = $pdo->query($query);
$friends = $statement->fetchALL(PDO::FETCH_ASSOC); ?>

<ul>
    <?php foreach ($friends as $friend) {
        ?><li><?php echo $friend['firstname'] . ' ' . $friend['lastname'] ; ?></li><?php
    }    ?>
</ul>

<form action="index.php" method="POST">
    <label for="firstname">Firstname :</label>
    <input type="text" id="firstname" name="firstname">

    <label for="lastname">Lastname :</label>
    <input type="text" id="lastname" name="lastname">

    <button>Submit</button>
</form>

<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST['firstname'])) {
        $errors[] = 'Le champ du prénom ne doit pas être vide';
    }

    if (empty($_POST['lastname'])) {
        $errors[] = 'Le champ du nom ne doit pas être vide';
    }

    if (strlen($_POST['firstname']) > 45) {
        $errors[] = "Le prénom ne doit pas faire plus de 45 caractères";
    }

    if (strlen($_POST['lastname']) > 45) {
        $errors[] = "Le nom ne doit pas faire plus de 45 caractères";
    }

    if (!empty($errors)) { ?>
        <ul><?php
            foreach ($errors as $error) {?>
                <li><?= $error ?></li> <?php
            }?>
        </ul> <?php } else {

        $firstname = trim($_POST['firstname']);
        $lastname = trim($_POST['lastname']);
        $query = 'INSERT INTO friend (firstname, lastname) VALUES (:firstname, :lastname)';
        $statement = $pdo->prepare($query);

        $statement->bindValue(':lastname', $lastname, \PDO::PARAM_STR);
        $statement->bindvalue(':firstname', $firstname, \PDO::PARAM_STR);
        $statement->execute();

        $friends = $statement->fetchall();
        header("location: index.php");
    }
}

?>

</body>
</html>
