<?php
$msg = "";
$disciplinas = [];

function carregarDisciplinas() {
    $disciplinas = [];
    if (file_exists("disciplinas.txt")) {
        $arqDisc = fopen("disciplinas.txt", "r") or die("Erro ao abrir arquivo");
        while (!feof($arqDisc)) {
            $linha = fgets($arqDisc);
            $colunaDados = explode(";", $linha);
            if (count($colunaDados) >= 3) {
                $disciplinas[] = [
                    "nome" => $colunaDados[0],
                    "sigla" => $colunaDados[1],
                    "carga_semanal" => trim($colunaDados[2])
                ];
            }
        }
        fclose($arqDisc);
    }
    return $disciplinas;
}

function salvarDisciplinas($disciplinas) {
    $arqDisc = fopen("disciplinas.txt", "w") or die("Erro ao criar arquivo");
    foreach ($disciplinas as $disciplina) {
        $linha = $disciplina["nome"] . ";" . $disciplina["sigla"] . ";" . $disciplina["carga_semanal"] . ";\n";
        fwrite($arqDisc, $linha);
    }
    fclose($arqDisc);
}

$disciplinas = carregarDisciplinas();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST["nome"] ?? '';
    $sigla = $_POST["sigla"] ?? '';
    $carga_semanal = $_POST["carga_semanal"] ?? '';
    $index = $_POST["index"] ?? '';

    if (isset($_POST['remover'])) {
        $index = $_POST['remover'];
        if (isset($disciplinas[$index])) {
            unset($disciplinas[$index]); 
            $disciplinas = array_values($disciplinas);
            salvarDisciplinas($disciplinas);
            $msg = "Disciplina removida com sucesso!";
        }
    }

    if (isset($_POST['cadastrar'])) {
        if (!empty($nome) && !empty($sigla) && !empty($carga_semanal)) {
            if ($index !== '') {
                $disciplinas[$index]["nome"] = $nome;
                $disciplinas[$index]["sigla"] = $sigla;
                $disciplinas[$index]["carga_semanal"] = $carga_semanal;
                $msg = "Disciplina alterada com sucesso!";
            } else {
                $disciplinas[] = [
                    "nome" => $nome,
                    "sigla" => $sigla,
                    "carga_semanal" => $carga_semanal
                ];
                $msg = "Disciplina cadastrada com sucesso!";
            }
            salvarDisciplinas($disciplinas);
        } else {
            $msg = "Todos os campos devem ser preenchidos!";
        }
    }

    if (isset($_POST['editar'])) {
        $index = $_POST['editar'];
        if (isset($disciplinas[$index])) {
            $disciplinaParaEditar = $disciplinas[$index];
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

        .acoes {
            display: flex;
            gap: 10px;
            justify-content: center;
        }

        a {
            text-decoration: none;
            color: cyan;
        }

    </style>
</head>
<body>
    <section>
        <form method="POST">
            <input type="hidden" name="index" value="<?= isset($disciplinaParaEditar) ? $index : '' ?>">
            <label for="nome">Nome da Disciplina:</label>
            <input type="text" name="nome" id="nome" value="<?= isset($disciplinaParaEditar) ? $disciplinaParaEditar['nome'] : '' ?>">
            <label for="sigla">Sigla:</label>
            <input type="text" name="sigla" id="sigla" value="<?= isset($disciplinaParaEditar) ? $disciplinaParaEditar['sigla'] : '' ?>">
            <label for="carga_semanal">Carga:</label>
            <input type="text" name="carga_semanal" id="carga_semanal" value="<?= isset($disciplinaParaEditar) ? $disciplinaParaEditar['carga_semanal'] : '' ?>">
            <input type="submit" name="cadastrar" value="<?= isset($disciplinaParaEditar) ? 'Salvar Alterações' : 'Cadastrar' ?>">
            <p><?= $msg ?></p>
        </form>
    </section>

    <h1>Disciplinas Cadastradas</h1>
    <table>
        <tr>
            <th>Nome</th>
            <th>Sigla</th>
            <th>Carga</th>
            <th>Ações</th>
        </tr>
        <?php foreach ($disciplinas as $index => $disciplina): ?>
            <tr>
                <td><?= $disciplina['nome'] ?></td>
                <td><?= $disciplina['sigla'] ?></td>
                <td><?= $disciplina['carga_semanal'] ?></td>
                <td class="acoes">
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="editar" value="<?= $index ?>">
                        <input type="submit" value="Editar">
                    </form>
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="remover" value="<?= $index ?>">
                        <input type="submit" value="Remover" onclick="return confirm('Tem certeza que deseja remover esta disciplina?')">
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>

