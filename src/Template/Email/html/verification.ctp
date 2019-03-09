<div style="padding: 5px 20px; border-top: 1px solid rgba(0,0,0,0.05);">
    <h3 style="margin-top: 10px;">Hi<?php echo !empty($name) ? ', ' . $name : ''; ?></h3>
    <p>&nbsp;</p>
    <div style="color: #636363; font-size: 14px;">
        <p>Kamu telah mendaftarkan email <?php echo $email;?> sebagai alamat email kamu di zolaku. </p>
        <p>Ayo verifikasi email kamu di zolaku, dan dapatkan berbagai penawaran ekslusif dan tips-tips menarik dari zolaku.</p>
        <p><a href="/verification/<?php echo $code;?>" style="padding:8px 20px;background-color:#DF0101;color:#fff;font-weight:bolder;font-size:16px;display:inline-block;margin:20px 0px;text-decoration:none"><?= 'Verifikasi Email'; ?></a> </p>
        <p></p>
    </div>
</div>