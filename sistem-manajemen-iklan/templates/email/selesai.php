<?php

function emailSelesai($nama, $invoice) {

return "

<div style='font-family:Poppins,sans-serif'>

    <h2 style='color:#2563eb'>
        Iklan Selesai
    </h2>

    <p>
        Halo <b>$nama</b>,
    </p>

    <p>
        Masa tayang iklan:
    </p>

    <h3>
        $invoice
    </h3>

    <p>
        telah selesai.
    </p>

</div>

";

}