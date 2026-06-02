<?php
/** @var mysqli $conn **/
session_start();
include "../../config/koneksi.php";

if ($_SESSION['role'] != 'user') {
    header("Location: ../../login.php");
    exit;
}

$id = $_GET['id'];
$user_id = $_SESSION['id'];

// VALIDASI IKLAN
$query = mysqli_query($conn,
    "SELECT *
     FROM iklan
     WHERE id='$id'
     AND user_id='$user_id'"
);

$data = mysqli_fetch_assoc($query);

// JIKA DATA TIDAK ADA
if (!$data) {
    header("Location: index.php");
    exit;
}

// VALIDASI STATUS
if ($data['status'] != 'Belum Dibayar') {
    echo "<script>
            alert('Iklan tidak dapat diedit');
            window.location='index.php';
          </script>";
    exit;
}

// AMBIL JENIS IKLAN
$queryJenis = mysqli_query($conn,
    "SELECT *
     FROM jenis_iklan
     ORDER BY nama_jenis ASC"
);

$jenisIklan = [];
while($row = mysqli_fetch_assoc($queryJenis)) {
    $jenisIklan[] = $row;
}

// UPDATE DATA
if (isset($_POST['update_form'])) { // Diubah agar menangkap submit via JavaScript

    $judul           = htmlspecialchars($_POST['judul']);
    $deskripsi       = htmlspecialchars($_POST['deskripsi']);
    $jenis_iklan_id  = $_POST['jenis_iklan_id'];
    $durasi          = $_POST['durasi'];
    $tanggal_mulai   = $_POST['tanggal_mulai'];
    $tanggal_selesai = $_POST['tanggal_selesai'];

    $getJenis = mysqli_query($conn,
    "SELECT * FROM jenis_iklan
     WHERE id='$jenis_iklan_id'"
    );

    $jenis = mysqli_fetch_assoc($getJenis);

    $mulai  = new DateTime($tanggal_mulai);
    $selesai = new DateTime($tanggal_selesai);
    $selisih = $mulai->diff($selesai);
    $jumlah_hari = $selisih->days + 1;

    if ($data['tipe_media'] == 'foto') {
        $harga_media = $jenis['harga_foto'];
    } else {
        $harga_media = ceil($durasi / 5) * $jenis['harga_video_per5detik'];
    }

    $harga_per_hari = $jenis['harga_per_hari'];
    $total_harga = $harga_media + ($harga_per_hari * $jumlah_hari);

    // VALIDASI SLOT
    $cekSlot = mysqli_query($conn,
        "SELECT COUNT(*) as total
         FROM iklan
         WHERE jenis_iklan_id='$jenis_iklan_id'
         AND tanggal_mulai='$tanggal_mulai'
         AND id != '$id'"
    );

    $slot = mysqli_fetch_assoc($cekSlot);

    if ($slot['total'] >= 10) {
        echo "<script>
                alert('Slot iklan penuh pada tanggal tersebut');
                window.history.back();
              </script>";
        exit;
    } else {
        mysqli_query($conn,
        "UPDATE iklan SET
                judul_iklan='$judul',
                deskripsi='$deskripsi',
                jenis_iklan_id='$jenis_iklan_id',
                harga_dasar='$harga_media',
                harga_per_hari='$harga_per_hari',
                jumlah_hari='$jumlah_hari',
                harga='$total_harga',
                durasi='$durasi',
                tanggal_mulai='$tanggal_mulai',
                tanggal_selesai='$tanggal_selesai'
            WHERE id='$id'"
        );

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
    <title>Edit Iklan</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 overflow-x-hidden">

<?php include "../layouts/header.php"; ?>
<?php include "../layouts/sidebar.php"; ?>
<?php include "../layouts/topbar.php"; ?>
<?php include "../layouts/toast.php"; ?>
<?php include "../layouts/loading.php"; ?>
<?php include "../layouts/modal.php"; ?>

<div id="updateConfirmModal" class="fixed inset-0 z-[999999] hidden">
    <div class="absolute inset-0 bg-black/70 backdrop-blur-sm"></div>
    
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="bg-white w-full max-w-md rounded-3xl shadow-2xl overflow-hidden">
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 p-6 text-white text-center">
                <div class="w-20 h-20 mx-auto rounded-full bg-white/20 flex items-center justify-center mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold">Pastikan Kembali!</h2>
                <p class="text-blue-100 mt-2 text-sm">Apakah data perubahan iklan sudah benar?</p>
            </div>

            <div class="p-6">
                <div class="space-y-4 mb-8">
                    <div class="flex justify-between gap-4">
                        <span class="text-gray-500">Judul Baru</span>
                        <span id="confirmJudul" class="font-semibold text-right text-gray-800"></span>
                    </div>
                    <div class="flex justify-between gap-4">
                        <span class="text-gray-500">Jenis Iklan</span>
                        <span id="confirmJenis" class="font-semibold text-right text-gray-800"></span>
                    </div>
                    <div class="flex justify-between gap-4">
                        <span class="text-gray-500">Estimasi Biaya</span>
                        <span id="confirmHarga" class="font-bold text-blue-600 text-lg text-right"></span>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-3">
                    <button type="button" id="cancelUpdateBtn" class="w-full py-3 rounded-2xl border border-gray-300 font-semibold hover:bg-gray-100 transition">Batal</button>
                    <button type="button" id="confirmUpdateBtn" class="w-full py-3 rounded-2xl bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold hover:scale-[1.02] transition">Ya, Perbarui</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="lg:ml-72 mt-24 p-4 sm:p-6">
    <div class="bg-white rounded-2xl shadow p-5 sm:p-6 lg:p-8">
        <div class="mb-8">
            <h2 class="text-2xl sm:text-3xl font-bold text-gray-800">Edit Iklan</h2>
            <p class="text-sm text-gray-500 mt-2">Perbarui data iklan Anda sebelum melakukan pembayaran</p>
        </div>

        <form method="POST" id="formEditIklan" class="space-y-6">
            <input type="hidden" name="update_form" value="1">

            <div>
                <label class="block mb-2 font-semibold text-sm sm:text-base">Judul Iklan</label>
                <input type="text" name="judul" id="judul" value="<?= $data['judul_iklan']; ?>" class="w-full border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none p-3 rounded-xl text-sm sm:text-base" required>
            </div>

            <div>
                <label class="block mb-2 font-semibold text-sm sm:text-base">Deskripsi</label>
                <textarea name="deskripsi" id="deskripsi" rows="5" class="w-full border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none p-3 rounded-xl text-sm sm:text-base resize-none" required><?= $data['deskripsi']; ?></textarea>
            </div>

            <div>
                <label class="block mb-3 font-semibold text-sm sm:text-base">Media Iklan</label>
                <?php if($data['tipe_media'] == 'foto') : ?>
                    <img src="../../assets/uploads/iklan/<?= $data['media']; ?>" class="w-full max-w-sm rounded-xl border object-cover">
                <?php else : ?>
                    <video controls class="w-full max-w-md rounded-xl border">
                        <source src="../../assets/uploads/iklan/<?= $data['media']; ?>">
                    </video>
                <?php endif; ?>
                <div class="mt-3 bg-red-50 border border-red-200 text-red-600 text-sm rounded-xl px-4 py-3">
                    Media tidak dapat diubah setelah upload.
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div>
                    <label class="block mb-2 font-semibold text-sm sm:text-base">Jenis Iklan</label>
                    <select name="jenis_iklan_id" id="jenis_iklan" class="w-full border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none p-3 rounded-xl text-sm sm:text-base" required>
                        <option value="">-- Pilih Jenis Iklan --</option>
                        <?php foreach($jenisIklan as $jenis) : ?>
                            <?php
                            $show = false;
                            if ($data['tipe_media'] == 'foto' && ($jenis['tipe_media'] == 'foto' || $jenis['tipe_media'] == 'keduanya')) {
                                $show = true;
                            }
                            if ($data['tipe_media'] == 'video' && ($jenis['tipe_media'] == 'video' || $jenis['tipe_media'] == 'keduanya')) {
                                $show = true;
                            }
                            ?>
                            <?php if($show) : ?>
                                <option value="<?= $jenis['id']; ?>"
                                    data-foto="<?= $jenis['harga_foto']; ?>"
                                    data-video="<?= $jenis['harga_video_per5detik']; ?>"
                                    <?= $data['jenis_iklan_id'] == $jenis['id'] ? 'selected' : ''; ?>>
                                    <?= $jenis['nama_jenis']; ?>
                                </option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label class="block mb-2 font-semibold text-sm sm:text-base">Durasi</label>
                    <input type="text" id="durasiView" class="w-full border border-gray-300 bg-gray-100 p-3 rounded-xl text-sm sm:text-base" value="<?= $data['durasi']; ?> detik" readonly>
                    <input type="hidden" name="durasi" id="durasi" value="<?= $data['durasi']; ?>">
                </div>
            </div>

            <div>
                <label class="block mb-2 font-semibold text-sm sm:text-base">Harga Media</label>
                <input type="text" id="hargaView" class="w-full border border-gray-300 bg-gray-100 p-3 rounded-xl text-sm sm:text-base font-semibold" readonly>
                <input type="hidden" name="harga" id="harga">
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div>
                    <label class="block mb-2 font-semibold text-sm sm:text-base">Tanggal Mulai</label>
                    <input type="date" name="tanggal_mulai" value="<?= $data['tanggal_mulai']; ?>" class="w-full border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none p-3 rounded-xl text-sm sm:text-base" required>
                </div>

                <div>
                    <label class="block mb-2 font-semibold text-sm sm:text-base">Tanggal Selesai</label>
                    <input type="date" name="tanggal_selesai" value="<?= $data['tanggal_selesai']; ?>" class="w-full border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none p-3 rounded-xl text-sm sm:text-base" required>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row gap-3 pt-2">
                <button type="button" id="btnTriggerUpdate" class="bg-blue-600 hover:bg-blue-700 transition text-white px-6 py-3 rounded-xl font-semibold text-sm sm:text-base">
                    Update Iklan
                </button>
                <a href="index.php" class="bg-gray-200 hover:bg-gray-300 transition text-gray-700 px-6 py-3 rounded-xl font-semibold text-center text-sm sm:text-base">
                    Kembali
                </a>
            </div>
        </form>
    </div>
</div>

<?php include "../layouts/footer.php"; ?>

<script>
const jenisSelect = document.getElementById('jenis_iklan');
const hargaInput = document.getElementById('harga');
const hargaView = document.getElementById('hargaView');
const durasi = parseInt(document.getElementById('durasi').value);
const tipeMedia = "<?= $data['tipe_media']; ?>";

const formEditIklan = document.getElementById('formEditIklan');
const btnTriggerUpdate = document.getElementById('btnTriggerUpdate');
const updateConfirmModal = document.getElementById('updateConfirmModal');
const cancelUpdateBtn = document.getElementById('cancelUpdateBtn');
const confirmUpdateBtn = document.getElementById('confirmUpdateBtn');

// FORMAT RUPIAH
function formatRupiah(angka) {
    return 'Rp ' + angka.toLocaleString('id-ID');
}

// HITUNG HARGA
function updateHarga() {
    const selected = jenisSelect.options[jenisSelect.selectedIndex];
    if (!selected.value) return;

    let harga = 0;

    if (tipeMedia == 'foto') {
        harga = parseInt(selected.getAttribute('data-foto')) || 0;
    } else {
        let hargaPer5 = parseInt(selected.getAttribute('data-video')) || 0;
        harga = Math.ceil(durasi / 5) * hargaPer5;
    }

    hargaInput.value = harga;
    hargaView.value = formatRupiah(harga);
}

// AUTO LOAD
updateHarga();
jenisSelect.addEventListener('change', updateHarga);

// MANAJEMEN MODAL PASTIKAN KEMBALI

btnTriggerUpdate.addEventListener('click', function() {
    // Validasi HTML5 bawaan (cek field kosong)
    if (!formEditIklan.checkValidity()) {
        formEditIklan.reportValidity();
        return;
    }

    // Ambil value ter-update untuk dimasukkan ke modal konfirmasi
    const judul = document.getElementById('judul').value;
    const jenisText = jenisSelect.options[jenisSelect.selectedIndex].text;
    const hargaText = hargaView.value;

    document.getElementById('confirmJudul').innerText = judul;
    document.getElementById('confirmJenis').innerText = jenisText;
    document.getElementById('confirmHarga').innerText = hargaText;

    // Tampilkan Modal
    updateConfirmModal.classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
});

// BATAL UPDATE
cancelUpdateBtn.addEventListener('click', function() {
    updateConfirmModal.classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
});

// CONFIRM YA, UPDATE SEKARANG
confirmUpdateBtn.addEventListener('click', function() {
    confirmUpdateBtn.innerHTML = `
        <div class="flex items-center justify-center gap-2">
            <div class="w-5 h-5 border-2 border-white border-t-transparent rounded-full animate-spin"></div>
            Memproses...
        </div>`;
    confirmUpdateBtn.disabled = true;
    
    // Submit Form
    formEditIklan.submit();
});

// CLOSE MODAL KLIK DILUAR BUBBLE
updateConfirmModal.addEventListener('click', function(e) {
    if (e.target === updateConfirmModal) {
        updateConfirmModal.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }
});
</script>
</body>
</html>