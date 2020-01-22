<?php
/* @var $this MentorController */
/* @var $modelWay MentorWays */
/* @var $model MentorPost */

$this->breadcrumbs=array(
    'Наставничество' => ['/mentor/index'],
    $modelWay->name,
);

?>
<h1 class="header"><?= $modelWay->name ?></h1>

<?= CHtml::link('Добавить пост', ['/mentor/create', 'idWay' => $modelWay->id], ['class'=>'btn btn-primary']); ?>

<?php 
$this->widget('bootstrap.widgets.TbGridView',array(
    'id'=>'mentor-grid',
    'itemsCssClass' => 'items',
    'dataProvider'=>$model->search(),
    'hideHeader'=>true,
    'summaryText'=>'',
    'columns'=>array(
        array(
            'value'=>'Yii::app()->getController()->renderPartial("_indexRow",array("data"=>$data), true)',
            'type'=>'html',
        ),
    ),
    'pager'=>array(
        'class'=>'bootstrap.widgets.TbPager',
        'displayFirstAndLast'=>true,
    ),
));
?>
<script type="text/javascript">
 	$('#mentor-grid table').removeClass('table');
 	$('.dropdown-toggle').dropdown();
 	$('.delete-confirm a').on('click', function() {
		return confirm('Вы уверены, что хотите удалить?');
 	});
</script>

