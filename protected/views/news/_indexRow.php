<?php
/**
 * @var $this CController
 * @var $data array
 */
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
		'id'=>$data['id'], 'organization'=>$data['id_organization']));	
?>

<div class="panel panel-default">
    <div class="panel-body vertical-align" style="background: #fbfbfb">
        <div class="col-sm-4 col-md-3 col-lg-2">
            <a href="<?= $url ?>" class="show-modal-dialog">
                <img src="<?= $imageUrl ?>" class="thumbnail" style="width: 100%;">
            </a>
        </div>
        <div class="col-sm-8 col-md-9 col-lg-10">
            <?php if ($data['date_top'] != ''): ?>
            <div style="float:right; font-size: xx-large; margin-top: 0px;">
                <i class="fa fa-thumbtack text-muted" data-toggle="popover" data-placement="bottom" data-content="Закреплена до <?= $data['date_top'] ?>"></i>
            </div>
            <?php endif; ?>
            <div class="icerik-bilgi">
                <a href="<?= $url ?>" class="show-modal-dialog">
                    <h4 style="color: #3B5998; font-weight: bold;">
                        <?php if (Yii::app()->dateHelper->dateDiffDays($data['date_create']) <= 0): ?>
                        <span class="label label-success">Новое</span>
                        <?php endif ?>
                        <?= $data['title']; ?>
                    </h4>
                </a>
                <div class="icon-group">
                    <span class="label label-default"><?= $data['count_like'] ?> <i class="fa fa-heart"></i></span>
                    <span class="label label-default"><?= $data['count_comment'] ?> <i class="fa fa-comments"></i></span>
                    <span class="label label-default"><?= $data['count_visit'] ?> <i class="fa fa-eye"></i></span>
                </div>
                <p></p>
                <hr>
                <span style="color:#666; font-size:12px;">
                    <i class="fa fa-building"></i> <?= $data['organization_name'] ?><br>
                    <i class="fa fa-clock"></i> <?= $data['date_create'] ?>
                    <i class="fa fa-user-edit"></i> <?= User::nameByLogin($data['author']) ?>
                </span>
                <div class="clearfix"></div>
            </div>
        </div>

    </div>

</div>
