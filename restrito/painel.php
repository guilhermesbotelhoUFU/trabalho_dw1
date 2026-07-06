<?php require_once '../auth.php'; ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel - VendaCarros</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/restrito.css">
</head>
<body>
    <header>
        <a href="painel.php"><img src="../logo.png" alt="VendaCarros" class="logo"></a>
    </header>
    <nav class="nav-restrita">
        <ul>
            <li><a href="painel.php">Painel</a></li>
            <li><a href="meus-anuncios.php">Meus anúncios</a></li>
            <li><a href="novo-anuncio.php">Novo anúncio</a></li>
            <li class="nav-sair"><a href="../logout.php">Sair</a></li>
        </ul>
    </nav>
    <main>
        <div class="container-fluid px-0">
            <h2 class="mb-4">Bem-vindo, <?php echo htmlspecialchars($_SESSION['usuario_nome']); ?>!</h2>
            <div class="row g-3">
                <div class="col-sm-6 col-md-4">
                    <div class="card h-100 text-center p-3">
                        <div class="card-body">
                            <h5 class="card-title">Novo anúncio</h5>
                            <p class="card-text text-muted">Anuncie seu veículo no portal.</p>
                            <a href="novo-anuncio.php" class="btn btn-primary">Criar anúncio</a>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-4">
                    <div class="card h-100 text-center p-3">
                        <div class="card-body">
                            <h5 class="card-title">Meus anúncios</h5>
                            <p class="card-text text-muted">Visualize e gerencie seus anúncios.</p>
                            <a href="meus-anuncios.php" class="btn btn-primary">Ver anúncios</a>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-4">
                    <div class="card h-100 text-center p-3">
                        <div class="card-body">
                            <h5 class="card-title">Todos os anúncios</h5>
                            <p class="card-text text-muted">Veja todos os anúncios do site.</p>
                            <a href="../index.html?todos=1" class="btn btn-primary">Ver todos</a>
                        </div>
                    </div>
                </div>
                <!-- <div class="col-sm-6 col-md-4">
                    <div class="card h-100 text-center p-3">
                        <div class="card-body">
                            <h5 class="card-title">Meus dados</h5>
                            <p class="card-text text-muted">Alterar meus dados cadastrais.</p>
                            <a href="meus-dados.php" class="btn btn-primary">Ver dados</a>
                        </div>
                    </div>
                </div> -->
                <div class="col-sm-6 col-md-4">
                    <div class="card h-100 text-center p-3">
                        <div class="card-body">
                            <h5 class="card-title">Sair</h5>
                            <p class="card-text text-muted">Encerrar a sessão atual.</p>
                            <a href="../logout.php" class="btn btn-outline-danger">Sair</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <footer>
        <p>&copy; 2025 VendaCarros. Todos os direitos reservados.</p>
    </footer>
</body>
</html>
