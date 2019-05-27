<?php
/**
 * @var \App\Model\Entity\Order $orderEntity
 * @var \App\Model\Entity\Transaction $transactionEntity
 */
?>
<p>Detail pembayaran</p>
<table cellpadding="0" cellspacing="0">
    <tr style="background: #efefef;">
        <td style="padding: 5px; width: 70%;">
            Nama Product
        </td>
        <td style="padding: 5px; width: 30%;">Harga</td>
    </tr>
    <?php foreach($orderEntity->order_details as $detail) : ?>
        <?php foreach($detail->order_detail_products as $product) : ?>
            <tr>
                <td>
                    <?= $product['product']['name']; ?>
                    <br/><?= $product['qty']; ?> x Rp <?= $this->Number->format($product['price']); ?>
                </td>
                <td style="text-align:right">Rp <?= $this->Number->format($product['total']); ?></td>
            </tr>
        <?php endforeach; ?>

        <tr>
            <td>
                <?= $detail['shipping_code']; ?> <?= $detail['shipping_service']; ?>
            </td>
            <td style="text-align:right">Rp <?= $this->Number->format($detail['shipping_cost']); ?></td>
        </tr>

    <?php endforeach; ?>
    <?php if ($orderEntity->discount_voucher) : ?>
        <tr>
            <td>
                Penggunaan voucher
            </td>
            <td style="text-align:right">Rp -<?= $this->Number->format($orderEntity->discount_voucher); ?></td>
        </tr>
    <?php endif; ?>
    <tr>
        <td>
            Total Pembayaran
        </td>
        <td style="text-align:right"><?= $this->Number->format($orderEntity->total); ?></td>
    </tr>
</table>