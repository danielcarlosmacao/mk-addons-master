<?php
// INCLUE FUNCOES DE ADDONS -----------------------------------------------------------------------
include('addons.class.php');

//permição nessecaria para acessar essa funcao -----------------------------------

$permissao = "perm_caixa";

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
</style>


</head>
    <script>
        function clearSearch() {
            document.getElementById('search').value = '';
            document.getElementById('statuspag').value = '';
            document.getElementById('data_inicial').value = '<?php echo date('Y-m-d'); ?>';
            document.getElementById('data_final').value = '<?php echo date('Y-m-d'); ?>';
            document.getElementById('searchForm').submit();
        }
        function clearSearchMeio() {
            document.getElementById('search').value = '';
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
    <?php include('../../../topo.php'); ?>

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


        <?php if ($acesso_permitido) : ?>
            <form id="searchForm" method="GET">
                <label for="search">login:</label>
                <input type="text" id="search" name="search" placeholder="todos" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                
                <label for="statuspag">Status Pagamento:</label>
                <!--<input type="text" id="statuspag" name="statuspag" placeholder="todos" value="<?php echo isset($_GET['statuspag']) ? htmlspecialchars($_GET['statuspag']) : ''; ?>">-->
                <select id="statuspag" name="statuspag" placeholder="todos" value="<?php echo isset($_GET['statuspag']) ? htmlspecialchars($_GET['statuspag']) : ''; ?>"> 
                <option value="Todos">Todos</option>
                <option value="Pago">Quitado</option>
                <option value="Deletado">Deletado</option>
                </select>
                <label for="data_inicial">Data Inicial:</label>
                <input type="date" id="data_inicial" name="data_inicial" value="<?php echo isset($_GET['data_inicial']) ? htmlspecialchars($_GET['data_inicial']) : date('Y-m-d'); ?>">
                <label for="data_final">Data Final:</label>
                <input type="date" id="data_final" name="data_final" value="<?php echo isset($_GET['data_final']) ? htmlspecialchars($_GET['data_final']) : date('Y-m-d'); ?>">
                <label for="search">Titulos com erro:</label>
                <input type="checkbox"  id="erro" name="erro" placeholder="todos" >
                <input type="submit" value="Buscar">
                <button type="button" onclick="clearSearch()" class="clear-button">Limpar Filtro</button>
                <button type="button" onclick="clearSearchMeio()" class="clear-button">limpar login e status</button>
            </form>

            <?php
            // Dados de conexão com o banco de dados já estão em config.php
            /*/ Consulta SQL para obter as entradas e saídas no caixa com histórico, data e usuário
            $query = "SELECT c.entrada, c.saida, c.historico, c.data, c.usuario 
                      FROM sis_caixa c ";*/
       


            $query = "SELECT c.login, c.datavenc, c.datapag, c.valorpag, c.formapag , c.coletor, c.referencia, c.datadel, c.obs, c.valor, c.status
                      FROM sis_lanc c";

            // Se datas não foram fornecidas no formulário de pesquisa, adicione condições à consulta SQL para buscar pela data atual
            if (!isset($_GET['data_inicial']) && !isset($_GET['data_final'])) {
                $data_atual = date('Y-m-d');
                $query .= " WHERE DATE(c.data) = '$data_atual'";
            }
            // Se datas foram fornecidas no formulário de pesquisa, adicione condições à consulta SQL
            else if (isset($_GET['data_inicial']) && isset($_GET['data_final'])) {
                $data_inicial = date('Y-m-d', strtotime($_GET['data_inicial']));
                $data_final = date('Y-m-d', strtotime($_GET['data_final']));
                $data_final .= ' 23:59:59'; // Incluir eventos que ocorram até o final do dia
                $query .= " WHERE DATE(c.datavenc) BETWEEN '$data_inicial' AND '$data_final'";
            }

            /// Se um termo de pesquisa foi fornecido, adicione condições à consulta SQL
            if (isset($_GET['search'])) {
            $search_term = mysqli_real_escape_string($link, trim($_GET['search'])); // Remover espaços em branco no início e no final
            if($search_term == true){
            $query .= " AND c.login LIKE '%$search_term%'";
            }}

            // Adicionando a cláusula ORDER BY para ordenar por data mais recente
           /* $query .= " ORDER BY  DESC";*/
            // Execute a consulta
            if($_GET['statuspag'] == 'Pago'){
                $query .= "AND c.status LIKE 'pago' ";
            }if($_GET['statuspag'] == 'Deletado'){
                $query .= "AND c.datadel > '2007-01-01' AND  DATE(c.datavenc) BETWEEN '$data_inicial' AND '$data_final'";
            }if($_GET['statuspag'] == null){
                $query .= " AND c.status LIKE 'pago'  OR c.datadel > '2007-01-01' AND  DATE(c.datavenc) BETWEEN '$data_inicial' AND '$data_final'";
            }

            $datadia = date('Y-m-d');
            $data = date('Y-m-d', strtotime($datadia. ' + 1 year'));
            $data2 = "2008-11-30";
            $status2 = $_GET['search'];

            $statuspag=$_GET['statuspag'];

            if($_GET['erro'] == on){
                $query = "SELECT c.login, c.datavenc, c.datapag, c.valorpag, c.formapag , c.coletor, c.referencia, c.datadel, c.obs, c.valor, c.status
                      FROM sis_lanc c where DATE(c.datavenc) > '$data' Or DATE(c.datavenc) <  '$data2'";
                      
            }

              if($_GET['erro'] == on && $_GET['search'] != null){
                $query = "SELECT c.login, c.datavenc, c.datapag, c.valorpag, c.formapag , c.coletor, c.referencia, c.datadel,  c.obs, c.valor, c.status 
                FROM sis_lanc c where DATE(c.datavenc) > '$data' AND c.status = '$status2' OR DATE(c.datavenc) < '$data2'
                 AND c.status  = '$status2' and c.datadel = null;";
               
              }

            $result = mysqli_query($link, $query);


            // Calcular o total de entradas e o número total de boletos
            $tot_entrada = 0;
            $total_boletos_ = 0; // Inicializando a contagem
            $tot_saida = 0;
           
            // Loop através dos resultados e somar as entradas e saídas
            while ($row = mysqli_fetch_assoc($result)) {
            $tot_entrada += $row['valorpag'];
            //$tot_saida += $row['saida'];
    
            // Verificar se a entrada é um boleto
            if ($row['login'] != false) {
            $total_boletos_++; // Incrementar a contagem de boletos apenas para entradas
            }
            }

            // Calcular o saldo como antes
            //$saldo = $tot_entrada - $tot_saida;


           // echo($query)
            ?>

            <div class="total-section" style="font-size: 20px;">
			    <div class="total" style="color: black;">Total Boletos: <?php echo $total_boletos_; ?></div><!-- Adicionando a contagem de boletos -->
                <div class="total" style="color: blue;">Total Entradas: R$ <?php echo number_format($tot_entrada, 2, ',', '.'); ?></div>		
            </div>



            <?php if ($result && mysqli_num_rows($result) > 0) : ?>
                <table>
                    <thead>
                        <tr>
                            <th style="color: white;">Nome do Cliente</th>
                            <th style="color: white;">Login</th>
                            <th style="color: white;">Data Vencimento</th>
                            <th style="color: white;">Data Pagamento</th>
                            <th style="color: white;">Deletado</th>
                            <th style="color: white;">Descrição</th>
                            <th style="color: white;">Valor</th>
                            <th style="color: white;">Coletor</th>

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
							
                                <!--Exibe Nome do Cliente	 -->
                            <td style="text-align: left; cursor: default;"> 
                                <?php
                                // Encontrar o ID no histórico usando expressão regular
                              /*  preg_match('/titulo (\d+)/', $row['historico'], $matches);
                                $id = isset($matches[1]) ? $matches[1] : '--';*/
                                $login =$row['login'];

                                // Consulta SQL para obter o login e uuid_cliente com base no ID
                                $cliente_query = "SELECT c.nome, l.login, c.uuid_cliente FROM sis_cliente l 
                                JOIN sis_cliente c ON l.login = c.login
                                WHERE l.login = '$login'";
                                $cliente_result = mysqli_query($link, $cliente_query);
                                $cliente_row = mysqli_fetch_assoc($cliente_result);
                                $nome_cliente = isset($cliente_row['nome']) ? $cliente_row['nome'] : '--';
                                $login = isset($cliente_row['login']) ? $cliente_row['login'] : '--';
                                $uuid_cliente = isset($cliente_row['uuid_cliente']) ? $cliente_row['uuid_cliente'] : '';

                                // Limitar o tamanho do nome do cliente
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
                                <td style="color:#0d6cea; font-weight: bold;">
                                   <?php
                                   // Verificar se $_GET['data_inicial'] está definido e não está vazio
                                   $data_inicial = (!empty($_GET['data_inicial'])) ? date('Y-m-d', strtotime($_GET['data_inicial'])) : date('Y-m-d');

                                   // Verificar se $_GET['data_final'] está definido e não está vazio
                                   $data_final = (!empty($_GET['data_final'])) ? date('Y-m-d', strtotime($_GET['data_final'])) : date('Y-m-d');

                                   // Construir o link de busca com as datas formatadas
                                   $link_busca = "?search=" . urlencode($row['login']) . "&data_inicial=" . urlencode($data_inicial) . "&data_final=" . urlencode($data_final);

                                   // Definir o comprimento máximo desejado para o nome de login
                                   $max_length = 15;

                                   // Limitar o tamanho do nome de login, se necessário
                                   $login_truncado = (strlen($row['login']) > $max_length) ? substr($row['login'], 0, $max_length) . '...' : $row['login'];
                                   ?>
                                   <a href="<?php echo $link_busca; ?>" title="<?php echo htmlspecialchars($row['login']); ?>" style="text-decoration: none; color: inherit;"><?php echo $login_truncado; ?></a>
                                </td>

                                <!--Exibe Data Vencimento --> 
                                <td style="font-weight: bold;"><?php echo date('d-m-Y', strtotime($row['datavenc'])); ?></td> <!-- Data -->

                                <!--Exibe Data pagamento --> 
                                <td style="color:green ;font-weight: bold;"><?php
                                    if($row['datapag'] != null){
                                        echo date('d-m-Y', strtotime($row['datapag']));
                                    }
                                 ?></td> 
                                <!--Exibe Data deletado --> 
                                <td style="color:red ;font-weight: bold;"><?php
                                    if($row['datadel'] != null){
                                        echo date('d-m-Y', strtotime($row['datadel']));
                                    }
                                 ?></td> 

                                <!--Exibe Valor descriçao -->
                                <td style="font-weight: bold;"><?php echo $row['obs']; ?></td>

                                <!--Exibe Valor pago -->
                                <td style=" font-weight: bold;">
                                    <?php 
                                    if($row['datapag'] == true){
                                        echo $row['valorpag']; 
                                    }else{
                                        echo $row['valor'];
                                    }
                                    
                                    ?></td>

                                <!--Exibe coletor -->
                                <td style="font-weight: bold;"><?php 
                                    if($row['datapag'] == true){
                                        echo $row['coletor']; 
                                    }else{
                                         
                                    }
                                ?></td>

                                
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
