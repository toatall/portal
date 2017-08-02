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

	function loadDataComments()
	{
		loadData('<?= Yii::app()->controller->createUrl('comment/index',array('id'=>$id)) ?>', '#container-comments-<?= $id ?>');
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
	function updateComment(url)
	{
		ajaxGET(url, null, '#modal-comment-div');
	}
	
	
		
</script>
<?= TbHtml::button('Добавить комментарий',['id'=>'btn-comment-create', 'data-toggle'=>'modal', 'data-target'=>'#modal-comment', 'class'=>'btn btn-primary'])  ?>
<script type="text/javascript">
	$('#btn-comment-create').on('click', function() {
		ajaxGET('<?= Yii::app()->controller->createUrl('comment/form', ['id'=>$id]) ?>', null, '#modal-comment-div');		
	});
</script><br /><br />
<div id="container-comments-<?= $id ?>"></div>

<hr />

<?php $form=$this->beginWidget('bootstrap.widgets.TbModal',array(
	'id'=>'modal-comment',
	'htmlOptions'=>[
		'data-backdrop'=>'static',
		'data-keyboard'=>false,		
	],
));
?>
	<div class="modal-header">
        <a class="close" data-dismiss="modal">&times;</a>
        <h4 id="modal-comment-title">Изменить комментарий</h4>
    </div>            
    <div class="modal-body" id="modal-comment-div"></div>
    <div class="modal-footer">
	    
	    <?php $this->widget('bootstrap.widgets.TbButton', array(
	    		'type'=>'primary',
	    		'label'=>'Сохранить',            
	            'htmlOptions'=>array('id'=>'btn-form-comment-submit'),
	        )); ?>
	    <script type="text/javascript">	    	
			$('#btn-form-comment-submit').on('click', function() {
				$('#comment-form').submit();
			});
	    </script>
    	<?php $this->widget('bootstrap.widgets.TbButton', array(
            'label'=>'Отмена',            
            'htmlOptions'=>array('data-dismiss'=>'modal'),
        )); ?>
        
    </div>   
<?php $this->endWidget(); ?>

