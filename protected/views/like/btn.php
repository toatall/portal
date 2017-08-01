<button class="btn btn-default btn-like" id="like-btn-<?= $id ?>"></button>

<script type="text/javascript">

	function loadAjax(urlStr)
	{
		$('#like-btn-<?= $id ?>').html('<img src="/images/loading.gif" />');
		$.ajax({
			url: urlStr
		})
		.done(function(data){
			$('#like-btn-<?= $id ?>').html(data);
		})
		.error(function(jqXHR){
			$('#like-btn-<?= $id ?>').html(jqXHR.statusText);
		});
	}
	
	// добавление/удаление лайка
	$('#like-btn-<?= $id ?>').on('click',function(){
		loadAjax('<?= Yii::app()->controller->createUrl('like/add', array('id'=>$id)) ?>');
		this.blur();
	});

	// загрузка лайков
	loadAjax('<?= Yii::app()->controller->createUrl('like/count', array('id'=>$id)) ?>');
	
</script>
