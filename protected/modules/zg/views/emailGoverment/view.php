<?php
/* @var $this EmailGovermentController */
/* @var $model EmailGoverment */
?>

<?php $this->widget('zii.widgets.CDetailView',array(
	'htmlOptions' => array(
		'class' => 'table table-striped table-condensed table-hover',
	),
	'data'=>$model,
	'attributes'=>array(
		'id',
		'org_name',
		'ruk_name',
		'telephone',
		'email',
		'post_address',
		'date_create',
		'date_update',
		'author',
	),
)); ?>