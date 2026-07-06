<?php
class Interesse {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function inserir($id_anuncio, $nome, $telefone, $mensagem) {
        $stmt = $this->pdo->prepare(
            'INSERT INTO interesse (id_anuncio, nome, telefone, mensagem) VALUES (?, ?, ?, ?)'
        );
        $stmt->execute([$id_anuncio, $nome, $telefone, $mensagem]);
    }

    public function listarPorAnuncio($id_anuncio) {
        $stmt = $this->pdo->prepare(
            'SELECT id, nome, telefone, mensagem FROM interesse WHERE id_anuncio = ? ORDER BY data_hora DESC'
        );
        $stmt->execute([$id_anuncio]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function excluir($id, $id_anunciante) {
        // $stmt = $this->pdo->prepare('DELETE FROM interesse WHERE id = ?');
        // $stmt->execute([$id]);
        // o JOIN garante que so o dono do anuncio consegue apagar
        $stmt = $this->pdo->prepare(
            'DELETE i FROM interesse i
             JOIN anuncio a ON a.id = i.id_anuncio
             WHERE i.id = ? AND a.id_anunciante = ?'
        );
        $stmt->execute([$id, $id_anunciante]);
        return $stmt->rowCount() > 0;
    }

    // public function contarPorAnuncio($id_anuncio) {
    //     $stmt = $this->pdo->prepare('SELECT COUNT(*) FROM interesse WHERE id_anuncio = ?');
    //     $stmt->execute([$id_anuncio]);
    //     return $stmt->fetchColumn();
    // }

    public function excluirPorAnuncio($id_anuncio) {
        $stmt = $this->pdo->prepare('DELETE FROM interesse WHERE id_anuncio = ?');
        $stmt->execute([$id_anuncio]);
    }
}
