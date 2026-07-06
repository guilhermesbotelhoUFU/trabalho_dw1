<?php
class Anuncio {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function listar($marca, $modelo, $cidade) {
        // $sql = "SELECT * FROM anuncio WHERE 1=1";
        // if ($marca != '')  $sql .= " AND marca = '$marca'";
        // if ($modelo != '') $sql .= " AND modelo = '$modelo'";
        // if ($cidade != '') $sql .= " AND cidade = '$cidade'";
        // return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);

        $sql = 'SELECT a.id, a.marca, a.modelo, a.ano, a.cidade, a.valor,
                       (SELECT nome_arquivo FROM foto WHERE foto.id_anuncio = a.id LIMIT 1) AS foto
                FROM anuncio a WHERE 1=1';
        $parametros = [];
        if ($marca !== '') {
            $sql .= ' AND a.marca = ?';
            $parametros[] = $marca;
        }
        if ($modelo !== '') {
            $sql .= ' AND a.modelo = ?';
            $parametros[] = $modelo;
        }
        if ($cidade !== '') {
            $sql .= ' AND a.cidade = ?';
            $parametros[] = $cidade;
        }
        // $sql .= ' ORDER BY a.id DESC LIMIT 20';
        $sql .= ' ORDER BY a.data_hora DESC LIMIT 20';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($parametros);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarPorId($id) {
        $stmt = $this->pdo->prepare(
            'SELECT marca, modelo, ano, cor, quilometragem, descricao, valor, estado, cidade FROM anuncio WHERE id = ?'
        );
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function buscarDoAnunciante($id, $id_anunciante) {
        $stmt = $this->pdo->prepare('SELECT * FROM anuncio WHERE id = ? AND id_anunciante = ?');
        $stmt->execute([$id, $id_anunciante]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function listarDoAnunciante($id_anunciante) {
        $stmt = $this->pdo->prepare(
            'SELECT id, marca, modelo, ano,
                    (SELECT nome_arquivo FROM foto WHERE foto.id_anuncio = anuncio.id LIMIT 1) AS foto
             FROM anuncio WHERE id_anunciante = ? ORDER BY data_hora DESC'
        );
        $stmt->execute([$id_anunciante]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function inserir($dados, $id_anunciante) {
        $stmt = $this->pdo->prepare(
            'INSERT INTO anuncio (id_anunciante, marca, modelo, ano, cor, quilometragem, descricao, valor, estado, cidade)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'
        );
        $stmt->execute([
            $id_anunciante,
            $dados['marca'],
            $dados['modelo'],
            $dados['ano'],
            $dados['cor'],
            $dados['quilometragem'],
            $dados['descricao'],
            $dados['valor'],
            $dados['estado'],
            $dados['cidade'],
        ]);
        return $this->pdo->lastInsertId();
    }

    public function excluir($id, $id_anunciante) {
        $stmt = $this->pdo->prepare('DELETE FROM anuncio WHERE id = ? AND id_anunciante = ?');
        $stmt->execute([$id, $id_anunciante]);
        return $stmt->rowCount() > 0;
    }

    public function marcas() {
        // $stmt = $this->pdo->query('SELECT marca FROM anuncio GROUP BY marca ORDER BY marca');
        $stmt = $this->pdo->query('SELECT DISTINCT marca FROM anuncio ORDER BY marca');
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function modelos($marca) {
        $stmt = $this->pdo->prepare('SELECT DISTINCT modelo FROM anuncio WHERE marca = ? ORDER BY modelo');
        $stmt->execute([$marca]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function cidades($marca, $modelo) {
        $sql = 'SELECT DISTINCT cidade FROM anuncio WHERE 1=1';
        $parametros = [];
        if ($marca !== '') {
            $sql .= ' AND marca = ?';
            $parametros[] = $marca;
        }
        if ($modelo !== '') {
            $sql .= ' AND modelo = ?';
            $parametros[] = $modelo;
        }
        $sql .= ' ORDER BY cidade';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($parametros);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    // public function contar() {
    //     return $this->pdo->query('SELECT COUNT(*) FROM anuncio')->fetchColumn();
    // }
}
