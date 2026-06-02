<!-- sidebar user -->
<div class="w-64 bg-white shadow-lg min-h-screen fixed">

    <div class="p-6 border-b">
        <h1 class="text-2xl font-bold text-blue-600">
            USER PANEL
        </h1>
    </div>

    <ul class="mt-6">

        <li class="mb-2">
            <a href="../dashboard.php"
               class="block px-6 py-3 hover:bg-blue-100">
                Dashboard
            </a>
        </li>

        <li class="mb-2">
            <a href="../pesan_iklan/index.php"
               class="block px-6 py-3 hover:bg-blue-100">
                Pesan Iklan
            </a>
        </li>

        <li class="mb-2">
            <a href="../kelola_iklan/index.php"
               class="block px-6 py-3 hover:bg-blue-100">
                Kelola Iklan
            </a>
        </li>

        <li class="mb-2">
            <a href="../riwayat_iklan/index.php"
               class="block px-6 py-3 hover:bg-blue-100">
                Riwayat Iklan
            </a>
        </li>

        <li class="mb-2">
            <a href="../pembayaran/index.php"
               class="block px-6 py-3 hover:bg-blue-100">
                Pembayaran
            </a>
        </li>

        <li class="mb-2">
            <a href="../profil/index.php"
               class="block px-6 py-3 hover:bg-blue-100">
                Profil
            </a>
        </li>

        <li class="mb-2">
            <a href="../../logout.php"
               class="block px-6 py-3 text-red-500 hover:bg-red-100">
                Logout
            </a>
        </li>

    </ul>

</div>




<!-- dashboard aawal admin -->
<?php
session_start();

if ($_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
}
?>

<!DOCTYPE html>
<html>
<head>

    <title>Dashboard Admin</title>

    <script src="https://cdn.tailwindcss.com"></script>

</head>

<body class="bg-gray-100">

<!-- SIDEBAR -->
<?php include "layouts/sidebar.php"; ?>

<!-- HEADER -->
<?php include "layouts/header.php"; ?>

<!-- CONTENT -->
<div class="ml-64 p-6">

    <div class="bg-white p-8 rounded-xl shadow">

        <h2 class="text-3xl font-bold mb-4">

            Dashboard Admin

        </h2>

        <p class="text-gray-600">

            Selamat datang di sistem manajemen iklan.

        </p>

    </div>

</div>

<!-- FOOTER -->
<?php include "layouts/footer.php"; ?>

</body>
</html>





// // DATA JENIS IKLAN DARI DATABASE
// const jenisIklanData =
// <?= json_encode($jenisIklan); ?>;

// const mediaInput =
// document.getElementById('media');

// const previewContainer =
// document.getElementById('previewContainer');

// const jenisIklanSelect =
// document.getElementById('jenis_iklan');

// const tipeMedia =
// document.getElementById('tipe_media');

// const hargaInput =
// document.getElementById('harga');

// const hargaView =
// document.getElementById('hargaView');

// const durasiInput =
// document.getElementById('durasi');

// const durasiView =
// document.getElementById('durasiView');

// let currentMediaType = '';
// let currentVideoDuration = 0;

// // DETEKSI FILE
// mediaInput.addEventListener('change', function(e) {

//     const file = e.target.files[0];

//     if (!file) return;

//     previewContainer.innerHTML = '';

//     jenisIklanSelect.innerHTML =
//         '<option value="">-- Pilih Jenis Iklan --</option>';

//     const fileType = file.type;

//     // FOTO
//     if (fileType.startsWith('image/')) {

//         currentMediaType = 'foto';

//         tipeMedia.value = 'foto';

//         // PREVIEW
//         const img =
//             document.createElement('img');

//         img.src =
//             URL.createObjectURL(file);

//         img.classList.add(
//             'w-64',
//             'rounded-lg',
//             'mb-4'
//         );

//         previewContainer.appendChild(img);

//         // FILTER JENIS IKLAN
//         jenisIklanData.forEach(item => {

//             if (
//                 item.tipe_media == 'foto' ||
//                 item.tipe_media == 'keduanya'
//             ) {

//                 jenisIklanSelect.innerHTML += `
//                     <option
//                         value="${item.id}"
//                         data-harga="${item.harga_foto}">
//                         ${item.nama_jenis}
//                     </option>
//                 `;
//             }
//         });

//         durasiInput.value = 0;

//         durasiView.value =
//             'Foto tidak memiliki durasi';

//         hargaView.value =
//             'Pilih jenis iklan';
//     }

//     // VIDEO
//     else if (fileType.startsWith('video/')) {

//         currentMediaType = 'video';

//         tipeMedia.value = 'video';

//         // PREVIEW VIDEO
//         const video =
//             document.createElement('video');

//         video.src =
//             URL.createObjectURL(file);

//         video.controls = true;

//         video.classList.add(
//             'w-64',
//             'rounded-lg',
//             'mb-4'
//         );

//         previewContainer.appendChild(video);

//         // FILTER JENIS IKLAN
//         jenisIklanData.forEach(item => {

//             if (
//                 item.tipe_media == 'video' ||
//                 item.tipe_media == 'keduanya'
//             ) {

//                 jenisIklanSelect.innerHTML += `
//                     <option
//                         value="${item.id}"
//                         data-harga="${item.harga_video_per5detik}">
//                         ${item.nama_jenis}
//                     </option>
//                 `;
//             }
//         });

//         // DETEKSI DURASI VIDEO
//         video.onloadedmetadata = function() {

//             let seconds =
//                 Math.ceil(video.duration);

//             currentVideoDuration =
//                 seconds;

//             durasiInput.value =
//                 seconds;

//             durasiView.value =
//                 seconds + ' detik';
//         };
//     }
// });

// // HITUNG HARGA
// jenisIklanSelect.addEventListener('change', function() {

//     const selected =
//         this.options[this.selectedIndex];

//     let baseHarga =
//         parseInt(
//             selected.getAttribute('data-harga')
//         );

//     if (!baseHarga) return;

//     let total = 0;

//     // FOTO
//     if (currentMediaType == 'foto') {

//         total = baseHarga;
//     }

//     // VIDEO
//     else {

//         total =
//             Math.ceil(currentVideoDuration / 5)
//             * baseHarga;
//     }

//     hargaInput.value = total;

//     hargaView.value =
//         'Rp ' +
//         total.toLocaleString('id-ID');
// });
