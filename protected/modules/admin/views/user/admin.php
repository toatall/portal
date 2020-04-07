<?php
    $this->breadcrumbs=array(
    	'Пользователи'=>array('admin'),
    	'Управление',
    );
    
    $this->menu=array(
    	array('label'=>'Список', 'icon'=>'list', 'url'=>array('admin')),
    	array('label'=>'Создать', 'icon'=>'asterisk', 'url'=>array('create')),
    );


    Yii::app()->clientScript->registerScript('search', "
        $('.search-button').click(function(){
        	$('.search-form').toggle();
        	return false;
        });
        $('.search-form form').submit(function(){
        	$.fn.yiiGridView.update('user-grid', {
        		data: $(this).serialize()
        	});
        	return false;
        });
    ");
?>

<h1>Управление пользователями</h1>

<?php echo CHtml::link('Расширенный поиск','#',array('class'=>'btn btn-success search-button')); ?><br /><br />
<div class="panel panel-default search-form" style="display:none">
    <div class="panel-body">
        <?php $this->renderPartial('_search',array(
            'model'=>$model,
        )); ?>
    </div>
</div><!-- search-form -->

<?php $this->widget('bootstrap.widgets.BsGridView',array(
	'id'=>'user-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'username_windows',
		'fio:ФИО',
        array(
            'name'=>'blocked',            
            'value'=>'($data->blocked)?"Да":"Нет"',
            'filter'=>array('0'=>'Нет', '1'=>'Да'),
        ),    
        array(
            'name'=>'role_admin',
            'value'=>'($data->role_admin)?"Да":"Нет"',
            'filter'=>array('0'=>'Нет', '1'=>'Да'),
        ),
		
		array(
			'class'=>'bootstrap.widgets.BsButtonColumn',
			'template'=>'{view} {update} {updatePfofile} {delete}',
				'buttons'=>array(
					'updatePfofile'=>array(
						'icon'=>'user',
						'label'=>'Изменить профиль',
						'url'=>'isset($data->profile) '
							.'? Yii::app()->createUrl("admin/profile/update", array("id"=>$data->profile->id)) '
							.': Yii::app()->createUrl("admin/profile/create", array("id"=>$data->id))',		
					),					
				),
		),
	),
    'pager'=>array(
        'class'=>'bootstrap.widgets.BsPager',
        'size' => BsHtml::BUTTON_SIZE_DEFAULT,
    ),
)); ?>
