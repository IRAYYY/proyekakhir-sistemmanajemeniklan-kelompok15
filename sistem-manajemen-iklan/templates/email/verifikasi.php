<?php

function emailVerifikasi($nama, $invoice) {

return "

<div style='font-family:Poppins,sans-serif'>

    <h2 style='color:#2563eb'>
        Pembayaran Diverifikasi
    </h2>

    <p>
        Halo <b>$nama</b>,
    </p>

    <p>
        Pembayaran iklan dengan invoice:
    </p>

    <h3>
        $invoice
    </h3>

    <p>
        berhasil diverifikasi admin.
    </p>

    <p>
        Iklan Anda akan segera diproses.
    </p>

    <br>

    <p>
        Terima kasih.
    </p>

</div>

";

}