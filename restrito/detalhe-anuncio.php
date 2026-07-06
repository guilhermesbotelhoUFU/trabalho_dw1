<?php require_once '../auth.php'; ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes do anúncio - VendaCarros</title>
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
            <h2 class="mb-4" id="titulo">Carregando...</h2>
            <div class="d-flex flex-wrap gap-3 mb-4" id="fotos"></div>
            <table class="table table-bordered" style="max-width: 600px;">
                <tbody id="dados"></tbody>
            </table>
        </div>
    </main>
    <footer>
        <p>&copy; 2025 VendaCarros. Todos os direitos reservados.</p>
    </footer>
    <script>
        const id = new URLSearchParams(location.search).get('id');
        const titulo = document.getElementById('titulo');
        const fotos = document.getElementById('fotos');
        const dados = document.getElementById('dados');

        // function linha(rotulo, valor) {
        //     return '<tr><th>' + rotulo + '</th><td>' + valor + '</td></tr>';
        // }

        function linha(rotulo, valor) {
            const tr = document.createElement('tr');
            const th = document.createElement('th');
            th.className = 'table-light';
            th.style.width = '40%';
            th.textContent = rotulo;
            const td = document.createElement('td');
            td.textContent = valor;
            tr.append(th, td);
            return tr;
        }

        fetch('../controlador-restrito.php?acao=detalhe&id=' + id)
            .then(resposta => resposta.json())
            .then(anuncio => {
                if (anuncio.erro) {
                    titulo.textContent = anuncio.erro;
                    return;
                }
                titulo.textContent = anuncio.marca + ' ' + anuncio.modelo;

                if (anuncio.fotos && anuncio.fotos.length > 0) {
                    anuncio.fotos.forEach(foto => {
                        const img = document.createElement('img');
                        img.src = '../uploads/' + foto;
                        img.alt = anuncio.marca + ' ' + anuncio.modelo;
                        img.style.width = '200px';
                        img.style.height = '150px';
                        img.style.objectFit = 'cover';
                        img.style.borderRadius = '4px';
                        fotos.appendChild(img);
                    });
                } else {
                    const aviso = document.createElement('p');
                    aviso.textContent = 'Este anúncio não possui fotos.';
                    fotos.appendChild(aviso);
                }

                const km = Number(anuncio.quilometragem).toLocaleString('pt-BR');
                const valor = Number(anuncio.valor).toLocaleString('pt-BR', { minimumFractionDigits: 2 });

                dados.append(
                    linha('Marca', anuncio.marca),
                    linha('Modelo', anuncio.modelo),
                    linha('Ano de fabricação', anuncio.ano),
                    linha('Cor', anuncio.cor),
                    linha('Quilometragem', km + ' km'),
                    linha('Valor', 'R$ ' + valor),
                    linha('Estado', anuncio.estado),
                    linha('Cidade', anuncio.cidade),
                    linha('Descrição', anuncio.descricao)
                );
            })
            .catch(erro => console.error('Erro ao carregar anúncio: ', erro));
    </script>
</body>
</html>
