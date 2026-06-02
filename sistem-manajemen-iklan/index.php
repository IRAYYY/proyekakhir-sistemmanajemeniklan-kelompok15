<?php
include "config/koneksi.php";

$query = mysqli_query($conn,
    "SELECT * FROM jenis_iklan
     ORDER BY id DESC"
);
?>

<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>
        AdVision - Digital Advertising Platform
    </title>

    <!-- TAILWIND -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- FONT AWESOME -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"/>

</head>

<body class="bg-gray-50 overflow-x-hidden">

<?php include "components/navbar.php"; ?>

<!-- HERO -->
<section id="home"
         class="relative overflow-hidden pt-40 pb-32">

    <!-- BG -->
    <div class="absolute inset-0 bg-gradient-to-br from-blue-50 via-white to-indigo-100"></div>

    <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-blue-200/30 rounded-full blur-3xl"></div>

    <div class="absolute bottom-0 left-0 w-[400px] h-[400px] bg-indigo-200/30 rounded-full blur-3xl"></div>

    <div class="relative max-w-7xl mx-auto px-6 lg:px-10">

        <div class="grid lg:grid-cols-2 gap-20 items-center">

            <!-- LEFT -->
            <div>

                <div class="inline-flex items-center gap-2 bg-blue-100 text-blue-700 px-5 py-2 rounded-full mb-8">

                    <i class="fa-solid fa-bolt"></i>

                    Platform Advertising Modern

                </div>

                <h1 class="text-5xl lg:text-7xl font-black leading-tight text-gray-900">

                    Kelola Iklan
                    Digital Lebih
                    <span class="text-blue-600">

                        Profesional

                    </span>

                </h1>

                <p class="text-xl text-gray-600 mt-8 leading-relaxed">

                    Platform manajemen iklan modern
                    untuk Instagram, TikTok,
                    dan Website Advertising
                    dengan sistem otomatis profesional.

                </p>

                <div class="flex flex-wrap gap-5 mt-10">

                    <a href="register.php"
                       class="bg-gradient-to-r from-blue-600 to-indigo-600 hover:scale-105 transition text-white px-8 py-5 rounded-2xl font-semibold shadow-2xl">

                        Mulai Pasang Iklan

                    </a>

                    <a href="#layanan"
                       class="bg-white hover:bg-gray-100 transition border border-gray-200 px-8 py-5 rounded-2xl font-semibold">

                        Lihat Layanan

                    </a>

                </div>

                <!-- STATS -->
                <div class="grid grid-cols-3 gap-8 mt-16">

                    <div>

                        <h2 class="text-4xl font-black text-blue-600">

                            100+

                        </h2>

                        <p class="text-gray-600 mt-2">

                            Active Client

                        </p>

                    </div>

                    <div>

                        <h2 class="text-4xl font-black text-indigo-600">

                            250+

                        </h2>

                        <p class="text-gray-600 mt-2">

                            Ads Published

                        </p>

                    </div>

                    <div>

                        <h2 class="text-4xl font-black text-purple-600">

                            99%

                        </h2>

                        <p class="text-gray-600 mt-2">

                            Satisfaction

                        </p>

                    </div>

                </div>

            </div>

            <!-- RIGHT -->
            <div class="relative">

                <div class="bg-white rounded-[40px] shadow-2xl p-8 border border-gray-100">

                    <div class="flex items-center justify-between mb-8">

                        <div>

                            <h3 class="text-2xl font-bold">

                                Dashboard Ads

                            </h3>

                            <p class="text-gray-500">

                                Advertising Management

                            </p>

                        </div>

                        <div class="w-14 h-14 rounded-2xl bg-blue-100 flex items-center justify-center">

                            <i class="fa-solid fa-chart-line text-blue-600 text-2xl"></i>

                        </div>

                    </div>

                    <div class="space-y-5">

                        <?php 
                        $counter = 0;
                        while($data = mysqli_fetch_assoc($query)) : 
                            if ($counter >= 3) break; 
                        ?>
                        <div class="bg-gray-50 border border-gray-100 rounded-2xl p-5 mb-4 flex justify-between items-center shadow-sm">
                            <div>
                                <h4 class="font-bold text-gray-800 text-lg mb-1">
                                    <?= htmlspecialchars($data['nama_jenis']); ?>
                                </h4>
                                <p class="text-sm text-gray-500 flex items-center gap-1.5">
                                    <span class="w-2 h-2 rounded-full bg-green-500 inline-block animate-pulse"></span>
                                    Tersedia Sekarang
                                </p>
                            </div>

                            <span class="bg-green-100 text-green-700 px-4 py-1.5 rounded-xl text-sm font-semibold tracking-wide border border-green-200">
                                Aktif
                            </span>
                        </div>
                        <?php 
                            $counter++;
                        endwhile; 
                        ?>


                        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-3xl p-8 text-white">

                            <p class="text-lg">

                                Total Revenue

                            </p>

                            <h2 class="text-5xl font-black mt-4">

                                Rp <span>120M+</span>

                            </h2>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>

