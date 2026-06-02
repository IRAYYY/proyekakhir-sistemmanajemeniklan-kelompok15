<?php if(isset($_SESSION['success'])) : ?>

<div id="toastSuccess"
     class="fixed top-5 right-5 bg-green-500 text-white px-6 py-4 rounded-xl shadow-2xl z-50">

    <?= $_SESSION['success']; ?>

</div>

<?php unset($_SESSION['success']); endif; ?>

<?php if(isset($_SESSION['error'])) : ?>

<div id="toastError"
     class="fixed top-5 right-5 bg-red-500 text-white px-6 py-4 rounded-xl shadow-2xl z-50">

    <?= $_SESSION['error']; ?>

</div>

<?php unset($_SESSION['error']); endif; ?>