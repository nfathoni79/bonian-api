<!DOCTYPE html>
<html>
<head>
    <title><?= $this->fetch('title') ?></title>
</head>
<body style="background-color: #efefef; padding: 20px; font-family: font-size: 14px; line-height: 1.43; font-family: &quot;Helvetica Neue&quot;, &quot;Segoe UI&quot;, Helvetica, Arial, sans-serif;">
<div style="max-width: 600px; margin: 0px auto; background-color: #fff; box-shadow: 0px 20px 50px rgba(0,0,0,0.05);">
    <?= $this->Element('Email/header'); ?>
    <?= $this->fetch('content') ?>
    <?= $this->Element('Email/footer'); ?>
</div>
</body>
</html>

