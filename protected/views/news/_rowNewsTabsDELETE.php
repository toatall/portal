<?php 	
	
	$imageUrl = $data['thumbail_image'];
	if ($imageUrl == null || !file_exists(Yii::app()->params['siteRoot'].$data['thumbail_image']) || !is_file(Yii::app()->params['siteRoot'].$data['thumbail_image']))
	{		
		$imageUrl = Yii::app()->params['noImage'];
	}
	

	
?>
<div class="span12" style="margin-boottom:5px;">   
	<?php $url = Yii::app()->getController()->createUrl('news/view',array( 
		'id'=>$data['id'], 'organization'=>$data['id_organization'])); ?>
    <a href="<?php echo $url; ?>">
    <img src="<?= $imageUrl ?>" class="thumbnail" style="float: left; margin-right: 20px; width: 200px;" /></a>
    <div style="overflow: hidden; text-align: justify;">
    	<h4><a href="<?php echo $url; ?>"><?= $data['title']; ?></a></h4>
        <i class="icon-calendar"></i> <i><?= $data['date_create'] ?></i>,
        <i class="icon-user"></i> <i><?= $data['author']; ?></i>,
        <br /><i class="icon-home"></i> <i><?= $data['org_name']; ?></i>
        <p><?php echo $data['message1']; ?></p>
   	</div>  
</div>
<div style="clear: both;"></div>
<hr />