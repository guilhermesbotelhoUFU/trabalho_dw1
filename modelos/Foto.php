<?php
class Foto {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function inserir($id_anuncio, $nome_arquivo) {
        $stmt = $this->pdo->prepare('INSERT INTO foto (id_anuncio, nome_arquivo) VALUES (?, ?)');
        $stmt->execute([$id_anuncio, $nome_arquivo]);
    }

    public function listarPorAnuncio($id_anuncio) {
        // $stmt = $this->pdo->prepare('SELECT * FROM foto WHERE id_anuncio = ?');
        // $stmt->execute([$id_anuncio]);
        // return $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt = $this->pdo->prepare('SELECT nome_arquivo FROM foto WHERE id_anuncio = ?');
        $stmt->execute([$id_anuncio]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    // public function excluir($id) {
    //     $stmt = $this->pdo->prepare('DELETE FROM foto WHERE id = ?');
    //     $stmt->execute([$id]);
    // }

    public function excluirPorAnuncio($id_anuncio) {
        $stmt = $this->pdo->prepare('DELETE FROM foto WHERE id_anuncio = ?');
        $stmt->execute([$id_anuncio]);
    }
}
