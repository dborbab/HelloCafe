import {
    showToast,
    getBackendUrlApi,
    getBackendUrl
} from "./../_shared/functions.js";

// Recupera dados da empresa logada
const userAuth = JSON.parse(localStorage.getItem("enterpriseAuth"));

if (!userAuth || !userAuth.token) {
    showToast("Você precisa estar logado para acessar o perfil", "error");
    setTimeout(() => {
        // window.location.href = getBackendUrl("login");
    }, 2000);
}

// Busca dados da empresa
fetch(getBackendUrlApi("enterprises/me"), {
    method: "GET",
    headers: {
        token: userAuth.token
    }
})
.then(async (response) => {
    const text = await response.text();
    try {
        return JSON.parse(text);
    } catch (e) {
        console.error("Resposta não é JSON:", text);
        throw new Error("Resposta inválida do servidor");
    }
})
.then((data) => {
    if (data.type === "error") {
        showToast(data.message, "error");
        setTimeout(() => {
            // window.location.href = getBackendUrl("login");
        }, 3000);
        return;
    }

    const enterprise = data.enterprise || data;

    // Preenche inputs
    document.querySelector("#name").value = enterprise.name || "";
    document.querySelector("#email").value = enterprise.email || "";
    document.querySelector("#address").value = enterprise.address || "";

    // Foto
    const avatar = document.querySelector("#imagePreview");
    if (enterprise.photo) {
        avatar.innerHTML = `<img src="${getBackendUrl(enterprise.photo)}" class="profile-image">`;
    } else {
        avatar.innerHTML = "Clique para<br>adicionar foto";
    }
})
.catch((error) => {
    console.error("Erro ao carregar dados:", error);
    showToast("Erro ao carregar perfil", "error");
});

/**
 * Atualizar dados da empresa
 */
const formUserUpdate = document.querySelector("#profileForm");
formUserUpdate.addEventListener("submit", (e) => {
    e.preventDefault();

    fetch(getBackendUrlApi("enterprises/update"), {
        method: "PUT",
        body: new URLSearchParams(new FormData(formUserUpdate)).toString(),
        headers: {
            token: userAuth.token,
            "Content-Type": "application/x-www-form-urlencoded"
        }
    })
    .then((response) => response.json())
    .then((data) => {
        if (data.type === "error") {
            showToast(data.message, "error");
            return;
        }

        showToast("Dados atualizados com sucesso!", "success");

        // Atualiza sidebar
        document.querySelector(".avatar-name").textContent = formUserUpdate.querySelector("#name").value;
        document.querySelector(".avatar-email").textContent = formUserUpdate.querySelector("#email").value;

        // Atualiza storage
        userAuth.name = formUserUpdate.querySelector("#name").value;
        userAuth.email = formUserUpdate.querySelector("#email").value;
        localStorage.setItem("enterpriseAuth", JSON.stringify(userAuth));
    });
});

/**
 * Atualizar foto
 */
const formPhoto = document.querySelector("#imagePreview");
formPhoto.addEventListener("submit", (e) => {
    e.preventDefault();

    fetch(getBackendUrlApi("enterprises/photo"), {
        method: "POST",
        body: new FormData(formPhoto),
        headers: {
            token: userAuth.token
        }
    })
    .then((response) => response.json())
    .then((data) => {
        if (data.type === "error") {
            showToast(data.message, "error");
            return;
        }

        showToast("Foto atualizada com sucesso!", "success");

        // Atualiza avatar
        const avatar = document.querySelector(".avatar");
        avatar.style.backgroundImage = `url(${getBackendUrl(data.enterprise.photo)})`;
        avatar.style.backgroundSize = "cover";
        avatar.style.backgroundPosition = "center";
        avatar.innerHTML = "";

        // Atualiza storage
        userAuth.photo = data.enterprise.photo;
        localStorage.setItem("enterpriseAuth", JSON.stringify(userAuth));
    });
});

/**
 * Mostrar nome do arquivo no input de foto
 */
document.querySelector("#profileImageInput").addEventListener("change", function () {
    const fileName = this.files[0]?.name;
    if (fileName) {
        document.querySelector(".file-upload-label").textContent = `📷 ${fileName}`;
    }
});
