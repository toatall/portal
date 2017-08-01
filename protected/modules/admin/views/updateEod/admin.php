<?php
$this->breadcrumbs=array(
	'Обновление СЭОД'=>array('admin','idTree'=>$idTree),
	'Управление',
);

$this->menu=array(
	array('label'=>'Создать','url'=>array('create','idTree'=>$idTree), 'icon'=>'asterisk'),
);

?>

<h1>Обновление СЭОД</h1>


<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'update-eod-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'support',
		'name',
		'path',
		'date_update',
		'date_create',
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
            'buttons'=>array(
                'view'=>array(
                    'url'=>'Yii::app()->createUrl("admin/updateEod/view", array("id"=>$data->id,"idTree"=>'.$idTree.'))',                    
                ),
                'update'=>array(
                    'url'=>'Yii::app()->createUrl("admin/updateEod/update", array("id"=>$data->id,"idTree"=>'.$idTree.'))',
                ),
                'delete'=>array(
                    'url'=>'Yii::app()->createUrl("admin/updateEod/delete", array("id"=>$data->id,"idTree"=>'.$idTree.'))',
                ),                
            ),
		),
	),
)); ?>
