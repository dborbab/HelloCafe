
import {
    getBackendUrl,
    getBackendUrlApi,
    getFirstName,
    showToast
} from "./../_shared/functions.js";

document.addEventListener("DOMContentLoaded", () => {

const formLogin = document.querySelector("#formLogin");
formLogin.addEventListener("submit", async (e) => {
    e.preventDefault();
    
    const formData = new FormData(formLogin);
    
    // First try to login as a user
    try {
        const userResponse = await fetch(getBackendUrlApi("users/login"), {
            method: "POST",
            body: formData
        });
        
        const userData = await userResponse.json();
        
        if (userData.type !== "error") {
            // User login successful
            localStorage.setItem("userAuth", JSON.stringify(userData.user));
            showToast(`Olá, ${getFirstName(userData.user.name)} como vai!`);
            setTimeout(() => {
                window.location.href = getBackendUrl("app/profile");
            }, 3000);
            return;
        }
        
        // If user login failed, try enterprise login
        const enterpriseResponse = await fetch(getBackendUrlApi("enterprises/login"), {
            method: "POST",
            body: formData
        });
        
        // Check if the request was successful
        if (!enterpriseResponse.ok) {
            console.error("Enterprise endpoint error:", enterpriseResponse.status);
            showToast("Email ou senha incorretos!");
            return;
        }
        
        const enterpriseData = await enterpriseResponse.json();
        
        if (enterpriseData.type !== "error" && enterpriseData.user) {
            // Enterprise login successful
            localStorage.setItem("enterpriseAuth", JSON.stringify(enterpriseData.user));
            showToast(`Olá, ${getFirstName(enterpriseData.user.name)} como vai!`);
            setTimeout(() => {
                window.location.href = getBackendUrl("admin/profile");
            }, 3000);
            return;
        }
        
        // Both logins failed
        showToast("Email ou senha incorretos!");
        
    } catch (error) {
        console.error("Login error:", error);
        showToast("Erro ao tentar fazer login. Tente novamente.");
    }
});

});
