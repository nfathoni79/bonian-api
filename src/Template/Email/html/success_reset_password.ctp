<div style="padding: 5px 20px; border-top: 1px solid rgba(0,0,0,0.05);">
    <h3 style="margin-top: 10px;">Hi<?php echo !empty($name) ? ', ' . $name : ''; ?></h3>
    <p>&nbsp;</p>
    <div style="color: #636363; font-size: 14px;">
        <p>Password akun untuk <?= $email; ?> telah berhasil dirubah pada tanggal <strong><?= $date; ?></strong>.</p>
        <p></p>
        <p>Silakan cek kembali akun kamu. Selalu jaga keamanan dan kerahasiaan akun kamu.</p>
        <p></p>
    </div>
</div>