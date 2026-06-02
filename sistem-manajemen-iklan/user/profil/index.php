<?php
/** @var mysqli $conn **/
session_start();
include "../../config/koneksi.php";

if ($_SESSION['role'] != 'user') {
    header("Location: ../../login.php");
    exit;
}

$id = $_SESSION['id'];

$query = mysqli_query($conn,
    "SELECT * FROM users WHERE id='$id'"
);

$userData = mysqli_fetch_assoc($query);

// Menangkap submit yang dipicu oleh JavaScript setelah konfirmasi modal
if (isset($_POST['update_profile'])) {

    $nama    = htmlspecialchars($_POST['nama']);
    $no_telp = htmlspecialchars($_POST['no_telp']);
    $alamat  = htmlspecialchars($_POST['alamat']);

    $foto = $userData['foto'];

    // UPLOAD FOTO
    if ($_FILES['foto']['name'] != '') {

        $namaFile = time() . '_' . $_FILES['foto']['name'];
        $tmp = $_FILES['foto']['tmp_name'];

        move_uploaded_file(
            $tmp,
            "../../assets/uploads/profil/" . $namaFile
        );

        $foto = $namaFile;
    }

    // UPDATE DATA
    $update = mysqli_query($conn,
        "UPDATE users SET
            nama='$nama',
            no_telp='$no_telp',
            alamat='$alamat',
            foto='$foto'
        WHERE id='$id'"
    );

    if ($update) {

        $_SESSION['nama'] = $nama;

        echo "<script>
                window.location='index.php';
              </script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil User</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 overflow-x-hidden">

<?php include "../layouts/header.php"; ?>
<?php include "../layouts/sidebar.php"; ?>
<?php include "../layouts/topbar.php"; ?>
<?php include "../layouts/toast.php"; ?>
<?php include "../layouts/loading.php"; ?>
<?php include "../layouts/modal.php"; ?>

<div id="profileModal" class="fixed inset-0 z-[999999] hidden">
    <div class="absolute inset-0 bg-black/70 backdrop-blur-sm"></div>
    
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="bg-white w-full max-w-md rounded-3xl shadow-2xl overflow-hidden">
            <div class="bg-gradient-to-r from-blue-600 to-blue-500 p-6 text-white text-center">
                <div class="w-20 h-20 mx-auto rounded-full bg-white/20 flex items-center justify-center mb-4 overflow-hidden border-2 border-white">
                    <img id="modalPreviewFoto" src="../../assets/uploads/profil/<?= $userData['foto'] ? $userData['foto'] : 'default.png'; ?>" class="w-full h-full object-cover hidden">
                    <div id="modalDefaultIcon" class="hidden">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A9 9 0 1118.364 4.56M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                </div>
                <h2 class="text-2xl font-bold">Yakin Update Profil?</h2>
                <p class="text-blue-100 mt-1 text-sm">Pastikan data yang Anda masukkan sudah benar</p>
            </div>

            <div class="p-6">
                <div class="space-y-4 mb-6">
                    <div class="border-b pb-2">
                        <span class="text-xs text-gray-400 block uppercase tracking-wider">Nama Lengkap</span>
                        <span id="confirmNama" class="font-semibold text-gray-800 text-sm sm:text-base"></span>
                    </div>
                    <div class="border-b pb-2">
                        <span class="text-xs text-gray-400 block uppercase tracking-wider">Nomor Telepon</span>
                        <span id="confirmTelp" class="font-semibold text-gray-800 text-sm sm:text-base"></span>
                    </div>
                    <div class="border-b pb-2">
                        <span class="text-xs text-gray-400 block uppercase tracking-wider">Alamat</span>
                        <p id="confirmAlamat" class="font-semibold text-gray-800 text-sm sm:text-base break-words line-clamp-2"></p>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-3">
                    <button type="button" id="btnCancelProfile" class="w-full py-3 rounded-2xl border border-gray-300 font-semibold hover:bg-gray-100 transition text-sm sm:text-base">
                        Periksa Kembali
                    </button>
                    <button type="button" id="btnConfirmProfile" class="w-full py-3 rounded-2xl bg-gradient-to-r from-blue-600 to-blue-500 text-white font-semibold hover:scale-[1.02] transition text-sm sm:text-base">
                        Ya, Simpan
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="lg:ml-72 mt-24 p-4 sm:p-6">
    <div class="max-w-5xl mx-auto">
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">

            <div class="bg-gradient-to-r from-blue-600 to-blue-500 px-6 sm:px-8 py-8">
                <div class="flex flex-col sm:flex-row sm:items-center gap-6">
                    <div class="flex justify-center sm:justify-start">
                        <?php if ($userData['foto']) : ?>
                            <img src="../../assets/uploads/profil/<?= $userData['foto']; ?>"
                                 class="w-28 h-28 sm:w-32 sm:h-32 rounded-full object-cover border-4 border-white shadow-lg">
                        <?php else : ?>
                            <div class="w-28 h-28 sm:w-32 sm:h-32 rounded-full bg-white/30 border-4 border-white flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A9 9 0 1118.364 4.56M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="text-center sm:text-left text-white">
                        <h1 class="text-2xl sm:text-3xl font-bold"><?= $userData['nama']; ?></h1>
                        <p class="mt-2 text-blue-100 break-all"><?= $userData['email']; ?></p>
                        <div class="mt-4 inline-flex items-center gap-2 bg-white/20 px-4 py-2 rounded-xl text-sm">
                            <span class="w-2 h-2 bg-green-400 rounded-full"></span>
                            Akun Aktif
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-4 sm:p-6 lg:p-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-8">Edit Profil</h2>

                <form method="POST" id="formProfil" enctype="multipart/form-data" class="space-y-6">
                    <input type="hidden" name="update_profile" value="1">

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div class="lg:col-span-2">
                            <label class="block mb-3 font-semibold text-gray-700">Foto Profil</label>
                            <div class="border-2 border-dashed border-gray-300 rounded-2xl p-6 bg-gray-50">
                                <input type="file" name="foto" id="inputFoto" accept="image/*" class="w-full text-sm sm:text-base border border-gray-300 rounded-xl p-3 bg-white">
                                <p class="text-sm text-gray-500 mt-3">Upload foto profil baru (JPG, PNG, JPEG)</p>
                            </div>
                        </div>

                        <div>
                            <label class="block mb-3 font-semibold text-gray-700">Nama Lengkap</label>
                            <input type="text" name="nama" id="inputNama" value="<?= $userData['nama']; ?>" class="w-full border border-gray-300 rounded-xl p-4 focus:outline-none focus:ring-2 focus:ring-blue-500 transition" required>
                        </div>

                        <div>
                            <label class="block mb-3 font-semibold text-gray-700">Email</label>
                            <input type="email" value="<?= $userData['email']; ?>" class="w-full border border-gray-300 rounded-xl p-4 bg-gray-100 text-gray-500 cursor-not-allowed" readonly>
                        </div>

                        <div class="lg:col-span-2">
                            <label class="block mb-3 font-semibold text-gray-700">Nomor Telepon</label>
                            <input type="text" name="no_telp" id="inputTelp" value="<?= $userData['no_telp']; ?>" placeholder="Masukkan nomor telepon" class="w-full border border-gray-300 rounded-xl p-4 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                        </div>

                        <div class="lg:col-span-2">
                            <label class="block mb-3 font-semibold text-gray-700">Alamat</label>
                            <textarea name="alamat" id="inputAlamat" rows="5" placeholder="Masukkan alamat lengkap" class="w-full border border-gray-300 rounded-xl p-4 focus:outline-none focus:ring-2 focus:ring-blue-500 transition resize-none"><?= $userData['alamat']; ?></textarea>
                        </div>
                    </div>

                    <div class="mt-8 flex flex-col sm:flex-row gap-4">
                        <button type="button" id="btnTriggerProfile" class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 transition text-white font-semibold px-8 py-4 rounded-xl shadow-md text-sm sm:text-base">
                            Update Profil
                        </button>
                        <a href="../dashboard/index.php" class="w-full sm:w-auto text-center bg-gray-200 hover:bg-gray-300 transition text-gray-700 font-semibold px-8 py-4 rounded-xl text-sm sm:text-base">
                            Kembali
                        </a>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

<?php include "../layouts/footer.php"; ?>

<script>
const formProfil = document.getElementById('formProfil');
const btnTriggerProfile = document.getElementById('btnTriggerProfile');
const profileModal = document.getElementById('profileModal');
const btnCancelProfile = document.getElementById('btnCancelProfile');
const btnConfirmProfile = document.getElementById('btnConfirmProfile');

const inputFoto = document.getElementById('inputFoto');
const modalPreviewFoto = document.getElementById('modalPreviewFoto');
const modalDefaultIcon = document.getElementById('modalDefaultIcon');

// Menyimpan state path gambar lama bawaan dari PHP ter-enkapsulasi
const fotoLamaExist = "<?= !empty($userData['foto']) ? '../../assets/uploads/profil/'.$userData['foto'] : '' ?>";

// LOGIKA PEMICU MODAL
btnTriggerProfile.addEventListener('click', function() {
    // Jalankan validasi bawaan HTML5 (Required checking)
    if (!formProfil.checkValidity()) {
        formProfil.reportValidity();
        return;
    }

    // Ambil value ter-update dari field input
    const namaValue = document.getElementById('inputNama').value;
    const telpValue = document.getElementById('inputTelp').value || '-';
    const alamatValue = document.getElementById('inputAlamat').value || '-';

    // Tempelkan teks ke komponen modal
    document.getElementById('confirmNama').innerText = namaValue;
    document.getElementById('confirmTelp').innerText = telpValue;
    document.getElementById('confirmAlamat').innerText = alamatValue;

    // LOGIKA PREVIEW FOTO DI DALAM MODAL
    if (inputFoto.files && inputFoto.files[0]) {
        // Jika user memilih file baru, buat temporary URL blob
        const reader = new FileReader();
        reader.onload = function(e) {
            modalPreviewFoto.src = e.target.result;
            modalPreviewFoto.classList.remove('hidden');
            modalDefaultIcon.classList.add('hidden');
        }
        reader.readAsDataURL(inputFoto.files[0]);
    } else if (fotoLamaExist !== '') {
        // Jika tidak upload file baru, tapi punya foto lama
        modalPreviewFoto.src = fotoLamaExist;
        modalPreviewFoto.classList.remove('hidden');
        modalDefaultIcon.classList.add('hidden');
    } else {
        // Jika tidak ada foto sama sekali
        modalPreviewFoto.classList.add('hidden');
        modalDefaultIcon.classList.remove('hidden');
    }

    // Munculkan Modal
    profileModal.classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
});

// TOMBOL BATAL DI MODAL
btnCancelProfile.addEventListener('click', function() {
    profileModal.classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
});

// TOMBOL YA, SIMPAN DI MODAL
btnConfirmProfile.addEventListener('click', function() {
    // Tampilkan efek loading memproses data
    btnConfirmProfile.innerHTML = `
        <div class="flex items-center justify-center gap-2">
            <div class="w-5 h-5 border-2 border-white border-t-transparent rounded-full animate-spin"></div>
            Menyimpan...
        </div>`;
    btnConfirmProfile.disabled = true;
    btnCancelProfile.disabled = true;

    // Kirim data form secara penuh ke server
    formProfil.submit();
});

// KETIKA USER KLIK AREA LUAR BUBBLE MODAL
profileModal.addEventListener('click', function(e) {
    if (e.target === profileModal) {
        profileModal.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }
});
</script>

</body>
</html>