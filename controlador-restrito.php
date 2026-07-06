<?php
require_once 'config/conexao.php';
require_once 'modelos/Anuncio.php';
require_once 'modelos/Foto.php';
require_once 'modelos/Interesse.php';

session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['usuario_id'])) {
    http_response_code(401);
    echo json_encode(['erro' => 'nao_autenticado']);
    exit;
}

$usuario_id = $_SESSION['usuario_id'];
$acao = $_GET['acao'] ?? $_POST['acao'] ?? '';
$anuncioModel = new Anuncio($pdo);

switch ($acao) {
    case 'meus':
        echo json_encode($anuncioModel->listarDoAnunciante($usuario_id));
        break;

    case 'detalhe':
        $id = filter_var($_GET['id'] ?? '', FILTER_VALIDATE_INT);
        $anuncio = $id ? $anuncioModel->buscarDoAnunciante($id, $usuario_id) : false;
        if (!$anuncio) {
            echo json_encode(['erro' => 'Anúncio não encontrado.']);
            break;
        }
        $anuncio['fotos'] = (new Foto($pdo))->listarPorAnuncio($id);
        echo json_encode($anuncio);
        break;

    case 'criar':
        criarAnuncio($pdo, $anuncioModel, $usuario_id);
        break;

    // case 'editar':
    //     editarAnuncio($pdo, $anuncioModel, $usuario_id);
    //     break;

    case 'excluir':
        $id = filter_var($_POST['id'] ?? '', FILTER_VALIDATE_INT);
        excluirAnuncio($pdo, $anuncioModel, $id, $usuario_id);
        break;

    case 'interesses':
        $id_anuncio = filter_var($_GET['idAnuncio'] ?? '', FILTER_VALIDATE_INT);
        $anuncio = $id_anuncio ? $anuncioModel->buscarDoAnunciante($id_anuncio, $usuario_id) : false;
        if (!$anuncio) {
            echo json_encode(['erro' => 'Anúncio não encontrado.']);
            break;
        }
        $interesses = (new Interesse($pdo))->listarPorAnuncio($id_anuncio);
        echo json_encode([
            'anuncio' => $anuncio['marca'] . ' ' . $anuncio['modelo'] . ' ' . $anuncio['ano'],
            'interesses' => $interesses,
        ]);
        break;

    case 'excluir-interesse':
        $id = filter_var($_POST['id'] ?? '', FILTER_VALIDATE_INT);
        (new Interesse($pdo))->excluir($id, $usuario_id);
        echo json_encode(['ok' => true]);
        break;

    default:
        echo json_encode(['erro' => 'Ação inválida.']);
}

function criarAnuncio($pdo, $anuncioModel, $usuario_id) {
    $marca = trim($_POST['marca'] ?? '');
    $modelo = trim($_POST['modelo'] ?? '');
    $ano = filter_var($_POST['ano'] ?? '', FILTER_VALIDATE_INT);
    $cor = trim($_POST['cor'] ?? '');
    $quilometragem = filter_var($_POST['quilometragem'] ?? '', FILTER_VALIDATE_INT);
    $descricao = trim($_POST['descricao'] ?? '');
    $valor = filter_var($_POST['valor'] ?? '', FILTER_VALIDATE_FLOAT);
    $estado = trim($_POST['estado'] ?? '');
    $cidade = trim($_POST['cidade'] ?? '');

    // quilometragem e valor podem ser 0, por isso testo com === false
    if (!$marca || !$modelo || !$ano || !$cor || $quilometragem === false || !$descricao || $valor === false || !$estado || !$cidade) {
        echo json_encode(['erro' => 'Preencha todos os campos corretamente.']);
        return;
    }

    // $foto = $_FILES['foto'] ?? null;
    // if ($foto && $foto['error'] === UPLOAD_ERR_OK) {
    //     move_uploaded_file($foto['tmp_name'], __DIR__ . '/uploads/' . $foto['name']);
    // }

    $fotos = $_FILES['foto'] ?? null;
    $indices = [];
    if ($fotos) {
        for ($i = 0; $i < count($fotos['name']); $i++) {
            if ($fotos['error'][$i] === UPLOAD_ERR_OK) {
                $indices[] = $i;
            }
        }
    }
    // o trabalho pede no minimo 3 fotos por anuncio
    // if (count($indices) < 1) {
    //     echo json_encode(['erro' => 'Envie pelo menos uma foto.']);
    //     return;
    // }
    if (count($indices) < 3) {
        echo json_encode(['erro' => 'Envie pelo menos tres fotos.']);
        return;
    }

    // $extensoes_ok = ['jpg', 'png'];
    $extensoes_ok = ['jpg', 'jpeg', 'png', 'webp'];
    $pasta = __DIR__ . '/uploads/';

    try {
        $dados = array(
            'marca' => $marca,
            'modelo' => $modelo,
            'ano' => $ano,
            'cor' => $cor,
            'quilometragem' => $quilometragem,
            'descricao' => $descricao,
            'valor' => $valor,
            'estado' => $estado,
            'cidade' => $cidade,
        );
        $id_anuncio = $anuncioModel->inserir($dados, $usuario_id);

        $fotoModel = new Foto($pdo);
        foreach ($indices as $i) {
            $ext = strtolower(pathinfo($fotos['name'][$i], PATHINFO_EXTENSION));
            if (!in_array($ext, $extensoes_ok) || $fotos['size'][$i] > 3 * 1024 * 1024) {
                throw new Exception('foto invalida');
            }
            // nome unico pra uma foto nao sobrescrever a outra
            $nome = uniqid('foto_', true) . '.' . $ext;
            if (!move_uploaded_file($fotos['tmp_name'][$i], $pasta . $nome)) {
                throw new Exception('falha ao salvar');
            }
            $fotoModel->inserir($id_anuncio, $nome);
        }

        echo json_encode(['ok' => true, 'id' => $id_anuncio]);
    } catch (Exception $e) {
        echo json_encode(['erro' => 'Não foi possível publicar o anúncio.']);
    }
}

function excluirAnuncio($pdo, $anuncioModel, $id, $usuario_id) {
    $anuncio = $id ? $anuncioModel->buscarDoAnunciante($id, $usuario_id) : false;
    if (!$anuncio) {
        echo json_encode(['erro' => 'Anúncio não encontrado.']);
        return;
    }

    // pego os nomes antes de apagar do banco pra conseguir apagar os arquivos depois
    $fotoModel = new Foto($pdo);
    $nomes = $fotoModel->listarPorAnuncio($id);

    (new Interesse($pdo))->excluirPorAnuncio($id);
    $fotoModel->excluirPorAnuncio($id);
    $anuncioModel->excluir($id, $usuario_id);

    foreach ($nomes as $nome) {
        @unlink(__DIR__ . '/uploads/' . $nome);
    }
    echo json_encode(['ok' => true]);
}
