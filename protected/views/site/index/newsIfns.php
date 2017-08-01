<?php
    // @toto перенести в controller
    $news = Yii::app()->db->createCommand("
        SELECT TOP 10 t.id, t.id_tree, t.thumbail_title, t.thumbail_image, t.message1, t.author, t.date_create, org.name
    	FROM {{news}} t
            LEFT OUTER JOIN {{tree}} tree ON t.id_tree=tree.id
    		LEFT OUTER JOIN {{organization}} org ON t.id_organization=org.code
        WHERE t.date_end_pub > getdate() AND t.date_delete IS NULL AND t.flag_enable=1
            AND t.on_general_page=1 AND tree.module = 'news' AND t.id_organization <> '8600'
        ORDER BY t.date_create DESC, t.id desc
    ")->queryAll();
    
    
    
    
    
    foreach ($news as $value)
    {
		
    	
    	if (file_exists($_SERVER['DOCUMENT_ROOT'].$value['thumbail_image']) && is_file($_SERVER['DOCUMENT_ROOT'].$value['thumbail_image']))
    	{
    		$miniature = $value['thumbail_image'];
    	}
    	else
    	{
    		$miniature = '/images/newspaper-icon-20.png';
    	}
?>
    <div class="bs-callout bs-callout-info">
    <div style="clear: both;">  
        <?php $url = Yii::app()->getController()->createUrl('news/view',array( 
            'id'=>$value['id'])); ?>        
        <a href="<?php echo $url; ?>">
        <img src="<?php echo $miniature; ?>" class="thumbnail" style="float: left; width: 200px;" /></a>
        <div style="padding-left: 20px; overflow: hidden;">
            <h4><a href="<?php echo $url; ?>"><?php echo $value['thumbail_title']; ?></a></h4>
            <i class="icon-calendar"></i> <i><?php echo date('d.m.Y',strtotime($value['date_create'])); ?></i>,
            <i class="icon-user"></i> <i><?php echo $value['author']; ?></i>
            <br /><i class="icon-home"></i> <i><?php echo $value['name']; ?></i>
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
    'url'=>Yii::app()->getController()->createUrl('news/index'),
    'label'=>'Все новости',
    'htmlOptions'=>array(
        'style'=>'float:right',
    ),
)); ?>
