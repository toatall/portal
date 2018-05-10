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
	<?php 
	   $d1 = new DateTime();
	   $d2 = new DateTime('2018-05-16');
	   $dateDiff = $d1->diff($d2)->format('%r%a');	
	?>
	
<!-- header -->	
	<table id="head">
	    <tr>
	    	<td id="header-right">
	    		<?php if ($dateDiff > 0): ?>
	    		<div style="color: #fff; float: right; width:300px;">
	    			<h4>До начала XV Спартакиады налоговых органов Югры осталось</h4> <h1><?= $dateDiff ?></h1> <h4>дней</h4>
	    		</div>
	    		<?php endif; ?>
	    	</td>	    	
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
        'htmlOptions'=>array('style'=>'top: 200px;', 'id'=>'top_nav', 'class'=>'navbar-static-top'),
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
        <h3>&copy; Управление ФНС России 
                по Ханты-Мансийскому автономному округу - Югре, <?php echo date('Y'); ?>
                <br /><span style="font-size:14px; color:white;">Разработка и сопровождение: Трусов Олег Алексеевич</span>                
		</h3>                                                   
        <?php echo CHtml::link('Рекомендуемые браузеры', array('site/browsers')); ?>
        <br /><br />
        <?php echo Log::StatisticOnlineAndDay(); ?>
        <br />
        <?= CHtml::link('Направить обращение', array('site/contact')) ?>
        <br /><br />        
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
<script type="text/javascript">
    $('#modalPreview').on('hide', function() {
        removeParametrDialog();
    });

    $(document).on('click', '.sw_dlg', function() {    		
    	getJson($(this).attr('href'));
		$('#modalPreview').modal('show');
		return false;
	});
</script>
</body>

</html>
