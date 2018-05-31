<div id="content-vote">
	
<?php
   /**
    * @param VoteMain $modelMain
    * @param VoteQuestion[] $modelQuestion
    * @param VoteQuestion $model
    */ 
    
    if ($modelMain->endVote)
    {
    ?>
		<div class="alert"><strong>Голосование завершено!</strong></div>        
        <?php 
    }
    else
    {
        ?>
        <div class="alert alert-info">
        	Голосование проводится с <strong><?= $modelMain->date_start ?></strong> по <strong><?= $modelMain->date_end ?></strong>
        </div>
        <?php     
    }
    
?>
	<?php if (strlen($modelMain->description)>0): ?>
	<div class="well well-small"><?= $modelMain->description ?></div>
	<?php endif; ?>
<?php 
    
    // голосование
    if (!$modelMain->isVoted)
    {
        
        ?>
        <form id="form_vote" action="<?= Yii::app()->createUrl('vote/view', ['id'=>$modelMain->id]) ?>">
        <?php 
        
        foreach ($modelQuestion as $model) {
            ?>
           
        	<label class="<?= ($modelMain->multi_answer ? "checkbox" : "radio") ?>">
        		<input type="<?= ($modelMain->multi_answer ? "checkbox" : "radio") ?>" name="vote[]" value="<?= $model->id ?>" />
        		<?= $model->text_question ?>        		
        	</label>
           
            <?php         
        }
            
        ?>
        	<div class="form-actions">    
        		<button type="submit" class="btn btn-default">Голосовать</button>
        	</div>
        </form>
    <?php 
    
    }   
    // результаты голосования
    else    
    {
        ?>
        <table class="table">
        <?php 
        foreach ($modelQuestion as $model) {
            ?>
            <tr>
            	<td>
            		<span class="badge badge-info" title="Количество голосов"><?= $model->count_votes ?></span>
            		<?= $model->text_question ?>
                    <div class="progress" style="width: 500px; margin-top:10px;">
                    	<div class="bar" style="width: <?= round($model->count_votes / ($modelMain->countMax == 0 ? 1 : $modelMain->countMax) * 100) ?>%;"></div>
                    </div>
                 </td>                 
            </tr>            
            <?php 
        }
        ?>
        </table>
        Всего проголосовало <strong><?= $modelMain->countAnswer ?></strong>
        <?php 
    }
    
    ?>
</div>    

<script type="text/javascript">

	// load buttons
	$('#form_vote').on('submit',function() {
		var ser = $(this).serialize();

		if (ser == "")
			return false;

		ajaxGET($(this).attr('action'), ser, '#content-vote');
    	
    	return false;
	});
	
</script>