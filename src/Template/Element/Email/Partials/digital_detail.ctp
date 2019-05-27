<?php
/**
 * @var \App\Model\Entity\Order $orderEntity
 * @var \App\Model\Entity\Transaction $transactionEntity
 */
?>
<p>Detail Pembayaran Pembelian produk digital</p>

<table>
    <tr>
        <td>
            Nomor customer
        </td>
        <td>
            <?= $orderEntity->order_digital->customer_number; ?>
        </td>
    </tr>
    <tr>
        <td>
            Nama produk
        </td>
        <td>
            <?= $orderEntity->order_digital->digital_detail->name; ?>
        </td>
    </tr>
    <tr>
        <td>
            Harga
        </td>
        <td>
            Rp <?= $this->Number->format($orderEntity->order_digital->price); ?>
        </td>
    </tr>
</table>