<?php

include_once("../helpers/url.php");
include_once("../process/conn.php");

$txtMSG = "";

if(isset($_SESSION['txt_msg'])){

    //Atribue valor para exibir a msg:
    $txtMSG = $_SESSION['txt_msg'];
    $tipoMSG = $_SESSION['tipo_msg'];

    //Limpar a msg para não exibir mais após o erro:
    $_SESSION['txt_msg'] = "";
    $_SESSION['tipo_msg'] = "";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faça seu pedido!</title>
    <!--Link Boostrap CSS: -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <!--Fonts (CDN): -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!--CSS local: -->
    <link rel="stylesheet" href="<?= $BASE_URL ?>css/style.css">
</head>
<body>
    <header>
        <nav class="navbar navbar-expand-lg">
            <a href="index.php" class="navbar-brand">
                <img src="../img/pizza.svg" alt="Pizzaria Icarus" id="brand-logo">
            </a>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item active">
                        <a href="./" class="nav-link">
                            Peça sua Pizza!
                        </a>
                    </li>
                </ul>
                <!-- <ul class="navbar-nav mx-auto" style="width: 35%">
                    <li class="nav-item active">
                        <a href="" class="nav-link" id="nav-titulo">
                            <h1>Pizzaria Icarus</h1>
                         </a>
                     </li>
                </ul> -->
            </div>
        </nav>
    </header>
    <?php if($txtMSG != ""): ?>
        <div class="alert alert-<?= $tipoMSG ?>" id="msgAlerta">
            <p><?= $txtMSG ?></p>
        </div>
    <?php endif; ?>

    <script>
        // Função para esconder a mensagem após 3 segundos:
        setTimeout(function() {
            var msgAlerta = document.getElementById('msgAlerta');
            if (msgAlerta) {
                msgAlerta.style.display = 'none';
            }
        }, 3000); // 3 segundos
    </script>