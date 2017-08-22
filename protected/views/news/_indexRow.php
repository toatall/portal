<?php 	
    // для проверки существавания файла нужно использовать iconv
    $imageUrl = iconv('UTF-8', 'windows-1251', $data['thumbail_image']);
    if ($imageUrl == null || !file_exists(Yii::app()->params['siteRoot'] . $imageUrl) 
        || !is_file(Yii::app()->params['siteRoot'] . $imageUrl))
	{
		$imageUrl = Yii::app()->params['noImage'];
	}
	else
	{
	   // ... но для отображения не нужно!!
	    $imageUrl = $data['thumbail_image'];
	}
	
	$url = Yii::app()->getController()->createUrl('news/view',array( 
		'id'=>$data->id, 'organization'=>$data->id_organization));	
?>

<div class="row">
    <div class="span12 thumbnail">
    	<div class="span2">
    		<a href="<?= $url ?>">
    			<img src="<?= $imageUrl ?>" class="thumbnails" style="float: left; margin-right: 20px; max-width: 200px; max-height:150px;" />
    		</a>
    	</div>
    	<div class="span10 text-left" style="padding-right:20px;">
    		<h4><a href="<?= $url ?>" class="link-title"><?php echo $data->title; ?></a></h4>
    		<blockquote>
    			<span style="text-align: justify; font-style: normal;"><?= $data->message1 ?></span>    			
    		</blockquote>
    		<small>
    			<i class="icon-calendar"></i> <i><?= date('d.m.Y H:i:s',strtotime($data->date_create)) ?></i>,
    			<i class="icon-user"></i> <i><?= $data->author ?></i>,    			
	            <i class="icon-heart"></i> <i><?= $data->count_like ?></i>,
	            <i class="icon-comment"></i> <i><?= $data->count_comment ?></i>,
	            <i class="icon-eye-open" title="Просмотров"></i> <i><?= $data->count_visit ?></i>,
	            <br /><i class="icon-home"></i> <i><?= $data->organization->name ?></i>
    		</small>
    	</div>
    </div>    
</div>
<br />
