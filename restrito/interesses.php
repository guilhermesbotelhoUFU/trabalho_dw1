<?php require_once '../auth.php'; ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Interesses - VendaCarros</title>
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
            <div class="mb-3">
                <a href="meus-anuncios.php" class="btn btn-outline-secondary btn-sm">&larr; Voltar para meus anúncios</a>
            </div>
            <h2 class="mb-1">Interesses recebidos</h2>
            <p class="text-muted mb-4" id="subtitulo"></p>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>Nome</th>
                            <th>Telefone</th>
                            <th>Mensagem</th>
                            <th>Ação</th>
                        </tr>
                    </thead>
                    <tbody id="lista-interesses"></tbody>
                </table>
            </div>
        </div>
    </main>
    <footer>
        <p>&copy; 2025 VendaCarros. Todos os direitos reservados.</p>
    </footer>
    <script>
        const id = new URLSearchParams(location.search).get('id');
        const subtitulo = document.getElementById('subtitulo');
        const corpo = document.getElementById('lista-interesses');

        function celulaTexto(valor) {
            const td = document.createElement('td');
            td.textContent = valor;
            return td;
        }

        function excluirInteresse(idInteresse, celula) {
            if (!confirm('Excluir esta mensagem de interesse?')) {
                return;
            }
            const dados = new FormData();
            dados.append('id', idInteresse);
            fetch('../controlador-restrito.php?acao=excluir-interesse', { method: 'POST', body: dados })
                .then(resposta => resposta.json())
                .then(retorno => {
                    if (retorno.ok) {
                        celula.parentElement.remove();
                    } else {
                        alert(retorno.erro || 'Não foi possível excluir.');
                    }
                });
        }

        function mostrarInteresses(interesses) {
            corpo.innerHTML = '';
            if (interesses.length === 0) {
                const linha = document.createElement('tr');
                const td = document.createElement('td');
                td.colSpan = 4;
                td.textContent = 'Nenhum interesse registrado neste anúncio.';
                linha.appendChild(td);
                corpo.appendChild(linha);
                return;
            }
            interesses.forEach(interesse => {
                const linha = document.createElement('tr');
                linha.appendChild(celulaTexto(interesse.nome));
                linha.appendChild(celulaTexto(interesse.telefone));
                linha.appendChild(celulaTexto(interesse.mensagem));

                const acao = document.createElement('td');
                const excluir = document.createElement('button');
                excluir.type = 'button';
                excluir.className = 'btn btn-sm btn-outline-danger';
                excluir.textContent = 'Excluir';
                excluir.addEventListener('click', () => excluirInteresse(interesse.id, acao));
                acao.appendChild(excluir);
                linha.appendChild(acao);

                corpo.appendChild(linha);
            });
        }

        fetch('../controlador-restrito.php?acao=interesses&idAnuncio=' + id)
            .then(resposta => resposta.json())
            .then(dados => {
                if (dados.erro) {
                    subtitulo.textContent = dados.erro;
                    return;
                }
                subtitulo.textContent = 'Anúncio: ' + dados.anuncio;
                // subtitulo.textContent += ' (' + dados.interesses.length + ' interesses)';
                mostrarInteresses(dados.interesses);
            })
            .catch(erro => console.error('Erro ao carregar interesses: ', erro));
    </script>
</body>
</html>
