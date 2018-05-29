<?php
    /**
     * @param NewsSearch $model
     */
    
    // для проверки существавания файла нужно использовать iconv
    $imageUrl = iconv('UTF-8', 'windows-1251', $model['thumbail_image']);
    if ($imageUrl == null || !file_exists(Yii::app()->params['siteRoot'] . $imageUrl)
        || !is_file(Yii::app()->params['siteRoot'] . $imageUrl)) {
        $imageUrl = Yii::app()->params['noImage'];
    }
    else {
        // ... но для отображения не нужно!!
        $imageUrl = $model['thumbail_image'];
    }
    
    $url = Yii::app()->getController()->createUrl('news/view',array(
        'id'=>$model['id'], 'organization'=>$model['id_organization']));
?>

<div style="background: white; padding: 20px; width: 600px; height: 500px; margin-bottom: 20px; margin-right: 20px; -webkit-border-radius: 4px; -moz-border-radius: 4px; border-radius: 4px;">
		
	<div style="margin-bottom: 10px; font-size:16px; text-align: justify;">		
		<?= CHtml::link('<i class="fas fa-home"></i> ' . $model->organization->name, ['news/index', 'organization'=>$model->organization->code]) ?>
	</div>

    <div class="jumbotron centered" style="height:400px;">
    	<a class="snippet-lnk centered sw_dlg" href="<?= $url ?>" style="position:relative;">
        	<div class="snippet-container centered" style="background: url('<?= $imageUrl ?>') no-repeat center; background-size: cover; background-position:50%;"></div>
        	<div class="snippet-container snippet-img"></div>
        	<div class="snippet-container" style="width:600px; height:400px; top:0; color:white;">
                <div style="text-align: center; padding: 10px; text-shadow:0 4px 21px rgba(0,0,0,.32);">
                	<h3 style="color:white;"><?= $model['title'] ?></h3>
                </div>
            </div>
        </a>
    </div>
	
    <div style="text-align:center; margin-top: 30px; font-size:16px; color:#555;">
		<div class="span3 offset1">
			<i class="<?php if ($model['count_like']) { ?>fas<?php } else { ?>far<?php } ?> fa-heart" title="Понравилось"></i> <i><?= $model['count_like'] ?></i>
		</div>
		<div class="span3">
        	<i class="<?php if ($model['count_comment']) { ?>fas<?php } else { ?>far<?php } ?> fa-comment-alt" title="Комментарии"></i> <i><?= $model['count_comment'] ?></i>
		</div>  			
        <div class="span3">
        	<i class="far fa-eye" title="Просмотров"></i> <i><?= $model['count_visit']; ?></i>
        </div>
	</div>
	<div class="clear"></div>
    
</div>

