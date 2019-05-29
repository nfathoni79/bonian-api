<?php
/**
 * @var \App\Model\Entity\Order $orderEntity
 * @var \App\Model\Entity\Transaction $transactionEntity
 */
?>
<div style="padding: 5px 20px; border-top: 1px solid rgba(0,0,0,0.05);"> 
    <div style="color: #636363; font-size: 12px;">  
        <p><strong>Pesanan Anda telah kami batalkan karena kami belum menerima pembayaran hingga tanggal yang telah ditentukan.</strong> </p>
        <table  border="0" cellspacing="0" cellpadding="0" style="width:100%;">
            <tr style="margin-top:15px;">
                <td><strong>Total pembayaran</strong></td>
                <td><strong>Batas waktu pembayaran</strong></td>
            </tr>
            <tr>
                <td style="vertical-align:top;">Rp. <?= $this->Number->format($orderEntity->total); ?><br><br></td>
                <td style="vertical-align:top;"><?= date('d M Y, H:i',strtotime($orderEntity->created . ' +1 day')); ?><br><br></td>
            </tr>
            <tr style="margin-top:15px;">
                <td style="vertical-align:top;"><strong>Metode pembayaran</strong></td>
                <td style="vertical-align:top;"><strong>Referensi pembayaran</strong></td>
            </tr>
            <tr>
                <td style="vertical-align:top;">
                    <?php if ($transactionEntity->payment_type == 'bank_transfer') : ?>
                    <img src="<?= Cake\Core\Configure::read('mainSite');?>/img/logo/<?= $transactionEntity->bank; ?>.png"><br>
                    Virtual Account: <?= $transactionEntity->va_number; ?><br><br>
                    <?php elseif($transactionEntity->payment_type == 'gopay') : ?>
                    <img src="<?= Cake\Core\Configure::read('mainSite');?>/img/logo/<?= $transactionEntity->bank; ?>.png"> 
					<br><br>
                    <?php endif; ?>
                </td>
                <td style="vertical-align:top;"><?= $orderEntity->invoice; ?><br><br></td> 
            </tr>
            <tr style="margin-top:15px;">
                <td style="vertical-align:top;"><strong>Status pembayaran</strong></td>
                <td style="vertical-align:top;"></td>
            </tr>
            <tr>
                <td style="vertical-align:top;">Di Batalkan - Kadaluarsa<br><br></td>
                <td style="vertical-align:top;"><br><br></td>
            </tr>
        </table> 

        <?php if ($orderEntity->order_type == 1) : ?>
            <?php echo $this->element('Email/Partials/product_detail', ['orderEntity' => $orderEntity, 'transactionEntity' => $transactionEntity]); ?>
        <?php elseif ($orderEntity->order_type == 2) : ?>
            <?php echo $this->element('Email/Partials/digital_detail', ['orderEntity' => $orderEntity, 'transactionEntity' => $transactionEntity]); ?>
        <?php endif; ?>
        <p></p>
    </div>
</div>