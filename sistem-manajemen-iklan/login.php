<?php
session_start();
include "config/koneksi.php";

if (isset($_POST['login'])) {

    $email    = htmlspecialchars($_POST['email']);
    $password = $_POST['password'];

    $query = mysqli_query($conn,
        "SELECT * FROM users WHERE email='$email'"
    );

    if (mysqli_num_rows($query) > 0) {

        $data = mysqli_fetch_assoc($query);

        if (password_verify($password, $data['password'])) {

            $_SESSION['id']    = $data['id'];
            $_SESSION['nama']  = $data['nama'];
            $_SESSION['role']  = $data['role'];
            $_SESSION['foto']  = $data['foto'];

            if ($data['role'] == 'admin') {

                header("Location: admin/dashboard/dashboard.php");
                exit;

            } else {

                header("Location: user/dashboard/index.php");
                exit;
            }

        } else {

            $error = "Password salah!";
        }

    } else {

        $error = "Email tidak ditemukan!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Login - Sistem Manajemen Iklan</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>

</head>

<body class="min-h-screen bg-gradient-to-br from-blue-600 via-blue-500 to-indigo-600 overflow-x-hidden">

<!-- BACKGROUND -->
<div class="absolute inset-0 overflow-hidden">

    <div class="absolute -top-32 -left-32 w-80 h-80 bg-white/10 rounded-full blur-3xl"></div>

    <div class="absolute bottom-0 right-0 w-96 h-96 bg-cyan-300/10 rounded-full blur-3xl"></div>

</div>

<!-- CONTAINER -->
<div class="relative z-10 min-h-screen flex items-center justify-center px-4 py-10">

    <div class="w-full max-w-6xl bg-white rounded-3xl shadow-2xl overflow-hidden">

        <div class="grid grid-cols-1 lg:grid-cols-2">

            <!-- LEFT -->
            <div class="hidden lg:flex flex-col justify-center bg-gradient-to-br from-blue-700 to-indigo-700 p-12 text-white relative overflow-hidden">

                <div class="absolute top-0 right-0 w-72 h-72 bg-white/10 rounded-full blur-3xl"></div>

                <div class="relative z-10">

                    <div class="w-20 h-20 bg-white/20 rounded-2xl flex items-center justify-center mb-8 backdrop-blur-sm">

                        <i class="fa-solid fa-bullhorn text-4xl"></i>

                    </div>

                    <h1 class="text-5xl font-bold leading-tight mb-6">

                        Sistem Manajemen Iklan

                    </h1>

                    <p class="text-lg text-blue-100 leading-relaxed">

                        Kelola iklan dengan mudah, cepat, dan profesional.
                        Pantau pembayaran, status iklan, serta notifikasi
                        secara realtime dalam satu sistem.

                    </p>

                    <div class="mt-10 space-y-5">

                        <div class="flex items-center gap-4">

                            <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">

                                <i class="fa-solid fa-check"></i>

                            </div>

                            <div>

                                <h3 class="font-semibold text-lg">

                                    Dashboard Modern

                                </h3>

                                <p class="text-blue-100 text-sm">

                                    Tampilan responsif dan mudah digunakan

                                </p>

                            </div>

                        </div>

                        <div class="flex items-center gap-4">

                            <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">

                                <i class="fa-solid fa-credit-card"></i>

                            </div>

                            <div>

                                <h3 class="font-semibold text-lg">

                                    Pembayaran Mudah

                                </h3>

                                <p class="text-blue-100 text-sm">

                                    Upload bukti pembayaran dengan cepat

                                </p>

                            </div>

                        </div>

                        <div class="flex items-center gap-4">

                            <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">

                                <i class="fa-solid fa-bell"></i>

                            </div>

                            <div>

                                <h3 class="font-semibold text-lg">

                                    Notifikasi Realtime

                                </h3>

                                <p class="text-blue-100 text-sm">

                                    Pantau status iklan kapan saja

                                </p>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

            <!-- RIGHT -->
            <div class="p-6 sm:p-10 lg:p-14 flex items-center">

                <div class="w-full">

                    <!-- MOBILE LOGO -->
                    <div class="lg:hidden text-center mb-8">

                        <div class="w-20 h-20 bg-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg">

                            <i class="fa-solid fa-bullhorn text-white text-3xl"></i>

                        </div>

                        <h1 class="text-3xl font-bold text-gray-800">

                            Sistem Manajemen Iklan

                        </h1>

                    </div>

                    <!-- TITLE -->
                    <div class="mb-8">

                        <h2 class="text-3xl sm:text-4xl font-bold text-gray-800">

                            Selamat Datang 👋

                        </h2>

                        <p class="text-gray-500 mt-3 text-base sm:text-lg">

                            Silakan login untuk melanjutkan

                        </p>

                    </div>

                    <!-- ALERT -->
                    <?php if(isset($error)) : ?>

                        <div class="mb-6 bg-red-100 border border-red-200 text-red-600 px-5 py-4 rounded-2xl flex items-center gap-3">

                            <i class="fa-solid fa-circle-exclamation"></i>

                            <span>

                                <?= $error; ?>

                            </span>

                        </div>

                    <?php endif; ?>

                    <!-- FORM -->
                    <form method="POST"
                          class="space-y-6">

                        <!-- EMAIL -->
                        <div>

                            <label class="block mb-3 font-semibold text-gray-700">

                                Email

                            </label>

                            <div class="relative">

                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">

                                    <i class="fa-solid fa-envelope"></i>

                                </span>

                                <input type="email"
                                       name="email"
                                       placeholder="Masukkan email"
                                       class="w-full border border-gray-300 rounded-2xl pl-12 pr-4 py-4 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                       required>

                            </div>

                        </div>

                        <!-- PASSWORD -->
                        <div>

                            <label class="block mb-3 font-semibold text-gray-700">

                                Password

                            </label>

                            <div class="relative">

                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">

                                    <i class="fa-solid fa-lock"></i>

                                </span>

                                <input type="password"
                                       name="password"
                                       id="password"
                                       placeholder="Masukkan password"
                                       class="w-full border border-gray-300 rounded-2xl pl-12 pr-14 py-4 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                       required>

                                <button type="button"
                                        id="togglePassword"
                                        class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-blue-600 transition">

                                    <i class="fa-solid fa-eye"></i>

                                </button>

                            </div>

                        </div>

                        <!-- BUTTON -->
                        <button type="submit"
                                name="login"
                                class="w-full bg-blue-600 hover:bg-blue-700 transition-all duration-300 text-white font-semibold py-4 rounded-2xl shadow-lg hover:shadow-blue-300">

                            Login

                        </button>

                    </form>

                    <!-- REGISTER -->
                    <p class="mt-8 text-center text-gray-600">

                        Belum punya akun?

                        <a href="register.php"
                           class="text-blue-600 font-semibold hover:underline">

                            Register Sekarang

                        </a>

                    </p>

                </div>

            </div>

        </div>

    </div>

</div>

<script>

// TOGGLE PASSWORD
const togglePassword =
document.getElementById('togglePassword');

const password =
document.getElementById('password');

togglePassword.addEventListener('click', function() {

    const type =
        password.getAttribute('type') === 'password'
        ? 'text'
        : 'password';

    password.setAttribute('type', type);

    this.innerHTML =
        type === 'password'
        ? '<i class="fa-solid fa-eye"></i>'
        : '<i class="fa-solid fa-eye-slash"></i>';
});

</script>

</body>
</html>