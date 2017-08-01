
<div class="bs-callout bs-callout-info">
    <div style="clear: both;">
        <?php $url = Yii::app()->getController()->createUrl('department/view',array( 
            'id'=>$data->id)); ?>
        <a href="<?php echo $url; ?>">
        <img src="<?php if (file_exists(Yii::app()->params['siteRoot'].$data->thumbail_image)) { echo $data->thumbail_image; } ?>" class="thumbnail" style="float: left; margin-right: 20px;" /></a>
        <div style="overflow: hidden; text-align: justify;">
            <h4><a href="<?php echo $url; ?>"><?php echo $data->title; ?></a></h4>
            <i class="icon-calendar"></i> <i><?php echo date('d.m.Y',strtotime($data->date_create)); ?></i>,
            <i class="icon-user"></i> <i><?php echo $data->author; ?></i>,
            <br /><i class="icon-home"></i> <i><?php echo Organization::model()->findByPk($data->id_organization)->name; ?></i>
            <p><?php echo $data->message1; ?></p>
        </div> 
    </div>
    <div style="clear: both;"></div>      
</div>