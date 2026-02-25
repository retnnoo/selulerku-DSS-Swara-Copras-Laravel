document.addEventListener("DOMContentLoaded", function () {
    // Tampilkan alert sukses
    if (window.flashSuccess) {
        Swal.fire({
            icon: "success",
            title: "Berhasil",
            text: window.flashSuccess,
            showConfirmButton: false,
            timer: 2000,
        });
    }

    // Tampilkan alert error
    if (window.flashError) {
        Swal.fire({
            icon: "error",
            title: "Gagal",
            text: window.flashError,
            showConfirmButton: true,
        });
    }
});

function konfirmasiHapus(formId) {
    const overlay = document.getElementById("loadingOverlay");

    Swal.fire({
        title: "Yakin ingin menghapus?",
        text: "Data akan dihapus permanen!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Ya, hapus!",
        cancelButtonText: "Batal",
    }).then((result) => {
        if (result.isConfirmed) {
            if (overlay) {
                overlay.classList.remove("invisible", "opacity-0");
                overlay.classList.add("pointer-events-auto");
            }
            const form = document.getElementById(formId);
            if (form) form.submit();
        }
    });
}

function openModal(id) {
    const modal = document.getElementById(id);
    modal.classList.remove("hidden");
    modal.classList.add("flex");
    document.body.classList.add("overflow-hidden");
}

function closeModal(id) {
    const modal = document.getElementById(id);
    modal.classList.add("hidden");
    modal.classList.remove("flex");
    document.body.classList.remove("overflow-hidden");
}

document.addEventListener("DOMContentLoaded", function () {
    const overlay = document.getElementById("loadingOverlay");

    // Tangkap semua form
    const semuaForm = document.querySelectorAll("form");

    semuaForm.forEach((form) => {
        form.addEventListener("submit", function (e) {
            // Tampilkan overlay
            overlay.classList.remove("invisible", "opacity-0");
            overlay.classList.add("pointer-events-auto");
        });
    });
});

$(document).ready(function () {
    $("#kriteriaTable").DataTable({
        paging: true,
        searching: true,
        ordering: false,
        info: true,
        lengthMenu: [10, 20],
        language: {
            lengthMenu: "Tampilkan _MENU_ entri",
            search: "Cari:",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
            paginate: {
                previous: "«",
                next: "»",
            },
        },
    });
});

// --- Sidebar Toggle ---
const sidebar = document.getElementById("sidebar");
const overlay = document.getElementById("overlay");
const sidebarBtn = document.getElementById("sidebarBtn");

function openSidebar() {
    sidebar.classList.remove("hidden");
    sidebar.classList.add("flex", "flex-col");
    overlay.classList.remove("hidden");
}

function closeSidebar() {
    sidebar.classList.add("hidden");
    sidebar.classList.remove("flex", "flex-col");
    overlay.classList.add("hidden");
}

sidebarBtn?.addEventListener("click", openSidebar);
overlay?.addEventListener("click", closeSidebar);

const links = document.querySelectorAll("nav a");
const currentPath = window.location.pathname;

links.forEach((link) => {
    if (link.getAttribute("href") === currentPath) {
        link.classList.add("bg-blue-100", "text-blue-500");
    }
});
