<?php
$this->breadcrumbs=array(
	'Отдел (' . $model->concatened . ')' => array('admin', 'idTree'=>$model->id_tree),
	'Настройки',
);

$this->menu=array(
	array('label'=>'Управление','url'=>array('admin', 'idTree'=>$model->id_tree), 'icon'=>'user'),
	
	array('label'=>'<hr />','type'=>'raw'),
	array('label'=>'Настройка отдела','url'=>array('options', 'id'=>$model->id, 'idTree'=>$model->id_tree), 'icon'=>'cog'),
);

if ($model->use_card):
$this->menu = array_merge($this->menu, array(
		array('label'=>'<hr />','type'=>'raw'),
		array('label'=>'Структура отдела','url'=>array('department/updateStructure', 'id'=>$model->id), 'icon'=>'list-alt'),
));
endif;

?>

<h1>Настройки отдела <?= $model->concatened; ?></h1>


<?php 
	$this->widget('bootstrap.widgets.TbAlert', array('block'=>true));
?>

<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'department-form',
)); ?>

	<div class="well">
		<?php echo $form->checkBoxRow($model,'use_card'); ?>
		<hr />
		<?php echo $form->dropDownListRow($model,'general_page_type',$model->typeGeneralPage, array('id'=>'general_page_type')); ?>
		
		<div class="thumbnail" id="container-general-page-tree-id">
			<?php echo $form->dropDownListRow($model,'general_page_tree_id',$model->treeList, array('id'=>'general_page_type')); ?>
		</div>
	</div>
	
<script type="text/javascript">
	$(document).ready(function() {
		function checkTypePage()
		{
			if ($('#general_page_type').val()==1)
			{
				$('#container-general-page-tree-id').show();
			}
			else
			{
				$('#container-general-page-tree-id').hide();
			}
		}

		checkTypePage();

		$('#general_page_type').on('change',function() {
			checkTypePage();
		});
	});
</script>	
	
	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>'Сохранить',
		)); ?>
	</div>

<?php $this->endWidget(); ?>