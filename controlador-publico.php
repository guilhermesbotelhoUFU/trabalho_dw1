<?php
require_once 'config/conexao.php';
require_once 'modelos/Anuncio.php';
require_once 'modelos/Anunciante.php';
require_once 'modelos/Foto.php';
require_once 'modelos/Interesse.php';

header('Content-Type: application/json');

$acao = $_GET['acao'] ?? $_POST['acao'] ?? '';
$anuncioModel = new Anuncio($pdo);

switch ($acao) {
    case 'marcas':
        echo json_encode($anuncioModel->marcas());
        break;

    case 'modelos':
        $marca = trim($_GET['marca'] ?? '');
        echo json_encode($anuncioModel->modelos($marca));
        break;

    case 'cidades':
        $marca = trim($_GET['marca'] ?? '');
        $modelo = trim($_GET['modelo'] ?? '');
        echo json_encode($anuncioModel->cidades($marca, $modelo));
        break;

    // case 'todos':
    //     echo json_encode($anuncioModel->listar('', '', ''));
    //     break;

    case 'listar':
        $marca = trim($_GET['marca'] ?? '');
        $modelo = trim($_GET['modelo'] ?? '');
        $cidade = trim($_GET['cidade'] ?? '');
        echo json_encode($anuncioModel->listar($marca, $modelo, $cidade));
        break;

    case 'detalhe':
        $id = filter_var($_GET['id'] ?? '', FILTER_VALIDATE_INT);
        $anuncio = $id ? $anuncioModel->buscarPorId($id) : false;
        if (!$anuncio) {
            echo json_encode(['erro' => 'Anúncio não encontrado.']);
            break;
        }
        $anuncio['fotos'] = (new Foto($pdo))->listarPorAnuncio($id);
        echo json_encode($anuncio);
        break;

    case 'cadastrar':
        $nome = trim($_POST['nome'] ?? '');
        $cpf = trim($_POST['cpf'] ?? '');
        $email = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);
        $senha = $_POST['senha'] ?? '';
        $telefone = trim($_POST['telefone'] ?? '');

        if (!$nome || !$cpf || !$email || !$senha || !$telefone) {
            echo json_encode(['erro' => 'Preencha todos os campos corretamente.']);
            break;
        }

        // if (strlen($senha) < 6) {
        //     echo json_encode(['erro' => 'A senha deve ter pelo menos 6 caracteres.']);
        //     break;
        // }
        try {
            $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
            (new Anunciante($pdo))->inserir($nome, $cpf, $email, $senha_hash, $telefone);
        } catch (PDOException $e) {
            echo json_encode(['erro' => 'Este e-mail já está cadastrado.']);
            break;
        }
        echo json_encode(['ok' => true]);
        break;

    case 'login':
        session_start();
        $email = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);
        $senha = $_POST['senha'] ?? '';
        $lembrar = isset($_POST['lembrar']);

        $usuario = $email ? (new Anunciante($pdo))->buscarPorEmail($email) : false;

        // if (!$usuario || $usuario['senha_hash'] !== md5($senha)) {
        //     echo json_encode(['erro' => 'E-mail ou senha inválidos.']);
        //     break;
        // }

        if (!$usuario || !password_verify($senha, $usuario['senha_hash'])) {
            echo json_encode(['erro' => 'E-mail ou senha inválidos.']);
            break;
        }

        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['usuario_nome'] = $usuario['nome'];
        // $_SESSION['logado'] = true;

        if ($lembrar) {
            setcookie('lembrar_email', $email, time() + (30 * 24 * 3600), '/');
        } else {
            setcookie('lembrar_email', '', time() - 3600, '/');
        }
        echo json_encode(['ok' => true, 'nome' => $usuario['nome']]);
        break;

    case 'interesse':
        $id_anuncio = filter_var($_POST['id_anuncio'] ?? '', FILTER_VALIDATE_INT);
        $nome = trim($_POST['nome'] ?? '');
        $telefone = trim($_POST['telefone'] ?? '');
        $mensagem = trim($_POST['mensagem'] ?? '');

        if (!$id_anuncio || !$nome || !$telefone || !$mensagem) {
            echo json_encode(['erro' => 'Preencha todos os campos corretamente.']);
            break;
        }
        try {
            (new Interesse($pdo))->inserir($id_anuncio, $nome, $telefone, $mensagem);
        } catch (PDOException $e) {
            echo json_encode(['erro' => 'Não foi possível registrar seu interesse.']);
            break;
        }
        echo json_encode(['ok' => true]);
        break;

    default:
        echo json_encode(['erro' => 'Ação inválida.']);
}
