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
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css" />
	
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" />        
    <link rel="shortcut icon" href="/css/favicon.png" />
    
    <?php 
        Yii::app()->getClientScript()->registerScriptFile(Yii::app()->baseUrl.'/extension/upButton/query.js');
    ?>
    
	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/menu.css" />    
    <?php Yii::app()->getClientScript()->registerScriptFile(Yii::app()->request->baseUrl . '/js/main.js', CClientScript::POS_END); ?>
</head>
    
<body>
  
<!-- header -->	
	<table id="head">
	    <tr>
	        <td id="header-right"><a href="index.php"></a></td>
	    </tr>
	</table>
<!-- header -->

<?php
    Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/extension/spoiler/spoiler.js');
    Yii::app()->clientScript->registerCssFile(Yii::app()->request->baseUrl.'/extension/spoiler/spoiler.css');    
    Yii::app()->clientScript->registerScript('fixed-menu', "
    	var h_hght = 200; // высота шапки
		var h_mrg = 0;    // отступ когда шапка уже не видна
		                 
		$(function(){
		 
		    var elem = $('#top_nav');
		    var top = $(this).scrollTop();
		     
		    if(top > h_hght){
		        elem.css('top', h_mrg);
		    }           
		     
		    $(window).scroll(function(){
		        top = $(this).scrollTop();
		         
		        if (top+h_mrg < h_hght) {
		            elem.css('top', (h_hght-top));
		        } else {
		            elem.css('top', h_mrg);
		        }
		    });
		 
		});        		    	
    ", CClientScript::POS_END);
?>



<?php      
   
    $this->widget('bootstrap.widgets.TbNavbar',array(   
        'brand'=>'',
        //'fixed'=>'inverse',
        'htmlOptions'=>array('style'=>'top: 200px;', 'id'=>'top_nav'),
        'items'=>array(
            array(
                'class'=>'bootstrap.widgets.TbMenu',
                'items'=>Menu::getTopMenuArray(),
            )),
    ));
      
?>
    
<div id="page">

    <?php if(isset($this->breadcrumbs)):?>
		<?php $this->widget('bootstrap.widgets.TbBreadcrumbs', array(
            'homeLink' => CHtml::link('Главная', array('site/index')),
			'links' => $this->breadcrumbs,            
		)); ?><!-- breadcrumbs -->
	<?php endif?>
    
    <div class="content-fluid">
    	<div class="row-fluid">
    		<div class="span2">
    			<ul class="dropdown-menu dropdown-menu-main dropdown-menu-wrap">
		        <?php
		            echo Menu::model()->getLeftMenuArray();                                    
		        ?>         
		        </ul>
		        <?php echo Menu::model()->getLeftMenuAdd(Menu::$leftMenuAdd); ?>
		        <div id="container-conference-today"></div>   		
    		</div>
    		<div class="span10">
    			<?php 
					//if (Yii::app()->browser->getBrowser() == "Internet Explorer" && Yii::app()->browser->getVersion() < 9):
					?>
					<!--[if lt IE 9]>
						<div class="alert alert-danger">
					    	<strong>Для корректной работы портала необходимо использовать браузер Internet Explorer версии 9 и выше!</strong>
					    	<br />Рекомендуется использовать Google Chrome.
					    	<br /><?= CHtml::link('Список браузеров', array('site/browsers')) ?>
					    </div>
					<![endif]-->
				<?php 
					//endif;
				?>
				
    			<?php echo $content; ?>
    		</div>
    	</div>
    </div>
    
    
	<div class="clear"></div>
    
	<div id="footer">
        <h3>&copy; Управление ФНС России 
                по Ханты-Мансийскому автономному округу - Югре, <?php echo date('Y'); ?></h3>                                                                   
        <?php echo CHtml::link('Рекомендуемые браузеры', array('site/browsers')); ?>
        <br /><br />
        <?php echo Statistic::StatisticOnlineAndDay(); ?>
        <br />
        <?= CHtml::link('Направить обращение', array('site/contact')) ?>
        <br /><br />        
	</div><!-- footer -->

</div><!-- page -->

	
</body>
</html>
