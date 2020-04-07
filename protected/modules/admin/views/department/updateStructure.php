<?php
$this->breadcrumbs=array(
	'Отделы'=>array('admin'),
	'Структура отдела ' . $model->concatened,
);

$this->menu=array(	
	array('label'=>'Управление','url'=>array('admin'), 'icon'=>'user'),
);
?>

<h1>Структура отдела</h1>

<?php
    echo BsHtml::tabbableTabs([
        [
            'label'=>'Сотрудники отдела (' . $model->concatened . ')',
            'content'=>$this->renderPartial('_card', [
                'model'=>$model,
                'modelCard'=>$modelCard
            ], true, true),
            'active'=>'true',
        ],
    ]);
    /*
	$this->widget('bootstrap.widgets.TbTabs', array(
        'id'=>'myTabs',
        'type'=>'tabs',
        'encodeLabel'=>false,
        'tabs'=>array(
            array(                
                'label'=>'Сотрудники отдела (' . $model->concatened . ')', 
                'content'=>$this->renderPartial('_card', [
                	'model'=>$model, 
                	'modelCard'=>$modelCard
				], true, true), 
                'active'=>'true',            
			),           
        ),
    
    ));
    */
?>

