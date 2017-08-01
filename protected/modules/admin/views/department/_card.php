<?php 
// кнопка добавить пользователя
/*$this->widget('bootstrap.widgets.TbButton', array(
		'label'=>'Добавить',   
        'type'=>'success',
        'url'=>'javascript:;',
        'htmlOptions'=>array(
	        'data-toggle'=>'modal', 
	        'data-target'=>'#dialogCreateCardUser',
	    	'onclick'=>'createUser();',
		),
));*/
?>

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


<?php 
	/*$this->beginWidget('bootstrap.widgets.TbModal', array('id'=>'dialogCreateCardUser')); ?>
    
    <div class="modal-header">
        <a class="close" data-dismiss="modal">&times;</a>
        <h4>Пользователь</h4>        
    </div>
            
    <div class="modal-body" id="user_body"></div>
            
    <div class="modal-footer">
        <?php $this->widget('bootstrap.widgets.TbButton', array(
            'label'=>'Отмена',            
            'htmlOptions'=>array('data-dismiss'=>'modal'),
        )); ?>
    </div>
    
<?php $this->endWidget(); */?>


