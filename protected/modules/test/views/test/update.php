<?php
/* @var $this TestController */
/* @var $model Test */

$this->breadcrumbs=array(
	'Тесты'=>array('admin'),
	$model->name=>array('view','id'=>$model->id),
	'Изменить',
);
?>

<?= BsHtml::pageHeader('Тесты', $model->name) ?>

<div class="panel panel-default">
    <div class="panel-heading">
        Управление
    </div>

    <div class="panel-body btn-group">
        <?= BsHtml::link('<i class="fas fa-eye"></i> Просмотр', ['/test/test/view', 'id'=>$model->id], ['class'=>'btn btn-primary']) ?>
        <?= BsHtml::link('<i class="fas fa-trash"></i> Удалить', [], ['class' => 'btn btn-danger', 'confirm' => 'Вы уверены, что хотите удалить?', 'submit' => ['/test/test/delete', 'id'=>$model->id]]) ?>
    </div>
</div>


<?php $this->renderPartial('_form', array('model'=>$model)); ?>