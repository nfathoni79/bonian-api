<?php
/**
 * @var \App\Model\Entity\Order $orderEntity
 * @var \App\Model\Entity\Transaction $transactionEntity
 */
?>
<div style="padding: 5px 20px; border-top: 1px solid rgba(0,0,0,0.05);">
    <h3 style="margin-top: 10px;">Hi<?php echo !empty($name) ? ', ' . $name : ''; ?></h3>
    <p>&nbsp;</p>
    <div style="color: #636363; font-size: 14px;"><strong>Silahkan selesaikan pembayaran anda,</strong>
        <p>Checkout berhasil pada tanggal <?= date('d M Y, H:i',strtotime($orderEntity->created)); ?> WIB</p>
        <table  border="0" cellspacing="0" cellpadding="0" style="width:100%;">
            <tr>
                <td><strong>Total pembayaran</strong></td>
                <td><strong>Batas waktu pembayaran</strong></td>
            </tr>
            <tr>
                <td><?= $this->Number->format($orderEntity->total); ?></td>
                <td><?= date('d M Y, H:i',strtotime($orderEntity->created . '+1 day')); ?></td>
            </tr>
            <tr>
                <td><strong>Metode pembayaran</strong></td>
                <td><strong>Referensi pembayaran</strong></td>
            </tr>
            <tr>
                <td>
                    <?php if ($transactionEntity->payment_type == 'bank_transfer') : ?>
                    <img src="<?= Configure::read('mainSite');?>/img/logo/<?= $transactionEntity->bank; ?>.png">
                    <p>Nomor Virtual account: <?= $transactionEntity->va_number; ?></p>
                    <?php elseif($transactionEntity->payment_type == 'gopay') : ?>
                    <img src="<?= Configure::read('mainSite');?>/img/logo/<?= $transactionEntity->bank; ?>.png">
                    <?php endif; ?>
                </td>
                <td><?= $orderEntity->invoice; ?></td>
            </tr>
            <tr>
                <td><strong>Status pembayaran</strong></td>
                <td></td>
            </tr>
            <tr>
                <td>Menunggu Pembayaran</td>
                <td></td>
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