<?php
$msg = "";
$disciplinas = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST["nome"] ?? '';
    $sigla = $_POST["sigla"] ?? '';
    $carga_semanal = $_POST["carga_semanal"] ?? '';
    $creditos_necessarios = $_POST["creditos_necessarios"] ?? '';
    $creditos_conclusao = $_POST["creditos_conclusao"] ?? '';

    if (isset($_POST['cadastrar'])) {
        if (!empty($nome) && !empty($sigla) && !empty($carga_semanal) && !empty($creditos_necessarios) && !empty($creditos_conclusao)) {
            $arqDisc = fopen("disciplinas.txt", "a") or die("Erro ao criar arquivo");
            $linha = $nome . ";" . $sigla . ";" . $carga_semanal . ";" . $creditos_necessarios . ";" . $creditos_conclusao . "\n";

            fwrite($arqDisc, $linha);
            fclose($arqDisc);

            $msg = "Disciplina cadastrada com sucesso!";
        } else {
            $msg = "Todos os campos devem ser preenchidos!";
        }
    }

    if (isset($_POST['exibir'])) {
        if (file_exists("disciplinas.txt")) {
            $disciplinas = file_get_contents("disciplinas.txt");
            $disciplinas = nl2br($disciplinas);
        } else {
            $msg = "Nenhuma disciplina cadastrada.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Disciplinas</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-image: url("img/Wings of Honour Screenshot 2024.08.27 - 22.17.09.37.png");
        }

        p, label, input, textarea {
            display: block;
            color: cyan;
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
        }

        form {
            width: 100%;
            display: flex;
            flex-direction: column;
        }

        input[type="submit"] {
            margin-top: 20px;
        }

        textarea {
            width: 100%;
            height: 100px;
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
            <label for="carga_semanal">Carga Horária Semanal:</label>
            <input type="text" name="carga_semanal" id="carga_semanal">
            <label for="creditos_necessarios">Créditos Necessários:</label>
            <input type="text" name="creditos_necessarios" id="creditos_necessarios">
            <label for="creditos_conclusao">Créditos por Conclusão:</label>
            <input type="text" name="creditos_conclusao" id="creditos_conclusao">
            <input type="submit" name="cadastrar" value="Cadastrar">
            <input type="submit" name="exibir" value="Exibir Disciplinas">
            <p><?= $msg ?></p>
        </form>
        <?php if (!empty($disciplinas)): ?>
            <textarea readonly><?= $disciplinas ?></textarea>
        <?php endif; ?>
    </section>
</body>
</html>
