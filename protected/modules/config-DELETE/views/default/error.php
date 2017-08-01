<?php
/* @var $this SiteController */
/* @var $error array */

$this->pageTitle=Yii::app()->name . ' - Ошибка';
$this->breadcrumbs=array(
	'Ошибка',
);
?>
<h2>Ошибка #<?php echo $code; ?></h2>

<div class="error">
<?php echo CHtml::encode($message); ?>
</div>

<?php if ($code==404): ?>
<img src="/images/admin/404-error-page-lego.jpg" />
<?php endif; ?>
