import {
    showToast,
    getBackendUrlApi,
    getBackendUrl
} from "./../_shared/functions.js"

// Verificar autenticação
const userAuth = JSON.parse(localStorage.getItem("enterpriseAuth"));

if (!userAuth || !userAuth.token) {
    showToast("Você precisa estar logado para acessar os produtos", "error");
    setTimeout(() => {
        // Redirecionar para login se necessário
    }, 2000);
}

// Variáveis globais
let products = [];
let editingProduct = null;

// Elementos DOM
const form = document.getElementById('form-produto');
const formTitle = document.getElementById('form-title');
const btnSubmit = document.getElementById('btn-submit');
const btnCancel = document.getElementById('btn-cancel');
const btnText = document.getElementById('btn-text');
const btnLoading = document.getElementById('btn-loading');
const listaProdutos = document.getElementById('lista-produtos');

// Inicializar aplicação
document.addEventListener('DOMContentLoaded', function() {
    loadProducts();
    setupEventListeners();
});

function setupEventListeners() {
    form.addEventListener('submit', handleSubmit);
    btnCancel.addEventListener('click', cancelEdit);
    
    // Formatação de preço em tempo real
    document.getElementById('preco').addEventListener('input', function(e) {
        let value = e.target.value;
        if (value && !isNaN(value)) {
            // Apenas formatar visualmente, não alterar o value
            const formatted = parseFloat(value).toFixed(2);
            if (formatted !== 'NaN') {
                e.target.dataset.formatted = `R$ ${formatted}`;
            }
        }
    });
}

// Função para fazer requisições à API (seguindo seu padrão)
async function apiRequest(endpoint, options = {}) {
    const defaultHeaders = {
        token: userAuth.token
    };

    // Se for POST/PUT com JSON, adicionar Content-Type
    if (options.body && typeof options.body === 'string') {
        defaultHeaders['Content-Type'] = 'application/json';
    }

    const finalOptions = {
        ...options,
        headers: {
            ...defaultHeaders,
            ...options.headers
        }
    };

    try {
        const response = await fetch(getBackendUrlApi(endpoint), finalOptions);
        const text = await response.text();
        
        console.log(`Resposta ${endpoint}:`, text);

        // Verificar se a resposta é JSON válido
        if (!text.trim().startsWith('{') && !text.trim().startsWith('[')) {
            console.error("Resposta não é JSON:", text);
            throw new Error("Servidor retornou dados inválidos");
        }

        const data = JSON.parse(text);
        
        if (data.type === "error" || data.error) {
            const message = data.message || data.error?.message || "Erro desconhecido";
            throw new Error(message);
        }
        
        return data;
    } catch (error) {
        console.error(`Erro na API ${endpoint}:`, error);
        throw error;
    }
}

// Carregar produtos
async function loadProducts() {
    try {
        const data = await apiRequest('products');
        products = data.products || [];
        renderProducts();
        updateStats();
    } catch (error) {
        console.error('Erro ao carregar produtos:', error);
        showToast('Erro ao carregar produtos: ' + error.message, 'error');
        
        // Se erro de autenticação, limpar storage
        if (error.message.includes("Token") || error.message.includes("autenticado")) {
            setTimeout(() => {
                localStorage.removeItem("userAuth");
                // Redirecionar para login
            }, 3000);
        }
    }
}

// Renderizar produtos na lista
function renderProducts() {
    if (products.length === 0) {
        listaProdutos.innerHTML = `
            <div class="empty-state">
                <div class="empty-state-icon">🛍️</div>
                <p>Nenhum produto cadastrado ainda.<br>Use o formulário ao lado para adicionar produtos!</p>
            </div>
        `;
        return;
    }

    const html = products.map(product => `
        <div class="product-card">
            <div class="product-header">
                <h3 class="product-name">${product.name}</h3>
                <span class="product-price">R$ ${parseFloat(product.price || 0).toFixed(2)}</span>
            </div>
            <p class="product-description">${product.description}</p>
            ${product.image ? `
                <div class="product-image" style="margin: 10px 0;">
                    <img src="${product.image}" alt="${product.name}" style="width: 100%; max-height: 150px; object-fit: cover; border-radius: 8px;">
                </div>
            ` : ''}
            <div class="product-details">
                <div class="product-detail">
                    <span>📦</span>
                    <span>Estoque: ${product.stock}</span>
                </div>
                <div class="product-detail">
                    <span>🏷️</span>
                    <span>Categoria: ${product.category_id}</span>
                </div>
            </div>
            <div class="product-actions">
                <button class="btn-edit" onclick="editProduct(${product.id})">
                    ✏️ Editar
                </button>
                <button class="btn-delete" onclick="deleteProduct(${product.id})">
                    🗑️ Excluir
                </button>
            </div>
        </div>
    `).join('');

    listaProdutos.innerHTML = html;
}

