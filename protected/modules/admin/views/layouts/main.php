<?php /* @var $this Controller */ ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
	<meta name="language" content="en" />
	<title><?php echo CHtml::encode($this->pageTitle); ?></title>

	<?php Yii::app()->bootstrap->register(); ?>   
	
	<link rel="stylesheet" type="text/css" href="/css/admin/styles.css" />
	<link rel="shortcut icon" href="/css/admin/favicon.png" />
</head>

<body>

<?php    
	/**
	 * отключить прокрутку при открытии диалогового окна
	 * fix
	 * @author alexeevich
	 * @version 18.05.2016
	 */ 
	Yii::app()->clientScript->registerScript('fixScrollOnOpenModal', "
		$('.modal') 
	  		.on('shown', function(){ 
	  	  		$('body').css({overflow: 'hidden'}); 
	  		}) 
	  		.on('hidden', function(){ 
	  	  		$('body').css({overflow: ''}); 
	  		});
		", CClientScript::POS_END);
?>

<?php $this->widget('bootstrap.widgets.TbNavbar',array(
    'brandUrl' => array('/admin/default/index'),
    'items'=>array(
        array(
            'class'=>'bootstrap.widgets.TbMenu',
            'items'=>array(
                array('label'=>'Главная', 'url'=>array('/admin/default/index'),'visible'=>!Yii::app()->user->isGuest),
            	array('label'=>'Портал', 'url'=>array('/site/index')),
                array('label'=>'Администрирование', 'url'=>'#', 'items'=>array(    
                    array('label'=>'Пользователи и группы'),
                    array('label'=>'Пользователи', 'url'=>array('/admin/user/admin')),
                    array('label'=>'Группы', 'url'=>array('/admin/group/admin')),
                    array('label'=>'Модули', 'url'=>array('/admin/module/admin')),
                    '---',
                    array('label'=>'Управление структурой'),
                    array('label'=>'Организации', 'url'=>array('/admin/organization/admin')),
                    array('label'=>'Меню', 'url'=>array('/admin/menu/admin')),
                    ), 'visible'=>(!Yii::app()->user->isGuest && Yii::app()->user->admin),
                ),
                array('label'=>'Контент', 'url'=>'#', 'items'=>array(                   
                    array('label'=>'Структура', 'url'=>array('/admin/tree/admin')),
                	array('label'=>'Отделы', 'url'=>array('/admin/department/admin')),
                                       
                ), 'visible'=>!Yii::app()->user->isGuest),
                array('label'=>'Справка', 'url'=>array('/admin/default/help')),             
                array('label'=>'Вход', 'url'=>array('/admin/default/login'), 'visible'=>Yii::app()->user->isGuest),
                array('label'=>'Выход ('.Yii::app()->user->name.')', 'url'=>array('/admin/default/logout'), 'visible'=>!Yii::app()->user->isGuest),
                                                
            ),           
        ),
        (!Yii::app()->user->isGuest && !empty(Yii::app()->session['organization']) ?
            '<div style="float:right; padding: 10px;">
            НО: <a href="" style="text-underline:none; border-bottom: 1xp dashed red;" data-toggle="modal" data-target="#changeOrganizationModal" >'
                .Yii::app()->session['organization'].'</a>
            </div>' : ''),
    ),
)); ?>
    
    <?php  if (!Yii::app()->user->isGuest): ?>
  	
  	
    <?php $this->beginWidget('bootstrap.widgets.TbModal', array('id'=>'changeOrganizationModal')); ?>
    
    <div class="modal-header">
        <a class="close" data-dismiss="modal">&times;</a>
        <h4>Организации</h4>        
    </div>
      
    <div class="modal-body" style="font-size:12px;">
		
        <?php
            // спсиок организаций со ссылками для изменения
            $modelOrganization = User::userOrganizations();
            $arrayOrganizationChange = array();            
            if ($modelOrganization !== null)            	
	            foreach ($modelOrganization as $record) {
	                $arrayOrganizationChange[] = array(
	                    'label'=>$record->code.' - '.$record->name,
	                    'url'=>$this->createUrl('/admin/default/changeCode', 
	                        array('code'=>$record->code)),
	                );
	            }
            
            $this->widget('bootstrap.widgets.TbMenu', array(               
                'items'=>$arrayOrganizationChange,
            ));       
        ?>
    </div>
            
    <div class="modal-footer">       
        <?php $this->widget('bootstrap.widgets.TbButton', array(
            'label'=>'OK',
            'url'=>'#',
            'htmlOptions'=>array('data-dismiss'=>'modal'),
        )); ?>
    </div>
    
    <?php $this->endWidget(); ?>
        
    <?php endif; ?>   
    
<div class="container" id="page">   

	<?php if(isset($this->breadcrumbs)):?>
		<?php $this->widget('bootstrap.widgets.TbBreadcrumbs', array(
            'homeLink' => CHtml::link('Главная', array('/admin/default/index')),
			'links' => $this->breadcrumbs,
		)); ?><!-- breadcrumbs -->
	<?php endif?>
	    
    <?php if ($this->module->errorLogin === false): ?>    	
		<?php echo $content; ?>    	
    <?php else: ?>
    <div class="error">
    	<h1>Ошибка!</h1>
        <?= $this->module->errorLogin; ?>
    </div>
	<?php endif; ?>
    
	<div class="clear"></div>
    
    <div class="form-actions">
	<div id="footer">
		Система управления Порталом Управления ФНС России по Ханты-Мансийскому автономному округу - Югре &copy; <?php echo date('Y'); ?><br />
        Разработка: Трусов Олег Алексеевич<br />
        Работает на <a href="http://www.yiiframework.com/">Yii Framework</a>   
	</div><!-- footer -->
    </div>

</div><!-- page -->

</body>
</html>
