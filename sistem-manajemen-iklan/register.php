<?php
session_start();
include "config/koneksi.php";

if (isset($_POST['register'])) {

    $nama     = htmlspecialchars($_POST['nama']);
    $email    = htmlspecialchars($_POST['email']);
    $password = $_POST['password'];

    // VALIDASI PASSWORD
    if (strlen($password) < 6) {

        $error = "Password minimal 6 karakter!";

    } else {

        // VALIDASI EMAIL
        $cek = mysqli_query($conn,
            "SELECT * FROM users WHERE email='$email'"
        );

        if (mysqli_num_rows($cek) > 0) {

            $error = "Email sudah digunakan!";

        } else {

            // HASH PASSWORD
            $hashPassword =
                password_hash($password, PASSWORD_DEFAULT);

            // INSERT USER
            $query = mysqli_query($conn,
                "INSERT INTO users
                (
                    nama,
                    email,
                    password,
                    role
                )

                VALUES
                (
                    '$nama',
                    '$email',
                    '$hashPassword',
                    'user'
                )"
            );

            if ($query) {

                echo "<script>
                        alert('Register berhasil');
                        window.location='login.php';
                      </script>";

                exit;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Register - Sistem Manajemen Iklan</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>

</head>

<body class="min-h-screen bg-gradient-to-br from-blue-600 via-indigo-600 to-cyan-500 overflow-x-hidden">

<!-- BACKGROUND -->
<div class="absolute inset-0 overflow-hidden">

    <div class="absolute -top-40 -left-40 w-96 h-96 bg-white/10 rounded-full blur-3xl"></div>

    <div class="absolute bottom-0 right-0 w-[500px] h-[500px] bg-cyan-300/10 rounded-full blur-3xl"></div>

</div>

<!-- CONTAINER -->
<div class="relative z-10 min-h-screen flex items-center justify-center px-4 py-10">

    <div class="w-full max-w-6xl bg-white rounded-3xl shadow-2xl overflow-hidden">

        <div class="grid grid-cols-1 lg:grid-cols-2">

            <!-- LEFT -->
            <div class="hidden lg:flex flex-col justify-center bg-gradient-to-br from-blue-700 to-indigo-700 p-14 text-white relative overflow-hidden">

                <!-- DECORATION -->
                <div class="absolute top-0 right-0 w-72 h-72 bg-white/10 rounded-full blur-3xl"></div>

                <div class="relative z-10">

                    <!-- ICON -->
                    <div class="w-20 h-20 bg-white/20 rounded-2xl flex items-center justify-center mb-8 backdrop-blur-sm">

                        <i class="fa-solid fa-user-plus text-4xl"></i>

                    </div>

                    <!-- TITLE -->
                    <h1 class="text-5xl font-bold leading-tight mb-6">

                        Buat Akun Baru

                    </h1>

                    <p class="text-lg text-blue-100 leading-relaxed">

                        Daftarkan akun Anda untuk mulai mengelola
                        iklan, pembayaran, dan notifikasi dengan
                        sistem yang modern dan responsif.

                    </p>

                    <!-- FEATURES -->
                    <div class="mt-10 space-y-5">

                        <div class="flex items-center gap-4">

                            <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">

                                <i class="fa-solid fa-shield-halved"></i>

                            </div>

                            <div>

                                <h3 class="font-semibold text-lg">

                                    Keamanan Terjamin

                                </h3>

                                <p class="text-blue-100 text-sm">

                                    Password terenkripsi dengan aman

                                </p>

                            </div>

                        </div>

                        <div class="flex items-center gap-4">

                            <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">

                                <i class="fa-solid fa-mobile-screen"></i>

                            </div>

                            <div>

                                <h3 class="font-semibold text-lg">

                                    Responsive Design

                                </h3>

                                <p class="text-blue-100 text-sm">

                                    Nyaman diakses di semua perangkat

                                </p>

                            </div>

                        </div>

                        <div class="flex items-center gap-4">

                            <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">

                                <i class="fa-solid fa-chart-line"></i>

                            </div>

                            <div>

                                <h3 class="font-semibold text-lg">

                                    Dashboard Modern

                                </h3>

                                <p class="text-blue-100 text-sm">

                                    Pantau seluruh iklan secara realtime

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

                            <i class="fa-solid fa-user-plus text-white text-3xl"></i>

                        </div>

                        <h1 class="text-3xl font-bold text-gray-800">

                            Register Account

                        </h1>

                    </div>

                    <!-- TITLE -->
                    <div class="mb-8">

                        <h2 class="text-3xl sm:text-4xl font-bold text-gray-800">

                            Buat Akun 👋

                        </h2>

                        <p class="text-gray-500 mt-3 text-base sm:text-lg">

                            Isi data di bawah untuk membuat akun baru

                        </p>

                    </div>

                    <!-- ERROR -->
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
                          class="space-y-6"
                          id="registerForm">

                        <!-- NAMA -->
                        <div>

                            <label class="block mb-3 font-semibold text-gray-700">

                                Nama Lengkap

                            </label>

                            <div class="relative">

                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">

                                    <i class="fa-solid fa-user"></i>

                                </span>

                                <input type="text"
                                       name="nama"
                                       placeholder="Masukkan nama lengkap"
                                       class="w-full border border-gray-300 rounded-2xl pl-12 pr-4 py-4 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                       required>

                            </div>

                        </div>

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
                                       minlength="6"
                                       placeholder="Masukkan password"
                                       class="w-full border border-gray-300 rounded-2xl pl-12 pr-14 py-4 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                       required>

                                <button type="button"
                                        id="togglePassword"
                                        class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-blue-600 transition">

                                    <i class="fa-solid fa-eye"></i>

                                </button>

                            </div>

                            <p class="text-sm text-gray-500 mt-2">

                                Password minimal 6 karakter

                            </p>

                        </div>

                        <!-- BUTTON -->
                        <button type="submit"
                                name="register"
                                class="w-full bg-blue-600 hover:bg-blue-700 transition-all duration-300 text-white font-semibold py-4 rounded-2xl shadow-lg hover:shadow-blue-300">

                            Register Sekarang

                        </button>

                    </form>

                    <!-- LOGIN -->
                    <p class="mt-8 text-center text-gray-600">

                        Sudah punya akun?

                        <a href="login.php"
                           class="text-blue-600 font-semibold hover:underline">

                            Login Sekarang

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

// VALIDASI PASSWORD
const form =
document.getElementById('registerForm');

form.addEventListener('submit', function(e) {

    if(password.value.length < 6)
    {
        e.preventDefault();

        alert('Password minimal 6 karakter');

        password.focus();
    }

});

</script>

</body>
</html>