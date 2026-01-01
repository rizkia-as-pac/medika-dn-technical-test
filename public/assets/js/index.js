/* ===============================
   AUTH GUARD
   =============================== */
const token = localStorage.getItem("admin_token");
if (!token) {
    window.location.replace("/login.html");
}

/* ===============================
   ELEMENTS
   =============================== */
const currentEl = document.getElementById("current");
const metaEl = document.getElementById("meta");
const listEl = document.getElementById("list");
const adminStatusEl = document.getElementById("adminStatus");

const btnIssue = document.getElementById("btnIssue");
const btnPrev = document.getElementById("btnPrev");
const btnNext = document.getElementById("btnNext");
const btnLogout = document.getElementById("btnLogout");

function setAdminStatus(text) {
    adminStatusEl.textContent = text || "";
}

/* ===============================
   LOGOUT
   =============================== */
function logout() {
    localStorage.removeItem("admin_token");
    window.location.replace("/login.html");
}

/* ===============================
   FETCH PUBLIC QUEUE
   =============================== */
async function fetchQueue() {
    try {
        const res = await fetch("/api/queue/public", {
            headers: { Accept: "application/json" },
        });

        const json = await res.json();
        if (!json.success) throw new Error();

        const data = json.data;
        currentEl.textContent = String(data.current_number);
        metaEl.textContent = `Date: ${data.date} • Total tickets: ${data.queues.length}`;

        listEl.innerHTML = data.queues
            .map(
                (q) => `
        <tr>
          <td>${q.number}</td>
          <td><span class="badge">${q.status}</span></td>
          <td>${new Date(q.created_at).toLocaleString()}</td>
        </tr>
      `,
            )
            .join("");
    } catch {
        metaEl.textContent = "Error loading queue data";
    }
}

/* ===============================
   ADMIN ACTIONS
   =============================== */
async function callAdmin(endpoint) {
    setAdminStatus("Processing...");

    try {
        const res = await fetch(endpoint, {
            method: "POST",
            headers: {
                Authorization: "Bearer " + token,
                Accept: "application/json",
            },
        });

        const json = await res.json();

        if (!res.ok) {
            setAdminStatus(json.message || "Action failed");
            return;
        }

        setAdminStatus("Success");
        fetchQueue();
    } catch {
        setAdminStatus("Request error");
    }
}

function issueQueue() {
    callAdmin("/api/admin/queue/issue");
}

function nextQueue() {
    callAdmin("/api/admin/queue/next");
}

function prevQueue() {
    callAdmin("/api/admin/queue/prev");
}

/* ===============================
   BIND EVENTS
   =============================== */
btnIssue.addEventListener("click", issueQueue);
btnNext.addEventListener("click", nextQueue);
btnPrev.addEventListener("click", prevQueue);
btnLogout.addEventListener("click", logout);

/* ===============================
   INIT
   =============================== */
fetchQueue();
setInterval(fetchQueue, 2000);
