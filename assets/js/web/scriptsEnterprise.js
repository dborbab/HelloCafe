import {
    getBackendUrlApi,
    getBackendUrl,
    showToast
} from "./../_shared/functions.js";

document.addEventListener("DOMContentLoaded", () => {
    const formRegister = document.querySelector("#formRegister");

    if (!formRegister) {
        console.error("formRegister não encontrado");
        return;
    }

    formRegister.addEventListener("submit", async (e) => {
        e.preventDefault();

        const formData = new FormData(formRegister);

        try {
            const response = await fetch(getBackendUrlApi("enterprises/createEnterprise"), {
                method: "POST",
                body: formData
            });

            const data = await response.json();
            console.log("Resposta do backend:", data);

            if (data.type === "error") {
                showToast(data.message);
                return;
            }

            showToast("Empresa cadastrada com sucesso!");
            setTimeout(() => {
                window.location.href = getBackendUrl("admin/services");
            }, 2000);

        } catch (error) {
            console.error("Erro ao cadastrar:", error);
            showToast("Erro inesperado. Tente novamente.");
        }
    });
});

