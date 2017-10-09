
<div class="">
	<hr />
	<h3><?= $modelInterview->title ?></h3>
	
	
	<?php 
	   $btnDisableExpiried = false;
	?>
	
	<?php if ($interviewExpiried): ?>
		<div class="alert alert-danger">
			Голосование завершено!
		</div>	
		<?php $btnDisableExpiried = true; ?>
	<?php elseif (count($countLikeUser) >= $modelInterview->count_like): ?>
		<div class="alert alert-danger">
			Вы проголосовали <b><?= $modelInterview->count_like; ?></b> раз!
		</div>	
		<?php $btnDisableExpiried = true; ?>
	<?php endif; ?>
	
	
	<?php
	   
	   foreach ($modelInterviewQuestion as $question):
	?>		
		<div class="row" style="padding-left:30px;">
			<div class="span3">        			
    			<p><?= $question['description'] ?></p>    			
			</div>
			<div class="span2" style="text-align: left;">
				<?php
				$voted = false;				
				if (isset($countLikeUser[$question['id']]))
				{
				   $voted = true;     
				}				
				?>
														
    			<button class="btn btn-default btn-like"<?= ($voted || $btnDisableExpiried) ? ' disabled="disabled"' : '' ?> id="like-btn-<?= $question['id'] ?>" onclick="likeBtn(<?= $question['id'] ?>);">
    				<span class="ic-like<?= (!$voted) ? '-not' : '' ?>"></span> Голосовать <span class="badge" style="background: #3d6899;"><?= $question['count_like']; ?></span>
    			</button>
    			
    		</div>
		</div>
	<?php        
	   endforeach;
	?>
	
</div>
<script type="text/javascript">
    
    function likeBtn(id)
	{
		$('#like-btn-' + id).html('<img src="/images/loading.gif" />');
		$.get('/interview/like?id=' + id + '&idInterview=<?= $modelInterview->id; ?>')
    		.done(function(data){
    			$('#like-btn-' + id).html(data);    			
    		})
    		.error(function(jqXHR){
    			$('#like-btn-' + id).html(jqXHR.statusText);    			
    		});
	}


    $(document).ready(function() {
        if ($('.fancybox').length)
        {
            $('.fancybox').fancybox();
        }
    });
    
</script>