<?php /* @var $this Controller */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
	<meta name="language" content="en" />
	
    <?php Yii::app()->bootstrap->register(); ?>
	
	<!-- blueprint CSS framework -->
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css" media="screen, projection" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css" media="print" />
	<!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection" />
	<![endif]-->
    
	
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" />        
    <link rel="shortcut icon" href="/css/favicon.png" />
    
    <?php 
        Yii::app()->getClientScript()->registerScriptFile(Yii::app()->baseUrl.'/extension/upButton/query.js');     
    ?>
    
	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/menu.css" />  
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css" />  
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/fontawesome/fontawesome-all.min.css" />  
    <?php Yii::app()->getClientScript()->registerScriptFile(Yii::app()->request->baseUrl . '/js/main.js', CClientScript::POS_BEGIN); ?>   
    <?php 
        Yii::app()->clientScript->registerScriptFile(
            Yii::app()->baseUrl.'/extension/baguetteBox/baguetteBox.min.js');
        Yii::app()->getClientScript()->registerCssFile(
            Yii::app()->baseUrl.'/extension/baguetteBox/baguetteBox.min.css');
    ?>
    
</head>
    
<body>	
	
<!-- header -->	
	<table id="head">
	    <tr>
	    	<td id="header-right">    		
	    	</td>	    	
	    </tr>	   
	</table>
<!-- header -->

<?php    
    Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/extension/spoiler/spoiler.js');
    Yii::app()->clientScript->registerCssFile(Yii::app()->request->baseUrl.'/extension/spoiler/spoiler.css');        
?>

<?php $this->widget('bootstrap.widgets.TbNavbar',array(   
        'brand'=>'',
        //'fixed'=>'inverse',
        'htmlOptions'=>array('style'=>'top: 200px;', 'id'=>'top_nav', 'class'=>'navbar-static-top'),
        'items'=>array(
            array(
                'class'=>'bootstrap.widgets.TbMenu',
                'items'=>Menu::getTopMenuArray(),
            )),
    )); ?>    
<div id="page">

    <?php if(isset($this->breadcrumbs)):?>
		<?php $this->widget('bootstrap.widgets.TbBreadcrumbs', array(
            'homeLink' => CHtml::link('Главная', array('site/index')),
			'links' => $this->breadcrumbs,    
		    'htmlOptions'=>array(
                'class' => 'well',
                'style' => 'margin: 10px;',
		    ),
		)); ?><!-- breadcrumbs -->
	<?php endif?>
	    				
    <div class="content-fluid">
    	<div class="row-fluid">
    		<div class="span2" style="margin-left:10px;">
    			<ul class="dropdown-menu dropdown-menu-main dropdown-menu-wrap">
		        <?php
		            echo Menu::model()->getLeftMenuArray();                                    
		        ?>         
		        </ul>
		        <?php echo Menu::model()->getLeftMenuAdd(Menu::$leftMenuAdd); ?>
		        <div id="container-conference-today"></div>   	
		        <div>
		        	<ul class="dropdown-menu dropdown-menu-main" style="z-index: 0;">
		        		<li class="nav-header">Голосование</li>
		        		<li>
		        			<p style="padding: 0 15px;" id="container-votes"></p>
		        		</li>
		        		<li class="divider"></li>
		        		<li><a href="<?= Yii::app()->createUrl('vote/index') ?>">Смотреть все</a></li>		        		
		        	</ul>	
		        </div>	
    		</div>
    		<div class="span9">
				<!--[if lt IE 9]>
					<div class="alert alert-danger">
				    	<strong>Для корректной работы портала необходимо использовать браузер Internet Explorer версии 9 и выше!</strong>
				    	<br />Рекомендуется использовать Google Chrome.
				    	<br /><?= CHtml::link('Список браузеров', array('site/browsers')) ?>
				    </div>
				<![endif]-->				
    			<?php echo $content; ?>
    		</div>
    	</div>
    </div>
    
    
	<div class="clear"></div>
    
	<div id="footer">        
        <h4>Разработка и соопровождение: Трусов Олег Алексеевич</h4>
        <?php echo CHtml::link('Рекомендуемые браузеры', array('site/browsers')); ?>
        <br /><br />
        <?php echo Log::StatisticOnlineAndDay(); ?>
        <br />
        <?= CHtml::link('Направить обращение', array('site/contact')) ?>        
        <br /><br />
        <h4>&copy; Powered by Yii Framework 1.1</h4> 
	</div><!-- footer -->
</div><!-- page -->

<?php $this->beginWidget('bootstrap.widgets.TbModal', array(
    'id'=>'modalPreview',
    'htmlOptions'=>array('style'=>'width:90%; margin-left:-45%; margin-top:-3%;'),
)); ?>
<div class="modal-header" style="height1:30px;">
    <a class="close" data-dismiss="modal"><h3 class="text-error">&times;</h3></a>
    <h2 id="modal-title-preview"></h2>        
</div>
<div class="modal-body" id="modal-content-preview" style="max-height:70vh;"></div>
<div class="modal-footer">
    <?php $this->widget('bootstrap.widgets.TbButton', array(
        'label'=>'Закрыть',   
        'type'=>'primary',
        'htmlOptions'=>array('data-dismiss'=>'modal'),
    )); ?>
</div>
<?php $this->endWidget(); ?>

</body>
</html>
