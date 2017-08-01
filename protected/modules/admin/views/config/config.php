<?php
$this->breadcrumbs = array(
    'Настройки приложения',
);
?>

<style type="text/css">
    .row, .span9 {
        margin-left: 0;
    }
    .form-actions {
        padding: 20px 20px 0 0;
        margin-bottom: 0;
    }
</style>

<?php if (Yii::app()->user->hasFlash('successesSaveConfig')): ?>
    <div class="well">
        <?php echo Yii::app()->user->getFlash('successesSaveConfig'); ?>
    </div>
<?php endif; ?>

<div class="form well" style="">
    <?php echo $form; ?>    
</div>
