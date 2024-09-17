<?php
include('addons.class.php');

//permição nessecaria para acessar essa funcao -----------------------------------

$permissao = "perm_nfe";

include('../app/config/config.php');

session_name('mka');
session_start();

if (!isset($_SESSION['MKA_Logado'])) {
    exit('Acesso negado... <a href="/admin/">Fazer Login</a>');
}

// Assuming $Manifest is defined somewhere before this code
$manifestTitle = isset($Manifest->{'name'}) ? $Manifest->{'name'} : '';
$manifestVersion = isset($Manifest->{'version'}) ? $Manifest->{'version'} : '';
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="utf-8">
    <title>MK - AUTH :: <?php echo htmlspecialchars($manifestTitle . " - V " . $manifestVersion); ?></title>

    <link href="../../../estilos/mk-auth.css" rel="stylesheet" type="text/css" />
    <link href="../../../estilos/font-awesome.css" rel="stylesheet" type="text/css" />

    <script src="../../../scripts/jquery.js"></script>
    <script src="../../../scripts/mk-auth.js"></script>

    <style type="text/css">
        /* Estilos CSS personalizados */
        body {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding-top: 40px;
        }

        form,
        .table-container,
        .client-count-container {
            width: 80%;
            margin: 0 auto;
        }

        form {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 20px;
        }

        label {
            font-weight: bold;
            margin-bottom: 5px;
        }

        input[type="text"],
        input[type="submit"],
        .clear-button {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
        }

        input[type="submit"] {
            background-color: #4caf50;
            color: white;
            font-weight: bold;
            cursor: pointer;
        }

        .clear-button {
            background-color: #e74c3c;
            color: white;
            font-weight: bold;
            cursor: pointer;
        }

        .clear-button:hover {
            background-color: #c0392b;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th,
        table td {
            padding: 2px;
            text-align: left;
        }

        table th {
            background-color: #4caf50;
            color: white;
            font-weight: bold;
            text-align: center;
        }

        h1 {
            color: #4caf50;
        }

        .client-count-container {
            text-align: center;
            margin-top: 10px;
        }

        .client-count {
            color: #4caf50;
            font-weight: bold;
        }

        .client-count.blue {
            color: #2196F3;
        }

        .nome_cliente a {
            color: blue;
            text-decoration: none;
            font-weight: bold;
        }

        .nome_cliente a:hover {
            text-decoration: underline;
        }

        .nome_cliente td {
            border-bottom: 1px solid #ddd;
            text-align: center; /* Adicionado para centralizar os nomes dos clientes na coluna "Nome do Cliente" */
        }

        .nome_cliente:nth-child(odd) {
            background-color: #FFFF99;
        }
    </style>

    <script type="text/javascript">
        function clearSearch() {
            document.getElementById('search').value = '';
            document.forms['searchForm'].submit();
        }

        document.addEventListener("DOMContentLoaded", function () {
            var cells = document.querySelectorAll('.table-container tbody td.plan-name');
            cells.forEach(function (cell) {
                cell.addEventListener('click', function () {
                    var planName = this.innerText;
                    document.getElementById('search').value = planName;
                    document.title = 'Painel: ' + planName;
                    document.forms['searchForm'].submit();
                });
            });
        });
    </script>

</head>

<body>
    <?php include('../../../topo.php'); ?>

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
    if ($acesso_permitido) {
        // Formulário Atualizado com Funcionalidade de Busca
    ?>
        <form id="searchForm" method="GET">
            <label for="search">Buscar Cliente:</label>
            <input type="text" id="search" name="search" placeholder="Digite o nome do cliente" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
            <label for="isento">ISENTOS:</label>
            <input type="checkbox" id="isento" name="isento" <?php if(isset($_GET['isento'])){echo 'checked'; }?>>
            <input type="submit" value="Buscar">
            <button type="button" onclick="clearSearch()" class="clear-button">Limpar</button>
        </form>

        <?php
        // Dados de conexão com o banco de dados já estão em config.php
        // Consulta SQL para obter a quantidade de clientes sem titulo
        $countQuery = "SELECT COUNT(c.login) AS client_count FROM sis_cliente c WHERE c.tit_abertos = 0 AND c.cli_ativado = 's' AND c.tipo_cob LIKE 'titulo' AND c.isento LIKE 'nao'";

        if (!empty($_GET['search'])) {
        $countQuery .= " AND (c.login LIKE ? OR c.nome LIKE ?)";
        }

        $stmt = mysqli_prepare($link, $countQuery);

        if (!empty($_GET['search'])) {
        $search = '%' . mysqli_real_escape_string($link, $_GET['search']) . '%';
        mysqli_stmt_bind_param($stmt, "ss", $search, $search);
        }

        mysqli_stmt_execute($stmt);
        $countResult = mysqli_stmt_get_result($stmt);

        if ($countResult) {
        $countRow = mysqli_fetch_assoc($countResult);
        $clientCount = $countRow['client_count'];

        echo "<div class='client-count-container'><p class='client-count blue'>Quantidade de clientes sem titulo: $clientCount</p></div>";
        } else {
        echo "<div class='client-count-container'><p class='client-count blue'>Erro ao obter a quantidade de clientes</p></div>";
        }

        // Tabela: Nomes dos Clientes com Logins Lado a Lado
        ?>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Nome do Cliente</th>
                        <th>Grupo</th>
                        <th>Observação</th>
                        <th>Data de Pagamento</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Adicione a condição de busca, se houver
                    $searchCondition = '';
                    if (!empty($_GET['search'])) {
                        $search = mysqli_real_escape_string($link, $_GET['search']);
                        $searchCondition = " AND (c.login LIKE '%$search%' OR c.nome LIKE '%$search%')";
                    }


                    if(!empty($_GET['isento'])){
                    // Consulta SQL para obter os clientes sem titulo
                    $query = "SELECT c.uuid_cliente, c.nome, c.grupo, c.isento, c.obs, MAX(l.datapag) AS datapag
                    FROM sis_cliente c
                    LEFT JOIN sis_lanc l ON c.login = l.login
                    WHERE c.tit_abertos = 0 AND c.cli_ativado = 's' AND c.tipo_cob LIKE 'titulo'"
                    . $searchCondition .
                    " GROUP BY c.uuid_cliente, c.nome
                    ORDER BY nome ASC";  // Adicionando a cláusula ORDER BY para ordenar por data de pagamento decrescente
                    }else{
                                            // Consulta SQL para obter os clientes sem titulo
                    $query = "SELECT c.uuid_cliente, c.nome, c.grupo, c.isento, c.obs, MAX(l.datapag) AS datapag
                    FROM sis_cliente c
                    LEFT JOIN sis_lanc l ON c.login = l.login
                    WHERE c.tit_abertos = 0 AND c.cli_ativado = 's' AND c.tipo_cob LIKE 'titulo' AND c.isento LIKE 'nao'"
                    . $searchCondition .
                    " GROUP BY c.uuid_cliente, c.nome
                    ORDER BY datapag DESC";  // Adicionando a cláusula ORDER BY para ordenar por data de pagamento decrescente
                    }


                    // Execute a consulta
                    $result = mysqli_query($link, $query);

                    // Verifique se a consulta foi bem-sucedida
                    if ($result) {
                        // Exiba os resultados da consulta SQL
                        $rowNumber = 0;
                        while ($row = mysqli_fetch_assoc($result)) {
                            $nome_por_num_titulo = "Nome do Cliente: " . $row['nome'] . " - UUID: " . $row['uuid_cliente'];

                            // Adiciona a classe 'nome_cliente' e 'highlight' (para linhas ímpares) alternadamente
                            $rowNumber++;
                            $nomeClienteClass = ($rowNumber % 2 == 0) ? 'nome_cliente' : 'nome_cliente highlight';

                            // Adiciona o link apenas no campo de nome do cliente
                            echo "<tr class='$nomeClienteClass'> ";
                            echo "<td><a href='../../../cliente_det.hhvm?uuid=" . $row['uuid_cliente'] . "' target='_blank' title='VER CLIENTE: $nome_por_num_titulo'>" . $row['nome'] . "</a></td>";
                            echo "<td style='text-align: center;'>" .$row['grupo']."</td>";
                            
                            // Limitar o tamanho do OBS
                            $obs =$row['obs'];
                            $max_length = 40; // Defina o comprimento máximo desejado
                            $obs_truncado = strlen($obs) > $max_length ? substr($obs, 0, $max_length) . '...' : $obs;

                            echo "<td style='text-align: center;'>" .$obs_truncado."</td>";
                            echo "<td style='text-align: center;'>" . ($row['datapag'] ? date('d/m/Y', strtotime($row['datapag'])) : 'N/A') . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        // Se a consulta falhar, exiba uma mensagem de erro
                        echo "<tr><td colspan='2'>Erro na consulta: " . mysqli_error($link) . "</td></tr>";
                    }
                    ?>
                </tbody>
            </table >
        </div>
    <?php
    } else {
        echo "Acesso não permitido!";
    }
    ?>

    <?php include('../../../baixo.php'); ?>

    <script src="../../../menu.js.php"></script>
    <?php include('../../../rodape.php'); ?>
</body>

</html>
