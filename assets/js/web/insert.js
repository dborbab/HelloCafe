import {
    getBackendUrlApi,
    getBackendUrl,
    showToast
} from "./../_shared/functions.js";


document.addEventListener("DOMContentLoaded", () => {
    console.log(document.body.innerHTML)
    const formRegister = document.querySelector("#formRegister");

    if (!formRegister) {
        console.error("formRegister não encontrado");
        return;
    }

    formRegister.addEventListener("submit", async (e) => {
        e.preventDefault();

        const formData = new FormData(formRegister);

        try {
            const response = await fetch(getBackendUrlApi("users/create"), {
                method: "POST",
                body: formData
            });

            const data = await response.json();

            if (data.error) {
                showToast(data.message);
                return;
            }

            showToast("Usuário cadastrado com sucesso!");
            setTimeout(() => {
                window.location.href = getBackendUrl("app/profile");
            }, 2000);

        } catch (error) {
            console.error("Erro ao cadastrar:", error);
            showToast("Erro inesperado. Tente novamente.");
        }
    });
});
