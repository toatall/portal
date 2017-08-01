<?php
$this->breadcrumbs=array(
	'ВКС УФНС'=>array('admin','idTree'=>$idTree),
	'Управление',
);

$this->menu=array(	
	array('label'=>'Создать','url'=>array('create','idTree'=>$idTree), 'icon'=>'asterisk'),
);

?>

<h1>ВКС УФНС</h1>

<style type="text/css">
    .delete-row {
        color: #D50000;
        text-decoration: line-through;
    }
</style>


<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'vksUfns-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
    'rowCssClassExpression' => '($data["date_delete"] == "") ? "" : "delete-row"',
	'columns'=>array(
		'id',
		'theme',
		'responsible',
		'members_people',
		'members_organization',
		'date_start',		
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
            'buttons'=>array(
                'view'=>array(
                    'url'=>'Yii::app()->createUrl("admin/vksUfns/view", array("id"=>$data->id,"idTree"=>'.$idTree.'))',                    
                ),
                'update'=>array(
                    'url'=>'Yii::app()->createUrl("admin/vksUfns/update", array("id"=>$data->id,"idTree"=>'.$idTree.'))',
                ),
                'delete'=>array(
                    'url'=>'Yii::app()->createUrl("admin/vksUfns/delete", array("id"=>$data->id,"idTree"=>'.$idTree.'))',
                ),                
            ),
		),
	),
)); ?>
