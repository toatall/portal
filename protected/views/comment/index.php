<h3>Комментарии <button class="btn btn-default" onclick="loadData('<?= Yii::app()->controller->createUrl('comment/index',array('id'=>$id)) ?>', '#container-comments-<?= $id ?>');"><i class="icon-refresh"></i></button></h3>


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

	$(document).ready(function(){
		loadData('<?= Yii::app()->controller->createUrl('comment/index',array('id'=>$id)) ?>', '#container-comments-<?= $id ?>');
		loadData('<?= Yii::app()->controller->createUrl('comment/form',array('id'=>$id)) ?>', '#container-comment-form-<?= $id ?>');
	});	

	

	// удаление комментария
	function deleteComment(id, url)
	{
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
		
</script>
<div id="container-comments-<?= $id ?>"></div>

<hr />

<div id="container-comment-form-<?= $id ?>"></div>
