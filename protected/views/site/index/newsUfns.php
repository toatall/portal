<div class="row">
<?php 
	foreach ($modelUFNS as $data)
	{
		echo $this->renderPartial('application.views.news._rowNewsTabs', ['data'=>$data], true, true);
	}

?>
</div>

<br />
<?php $this->widget('bootstrap.widgets.TbButton', array(
    'type'=>'primary',
    'url'=>Yii::app()->getController()->createUrl('news/index', array('organization'=>'8600')),
    'label'=>'Все новости',
    'htmlOptions'=>array(
        'style'=>'float:right',
    ),
)); ?>



