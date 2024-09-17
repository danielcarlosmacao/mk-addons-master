<?php
// INCLUE FUNCOES DE ADDONS -----------------------------------------------------------------------
include('addons.class.php');


//permição nessecaria para acessar essa funcao -----------------------------------
/*$permissao = "perm_logi";

include('/config/config.php'); */

// VERIFICA SE O USUARIO ESTA LOGADO --------------------------------------------------------------
session_name('mka');
if (!isset($_SESSION)) session_start();
if (!isset($_SESSION['mka_logado']) && !isset($_SESSION['MKA_Logado'])) exit('Acesso negado... <a href="/admin/login.php">Fazer Login</a>');
// VERIFICA SE O USUARIO ESTA LOGADO --------------------------------------------------------------

// Assuming $Manifest is defined somewhere before this code
$manifestTitle = $Manifest->{'name'} ?? '';
$manifestVersion = $Manifest->{'version'} ?? '';
?>

<!DOCTYPE html>
<html lang="pt-BR" class="has-navbar-fixed-top">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta charset="iso-8859-1">
<title>MK-AUTH :: <?php echo $Manifest->{'name'}; ?></title>

<link href="../../estilos/mk-auth.css" rel="stylesheet" type="text/css" />
<link href="../../estilos/font-awesome.css" rel="stylesheet" type="text/css" />
<link href="../../estilos/bi-icons.css" rel="stylesheet" type="text/css" />

<link href="public/css/main.css" rel="stylesheet" type="text/css" />

<script src="../../scripts/jquery.js"></script>
<script src="../../scripts/mk-auth.js"></script>

<style>
    

</style>


</head>
    <script>
        console.log('<?php echo $raiz?>')

    </script>

<body>
    <?php include('../../topo.php'); ?>
    <div class="container">
        <nav class="breadcrumb has-bullet-separator is-centered" aria-label="breadcrumbs">
            <ul>
                <li><a  href="#"> ADDON</a></li>
                <li class="is-active">
                    <a href="#" aria-current="page"> <?php echo htmlspecialchars($manifestTitle . " - V " . $manifestVersion); ?> </a>
                </li>
                <li><a href="ajuda" target="_blank">?</a></li>
            </ul>
        </nav>
        <div style="margin-top: 10px;">

            <div class="box-menu">
               <a href="add-buscador_notas" class="BUTTON"><img src="./public/img/notas.png" ></a>
               <h5>Notas Fiscais</h5>
            </div>
            <div class="box-menu">
               <a href="add-cli_obs" class="BUTTON"><img src="./public/img/clientes_obs.png" ></a>
               <h5>Clientes em OBS</h5>
            </div>
            <div class="box-menu">
               <a href="add-relatorio_titulos" class="BUTTON"><img src="./public/img/titulos.png" ></a>
               <h5>Titulos</h5>
            </div>
            <div class="box-menu">
               <a href="add-relatorio_titulos_baixados" class="BUTTON"><img src="./public/img/titulos_quitado.png" ></a>
               <h5>Titulos quitado</h5>
            </div>
            <div class="box-menu">
               <a href="add-sem_titulo" class="BUTTON"><img src="./public/img/clientes_s_titulo.png" ></a>
               <h5>Clientes sem titulo</h5>
            </div>

           
            </div>
    
        <?php include('../../baixo.php'); ?>
    </div>

    <script src="../../menu.js.php"></script>
    <script src="public/js/main.js"></script>
    <?php include('../../rodape.php'); ?>

</body>

</html>
