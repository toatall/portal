<?php
    
    $news = Yii::app()->db->createCommand("
        SELECT t.id, t.id_tree, t.title, t.thumbail_title, t.thumbail_image, t.message1, t.author, t.date_create, t.id_organization
    	FROM {{news}} t
            LEFT OUTER JOIN {{tree}} tree ON t.id_tree=tree.id
        WHERE t.date_end_pub > getdate() AND t.date_delete IS NULL AND t.flag_enable=1
            AND tree.param1 = '$page'
        ORDER BY t.date_create DESC        
    ")->queryAll();
    
    $miniaturePath = Yii::app()->params['miniatureImage'];
    //$miniaturePath = str_replace('{code_no}', '8600', $miniaturePath);
    //$miniaturePath = str_replace('{module}', 'news', $miniaturePath);
    
    foreach ($news as $value)
    {
    	$miniaturePathTemp = str_replace('{code_no}', $value['id_organization'], $miniaturePath);
    	$miniaturePathTemp = str_replace('{module}', $page, $miniaturePathTemp);
    	$miniaturePathTemp = str_replace('{id}', $value['id'], $miniaturePath);

?>
    <div class="bs-callout bs-callout-info">
    <div style="clear: both;">
        <?php $url = Yii::app()->getController()->createUrl('page/view',array( 
            'id'=>$value['id'])); ?>
        <?php if (file_exists($_SERVER['DOCUMENT_ROOT'].$miniaturePathTemp.$value['thumbail_image'])): ?>
        <a href="<?php echo $url; ?>">
        <img src="<?php echo $miniaturePathTemp.$value['thumbail_image']; ?>" class="thumbnail" style="float: left;" /></a>
        <?php endif; ?>
        <div style="padding-left: 20px; overflow: hidden;">
            <h4><a href="<?php echo $url; ?>"><?php echo $value['title']; ?></a></h4>
            <i class="icon-calendar"></i> <i><?php echo date('d.m.Y',strtotime($value['date_create'])); ?></i>,
            <i class="icon-user"></i> <i><?php echo $value['author']; ?></i>
            <p><?php echo $value['message1']; ?></p>
        </div>
    </div>
    <div style="clear: both;"></div>      
    </div>

<?php        
        
        
    }      

?>

<br />
<?php $this->widget('bootstrap.widgets.TbButton', array(
    'type'=>'primary',
    'url'=>Yii::app()->getController()->createUrl('page/index', 
        [
        	'page'=>$page,        	
		]),
    'label'=>'Все материалы',
    'htmlOptions'=>array(
        'style'=>'float:right',
    ),
)); ?>


