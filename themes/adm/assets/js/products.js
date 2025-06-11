    const form = document.getElementById('form-produto');
    const listaProdutos = document.getElementById('lista-produtos');

    form.addEventListener('submit', function(e) {
      e.preventDefault();

      // Pega valores
      const nome = form.nome.value.trim();
      const descricao = form.descricao.value.trim();
      const preco = parseFloat(form.preco.value).toFixed(2);
      const quantidade = parseInt(form.quantidade.value);

      // Criar div do produto
      const produtoItem = document.createElement('div');
      produtoItem.className = 'produto-item';

      // Conteúdo do produto
      produtoItem.innerHTML = `
        <div class="produto-info">
          <strong>${nome}</strong>
          <p>${descricao}</p>
          <p>Preço: R$ ${preco} | Quantidade: ${quantidade}</p>
        </div>
        <button class="btn-excluir">Excluir</button>
      `;

      // Botão excluir
      const btnExcluir = produtoItem.querySelector('.btn-excluir');
      btnExcluir.addEventListener('click', () => {
        listaProdutos.removeChild(produtoItem);
      });

      // Adiciona à lista
      listaProdutos.appendChild(produtoItem);

      // Limpa o formulário
      form.reset();
      form.nome.focus();
    });