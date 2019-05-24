<div style="padding: 5px 20px; border-top: 1px solid rgba(0,0,0,0.05);">
    <h3 style="margin-top: 10px;">Hi<?php echo !empty($name) ? ', ' . $name : ''; ?></h3>
    <p>&nbsp;</p>
    <div style="color: #636363; font-size: 14px;">
        <p><?php echo $message; ?></p>
        <p>&nbsp;</p>
        <p><?= __d('AdminPanel', 'Terima kasih'); ?>,</p>
    </div>
</div>