// Atualizar estatísticas
function updateStats() {
    const totalProducts = products.length;
    const totalValue = products.reduce((sum, product) => sum + (parseFloat(product.price || 0) * parseInt(product.stock || 0)), 0);
    const totalQuantity = products.reduce((sum, product) => sum + parseInt(product.stock || 0), 0);

    document.getElementById('total-products').textContent = totalProducts;
    document.getElementById('total-value').textContent = `R$ ${totalValue.toFixed(2)}`;
    document.getElementById('total-quantity').textContent = totalQuantity;
}

// Manipular envio do formulário
async function handleSubmit(e) {
    e.preventDefault();
    
    // Mostrar loading
    btnText.style.display = 'none';
    btnLoading.style.display = 'inline-block';
    btnSubmit.disabled = true;

    try {
        const formData = new FormData(form);
        
        // Converter para o formato esperado pela API
        const productData = {
            name: formData.get('name'),
            description: formData.get('description'),
            price: parseFloat(formData.get('price')),
            stock: parseInt(formData.get('stock')),
            image: (formData.get('image') || "").trim(),
            category_id: parseInt(formData.get('category_id'), 10) || null
        };

        // Validações
        if (!productData.name || !productData.description || isNaN(productData.price) || isNaN(productData.stock) || isNaN(productData.category_id)) {
    throw new Error('Preencha todos os campos obrigatórios');
}

        if (productData.price <= 0) {
            throw new Error('Preço deve ser maior que zero');
        }

        if (productData.stock < 0) {
            throw new Error('Estoque não pode ser negativo');
        }

        if (editingProduct) {
            // Atualizar produto existente
            productData.id = editingProduct.id;
            await apiRequest(`products/${editingProduct.id}`, {
                method: 'PUT',
                body: JSON.stringify(productData)
            });
            showToast('Produto atualizado com sucesso!', 'success');
        } else {
            // Criar novo produto
            await apiRequest('products', {
                method: 'POST',
                body: JSON.stringify(productData)
            });
            showToast('Produto cadastrado com sucesso!', 'success');
        }
        
        // Limpar formulário e recarregar lista
        form.reset();
        await loadProducts();
        
        if (editingProduct) {
            cancelEdit();
        }

    } catch (error) {
        console.error('Erro ao salvar produto:', error);
        showToast('Erro ao salvar produto: ' + error.message, 'error');
    } finally {
        // Restaurar botão
        btnText.style.display = 'inline';
        btnLoading.style.display = 'none';
        btnSubmit.disabled = false;
    }
}

// Editar produto
window.editProduct = function(id) {
    const product = products.find(p => p.id === id);
    if (!product) {
        showToast('Produto não encontrado', 'error');
        return;
    }

    editingProduct = product;
    
    // Preencher formulário
    document.getElementById('produto-id').value = product.id;
    document.getElementById('nome').value = product.name;
    document.getElementById('descricao').value = product.description;
    document.getElementById('preco').value = product.price;
    document.getElementById('quantidade').value = product.stock;
    document.getElementById('imagem').value = product.image || null;
    document.getElementById('categoria').value = product.category_id;

    // Atualizar interface
    formTitle.textContent = '✏️ Editar Produto';
    btnText.textContent = '💾 Salvar Alterações';
    btnCancel.style.display = 'block';

    // Scroll para o formulário
    document.querySelector('.form-container').scrollIntoView({ 
        behavior: 'smooth',
        block: 'start'
    });
};

// Cancelar edição
function cancelEdit() {
    editingProduct = null;
    form.reset();
    formTitle.textContent = '☕ Cadastrar Produto';
    btnText.textContent = '✨ Cadastrar Produto';
    btnCancel.style.display = 'none';
}

// Deletar produto
window.deleteProduct = async function(id) {
    const product = products.find(p => p.id === id);
    if (!product) {
        showToast('Produto não encontrado', 'error');
        return;
    }

    if (!confirm(`Tem certeza que deseja excluir "${product.name}"?`)) {
        return;
    }

    try {
        await apiRequest(`products/${id}`, {
            method: 'DELETE'
        });
        
        showToast('Produto excluído com sucesso!', 'success');
        
        // Se estava editando este produto, cancelar edição
        if (editingProduct && editingProduct.id === id) {
            cancelEdit();
        }
        
        await loadProducts();
    } catch (error) {
        console.error('Erro ao excluir produto:', error);
        showToast('Erro ao excluir produto: ' + error.message, 'error');
    }
};

// Função para buscar produto por ID (caso precise)
window.getProductById = async function(id) {
    try {
        const data = await apiRequest(`products/${id}`);
        return data.product;
    } catch (error) {
        console.error('Erro ao buscar produto:', error);
        showToast('Erro ao buscar produto: ' + error.message, 'error');
        return null;
    }
};
