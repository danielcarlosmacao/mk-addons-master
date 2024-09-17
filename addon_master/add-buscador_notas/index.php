<?php
// INCLUE FUNCOES DE ADDONS -----------------------------------------------------------------------
include('addons.class.php');



include('../../../topo.php'); 
 
//permição nessecaria para acessar essa funcao -----------------------------------

$permissao = "perm_nfe";

include('../app/config/config.php');    


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

<link href="../../../estilos/mk-auth.css" rel="stylesheet" type="text/css" />
<link href="../../../estilos/font-awesome.css" rel="stylesheet" type="text/css" />
<link href="../../../estilos/bi-icons.css" rel="stylesheet" type="text/css" />

<script src="../../../scripts/jquery.js"></script>
<script src="../../../scripts/mk-auth.js"></script>

<style>
    /* Estilos CSS personalizados */
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #ffffff;
        margin: 0;
        padding: 0;
        color: #333;
    }

    .container {
        width: 100%;
        max-width: 1600px; /* Aumentando a largura máxima */
        margin: 20px auto;
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        padding: 20px;
    }

    form {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    form label {
        font-weight: bold;
        margin-right: 10px;
    }

    .search-group {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
    }

    input[type="text"],
    input[type="date"],
    input[type="submit"],
    .clear-button {
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        margin-bottom: 10px;
    }

    input[type="submit"],
    .clear-button {
        background-color: #007bff;
        color: #fff;
        cursor: pointer;
    }

    input[type="submit"]:hover,
    .clear-button:hover {
        background-color: #0056b3;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    th,
    td {
        padding: 9px; /* Aumentando o padding para melhor legibilidade */
        text-align: left;
        border-bottom: 1px solid #ddd;
        font-size: 14px;
    }

    th {
        background-color: #007bff;
        color: #fff;
    }

    .no-data {
        text-align: center;
        color: #777;
        padding: 20px;
    }

    .total-section {
        display: flex;
        justify-content: space-between;
        margin-top: 20px;
        padding: 10px;
        border-radius: 5px;
        background-color: #e4e4e4;
        color: #fff;
    }

    .total-section .total {
        font-weight: bold;
    }

    .hidden {
        display: none;
    }

    .tamanhomed{
        width: 100px;
    }
    .tamanhogran{
        width: 250px;
    }

    
</style>


</head>
    <script>
        function clearSearch() {
            document.getElementById('search').value = '';
            document.getElementById('plano').value = '';
            document.getElementById('nota_inicial').value = '';
            document.getElementById('nota_final').value = '';
            document.getElementById('searchForm').submit();
        }
        function clearSearchvalor() {
            document.getElementById('search').value = '';
            document.getElementById('ex1').value = '';
            document.getElementById('ex2').value = '';
            document.getElementById('ex3').value = '';
            document.getElementById('ex4').value = '';
            document.getElementById('ex5').value = '';
            document.getElementById('ex6').value = '';
            document.getElementById('ex7').value = '';
            document.getElementById('searchForm').submit();
        }

// Esconde os elementos tarifa-row quando a página é carregada
window.onload = function() {
    toggleTarifaRows();
};

function toggleTarifaRows() {
    var tarifaRows = document.querySelectorAll('tr.tarifa-row');
    var toggleButton = document.getElementById('toggleButton');

    tarifaRows.forEach(function(row) {
        row.classList.toggle('hidden');
    });

    if (toggleButton.innerText === 'Mostrar') {
        toggleButton.innerText = 'Ocultar';
    } else {
        toggleButton.innerText = 'Mostrar';
    }
}

    </script>

<body>
 

    <div class="container">
        <nav class="breadcrumb has-bullet-separator is-centered" aria-label="breadcrumbs">
            <ul>
                <li><?php include('../app/view/voltar.php');?></li>
                <li><a href="#"> ADDON</a></li>
                <li class="is-active">
                <a href="#" aria-current="page"> <?php echo htmlspecialchars($manifestTitle . " - V " . $manifestVersion); ?> </a>
                </li>
                
            </ul>
        </nav>

        <?php 
 ?>

        <?php if ($acesso_permitido) : ?>
            <form id="searchForm" method="GET">

                <label for="nota_inicial"> Nota Inicial:</label>
                <input type="text" class="tamanhogran" id="nota_inicial" name="nota_inicial" value="<?php echo isset($_GET['nota_inicial']) ? htmlspecialchars($_GET['nota_inicial']) : ''; ?>">
                <label for="nota_final"> Nota Final:</label>
                <input type="text" class="tamanhogran" id="nota_final" name="nota_final" value="<?php echo isset($_GET['nota_final']) ? htmlspecialchars($_GET['nota_final']) : ''; ?>">
               
                <label for="search" >valor:</label>
                <input type="text" class="tamanhogran" id="search" name="search" placeholder="todos" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
            


                <input type="submit" value="Buscar">
                <button type="button" onclick="clearSearch()" class="clear-button">Limpar Filtro</button>
                <button type="button" onclick="clearSearchvalor()" class="clear-button">limpar valor</button>
                <br><br><br><br><br>
                <div>
                    <label for="cfoppf"> CFOP PF:</label>
                    <input type="text" class="tamanhomed" id="cfoppf" name="cfoppf" value="<?php echo isset($_GET['cfoppf']) ? htmlspecialchars($_GET['cfoppf']) : ''; ?>">
                    <label for="cfoppj"> CFOP PJ:</label>
                    <input type="text" class="tamanhomed" id="cfoppj" name="cfoppj" value="<?php echo isset($_GET['cfoppj']) ? htmlspecialchars($_GET['cfoppj']) : ''; ?>">
                    <label for="plano">Plano:</label>
                    <input type="text" class="tamanhogrand"  id="plano" name="plano" placeholder="todos" value="<?php echo isset($_GET['plano']) ? htmlspecialchars($_GET['plano']) : ''; ?>">
                
                    <label for="ex1">exeto os valores:</label>
                    <input type="checkbox"  id="erro" name="erro"  placeholder="todos" <?php if(isset($_GET['erro'])){echo 'checked'; }?>>
                    <input type="text" class="tamanhomed" id="ex1" name="ex1" value="<?php echo isset($_GET['ex1']) ? htmlspecialchars($_GET['ex1']) : ''; ?>">
                    <input type="text" class="tamanhomed" id="ex2" name="ex2" value="<?php echo isset($_GET['ex2']) ? htmlspecialchars($_GET['ex2']) : ''; ?>">
                    <input type="text" class="tamanhomed" id="ex3" name="ex3" value="<?php echo isset($_GET['ex3']) ? htmlspecialchars($_GET['ex3']) : ''; ?>">
                    <input type="text" class="tamanhomed" id="ex4" name="ex4" value="<?php echo isset($_GET['ex4']) ? htmlspecialchars($_GET['ex4']) : ''; ?>">
                    <input type="text" class="tamanhomed" id="ex5" name="ex5" value="<?php echo isset($_GET['ex5']) ? htmlspecialchars($_GET['ex5']) : ''; ?>">
                    <input type="text" class="tamanhomed" id="ex6" name="ex6" value="<?php echo isset($_GET['ex6']) ? htmlspecialchars($_GET['ex6']) : ''; ?>">
                    <input type="text" class="tamanhomed" id="ex7" name="ex7" value="<?php echo isset($_GET['ex7']) ? htmlspecialchars($_GET['ex7']) : ''; ?>">
                </div>
            </form>

            <?php

            $query = "SELECT n.login, n.numero, n.emissao, n.cfop, n.id_titulo , n.valor_total, n.obs
                      FROM sis_nfe n";

            // Se datas não foram fornecidas no formulário de pesquisa, adicione condições à consulta SQL para buscar pela data atual
           if (!isset($_GET['nota_inicial']) && !isset($_GET['nota_final'])) {
                $nota_1 = '0';
                $query .= " WHERE numero = '$nota_1'";
            } /**/
            // Se datas foram fornecidas no formulário de pesquisa, adicione condições à consulta SQL
           else if (isset($_GET['nota_inicial']) && isset($_GET['nota_final'])) {
                $nota_inicial = $_GET['nota_inicial'];
                $nota_final =  $_GET['nota_final'];
                $query .= " WHERE n.numero BETWEEN '$nota_inicial' AND '$nota_final'";
            }

            /// Se um termo de pesquisa foi fornecido, adicione condições à consulta SQL
            if (isset($_GET['search'])) {
            $search_term = mysqli_real_escape_string($link, trim($_GET['search'])); // Remover espaços em branco no início e no final
            if($search_term == true){
            $query .= " AND n.valor_total = '$search_term'";
            }}


            if ($_GET['erro']== on){

                $ex1 = mysqli_real_escape_string($link, trim($_GET['ex1']));
                $ex2 = mysqli_real_escape_string($link, trim($_GET['ex2']));
                $ex3 = mysqli_real_escape_string($link, trim($_GET['ex3']));
                $ex4 = mysqli_real_escape_string($link, trim($_GET['ex4']));
                $ex5 = mysqli_real_escape_string($link, trim($_GET['ex5']));
                $ex6 = mysqli_real_escape_string($link, trim($_GET['ex6']));
                $ex7 = mysqli_real_escape_string($link, trim($_GET['ex7']));

                $query = "SELECT n.login, n.numero, n.emissao, n.cfop, n.id_titulo , n.valor_total, n.obs
                FROM sis_nfe n WHERE n.numero BETWEEN '$nota_inicial' AND '$nota_final' AND n.valor_total NOT IN ('$ex1','$ex2','$ex3','$ex4','$ex5','$ex6','$ex7')";

                
            
}

            // Adicionando a cláusula ORDER BY para ordenar por data mais recente
           /* $query .= " ORDER BY  DESC";*/
            // Execute a consulta
             
            $query .= " ORDER BY valor_total ASC";


            $result = mysqli_query($link, $query);

            
          
            // Calcular o total de entradas e o número total de boletos
            $tot_entrada = 0;
            $total_boletos_ = 0; // Inicializando a contagem
            $tot_saida = 0;
           
            // Loop através dos resultados e somar as entradas e saídas
            while ($row = mysqli_fetch_assoc($result)) {
            
            //$tot_saida += $row['saida'];

            // Verificar se a entrada é um boleto
            if ($row['login'] != false) {
            $total_boletos_++; // Incrementar a contagem de boletos apenas para entradas
            }
            if ($row['cfop'] == $_GET['cfoppf']) {
            $total_cfop1_++; // Incrementar a contagem de boletos apenas para entradas
            }
            if ($row['cfop'] == $_GET['cfoppj']) {
            $total_cfop2_++; // Incrementar a contagem de boletos apenas para entradas
            }


            }

            // Calcular o saldo como antes
            //$saldo = $tot_entrada - $tot_saida;


           // echo($query)
            ?>

            <div class="total-section" style="font-size: 20px;">
			    <div class="total" style="color: black;">Total Notas: <?php echo $total_boletos_; ?></div><!-- Adicionando a contagem de boletos -->	
			    <div class="total" style="color: black;">Total PF: <?php echo $total_cfop1_;?></div><!-- Adicionando a contagem  cfop pf-->	
			    <div class="total" style="color: black;">Total PJ: <?php echo $total_cfop2_; ?></div><!-- Adicionando a contagem  cfop pj -->	
            </div>



            <?php if ($result && mysqli_num_rows($result) > 0) : ?>
                <table>
                    <thead>
                        <tr>
                            <th style="color: white;">#</th>
                            <th style="color: white;">Nome do Cliente</th>
                            <th style="color: white;">Login</th>
                            <th style="color: white;">Plano</th>
                            <th style="color: white;">numero</th>
                            <th style="color: white;">data emissao</th>
                            <th style="color: white;">CFOP</th>
                            <th style="color: white;">OBS / Plano Adicional</th>
                            <th style="color: white;">Valor</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php mysqli_data_seek($result, 0); // Voltar ao início do resultado ?>
                        <?php $rowNumber = 0; ?>
                        <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                            <?php
                            // Adicionar a classe "highlight" a cada segunda linha
                            $nomeClienteClass = ($rowNumber % 2 == 0) ? 'highlight' : '';
                            $tarifaRowClass = (strpos($row['historico'], 'Tarifa do GerenciaNet') !== false) ? 'tarifa-row' : ''; 
                            ?>
                            <tr class="<?php echo $nomeClienteClass . ' ' . $tarifaRowClass; ?>">
                        
                            <!--Cria numeros em ordem crescente apartir do 1-->
                            
                            <td style="font-weight: bold;"><?php $i= $i+1 ; echo $i; ?></td>


                                <!--Exibe Nome do Cliente	 -->
                            <td style="text-align: left; cursor: default;"> 
                                <?php
                                // Encontrar o ID no histórico usando expressão regular
                              /*  preg_match('/titulo (\d+)/', $row['historico'], $matches);
                                $id = isset($matches[1]) ? $matches[1] : '--';*/
                                $login =$row['login'];

                                // Consulta SQL para obter o login e uuid_cliente com base no ID
                                $cliente_query = "SELECT c.nome, l.login, l.plano, c.uuid_cliente FROM sis_cliente l 
                                JOIN sis_cliente c ON l.login = c.login
                                WHERE l.login = '$login'";
                                $cliente_result = mysqli_query($link, $cliente_query);
                                $cliente_row = mysqli_fetch_assoc($cliente_result);
                                $nome_cliente = isset($cliente_row['nome']) ? $cliente_row['nome'] : '--';
                                $login = isset($cliente_row['login']) ? $cliente_row['login'] : '--';
                                $plano = isset($cliente_row['plano']) ? $cliente_row['plano'] : '--';
                                $uuid_cliente = isset($cliente_row['uuid_cliente']) ? $cliente_row['uuid_cliente'] : '';

                                // Limitar o tamanhogran do nome do cliente
                                $max_length = 20; // Defina o comprimento máximo desejado
                                $nome_cliente_truncado = strlen($nome_cliente) > $max_length ? substr($nome_cliente, 0, $max_length) . '...' : $nome_cliente;

                                // Exibir o nome do cliente e link
                               /**/ echo '<a href="../../../cliente_det.hhvm?uuid=' . $uuid_cliente . '" target="_blank" style="color: #06683e; display: flex; align-items: center;" title="' . $nome_cliente . '">';
                                //echo '<img src="img/icon_cliente.png" alt="Ícone Digital" style="width: 20px; height: 20px; margin-right: 10px; float: left;">';
                                echo '<span style="color: #0d6cea; font-weight: bold;">' . $nome_cliente_truncado . '</span>';
                                echo '</a>';
                                ?>
                                </td>

                                <!--Exibe login-->
                                <td style=" font-weight: bold;">
                                   <?php
                                   // Definir o comprimento máximo desejado para o nome de login
                                   $max_length = 15;

                                   // Limitar o tamanhogran do nome de login, se necessário
                                   $login_truncado = (strlen($row['login']) > $max_length) ? substr($row['login'], 0, $max_length) . '...' : $row['login'];
                                   echo  $login_truncado;
                                   ?>
                                  
                                </td>

                                <!--Exibe Plano --> 
                                <td style=" color:<?php

                                if($_GET['plano'] != "" && $_GET['plano'] != $plano){
                                    echo "red";
                                }
                                ?>; font-weight: bold;"><?php echo $plano;?></td> <!-- Data -->

                     
                                <!--Exibe Numero Nota--> 
                                <td style="font-weight: bold;"><?php echo $row['numero'];?></td> 
                     
                                <!--Exibe Data emissao--> 
                                <td style="font-weight: bold;"><?php echo date('d-m-Y', strtotime($row['emissao']));?></td> 

                                <!--Exibe cfop -->
                                <td style="font-weight: bold;"><?php echo $row['cfop']; ?></td>

                                <!--Exibe OBS -->
                                <td style="font-weight: bold;"><?php


                                $adicional_query = "SELECT  ad.plano FROM sis_adicional ad 
                                WHERE ad.login = '$login'";
                                $adicional_result = mysqli_query($link, $adicional_query);
                                $adicional_row = mysqli_fetch_assoc($adicional_result);
                                $planoad = isset($adicional_row['plano']) ? $adicional_row['plano'] : '--';

                                $max_length = 50;
                                $obs_truncado = (strlen($row['obs']) > $max_length) ? substr($row['obs'], 0, $max_length) . '...' : $row['obs'];
                                 echo $obs_truncado; 
                                 echo $planoad ;
                                 ?></td>

                                <!--Exibe Valor total -->
                                <td style="color:#0d6cea ;font-weight: bold;">
                                    <?php
                                // Construir o link de busca com as datas formatadas
                                   $link_busca = "?search=" . urlencode($row['valor_total']) . "&nota_inicial=" . urlencode($_GET['nota_inicial']) . "&nota_final=" . urlencode($_GET['nota_final']);
                                   ?>
                                 <a href="<?php echo $link_busca; ?>" title="<?php echo htmlspecialchars($row['valor_total']); ?>" style="text-decoration: none; color: inherit;"><?php echo $row['valor_total']; ?></a>
                            </td>


                                
                            </tr>
                            <?php $rowNumber++; ?>
                        <?php endwhile; ?>
                    </tbody>
                </table>

            <?php else : ?>
                <p class="no-data">Nenhum resultado encontrado.</p>
            <?php endif; ?>
        <?php else : ?>
            <p class="no-data">Acesso não permitido!</p>
        <?php endif; ?>

        <?php include('../../../baixo.php'); ?>
    </div>

    <script src="../../../menu.js.php"></script>
    <?php include('../../../rodape.php'); ?>

</body>

</html>
