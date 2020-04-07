<?php 
    /* @var $this CommentController */
    /* @var $model Comment[] */
   
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
?>
<ul class="media-list">
<?php
	foreach ($model as $m):
        ?>
    <li class="media" id="comment-body-<?= $m->id ?>">
        <?php if ($m->date_delete == null): ?>
        <div class="media-left">
            <img src="/images/user-nophoto.png" class="img-circle img-thumbnail" style="max-width: 80px;" />
        </div>
        <div class="media-body">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4><?= $m->user->fio ?> (<?= $m->username ?>)</h4>
                    <?= Yii::app()->dateFormatter->formatDatetime($m['date_create']) ?>
                </div>

                <div class="panel-body">
                    <div class="text-justiffy">
                        <form id="form-comment-<?= $m['id']; ?>" method="post" name="form-comment">
                            <input type="hidden" id="comment-hide-<?= $m['id']; ?>" name="Comment[comment]" />
                            <div contenteditable="false" class="" style="text-align: justify; margin-bottom: 5px;" id="div-comment-<?= $m['id']; ?>">
                                <?= $m['comment'] ?>
                            </div>
                            <div id="comment-smile-<?= $m['id'] ?>" style="display:none;">
                                <?php $this->widget('application.extensions.mySmile.SmileysWidget',array(
                                    'textareaId'=>'div-comment-' . $m['id'],
                                ));?>
                            </div>
                            <div id="comment-error-update-message-<?= $m['id']; ?>" class="alert alert-danger hide"></div>
                            <br />
                            <button id="btn-form-update-<?= $m['id']; ?>" style="display: none;" class="btn btn-primary" onclick="js: return sendFormCommentUpdate(<?= $m['id']; ?>);">Сохранить</button>
                        </form>
                    </div>
                    <hr />
                    <div>
                        <button onclick="updateComment(<?= $m['id'] ?>, '<?= $this->createUrl($urlUpdateStr, ['id'=>$m['id']]) ?>');" class="btn btn-default" title="Изменить" data-toggle="modal" data-target="#modal-comment">
                            <i class="glyphicon glyphicon-pencil"></i>
                        </button>
                        <button onclick="deleteComment(<?= $m['id'] ?>, '<?= $this->createUrl($urlDeleteStr, ['id'=>$m['id']]) ?>');" class="btn btn-default" title="Удалить">
                            <i class="glyphicon glyphicon-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <?php else: ?>
        <div class="media-body">
            <div class="alert alert-danger">Комментарий удален!</div>
        </div>
        <?php endif; ?>
    </li>

        <?php
    endforeach;
	    /*
?>	
	
	<div id="comment-body-<?= $m['id'] ?>" class="row panel panel-<?= ($m['date_delete'] == null ? 'default' : 'danger') ?>">
		<div class="col-md-1">
            <div class="thumbnail">
                <?= User::profileByLogin($m['username']) ?>
            </div>
        </div>
		<div class="col-md-10">
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
				<button onclick="deleteComment(<?= $m['id'] ?>, '<?= $this->createUrl($urlDeleteStr, ['id'=>$m['id']]) ?>');" class="btn btn-default" title="Удалить">
                    <i class="glyphicon glyphicon-trash"></i>
                </button>
				<button onclick="updateComment(<?= $m['id'] ?>, '<?= $this->createUrl($urlUpdateStr, ['id'=>$m['id']]) ?>');" class="btn btn-default" title="Изменить" data-toggle="modal" data-target="#modal-comment">
                    <i class="glyphicon glyphicon-pencil"></i>
                </button>
			</div>
			<?php else: ?>
			Комментарий удален
			<?php endif; ?>
		</div>
	</div>
<?php */

?>
</ul>
