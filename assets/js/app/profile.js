import {
    showToast,
    getBackendUrlApi,
    getBackendUrl
} from "./../_shared/functions.js";

const userAuth = JSON.parse(localStorage.getItem("userAuth"));

if (!userAuth || !userAuth.token) {
    showToast("Você precisa estar logado para acessar o perfil", "error");
    setTimeout(() => {
        window.location.href = getBackendUrl("login.html");
    }, 2000);
}
  
// Carregar dados do usuário
fetch(getBackendUrlApi("users/me"), {
    method: "GET",
    headers: {
        token: userAuth.token
    }
})
.then(async (response) => {
    const text = await response.text();
    console.log("Resposta do servidor:", text);

    // Verificar se a resposta é JSON válido
    if (!text.trim().startsWith('{') && !text.trim().startsWith('[')) {
        console.error("Resposta não é JSON:", text);
        throw new Error("Servidor retornou dados inválidos");
    }

    try {
        return JSON.parse(text);
    } catch (e) {
        console.error("Erro ao fazer parse do JSON:", e);
        console.error("Texto recebido:", text);
        throw new Error("Resposta inválida do servidor");
    }
})
.then((data) => {
    if (data.type === "error" || data.error) {
        const message = data.message || data.error?.message || "Erro desconhecido";
        showToast(message, "error");
        if (message.includes("Token") || message.includes("autenticado")) {
            setTimeout(() => {
                localStorage.removeItem("userAuth");
                window.location.href = getBackendUrl("login.html");
            }, 3000);
        }
        return;
    }

    // Preencher os dados do usuário
    if (data.user) {
        document.querySelector("#name").value = data.user.name || "";
        document.querySelector("#email").value = data.user.email || "";
        document.querySelector("#address").value = data.user.address || "";

        // Atualizar sidebar
        document.querySelector(".avatar-name").textContent = data.user.name;
        document.querySelector(".avatar-email").textContent = data.user.email;

        // Foto do usuário
        const avatar = document.querySelector(".avatar");
        if (data.user.photo) {
            avatar.style.backgroundImage = `url(${getBackendUrl(data.user.photo)})`;
            avatar.style.backgroundSize = "cover";
            avatar.style.backgroundPosition = "center";
            avatar.innerHTML = "";
        } else {
            avatar.textContent = "👤";
        }
    }
})
.catch(error => {
    console.error("Erro ao carregar dados do usuário:", error);
    showToast("Erro ao carregar dados do usuário", "error");
});

/**
 * Atualizar dados do usuário
 */
const formUserUpdate = document.querySelector("#profile");

formUserUpdate.addEventListener("submit", (e) => {
    e.preventDefault();

    fetch(getBackendUrlApi("users/update"), {
        method: "PUT",
        body: new URLSearchParams(new FormData(formUserUpdate)).toString(),
        headers: {
            token: userAuth.token,
            "Content-Type": "application/x-www-form-urlencoded"
        }
    })
    .then(async (response) => {
        const text = await response.text();
        
        if (!text.trim().startsWith('{')) {
            console.error("Resposta não é JSON:", text);
            throw new Error("Servidor retornou dados inválidos");
        }
        
        return JSON.parse(text);
    })
    .then((data) => {
        if (data.type === "error" || data.error) {
            const message = data.message || data.error?.message || "Erro ao atualizar";
            showToast(message, "error");
            return;
        }

        showToast("Dados atualizados com sucesso!", "success");

        // Atualizar sidebar
        if (data.user) {
            document.querySelector(".avatar-name").textContent = data.user.name;
            document.querySelector(".avatar-email").textContent = data.user.email;
        }
    })
    .catch(error => {
        console.error("Erro ao atualizar dados:", error);
        showToast("Erro ao atualizar dados", "error");
    });
});

/**
 * Atualizar foto do usuário
 */
const formPhoto = document.querySelector("#form-photo");

formPhoto.addEventListener("submit", (e) => {
    e.preventDefault();

    const photoInput = document.querySelector("#photo");
    const file = photoInput.files[0];

    // Validações no frontend
    if (!file) {
        showToast("Por favor, selecione uma foto", "error");
        return;
    }

    // Verificar tipo de arquivo
    const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
    if (!allowedTypes.includes(file.type)) {
        showToast("Formato inválido. Use apenas JPG, JPEG ou PNG", "error");
        return;
    }

    // Verificar tamanho (5MB)
    if (file.size > 5 * 1024 * 1024) {
        showToast("Arquivo muito grande. Máximo: 5MB", "error");
        return;
    }

    // Mostrar loading
    const submitBtn = formPhoto.querySelector('button[type="submit"]');
    const originalText = submitBtn.textContent;
    submitBtn.textContent = "Enviando...";
    submitBtn.disabled = true;

    fetch(getBackendUrlApi("users/photo"), {
        method: "POST",
        body: new FormData(formPhoto),
        headers: {
            token: userAuth.token
        }
    })
    .then(async (response) => {
        const text = await response.text();
        console.log("Resposta upload foto:", text);
        
        if (!text.trim().startsWith('{')) {
            console.error("Resposta não é JSON:", text);
            throw new Error("Servidor retornou dados inválidos");
        }
        
        return JSON.parse(text);
    })
    .then((data) => {
        if (data.type === "error" || data.error) {
            const message = data.message || data.error?.message || "Erro ao enviar foto";
            showToast(message, "error");
            return;
        }

        showToast("Foto atualizada com sucesso!", "success");

        // Atualizar avatar
        if (data.user && data.user.photo) {
            const avatar = document.querySelector(".avatar");
            avatar.style.backgroundImage = `url(${getBackendUrl(data.user.photo)})`;
            avatar.style.backgroundSize = "cover";
            avatar.style.backgroundPosition = "center";
            avatar.innerHTML = "";

            // Atualizar storage
            userAuth.photo = data.user.photo;
            localStorage.setItem("userAuth", JSON.stringify(userAuth));
        }

        // Limpar o formulário
        formPhoto.reset();
        document.querySelector(".file-upload-label").textContent = "📷 Escolher foto";
    })
    .catch(error => {
        console.error("Erro ao enviar foto:", error);
        showToast("Erro ao enviar foto", "error");
    })
    .finally(() => {
        // Restaurar botão
        submitBtn.textContent = originalText;
        submitBtn.disabled = false;
    });
});

/**
 * Mostrar nome do arquivo selecionado no input de foto
 */
document.querySelector("#photo").addEventListener("change", function () {
    const file = this.files[0];
    const label = document.querySelector(".file-upload-label");
    
    if (file) {
        // Validar arquivo antes de mostrar
        const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
        if (!allowedTypes.includes(file.type)) {
            showToast("Formato inválido. Use apenas JPG, JPEG ou PNG", "error");
            this.value = "";
            label.textContent = "📷 Escolher foto";
            return;
        }
        
        if (file.size > 5 * 1024 * 1024) {
            showToast("Arquivo muito grande. Máximo: 5MB", "error");
            this.value = "";
            label.textContent = "📷 Escolher foto";
            return;
        }
        
        label.textContent = `📷 ${file.name}`;
    } else {
        label.textContent = "📷 Escolher foto";
    }
});
