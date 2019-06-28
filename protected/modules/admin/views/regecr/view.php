<?php
$this->breadcrumbs=array(
    'Анкетирование по ГР'=>array('admin'),
    $model->id,
);

$this->menu=array(
	array('label'=>'Создать','url'=>array('create'), 'icon'=>'asterisk'),
	array('label'=>'Изменить','url'=>array('update','id'=>$model->id), 'icon'=>'pencil'),
	array('label'=>'Удалить','url'=>'#','linkOptions'=>array(
            'submit'=>array('delete','id'=>$model->id),
            'confirm'=>'Вы уверены что хотите удалить "'.$model->id.'"? Все дочерние подразделы будут удалены!'),
            'icon'=>'trash',
        ),
	array('label'=>'Управление','url'=>array('admin'), 'icon'=>'user'),
);
?>

<h1>Просмотр #<?php echo $model->id; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'attributes'=>array(
            'id',
            [
                'name' => 'organization.fullname',
                'label' => 'Организация',
            ],
            'date_reg',
            'count_create',
            'count_vote',
            'avg_eval_a_1_1',
            'avg_eval_a_1_2',
            'avg_eval_a_1_3',
            [
                'name' => 'user.fio',
                'label' => 'Автор',
            ],
            'date_create',
            'date_update',
        ),
)); ?>
