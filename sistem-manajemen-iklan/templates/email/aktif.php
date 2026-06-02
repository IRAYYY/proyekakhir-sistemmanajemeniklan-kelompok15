<?php

function emailAktif($nama, $invoice) {

return "

<div style='font-family:Poppins,sans-serif'>

    <h2 style='color:green'>
        Iklan Sedang Tayang
    </h2>

    <p>
        Halo <b>$nama</b>,
    </p>

    <p>
        Iklan Anda sekarang sedang aktif.
    </p>

    <h3>
        $invoice
    </h3>

</div>

";

}