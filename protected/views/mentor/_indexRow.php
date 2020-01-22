<?php 	

    $imageUrl = Yii::app()->params['noImage'];

    /* @var $data array */	
	$url = Yii::app()->getController()->createUrl('mentor/view', array('id'=>$data['id']));	
?>

<div class="row">
    <div class="span12 thumbnail">    	
    	<div class="span2 links">
    		<a href="<?= $url ?>" class="sw_dlg">
    			<img src="<?= $imageUrl ?>" class="thumbnails" style="float: left; margin-right: 10px; max: 200px; max:150px;" />
    		</a>    		
    	</div>
    	<div class="span9 text-left" style="padding-right:20px;">
    		<h4><a href="<?= $url ?>" class="link-title sw_dlg"><?php echo $data->title; ?></a></h4>
    		<small>
    			<i class="icon-calendar" title="Дата создания"></i> <i><?= date('d.m.Y H:i:s',strtotime($data['date_create'])) ?></i>,
    			<i class="icon-user" title="Автор"></i> <i><?= User::nameByLogin($data['author']) ?></i>,    				           
	            <br /><i class="icon-home"></i> <i><?= $data->org->fullName ?></i>
    		</small>
    	</div>
    	<div style="span1">
    		<?php if (Access::checkAccessMentorPost($data['id'])): ?>
    		<div class="btn-group">
    			<a class="btn btn-success dropdown-toggle" data-toggle="dropdown" href="#">
    				Управление
    				<span class="caret"></span>	
    			</a> 
    			<ul class="dropdown-menu">
    				<li><a href="<?= Yii::app()->createUrl('mentor/update', ['id'=>$data['id']]) ?>">Изменить</a></li>
    				<?php if (Access::checkAccessMentorIsModerator()): ?>
    				<li class="delete-confirm"><?= CHtml::link('Удалить', ['mentor/delete', 'id'=>$data['id']]) ?></li>
    				<?php endif; ?>
    			</ul>
    		</div>
    		<?php endif; ?>
    	</div>
    </div>    
</div>
<br />
