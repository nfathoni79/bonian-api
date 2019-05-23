<div style="padding: 5px 20px; border-top: 1px solid rgba(0,0,0,0.05);">
    <h3 style="margin-top: 10px;">Hi<?php echo !empty($name) ? ', ' . $name : ''; ?></h3>
    <p>&nbsp;</p>
    <div style="color: #636363; font-size: 14px;">
        <p>Perangkat baru telah masuk ke akun zolaku dengan email <?php echo $email;?>. </p>
        <p>Anda mendapatkan email ini sebagai notifikasi untuk memastikan ini memang anda.</p>
        <p>Berikut informasi mengenai perangkat: </p>
        <p>Device: <?= $device; ?></p>
        <p>Tanggal: <?= $date; ?></p>
        <p>Alamat IP: <?= $ip; ?></p>
        <p></p>
    </div>
</div>