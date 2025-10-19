document.addEventListener("DOMContentLoaded", function () {
    // Flash message
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

function konfirmasiHapus(formId) {
    const overlay = document.getElementById("loadingOverlay");
    const form = document.getElementById(formId);
    if (!form) return;

    Swal.fire({
        title: "Yakin ingin menghapus?",
        text: "Data akan dihapus permanen!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Ya, hapus!",
        cancelButtonText: "Batal",
        allowOutsideClick: false,
    }).then((result) => {
        if (result.isConfirmed) {
            // Tampilkan overlay
            if (overlay) {
                overlay.classList.remove("invisible", "opacity-0");
                overlay.style.pointerEvents = "none"; // biar form.submit tetap jalan
            }
            form.submit();
        }
    });
}
