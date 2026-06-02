<?php
/** @var mysqli $conn **/
session_start();
include "../../config/koneksi.php";

if ($_SESSION['role'] != 'user') {
    header("Location: ../../login.php");
    exit;
}

// AMBIL SEMUA JENIS IKLAN
$queryJenis = mysqli_query($conn, "SELECT * FROM jenis_iklan ORDER BY nama_jenis ASC");
$jenisIklan = [];
while ($row = mysqli_fetch_assoc($queryJenis)) {
    $jenisIklan[] = $row;
}

// SUBMIT FORM
if (isset($_POST['submit_form'])) { // Diubah agar mendeteksi input hidden khusus

    $user_id           = $_SESSION['id'];
    $judul             = htmlspecialchars($_POST['judul']);
    $deskripsi         = htmlspecialchars($_POST['deskripsi']);
    $jenis_iklan_id    = $_POST['jenis_iklan_id'];
    $durasi            = (int) $_POST['durasi'];
    $tanggal_mulai     = $_POST['tanggal_mulai'];
    $tanggal_selesai   = $_POST['tanggal_selesai'];
    $tipe_media        = $_POST['tipe_media'];

    // VALIDASI TANGGAL
    if ($tanggal_selesai < $tanggal_mulai) {
        echo "<script>alert('Tanggal selesai tidak valid'); window.history.back();</script>";
        exit;
    }

    // HITUNG JUMLAH HARI
    $mulai   = new DateTime($tanggal_mulai);
    $selesai = new DateTime($tanggal_selesai);
    $selisih = $mulai->diff($selesai);
    $jumlah_hari = $selisih->days + 1;

    // AMBIL DATA JENIS IKLAN
    $getJenis = mysqli_query($conn, "SELECT * FROM jenis_iklan WHERE id='$jenis_iklan_id'");
    $jenis = mysqli_fetch_assoc($getJenis);

    if (!$jenis) {
        die("Jenis iklan tidak ditemukan");
    }

    // HARGA MEDIA
    if ($tipe_media == 'foto') {
        $harga_media = (int) $jenis['harga_foto'];
    } else {
        $harga_per5 = (int) $jenis['harga_video_per5detik'];
        $harga_media = ceil($durasi / 5) * $harga_per5;
    }

    // HARGA PER HARI
    $harga_per_hari = (int) $jenis['harga_per_hari'];

    // TOTAL FINAL
    $harga = $harga_media + ($harga_per_hari * $jumlah_hari);

    // VALIDASI SLOT IKLAN
    $cekSlot = mysqli_query($conn, "
        SELECT COUNT(*) as total 
        FROM iklan 
        WHERE jenis_iklan_id='$jenis_iklan_id' 
        AND (tanggal_mulai <= '$tanggal_selesai' AND tanggal_selesai >= '$tanggal_mulai')
    ");
    $slot = mysqli_fetch_assoc($cekSlot);

    if ($slot['total'] >= 10) {
        echo "<script>
                alert('Slot iklan penuh pada tanggal yang dipilih');
                window.history.back();
              </script>";
        exit;
    }

    // VALIDASI FILE
    $media = "";
    if (isset($_FILES['media']) && $_FILES['media']['name'] != '') {
        $namaFile = time() . '_' . $_FILES['media']['name'];
        $tmp = $_FILES['media']['tmp_name'];
        
        // Pastikan folder tujuan ada atau buat otomatis jika belum ada
        if (!is_dir("../../assets/uploads/iklan/")) {
            mkdir("../../assets/uploads/iklan/", 0777, true);
        }

        move_uploaded_file($tmp, "../../assets/uploads/iklan/" . $namaFile);
        $media = $namaFile;
    }

    // GENERATE KODE PEMBAYARAN
    $kode_pembayaran = "INV-" . strtoupper(uniqid());

    // INSERT DATABASE
    $insert = mysqli_query($conn, "
        INSERT INTO iklan (
            user_id, jenis_iklan_id, judul_iklan, deskripsi, media, tipe_media, durasi,
            harga_dasar, harga_per_hari, jumlah_hari, harga, kode_pembayaran, status, tanggal_mulai, tanggal_selesai
        ) VALUES (
            '$user_id', '$jenis_iklan_id', '$judul', '$deskripsi', '$media', '$tipe_media', '$durasi',
            '$harga_media', '$harga_per_hari', '$jumlah_hari', '$harga', '$kode_pembayaran', 'Belum Dibayar', '$tanggal_mulai', '$tanggal_selesai'
        )
    ");

    if($insert) {
        echo "<script>
                alert('Pesanan berhasil dibuat');
                window.location='../kelola_iklan/index.php';
              </script>";
    } else {
        echo "<script>alert('Gagal menyimpan data ke database: " . mysqli_error($conn) . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesan Iklan</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 overflow-x-hidden">

<?php 
// Matikan error jika file include opsional belum dibuat, sesuaikan dengan arsitektur sistem Anda
@include "../layouts/header.php"; 
@include "../layouts/sidebar.php"; 
@include "../layouts/topbar.php"; 
@include "../layouts/toast.php"; 
@include "../layouts/loading.php"; 
@include "../layouts/modal.php"; 
?>

<div id="orderConfirmModal" class="fixed inset-0 z-[999999] hidden">
    <div class="absolute inset-0 bg-black/70 backdrop-blur-sm"></div>
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="bg-white w-full max-w-md rounded-3xl shadow-2xl overflow-hidden">
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 p-6 text-white text-center">
                <div class="w-20 h-20 mx-auto rounded-full bg-white/20 flex items-center justify-center mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold">Konfirmasi Pesanan</h2>
                <p class="text-blue-100 mt-2 text-sm">Pastikan data iklan sudah benar</p>
            </div>
            <div class="p-6">
                <div class="space-y-4 mb-8">
                    <div class="flex justify-between gap-4">
                        <span class="text-gray-500">Judul</span>
                        <span id="confirmJudul" class="font-semibold text-right text-gray-800"></span>
                    </div>
                    <div class="flex justify-between gap-4">
                        <span class="text-gray-500">Jenis</span>
                        <span id="confirmJenis" class="font-semibold text-right text-gray-800"></span>
                    </div>
                    <div class="flex justify-between gap-4">
                        <span class="text-gray-500">Durasi</span>
                        <span id="confirmDurasi" class="font-semibold text-right text-gray-800"></span>
                    </div>
                    <div class="flex justify-between gap-4">
                        <span class="text-gray-500">Total</span>
                        <span id="confirmHarga" class="font-bold text-blue-600 text-lg text-right"></span>
                    </div>
                </div>
                <div class="flex flex-col sm:flex-row gap-3">
                    <button type="button" id="cancelOrderBtn" class="w-full py-3 rounded-2xl border border-gray-300 font-semibold hover:bg-gray-100 transition">Batal</button>
                    <button type="button" id="confirmOrderBtn" class="w-full py-3 rounded-2xl bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold hover:scale-[1.02] transition">Ya, Pesan Sekarang</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="lg:ml-72 mt-24 p-4 sm:p-6">
    <div class="bg-white p-4 sm:p-6 lg:p-8 rounded-2xl shadow-sm">
        <div class="mb-8">
            <h2 class="text-2xl sm:text-3xl font-bold text-gray-800">Pesan Iklan</h2>
            <p class="text-gray-500 mt-2 text-sm sm:text-base">Lengkapi form berikut untuk membuat pesanan iklan baru</p>
        </div>

        <form method="POST" enctype="multipart/form-data" id="formPesanIklan" class="space-y-6">
            <input type="hidden" name="submit_form" value="1">

            <div>
                <label class="block mb-2 font-semibold text-gray-700">Judul Iklan</label>
                <input type="text" name="judul" id="judul" class="w-full border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 p-3 rounded-xl outline-none transition" placeholder="Masukkan judul iklan" required>
            </div>

            <div>
                <label class="block mb-2 font-semibold text-gray-700">Deskripsi</label>
                <textarea name="deskripsi" rows="4" class="w-full border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 p-3 rounded-xl outline-none transition resize-none" placeholder="Masukkan deskripsi iklan" required></textarea>
            </div>

            <div>
                <label class="block mb-2 font-semibold text-gray-700">Upload Media</label>
                <input type="file" name="media" id="media" accept="image/*,video/*" class="w-full border border-gray-300 p-3 rounded-xl file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-blue-600 file:text-white hover:file:bg-blue-700" required>
                <input type="hidden" name="tipe_media" id="tipe_media">
            </div>

            <div id="previewContainer" class="rounded-xl overflow-hidden"></div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div>
                    <label class="block mb-2 font-semibold text-gray-700">Jenis Iklan</label>
                    <select name="jenis_iklan_id" id="jenis_iklan" class="w-full border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 p-3 rounded-xl outline-none transition" required>
                        <option value="">-- Pilih Jenis Iklan --</option>
                        <?php foreach ($jenisIklan as $item): ?>
                            <option value="<?= $item['id']; ?>" 
                                    data-media-type="<?= $item['tipe_media']; ?>"
                                    data-foto="<?= $item['harga_foto']; ?>" 
                                    data-video="<?= $item['harga_video_per5detik']; ?>" 
                                    data-hari="<?= $item['harga_per_hari']; ?>">
                                <?= $item['nama_jenis']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label class="block mb-2 font-semibold text-gray-700">Durasi</label>
                    <input type="text" id="durasiView" class="w-full border border-gray-300 bg-gray-100 p-3 rounded-xl" value="Pilih atau upload media dahulu" readonly>
                    <input type="hidden" name="durasi" id="durasi" value="0">
                </div>

                <div>
                    <label class="block mb-2 font-semibold text-gray-700">Harga</label>
                    <input type="text" id="hargaView" class="w-full border border-gray-300 bg-gray-100 p-3 rounded-xl" readonly>
                    <input type="hidden" name="harga" id="harga">
                </div>

                <div>
    <label class="block mb-2 font-semibold text-gray-700">
        Tanggal Mulai
    </label>

    <input type="date"
           name="tanggal_mulai"
           id="tanggal_mulai"
           class="w-full border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 p-3 rounded-xl outline-none transition"
           required>
</div>

                <div class="lg:col-span-2">
    <label class="block mb-2 font-semibold text-gray-700">
        Tanggal Selesai
    </label>

    <input type="date"
           name="tanggal_selesai"
           id="tanggal_selesai"
           class="w-full border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 p-3 rounded-xl outline-none transition"
           required>
</div>
            </div>

            <div class="bg-gray-50 border border-gray-200 rounded-2xl p-4 sm:p-6">
                <h3 class="text-lg sm:text-xl font-bold mb-4 text-gray-800">Slot Jadwal Iklan</h3>
                <div id="slotContainer" class="space-y-3">
                    <div class="text-gray-500 text-sm sm:text-base">Pilih jenis iklan dan tanggal</div>
                </div>
            </div>

            <div class="bg-blue-50 border border-blue-100 rounded-2xl p-4 sm:p-6">
                <h3 class="text-lg sm:text-xl font-bold mb-5 text-gray-800">Rincian Harga</h3>
                <div class="space-y-4">
                    <div class="flex justify-between items-center gap-4">
                        <span class="text-gray-600 text-sm sm:text-base">Biaya Media</span>
                        <span id="hargaMedia" class="font-semibold text-sm sm:text-base">Rp 0</span>
                    </div>
                    <div class="flex justify-between items-center gap-4">
                        <span class="text-gray-600 text-sm sm:text-base">Biaya Per Hari</span>
                        <span id="hargaPerHari" class="font-semibold text-sm sm:text-base">Rp 0</span>
                    </div>
                    <div class="flex justify-between items-center gap-4">
                        <span class="text-gray-600 text-sm sm:text-base">Jumlah Hari</span>
                        <span id="jumlahHari" class="font-semibold text-sm sm:text-base">0 Hari</span>
                    </div>
                    <hr>
                    <div class="flex justify-between items-center gap-4">
                        <span class="text-xl sm:text-2xl font-bold text-gray-800">Total</span>
                        <span id="totalHarga" class="text-xl sm:text-2xl font-bold text-blue-600">Rp 0</span>
                    </div>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row gap-3 pt-2">
                <button type="button" id="openOrderModal" class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 transition text-white px-8 py-3 rounded-xl font-semibold">Pesan Iklan</button>
                <a href="../dashboard/index.php" class="w-full sm:w-auto text-center bg-gray-200 hover:bg-gray-300 transition text-gray-700 px-8 py-3 rounded-xl font-semibold">Kembali</a>
            </div>
        </form>
    </div>
</div>

<?php @include "../layouts/footer.php"; ?>

<script>
const jenisIklanData = <?= json_encode($jenisIklan); ?>;
const mediaInput = document.getElementById('media');
const previewContainer = document.getElementById('previewContainer');
const jenisIklanSelect = document.getElementById('jenis_iklan');
const tipeMedia = document.getElementById('tipe_media');
const hargaInput = document.getElementById('harga');
const hargaView = document.getElementById('hargaView');
const durasiInput = document.getElementById('durasi');
const durasiView = document.getElementById('durasiView');
const tanggalMulai = document.querySelector('[name=tanggal_mulai]');
const tanggalSelesai = document.querySelector('[name=tanggal_selesai]');
const formPesanIklan = document.getElementById('formPesanIklan');
const openOrderModal = document.getElementById('openOrderModal');
const orderConfirmModal = document.getElementById('orderConfirmModal');
const cancelOrderBtn = document.getElementById('cancelOrderBtn');
const confirmOrderBtn = document.getElementById('confirmOrderBtn');

let currentMediaType = '';
let currentVideoDuration = 0;

function formatRupiah(angka) {
    return 'Rp ' + angka.toLocaleString('id-ID');
}

function hitungTotal() {
    const selected = jenisIklanSelect.options[jenisIklanSelect.selectedIndex];
    if (!selected || !selected.value) return;

    let hargaMedia = 0;
    let hargaPerHari = 0;

    if (currentMediaType === 'foto') {
        hargaMedia = parseInt(selected.getAttribute('data-foto')) || 0;
    } else if (currentMediaType === 'video') {
        let hargaPer5 = parseInt(selected.getAttribute('data-video')) || 0;
        hargaMedia = Math.ceil(currentVideoDuration / 5) * hargaPer5;
    }

    hargaPerHari = parseInt(selected.getAttribute('data-hari')) || 0;
    let totalHari = 0;

    if (tanggalMulai.value && tanggalSelesai.value) {
        const mulai = new Date(tanggalMulai.value);
        const selesai = new Date(tanggalSelesai.value);
        const diff = selesai - mulai;
        totalHari = Math.floor(diff / (1000 * 60 * 60 * 24)) + 1;
        if (totalHari < 1) totalHari = 0;
    }

    let total = hargaMedia + (hargaPerHari * totalHari);

    document.getElementById('hargaMedia').innerText = formatRupiah(hargaMedia);
    document.getElementById('hargaPerHari').innerText = formatRupiah(hargaPerHari);
    document.getElementById('jumlahHari').innerText = totalHari + ' Hari';
    document.getElementById('totalHarga').innerText = formatRupiah(total);

    hargaInput.value = total;
    hargaView.value = formatRupiah(total);
}

// DETEKSI MEDIA
mediaInput.addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (!file) return;

    previewContainer.innerHTML = '';
    const fileType = file.type;

    if (fileType.startsWith('image/')) {
        currentMediaType = 'foto';
        tipeMedia.value = 'foto';

        const img = document.createElement('img');
        img.src = URL.createObjectURL(file);
        img.classList.add('w-full', 'max-w-sm', 'rounded-xl', 'shadow-sm');
        previewContainer.appendChild(img);

        durasiInput.value = 0;
        durasiView.value = 'Foto tidak memiliki durasi';
    } 
    else if (fileType.startsWith('video/')) {
        currentMediaType = 'video';
        tipeMedia.value = 'video';

        const video = document.createElement('video');
        video.src = URL.createObjectURL(file);
        video.controls = true;
        video.classList.add('w-full', 'max-w-sm', 'rounded-xl', 'shadow-sm');
        previewContainer.appendChild(video);

        video.onloadedmetadata = function() {
            let seconds = Math.ceil(video.duration);
            currentVideoDuration = seconds;
            durasiInput.value = seconds;
            durasiView.value = seconds + ' Detik';
            hitungTotal();
        };
    }

    // Filter opsi select yang cocok dengan tipe file
    Array.from(jenisIklanSelect.options).forEach(option => {
        if(option.value === "") return;
        const optMedia = option.getAttribute('data-media-type');
        if(optMedia === 'keduanya' || optMedia === currentMediaType) {
            option.style.display = 'block';
            option.disabled = false;
        } else {
            option.style.display = 'none';
            option.disabled = true;
        }
    });

    // Reset pilihan jika opsi terpilih sebelumnya menjadi tidak valid
    jenisIklanSelect.value = "";
    hitungTotal();
});

jenisIklanSelect.addEventListener('change', hitungTotal);
tanggalMulai.addEventListener('change', hitungTotal);
tanggalSelesai.addEventListener('change', hitungTotal);

async function loadSlotCalendar() {
    const jenis = jenisIklanSelect.value;
    const mulai = tanggalMulai.value;
    const selesai = tanggalSelesai.value;

    if (!jenis || !mulai || !selesai) return;

    try {
        const response = await fetch(`cek_slot.php?jenis_iklan_id=${jenis}&tanggal_mulai=${mulai}&tanggal_selesai=${selesai}`);
        const data = await response.json();
        const container = document.getElementById('slotContainer');
        container.innerHTML = '';

        data.forEach(item => {
            let warna = '';
            if (item.status == 'aman') {
                warna = 'bg-green-100 border-green-300 text-green-700';
            } else if (item.status == 'warning') {
                warna = 'bg-yellow-100 border-yellow-300 text-yellow-700';
            } else {
                warna = 'bg-red-100 border-red-300 text-red-700';
            }

            container.innerHTML += `
            <div class="border rounded-xl p-4 ${warna}">
                <div class="flex justify-between items-center gap-4">
                    <div>
                        <div class="font-bold text-sm sm:text-base">${item.tanggal}</div>
                        <div class="text-sm">Terisi: ${item.terisi}/10</div>
                    </div>
                    <div class="text-right">
                        <div class="font-bold text-lg">${item.sisa}</div>
                        <div class="text-sm">Slot Tersisa</div>
                    </div>
                </div>
            </div>`;
        });
    } catch (e) {
        console.error("Gagal memuat slot calendar", e);
    }
}

jenisIklanSelect.addEventListener('change', loadSlotCalendar);
tanggalMulai.addEventListener('change', loadSlotCalendar);
tanggalSelesai.addEventListener('change', loadSlotCalendar);

// VALIDASI MODAL
openOrderModal.addEventListener('click', function() {
    if (!formPesanIklan.checkValidity()) {
        formPesanIklan.reportValidity();
        return;
    }

    const judul = document.getElementById('judul').value;
    const jenis = jenisIklanSelect.options[jenisIklanSelect.selectedIndex].text;
    const durasi = durasiView.value;
    const harga = document.getElementById('totalHarga').innerText;

    document.getElementById('confirmJudul').innerText = judul;
    document.getElementById('confirmJenis').innerText = jenis;
    document.getElementById('confirmDurasi').innerText = durasi;
    document.getElementById('confirmHarga').innerText = harga;

    orderConfirmModal.classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
});

cancelOrderBtn.addEventListener('click', function() {
    orderConfirmModal.classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
});

confirmOrderBtn.addEventListener('click', function() {
    confirmOrderBtn.innerHTML = `
        <div class="flex items-center justify-center gap-2">
            <div class="w-5 h-5 border-2 border-white border-t-transparent rounded-full animate-spin"></div>
            Memproses...
        </div>`;
    confirmOrderBtn.disabled = true;
    formPesanIklan.submit(); // Memicu submit asli form ke server PHP
});

orderConfirmModal.addEventListener('click', function(e) {
    if (e.target === orderConfirmModal) {
        orderConfirmModal.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }
});


// HARI INI
const today = new Date()
.toISOString()
.split('T')[0];

// SET MIN TANGGAL
document
.getElementById('tanggal_mulai')
.min = today;

document
.getElementById('tanggal_selesai')
.min = today;

// AUTO VALIDASI
document
.getElementById('tanggal_mulai')
.addEventListener('change', function(){

    const mulai = this.value;

    // TANGGAL SELESAI
    // TIDAK BOLEH < TANGGAL MULAI
    document
    .getElementById('tanggal_selesai')
    .min = mulai;

    // RESET JIKA
    // TANGGAL SELESAI LEBIH KECIL
    if(
        document
        .getElementById('tanggal_selesai')
        .value < mulai
    )
    {
        document
        .getElementById('tanggal_selesai')
        .value = '';
    }

});
</script>


</body>
</html>