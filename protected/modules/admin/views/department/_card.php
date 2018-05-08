<?= CHtml::link('Добавить сотрудника', ['/admin/departmentCard/create', 'idDepartment'=>$model->id], ['class'=>'btn btn-success']); ?>

<script type="text/javascript">
	function createUser() {
		$('#user_body').html('Загрузка...');
		$.get('<?= Yii::app()->createUrl('admin/departmentCard/create', ['idDepartment'=>$model->id]) ?>', function(data){
			$('#user_body').html(data);
		});
	}			
</script>


<?php 
    $this->widget('bootstrap.widgets.TbGridView',array(
    	'id'=>'department-card-grid',
    	'dataProvider'=>$modelCard->search($model->id),	
    	'columns'=>array(
    		'id',		
    		'user_fio',
    		'user_rank', 
    		'user_position', 
    		'user_telephone',
    		'date_create',
    		
    		array(
    			'class'=>'bootstrap.widgets.TbButtonColumn',
    			'template'=>'{view} {update} {delete}',
    			'buttons'=>array(
    				'view'=>array(						
    					'url'=>'Yii::app()->createUrl("/admin/departmentCard/view", array("id"=>$data->id))',
    				),
    				'update'=>array(
    					'url'=>'Yii::app()->createUrl("/admin/departmentCard/update", array("id"=>$data->id))',
    				),
    				'delete'=>array(
    					'url'=>'Yii::app()->createUrl("/admin/departmentCard/delete", array("id"=>$data->id))',
    				),
    			),
    		),
    	),
    )); 
?>


