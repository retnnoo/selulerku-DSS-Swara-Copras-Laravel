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
            if (form) form.submit();
        }
    });
}
