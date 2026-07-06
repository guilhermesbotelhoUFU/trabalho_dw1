<?php require_once '../auth.php'; ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Novo anúncio - VendaCarros</title>
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
        <div class="container-fluid px-0" style="max-width: 700px;">
            <h2 class="mb-4">Novo anúncio</h2>
            <p id="mensagem-anuncio" class="mb-3"></p>
            <form id="form-anuncio" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="marca" class="form-label">Marca</label>
                    <input type="text" class="form-control" id="marca" name="marca" required>
                </div>
                <div class="mb-3">
                    <label for="modelo" class="form-label">Modelo</label>
                    <input type="text" class="form-control" id="modelo" name="modelo" required>
                </div>
                <div class="row">
                    <div class="col-sm-6 mb-3">
                        <label for="ano" class="form-label">Ano de fabricação</label>
                        <input type="text" class="form-control" id="ano" name="ano" maxlength="4" required>
                    </div>
                    <div class="col-sm-6 mb-3">
                        <label for="cor" class="form-label">Cor</label>
                        <input type="text" class="form-control" id="cor" name="cor" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6 mb-3">
                        <label for="quilometragem" class="form-label">Quilometragem (km)</label>
                        <input type="text" class="form-control" id="quilometragem" name="quilometragem" required>
                    </div>
                    <div class="col-sm-6 mb-3">
                        <label for="valor" class="form-label">Valor (R$)</label>
                        <input type="text" class="form-control" id="valor" name="valor" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="descricao" class="form-label">Descrição</label>
                    <textarea class="form-control" id="descricao" name="descricao" rows="4" required></textarea>
                </div>
                <div class="row">
                    <div class="col-sm-6 mb-3">
                        <label for="estado" class="form-label">Estado</label>
                        <select class="form-select" id="estado" name="estado" required>
                            <option value="">Selecione</option>
                            <option value="AC">Acre (AC)</option>
                            <option value="AL">Alagoas (AL)</option>
                            <option value="AM">Amazonas (AM)</option>
                            <option value="BA">Bahia (BA)</option>
                            <option value="CE">Ceará (CE)</option>
                            <option value="DF">Distrito Federal (DF)</option>
                            <option value="ES">Espírito Santo (ES)</option>
                            <option value="GO">Goiás (GO)</option>
                            <option value="MA">Maranhão (MA)</option>
                            <option value="MG">Minas Gerais (MG)</option>
                            <option value="MS">Mato Grosso do Sul (MS)</option>
                            <option value="MT">Mato Grosso (MT)</option>
                            <option value="PA">Pará (PA)</option>
                            <option value="PB">Paraíba (PB)</option>
                            <option value="PE">Pernambuco (PE)</option>
                            <option value="PI">Piauí (PI)</option>
                            <option value="PR">Paraná (PR)</option>
                            <option value="RJ">Rio de Janeiro (RJ)</option>
                            <option value="RN">Rio Grande do Norte (RN)</option>
                            <option value="RO">Rondônia (RO)</option>
                            <option value="RR">Roraima (RR)</option>
                            <option value="RS">Rio Grande do Sul (RS)</option>
                            <option value="SC">Santa Catarina (SC)</option>
                            <option value="SE">Sergipe (SE)</option>
                            <option value="SP">São Paulo (SP)</option>
                            <option value="TO">Tocantins (TO)</option>
                        </select>
                    </div>
                    <div class="col-sm-6 mb-3">
                        <label for="cidade" class="form-label">Cidade</label>
                        <input type="text" class="form-control" id="cidade" name="cidade" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="foto1" class="form-label">Foto 1</label>
                    <input type="file" class="form-control" id="foto1" name="foto[]" accept="image/*" required>
                </div>
                <div class="mb-3">
                    <label for="foto2" class="form-label">Foto 2</label>
                    <input type="file" class="form-control" id="foto2" name="foto[]" accept="image/*" required>
                </div>
                <div class="mb-3">
                    <label for="foto3" class="form-label">Foto 3</label>
                    <input type="file" class="form-control" id="foto3" name="foto[]" accept="image/*" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Publicar anúncio</button>
            </form>
        </div>
    </main>
    <footer>
        <p>&copy; 2025 VendaCarros. Todos os direitos reservados.</p>
    </footer>
    <script>
        const form = document.getElementById('form-anuncio');
        const mensagem = document.getElementById('mensagem-anuncio');

        form.addEventListener('submit', evento => {
            evento.preventDefault();
            mensagem.textContent = '';
            mensagem.className = 'mb-3';

            // const arquivos = form.querySelectorAll('input[type="file"]');
            // for (const arquivo of arquivos) {
            //     if (arquivo.files.length === 0) {
            //         mensagem.textContent = 'Selecione as tres fotos.';
            //         return;
            //     }
            // }

            fetch('../controlador-restrito.php?acao=criar', {
                method: 'POST',
                body: new FormData(form),
            })
                .then(resposta => resposta.json())
                .then(dados => {
                    if (dados.ok) {
                        // alert('Anúncio publicado com sucesso!');
                        window.location.href = 'meus-anuncios.php';
                    } else {
                        // alert(dados.erro);
                        mensagem.textContent = dados.erro;
                        mensagem.classList.add('text-danger');
                    }
                })
                .catch(() => {
                    mensagem.textContent = 'Erro ao publicar o anúncio. Tente novamente.';
                    mensagem.classList.add('text-danger');
                });
        });
    </script>
</body>
</html>
