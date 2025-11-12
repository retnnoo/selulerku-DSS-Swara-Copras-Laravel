// document.addEventListener("DOMContentLoaded", function () {
//     // Flash message
//     if (window.flashSuccess) {
//         Swal.fire({
//             icon: "success",
//             title: "Berhasil",
//             text: window.flashSuccess,
//             showConfirmButton: false,
//             timer: 2000,
//         });
//     }

//     if (window.flashError) {
//         Swal.fire({
//             icon: "error",
//             title: "Gagal",
//             text: window.flashError,
//             showConfirmButton: true,
//         });
//     }
// });

// function konfirmasiHapus(formId) {
//     const overlay = document.getElementById("loadingOverlay");

//     Swal.fire({
//         title: "Yakin ingin menghapus?",
//         text: "Data akan dihapus permanen!",
//         icon: "warning",
//         showCancelButton: true,
//         confirmButtonColor: "#d33",
//         cancelButtonColor: "#3085d6",
//         confirmButtonText: "Ya, hapus!",
//         cancelButtonText: "Batal",
//     }).then((result) => {
//         if (result.isConfirmed) {
//             if (overlay) {
//                 console.log("loading harusnya muncul");
//                 overlay.classList.remove("invisible", "opacity-0");
//                 overlay.classList.add("pointer-events-auto");
//             }
//             const form = document.getElementById(formId);
//             if (form) form.submit();
//         }
//     });
// }

// // function konfirmasiHapus(formId) {
// //     console.log("formId:", formId);

// //     Swal.fire({
// //         title: "Yakin ingin menghapus?",
// //         text: "Data akan dihapus permanen!",
// //         icon: "warning",
// //         showCancelButton: true,
// //         confirmButtonColor: "#d33",
// //         cancelButtonColor: "#3085d6",
// //         confirmButtonText: "Ya, hapus!",
// //         cancelButtonText: "Batal",
// //     }).then((result) => {
// //         console.log("result:", result);
// //         if (result.isConfirmed) {
// //             const form = document.getElementById(formId);
// //             console.log("form:", form);
// //             if (form) form.submit();
// //         }
// //     });
// // }