<!-- SERVICES -->
<section id="layanan"
         class="py-28">

    <div class="max-w-7xl mx-auto px-6 lg:px-10">

        <div class="text-center mb-20">

            <h2 class="text-5xl font-black text-gray-900">

                Layanan Iklan

            </h2>

            <p class="text-gray-600 text-xl mt-6">

                Pilih layanan advertising sesuai kebutuhan bisnis Anda

            </p>

        </div>

        <div class="grid lg:grid-cols-3 gap-8">

            <?php while($data = mysqli_fetch_assoc($query)) : ?>

            <div class="bg-white rounded-[32px] p-8 shadow-lg hover:-translate-y-2 hover:shadow-2xl transition duration-300 border border-gray-100">

                <div class="w-20 h-20 rounded-3xl bg-gradient-to-r from-blue-600 to-indigo-600 flex items-center justify-center mb-8">

                    <i class="fa-brands fa-youtube text-white text-3xl"></i>

                </div>

                <h3 class="text-3xl font-bold text-gray-800">

                    <?= $data['nama_jenis']; ?>

                </h3>

                <div class="mt-8 space-y-4">

                    <?php if($data['harga_foto']) : ?>

                    <div class="flex justify-between">

                        <span class="text-gray-600">
                            Harga Foto
                        </span>

                        <span class="font-bold text-blue-600">

                            Rp <?= number_format($data['harga_foto']); ?>

                        </span>

                    </div>

                    <?php endif; ?>

                    <?php if($data['harga_video_per5detik']) : ?>

                    <div class="flex justify-between">

                        <span class="text-gray-600">
                            Video / 5 Detik
                        </span>

                        <span class="font-bold text-indigo-600">

                            Rp <?= number_format($data['harga_video_per5detik']); ?>

                        </span>

                    </div>

                    <?php endif; ?>

                    <div class="flex justify-between">

                        <span class="text-gray-600">
                            Harga / Hari
                        </span>

                        <span class="font-bold text-purple-600">

                            Rp <?= number_format($data['harga_per_hari']); ?>

                        </span>

                    </div>

                </div>

                <a href="register.php"
                   class="mt-10 block text-center bg-gray-900 hover:bg-black transition text-white py-4 rounded-2xl font-semibold">

                    Pesan Sekarang

                </a>

            </div>

            <?php endwhile; ?>

        </div>

    </div>

</section>

<!-- WORKFLOW -->
<section id="workflow"
         class="py-28 bg-gray-900 text-white">

    <div class="max-w-7xl mx-auto px-6 lg:px-10">

        <div class="text-center mb-20">

            <h2 class="text-5xl font-black">

                Cara Kerja Platform

            </h2>

            <p class="text-gray-400 text-xl mt-6">

                Proses mudah dan otomatis

            </p>

        </div>

        <div class="grid lg:grid-cols-5 gap-8">

            <?php
            $steps = [
                ['icon'=>'fa-file-circle-plus','title'=>'Pesan'],
                ['icon'=>'fa-credit-card','title'=>'Bayar'],
                ['icon'=>'fa-check-to-slot','title'=>'Verifikasi'],
                ['icon'=>'fa-rocket','title'=>'Tayang'],
                ['icon'=>'fa-circle-check','title'=>'Selesai'],
            ];

            foreach($steps as $step) :
            ?>

            <div class="text-center">

                <div class="w-24 h-24 rounded-3xl bg-white/10 backdrop-blur-lg flex items-center justify-center mx-auto mb-8">

                    <i class="fa-solid <?= $step['icon']; ?> text-4xl"></i>

                </div>

                <h3 class="text-2xl font-bold">

                    <?= $step['title']; ?>

                </h3>

            </div>

            <?php endforeach; ?>

        </div>

    </div>

</section>

<!-- FAQ -->
<section id="faq"
         class="py-28">

    <div class="max-w-5xl mx-auto px-6">

        <div class="text-center mb-20">

            <h2 class="text-5xl font-black text-gray-900">

                Frequently Asked Questions

            </h2>

        </div>

        <div class="space-y-6">

            <?php
            $faq = [

                [
                    'q'=>'Bagaimana cara memesan iklan?',
                    'a'=>'Daftar akun, login, lalu pilih menu pesan iklan.'
                ],

                [
                    'q'=>'Bagaimana sistem pembayaran?',
                    'a'=>'Pembayaran dilakukan melalui metode pembayaran yang tersedia.'
                ],

                [
                    'q'=>'Kapan iklan mulai tayang?',
                    'a'=>'Iklan tayang otomatis sesuai tanggal mulai setelah diverifikasi admin.'
                ]

            ];

            foreach($faq as $item) :
            ?>

            <div class="bg-white rounded-3xl p-8 shadow-lg border border-gray-100">

                <h3 class="text-2xl font-bold text-gray-800">

                    <?= $item['q']; ?>

                </h3>

                <p class="text-gray-600 mt-4 text-lg leading-relaxed">

                    <?= $item['a']; ?>

                </p>

            </div>

            <?php endforeach; ?>

        </div>

    </div>

</section>

<?php include "components/footer.php"; ?>

</body>
</html>