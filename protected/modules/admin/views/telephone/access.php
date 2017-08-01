<?php
$this->breadcrumbs=array(
	'Структура сайта'=>array('admin'),
	$model->name=>array('view','id'=>$model->id),
	'Доступ',
);

$this->menu=array(	
	array('label'=>'Просмотр','url'=>array('view','id'=>$model->id), 'icon'=>'eye-open'),
	array('label'=>'Управление','url'=>array('admin'), 'icon'=>'user'),
);
?>


<h1>Дополнительные права</h1>
<div class="well" style="background-color: white;">
<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'user-form',
	'enableAjaxValidation'=>false,
    'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
)); ?>

<?php 
    $this->widget('bootstrap.widgets.TbTabs', array(
    'type'=>'tabs',
    'placement'=>'top',
    'tabs'=>array(
        array(
            'label'=>Yii::t('main', 'Группы'), 
            'content'=>$this->renderPartial(
                '../telephone/_accessGroup', 
                array('model'=>$model), true), 
        'active'=>true
        ),
        array(
            'label'=>Yii::t('main', 'Пользователи'),
            'content'=>$this->renderPartial(
                '../telephone/_accessUser', 
                array('model'=>$model), true),
        ))
    ));
?>
    


    <div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'link',
            'url'=>array('admin'),
			'type'=>'primary',
			'label'=>'Назад',
		)); ?>
	</div>
    
<?php $this->endWidget(); ?>
</div>
