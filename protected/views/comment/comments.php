<?php 
    /* @var $this CController */
    /* @var $model array */
   
    /* @var $urlDeleteStr string */
    $urlDeleteStr = isset($urlDeleteStr) ? $urlDeleteStr : 'comment/delete';
    
    /* @var $urlUpdateStr string */
    $urlUpdateStr = isset($urlUpdateStr) ? $urlUpdateStr : 'comment/update';
?>

<?php
	
	if (count($model) == 0):
?>
	Нет данных
<?php
	endif;
	
	foreach ($model as $m)
	{
?>	
	
	<div id="comment-body-<?= $m['id'] ?>" class="row alert alert-<?= ($m['date_delete'] == null ? 'default' : 'danger') ?>">			
		<div class="span2 "><?= User::profileByLogin($m['username']) ?></div>
		<div class="span8">
			<?php if ($m['date_delete'] == null): ?>
			<h5><?= $m['date_create'] ?></h5>
			<form id="form-comment-<?= $m['id']; ?>" method="post" name="form-comment">
				<input type="hidden" id="comment-hide-<?= $m['id']; ?>" name="Comment[comment]" />
				<div contenteditable="false" class="" style="text-align: justify;" id="div-comment-<?= $m['id']; ?>">
					<?= $m['comment'] ?>
				</div>
				<div id="comment-smile-<?= $m['id'] ?>" style="display:none;">
    				<?php $this->widget('application.extensions.mySmile.SmileysWidget',array(                    		   	             
                	   'textareaId'=>'div-comment-' . $m['id'],             	   
                	 ));?>
            	 </div>
				<div id="comment-error-update-message-<?= $m['id']; ?>" class="alert alert-danger hide"></div>
				<br /><button id="btn-form-update-<?= $m['id']; ?>" style="display: none;" class="btn btn-primary" onclick="js: return sendFormCommentUpdate(<?= $m['id']; ?>);">Сохранить</button>
			</form>
			<div>
				<button onclick="deleteComment(<?= $m['id'] ?>, '<?= Yii::app()->controller->createUrl($urlDeleteStr, ['id'=>$m['id']]) ?>');" class="btn btn-default" title="Удалить"><i class="icon-trash"></i></button>
				<button onclick="updateComment(<?= $m['id'] ?>, '<?= Yii::app()->controller->createUrl($urlUpdateStr, ['id'=>$m['id']]) ?>');" class="btn btn-default" title="Изменить" data-toggle="modal" data-target="#modal-comment"><i class="icon-pencil"></i></button>
			</div>			
			<?php else: ?>
			Комментарий удален
			<?php endif; ?>
		</div>
	</div>	
<?php 
	}
?>
