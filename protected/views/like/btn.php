<!--button class="btn btn-default btn-like" id="like-btn-<?= $id ?>"></button-->
<div id="like-btn-<?= $id ?>"></div>


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
		loadAjax('<?= $this->createUrl('like/add', array('id'=>$id)) ?>');
		this.blur();
	});

	// загрузка лайков
	loadAjax('<?= $this->createUrl('like/count', array('id'=>$id)) ?>');
	
</script>
