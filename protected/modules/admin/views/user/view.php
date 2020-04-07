<?php
$this->breadcrumbs=array(
	'Пользователи'=>array('admin'),
	$model->username_windows,
);

$this->menu=array(
	array('label'=>'Список','url'=>array('admin'), 'icon'=>'list'),
	array('label'=>'Создать','url'=>array('create'), 'icon'=>'asterisk'),
	array('label'=>'Изменить','url'=>array('update','id'=>$model->id), 'icon'=>'pencil'),
	array('label'=>'Изменить профиль','url'=>array('profile/update','id'=>$model->id), 'icon'=>'pencil'),		
	array(
        'label'=>'Удалить',
        'url'=>'#',
        'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),
            'confirm'=>'Вы уверены, что хотите удалить эту запись?'),
         'icon'=>'trash',
    ), 
);
?>

<h1>Просмотр пользователя #<?php echo $model->id; ?></h1>

<?php $this->widget('bootstrap.widgets.BsDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		//'username',
		'username_windows',		
		'date_create',	
		'date_edit',
		array(
            'label'=>$model->getAttributeLabel('blocked'),
            'value'=>($model->blocked) ? 'Да' : 'Нет',
        ),
        array(
            'label'=>$model->getAttributeLabel('role_admin'),
            'value'=>($model->role_admin) ? 'Да' : 'Нет',
        ),	
		'folder_path',
	),
)); ?>

<div class="well" style="background-color:#fff;">
	<h3>Профиль пользователя</h3>

<?php if (isset($model->profile)): ?>
	
	<?php $this->widget('bootstrap.widgets.BsDetailView',array(
		'data'=>$model->profile,
		'attributes'=>array(			
			'name',
			'telephone',
			'telephone_ip',
			'post',
			'rank',
			'photo_file',
			'about',
			'status',
			
		),
	)); ?>
<?php else: ?>

<p>Для текущего пользователя не создан профиль!</p>
<p><?php echo CHtml::link('Создать профиль', 
		array('profile/create', 'id'=>$model->id), array('class'=>'btn btn-primary')); ?></p>	
	
<?php endif; ?>
</div>

<div class="well" style="background-color: white;">
<?php     
    if ($model->role_admin) {
        $orgs = Organization::model()->findAll();
    } else $orgs = $model->organization;
    

    foreach ($orgs as $value)
    {
        echo $value->code . ' - ' . $value->name . '<br />';
    }
?>
</div>
