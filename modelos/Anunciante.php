<?php
class Anunciante {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function inserir($nome, $cpf, $email, $senha_hash, $telefone) {
        $stmt = $this->pdo->prepare(
            'INSERT INTO anunciante (nome, cpf, email, senha_hash, telefone) VALUES (?, ?, ?, ?, ?)'
        );
        $stmt->execute([$nome, $cpf, $email, $senha_hash, $telefone]);
    }

    public function buscarPorEmail($email) {
        // $stmt = $this->pdo->query("SELECT * FROM anunciante WHERE email = '$email'");
        // return $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt = $this->pdo->prepare('SELECT id, nome, senha_hash FROM anunciante WHERE email = ?');
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // public function buscarPorId($id) {
    //     $stmt = $this->pdo->prepare('SELECT nome, email, telefone FROM anunciante WHERE id = ?');
    //     $stmt->execute([$id]);
    //     return $stmt->fetch(PDO::FETCH_ASSOC);
    // }
}
