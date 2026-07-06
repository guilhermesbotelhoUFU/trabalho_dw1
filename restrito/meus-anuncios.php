<?php require_once '../auth.php'; ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meus anúncios - VendaCarros</title>
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
            <h2 class="mb-4">Meus anúncios</h2>
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Foto</th>
                            <th>Marca</th>
                            <th>Modelo</th>
                            <th>Ano</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody id="lista-anuncios"></tbody>
                </table>
            </div>
        </div>
    </main>
    <footer>
        <p>&copy; 2025 VendaCarros. Todos os direitos reservados.</p>
    </footer>
    <script>
        const corpo = document.getElementById('lista-anuncios');

        function celulaFoto(anuncio) {
            const td = document.createElement('td');
            if (anuncio.foto) {
                const img = document.createElement('img');
                img.src = '../uploads/' + anuncio.foto;
                img.alt = anuncio.marca + ' ' + anuncio.modelo;
                img.style.width = '80px';
                img.style.height = '60px';
                img.style.objectFit = 'cover';
                td.appendChild(img);
            } else {
                const box = document.createElement('div');
                box.className = 'bg-secondary text-white d-flex align-items-center justify-content-center';
                box.style.width = '80px';
                box.style.height = '60px';
                box.style.fontSize = '0.75rem';
                box.textContent = 'Sem foto';
                td.appendChild(box);
            }
            return td;
        }

        function celulaTexto(valor) {
            const td = document.createElement('td');
            td.textContent = valor;
            return td;
        }

        function celulaAcoes(anuncio) {
            const td = document.createElement('td');

            const detalhes = document.createElement('a');
            detalhes.href = 'detalhe-anuncio.php?id=' + anuncio.id;
            detalhes.className = 'btn btn-sm btn-outline-primary me-1';
            detalhes.textContent = 'Ver detalhes';

            const interesses = document.createElement('a');
            interesses.href = 'interesses.php?id=' + anuncio.id;
            interesses.className = 'btn btn-sm btn-outline-secondary me-1';
            interesses.textContent = 'Interesses';

            const excluir = document.createElement('button');
            excluir.type = 'button';
            excluir.className = 'btn btn-sm btn-outline-danger';
            excluir.textContent = 'Excluir';
            excluir.addEventListener('click', () => excluirAnuncio(anuncio.id, td));

            td.append(detalhes, interesses, excluir);
            return td;
        }

        function excluirAnuncio(id, celula) {
            if (!confirm('Excluir este anúncio e suas fotos?')) {
                return;
            }
            const dados = new FormData();
            dados.append('id', id);
            fetch('../controlador-restrito.php?acao=excluir', { method: 'POST', body: dados })
                .then(resposta => resposta.json())
                .then(retorno => {
                    if (retorno.ok) {
                        celula.parentElement.remove();
                    } else {
                        alert(retorno.erro || 'Não foi possível excluir.');
                    }
                });
        }

        // function mostrarAnuncios(anuncios) {
        //     let html = '';
        //     anuncios.forEach(anuncio => {
        //         html += '<tr><td>' + anuncio.marca + '</td><td>' + anuncio.modelo + '</td><td>' + anuncio.ano + '</td></tr>';
        //     });
        //     corpo.innerHTML = html;
        // }

        function mostrarAnuncios(anuncios) {
            corpo.innerHTML = '';
            if (anuncios.length === 0) {
                const linha = document.createElement('tr');
                const td = document.createElement('td');
                td.colSpan = 5;
                td.textContent = 'Você ainda não cadastrou nenhum anúncio.';
                linha.appendChild(td);
                corpo.appendChild(linha);
                return;
            }
            anuncios.forEach(anuncio => {
                const linha = document.createElement('tr');
                linha.appendChild(celulaFoto(anuncio));
                linha.appendChild(celulaTexto(anuncio.marca));
                linha.appendChild(celulaTexto(anuncio.modelo));
                linha.appendChild(celulaTexto(anuncio.ano));
                linha.appendChild(celulaAcoes(anuncio));
                corpo.appendChild(linha);
            });
        }

        fetch('../controlador-restrito.php?acao=meus')
            .then(resposta => resposta.json())
            .then(mostrarAnuncios)
            .catch(erro => console.error('Erro ao carregar anúncios: ', erro));
    </script>
</body>
</html>
