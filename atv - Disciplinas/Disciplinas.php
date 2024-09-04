<?php
$msg = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST["nome"] ?? '';
    $sigla = $_POST["sigla"] ?? '';
    $carga_semanal = $_POST["carga_semanal"] ?? '';


    if (isset($_POST['cadastrar'])) {
        if (!empty($nome) && !empty($sigla) && !empty($carga_semanal) ) {
            $arqDisc = fopen("disciplinas.txt", "a") or die("Erro ao criar arquivo");
            $linha = $nome . ";" . $sigla . ";" . $carga_semanal . ";" . "\n";

            fwrite($arqDisc, $linha);
            fclose($arqDisc);

            $msg = "Disciplina cadastrada com sucesso!";
        } else {
            $msg = "Todos os campos devem ser preenchidos!";
        }
    }
}
?>



<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro e Listagem de Disciplinas</title>
    <style>
        body {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin: 0;
            background-image: url("img/Wings of Honour Screenshot 2024.08.27 - 22.17.09.37.png");
            color: cyan;
        }

        p, label, input, textarea {
            display: block;
            margin: 10px;
        }

        input, textarea {
            color: black;
        }

        section {
            background-color: black;
            width: 300px;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            box-shadow: 10px 20px 20px 10px #20223f;
            border-radius: 30px;
            margin-bottom: 20px;
            margin-top: 80px;
        }

        form {
            width: 100%;
            display: flex;
            flex-direction: column;
        }

        input[type="submit"] {
            margin-top: 20px;
        }

        table {
            width: 80%;
            margin-top: 20px;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid cyan;
            padding: 10px;
            text-align: center;
        }

        th {
            background-color: #20223f;
        }
    </style>
</head>
<body>
    <section>
        <form method="POST">
            <label for="nome">Nome da Disciplina:</label>
            <input type="text" name="nome" id="nome">
            <label for="sigla">Sigla:</label>
            <input type="text" name="sigla" id="sigla">
            <label for="carga_semanal">Carga:</label>
            <input type="text" name="carga_semanal" id="carga_semanal">
            <input type="submit" name="cadastrar" value="Cadastrar">
            <p><?= $msg ?></p>
        </form>
    </section>

    <h1>Disciplinas Cadastradas</h1>
    <?php if (file_exists("disciplinas.txt")): ?>
    <table>
        <tr>
            <th>Nome</th>
            <th>Sigla</th>
            <th>Carga</th>
        </tr>
        <?php
        $arqDisc = fopen("disciplinas.txt", "r") or die("Erro ao abrir arquivo");
        
        while (!feof($arqDisc)) {
            $linha = fgets($arqDisc);
            $colunaDados = explode(";", $linha);
            if (count($colunaDados) >= 3) {
                echo "<tr><td>" . $colunaDados[0] . "</td>" .
                    "<td>" . $colunaDados[1] . "</td>" .
                    "<td>" . $colunaDados[2] . "</td></tr>";
            }
        }
        
        fclose($arqDisc);
        ?>
    </table>
    <?php else: ?>
        <p>Nenhuma disciplina cadastrada ainda.</p>
    <?php endif; ?>
</body>
</html>
