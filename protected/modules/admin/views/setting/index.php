<?php
$this->breadcrumbs=array(
	'Settings',
);

$this->menu=array(
	array('label'=>'Создать Setting','url'=>array('create'), 'icon'=>'asterisk'),
	array('label'=>'Управление Setting','url'=>array('admin'), 'icon'=>'user'),
);
?>

<h1>Настройки</h1>

<?php
    $this->widget('bootstrap.widgets.TbTabs', array(
        'id'=>'myTabs',
        //'htmlOptions'=>array('style'=>'margin-left: 320px;'),
        'type'=>'tabs',
        'encodeLabel'=>false,
        'tabs'=>array(
            array(                
                'label'=>'Общие', 
                'content'=>$this->renderPartial('tabs/general', array(), true, true), 
                'active'=>'true'
            ),            
            array(
                'label'=>'Расположения', 
                'content'=>$this->renderPartial('tabs/general', array(), true, false)
            ),   
        ),
    ));
?>

