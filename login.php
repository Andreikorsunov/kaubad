<?php
// login vorm koodis Andmebaasis salvestatud kasutajanimetus ja parooliga
session_start();

// login ja pass kontroll
if(isset($_REQUEST["knimi"]) && isset($_REQUEST["psw"])){
    $login=htmlspecialchars($_REQUEST["knimi"]);
    $pass=htmlspecialchars($_REQUEST["psw"]);

    $sool="vagavagatekst";
    $krypt=crypt($pass, $sool);
    // kontrollime kas andmebaasis on selline kasutaja
    $yhendus=new mysqli("localhost", "andrei17", "123456", "andrei17");
    $kask=$yhendus->prepare("SELECT id, unimi, psw, isadmin FROM uuedkasutajad WHERE unimi=?");
    $kask->bind_param("s", $login);
    $kask->bind_result($id, $kasutajanimi, $parool, $onadmin);
    $kask->execute();
    if($kask->fetch() && $krypt == $parool) {
        $_SESSION["unimi"] = $login;
        if ($onadmin == 1) {
            $_SESSION["admin"] = true;
            header("Location: kaubahaldus.php");
        }
        header("Location: kaubahaldus.php");
        $yhendus->close();
        exit();
    }
        echo "kasutaja $login või parool $krypt on vale";
        $yhendus->close();
    }
//CREATE TABLE kasutajad(
//    id int not null primary key AUTO_INCREMENT,
//    nimi varchar(50),
//    parool varchar(200),
//    onAdmin tinyint,
//    koduleht varchar(100))
?>
<!DOCTYPE html>
<html lang="et">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="login.css" alt="css">
</head>
<body>
<a href="#modal-opened" class="link-1" id="modal-closed">Login vorm</a>
<div class="modal-container" id="modal-opened">
    <div class="modal">
        <div class="modal__details">
            <form class="link-1" action="login.php" method="post">
                <img class="displayed" src="pildid/cr7.png" alt="cr7" width="100" height="100">
                <label for="knimi" class="modal__description">Kasutajanimi</label>
                <input type="text" class="modal__description" placeholder="Sisesta kasutajanimi"
                       name="knimi" id="knimi" required>
                <br>
                <label for="psw" class="modal__description">Parool</label>
                <input type="password" class="modal__description" placeholder="Sisesta parool"
                       name="psw" id="psw" required>
                <br>
                <br>
                <input type="submit" class="modal__btn" value="Logi Sisse">
                <a href="#modal-closed" class="link-2"></a>
                <br>
                <button type="button"
                        onclick="window.location.href='kaubahaldus.php'"
                        class="modal__btn">Loobu</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>