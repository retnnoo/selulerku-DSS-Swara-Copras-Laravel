document.addEventListener("DOMContentLoaded", function () {
    const overlay = document.getElementById("loadingOverlay");

    // Pastikan overlay disembunyikan saat awal halaman dimuat
    if (overlay) {
        overlay.classList.add("hidden");
        overlay.classList.remove("flex");
    }

    // Tampilkan overlay saat form disubmit
    document.querySelectorAll("form").forEach((form) => {
        form.addEventListener("submit", () => {
            if (overlay) {
                overlay.classList.remove("hidden");
                overlay.classList.add("flex");
            }
        });
    });

    // Flash message (opsional)
    if (window.flashSuccess) {
        Swal.fire({
            icon: "success",
            title: "Berhasil",
            text: window.flashSuccess,
            showConfirmButton: false,
            timer: 2000,
        });
    }

    if (window.flashError) {
        Swal.fire({
            icon: "error",
            title: "Gagal",
            text: window.flashError,
            showConfirmButton: true,
        });
    }
});

// Konfirmasi hapus (juga tampilkan loading kalau lanjut hapus)
function konfirmasiHapus(formId) {
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
            const form = document.getElementById(formId);
            const overlay = document.getElementById("loadingOverlay");

            if (overlay) {
                overlay.classList.remove("hidden");
                overlay.classList.add("flex");
            }

            if (form) form.submit();
        }
    });
}

document.addEventListener("DOMContentLoaded", function () {
    const overlay = document.getElementById("loadingOverlay");

    // Pastikan overlay disembunyikan saat awal halaman
    overlay.classList.add("hidden");
    overlay.classList.remove("flex");

    // Saat form disubmit, tampilkan overlay
    document.querySelectorAll("form").forEach((form) => {
        form.addEventListener("submit", (e) => {
            overlay.classList.remove("hidden");
            overlay.classList.add("flex");
        });
    });
});
