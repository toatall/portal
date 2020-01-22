<?php 
    /* @var $this CController */
    /* @var $id int */
    /* @var $urlIndex string */
    /* @var $urlUpdate string */
    /* @var $urlForm string */

    $urlIndex = isset($urlIndex) ? $urlIndex : Yii::app()->controller->createUrl('comment/index',array('id'=>$id));
    $urlUpdate = isset($urlUpdate) ? $urlUpdate : Yii::app()->controller->createUrl('comment/update',array('id'=>'_id_'));
    $urlForm = isset($urlForm) ? $urlForm : Yii::app()->controller->createUrl('comment/form', ['id'=>$id]);
?>


<h4>Комментарии
	<button class="btn btn-default" onclick="loadData('<?= $urlIndex ?>', '#container-comments-<?= $id ?>');">
		<i class="icon-refresh"></i></button>
</h4>


<script type="text/javascript">

	// загрузка комментариев
	function loadData(urlStr, container)
	{
		$(container).html('<img src="/images/loading.gif" />');
		$.ajax({
			url: urlStr
		})
		.done(function(data){
			$(container).html(data);
		})
		.error(function(jqXHR){
			$(container).html('<div class="alert alert-danger">' + jqXHR.statusText + '</div>');
		});
	}

	function loadDataComments()
	{
		loadData('<?= $urlIndex ?>', '#container-comments-<?= $id ?>');
	}
		
	$(document).ready(function(){
		loadDataComments();
	});	

	

	// удаление комментария
	function deleteComment(id, url)
	{
		if (!confirm('Вы уверены, что хотите удалить комментарий?'))
			return;
		
		$.ajax({
			url: url								
   	   	})
		.done(function(data){			
			if (data == 'OK')
			{
				$('#comment-body-' + id).hide();
			}
			else
			{
				alert(data);
			}
			// or refresh
		})
		.error(function(jqXHR){
			alert('Ошибка удаления! ' + jqXHR.statusText);
		});
	}

	// изменение комментария
	function updateComment(id, url)
	{		
	    // 1 подгрузить форму
		$('#div-comment-' + id).attr('contenteditable', true);
		$('#div-comment-' + id).addClass('uneditable-input span4');
		$('#div-comment-' + id).css('height', '100px');
		$('#div-comment-' + id).focus();
		$('#btn-form-update-' + id).show();		
		$('#comment-smile-' + id).show();
	}

	// направление формы для обновления комментария
	function sendFormCommentUpdate(id)
	{

		$('#btn-form-update-' + id).prop('disabled', true);
		
		$('#comment-hide-'+id).val($('#div-comment-'+id).html());        	
    	var dataForm = $('#form-comment-'+id).serialize();
    	
		var urlPost = '<?= $urlUpdate ?>';
		urlPost = urlPost.replace('_id_',id);
								 		
   		$.ajax({
   			type: 'POST',
			url: urlPost,
			data: dataForm
	   	})
   	   	.done(function(data){

   	   	   	if (data == 'OK')
	   	   	{
	   	   		loadDataComments();	   	   		
	   	   	}
	   	   	else
	   	   	{
	   	   		$('#comment-error-update-message-'+id).show();
				$('#comment-error-update-message-'+id).html(data);
	   	   	}

   	   		$('#btn-form-update-' + id).prop('disabled', false);
   	   	
		})
		.error(function(jqXHR){
			alert('Произошла ошибка! ' + jqXHR.statusText);
		});
   		
	   	return false;		
	}
	
	
		
</script>
<script type="text/javascript">
	$('#btn-comment-create').on('click', function() {
		ajaxGET('<?= $urlForm ?>', null, '#modal-comment-div');		
	});
</script><br /><br />


<div id="container-comments-<?= $id ?>"></div>
<hr />
<?php if (UserInfo::inst()->userAuth): ?>
<div id="container-comment-form"></div>
<?php else: ?>
<div class="alert alert-warning">
	Вы не можете оставлять комментарии!
</div>
<?php endif; ?>

<script type="text/javascript">	
	ajaxGET('<?= $urlForm ?>', {}, '#container-comment-form');
</script>

<hr />


