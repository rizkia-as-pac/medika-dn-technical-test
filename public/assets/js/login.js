const statusEl = document.getElementById("status");
const loginBtn = document.getElementById("loginBtn");
const publicBtn = document.getElementById("publicBtn");

function setStatus(text) {
    statusEl.textContent = text || "";
}

function goPublic() {
    window.location.href = "/index.html";
}

async function login() {
    const email = document.getElementById("email").value.trim();
    const password = document.getElementById("password").value;

    if (!email || !password) {
        setStatus("Email dan password wajib diisi.");
        return;
    }

    setStatus("Logging in...");
    loginBtn.disabled = true;

    try {
        const res = await fetch("/api/admin/login", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                Accept: "application/json",
            },
            body: JSON.stringify({ email, password }),
        });

        const json = await res.json();

        if (!res.ok) {
            setStatus(json.message || "Login gagal.");
            loginBtn.disabled = false;
            return;
        }

        const token = json.token;
        localStorage.setItem("admin_token", token);

        setStatus("Login sukses. Redirecting...");
        window.location.href = "/index.html";
    } catch (e) {
        setStatus("Request error.");
        loginBtn.disabled = false;
    }
}

// bind events
loginBtn.addEventListener("click", login);
publicBtn.addEventListener("click", goPublic);

// auto redirect if already logged in
const existing = localStorage.getItem("admin_token");
if (existing) {
    setStatus("Token ditemukan. Redirecting...");
    window.location.href = "/index.html";
}
