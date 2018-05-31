<?php
$this->breadcrumbs=array(
    'Голосование'=>array('admin'),
	$modelMain->name=>array('adminQuestion','idMain'=>$modelMain->id),
	'Управление',
);

$this->menu=array(
    array('label'=>'Управление','url'=>array('adminQuestion','idMain'=>$modelMain->id), 'icon'=>'list'),
    array('label'=>'Создать','url'=>array('createQuestion','idMain'=>$modelMain->id), 'icon'=>'asterisk'),
);


?>

<h1>Управление вопросами "<?= $modelMain->name ?>"</h1>


<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'vote-question-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'text_question',		
		'date_create',
		'date_edit',
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
		    'buttons'=>array(
		        'view'=>array(		           
		            'url'=>function($data) {
		                return Yii::app()->createUrl('admin/vote/viewQuestion', array('id'=>$data->id));
		            },
		        ),
		        'update'=>array(
		            'url'=>function($data) {
                        return Yii::app()->createUrl('admin/vote/updateQuestion', array('id'=>$data->id));		             
		            }		            
		        ),
		        'delete'=>array(
		            'url'=>function($data) {
		                return Yii::app()->createUrl('admin/vote/deleteQuestion',array('id'=>$data->id));
		            }
		        ),
		    ),
		),
	),
)); ?>
