<?php

function emailDitolak($nama, $invoice) {

return "

<div style='font-family:Poppins,sans-serif'>

    <h2 style='color:red'>
        Pembayaran Ditolak
    </h2>

    <p>
        Halo <b>$nama</b>,
    </p>

    <p>
        Pembayaran dengan invoice:
    </p>

    <h3>
        $invoice
    </h3>

    <p>
        ditolak oleh admin.
    </p>

    <p>
        Silakan upload ulang bukti pembayaran.
    </p>

</div>

";

}