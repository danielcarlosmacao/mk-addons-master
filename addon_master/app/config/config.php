<?php

// alteração rota para ir na raiz do mk 
$raiz = '../../';


// Conex�o com o Banco de Dados
$link = mysqli_connect("localhost", "root", "vertrigo", 'mkradius');

// Verificar a conex�o
if (!$link) {
    die("Falha na conex�o: " . mysqli_connect_error());
}

// Verificar se a sess�o MKA_Usuario est� vazia
$usuario_logado = isset($_SESSION['MKA_Usuario']) ? $_SESSION['MKA_Usuario'] : $_SESSION['MM_Usuario'];

// Fix MK-AUTH vers�es antigas
if (isset($_SESSION['MM_Usuario'])) {
    echo '<script src="'.$raiz.'../../scripts/vue.js"></script>';
}

//$permissao = "perm_nfe";

// Verificar a permiss�o no banco de dados
$query_permissao = mysqli_query($link, "SELECT usuario FROM sis_perm WHERE nome LIKE '$permissao' AND usuario LIKE '$usuario_logado' AND permissao LIKE 'sim'");

if ($query_permissao) {
    $liberar_permissao = mysqli_num_rows($query_permissao);
    if ($liberar_permissao >= 1) {
        //echo "Acesso Liberado!"; // TUDO OK.
        $acesso_permitido = true;
    } else {
        //echo "Acesso Negado!";
        $acesso_permitido = false;
    }
} else {
    echo "Erro na consulta de permiss�o: " . mysqli_error($link);
    $acesso_permitido = false;
}

// Fix for MKAUTH 22.02
$ext_mk = (file_exists($raiz."../../index.hhvm")) ? '.hhvm' : '.php';

// N�o feche a conex�o aqui, ser� fechada no final do script index.php

?>