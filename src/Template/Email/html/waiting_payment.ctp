<?php
/**
 * @var \App\Model\Entity\Order $orderEntity
 * @var \App\Model\Entity\Transaction $transactionEntity
 */
?>
<div style="padding: 5px 20px; border-top: 1px solid rgba(0,0,0,0.05);">
    <h3 style="margin-top: 10px;">Hi<?php echo !empty($name) ? ', ' . $name : ''; ?></h3>
    <p>&nbsp;</p>
    <div style="color: #636363; font-size: 14px;">
        <p>Silahkan selesaikan pembayaran anda,</p>
        <p>Nomor Invoice: <?= $orderEntity->invoice; ?></p>
        <p>Total Pembayaran: <?= $this->Number->format($orderEntity->total); ?></p>
        <p>Metode Pembayaran: <?= $transactionEntity->payment_type; ?></p>
        <?php if ($transactionEntity->payment_type == 'bank_transfer') : ?>
        <p>Bank: <?= $transactionEntity->bank; ?> virtual account</p>
        <p>Nomor Virtual account: <?= $transactionEntity->va_number; ?></p>
        <?php endif; ?>
        <p></p>

        <?php if ($orderEntity->order_type == 1) : ?>
            <?php echo $this->element('Email/Partials/product_detail', ['orderEntity' => $orderEntity, 'transactionEntity' => $transactionEntity]); ?>
        <?php elseif ($orderEntity->order_type == 2) : ?>
            <?php echo $this->element('Email/Partials/digital_detail', ['orderEntity' => $orderEntity, 'transactionEntity' => $transactionEntity]); ?>
        <?php endif; ?>
        <p></p>
    </div>
</div>