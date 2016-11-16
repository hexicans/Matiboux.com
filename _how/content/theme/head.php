<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta name="author" content="<?php echo $_Oli->getOption('owner'); ?>" />
<meta name="description" content="<?php echo $_Oli->getOption('description'); ?>" />
<meta name="keywords" content="<?php echo implode(',', explode(' ', $_Oli->getOption('name'))); ?>,<?php echo implode(',', explode(' ', $_Oli->getOption('description'))); ?>",<?php echo $_Oli->getOption('owner'); ?>" />

<?php $_Oli->loadCdnStyle('css/bootstrap.min.css', true); ?>
<?php $_Oli->loadCdnStyle('css/font-awesome.min.css', true); ?>
<?php $_Oli->loadLocalStyle('css/style.css', true, true); ?>

<?php $_Oli->loadCdnScript('js/jquery-2.1.4.min.js', false); ?>
<?php $_Oli->loadCdnScript('js/bootstrap.min.js', false); ?>