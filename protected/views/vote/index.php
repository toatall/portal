<?php
    $this->breadcrumbs=array(
    	'Голосование',
    );
?>
<div class="content content-color">
    <h1 class="page-header">Голосование</h1>
    <table class="table table-bordered">
    	<tr>
    		<th>#</th>
    		<th>Наименование</th>
    		<th>Статус</th>
      	</tr>
    <?php
        $i = 1;
       
        foreach ($models as $model)
        {
            $endVote = $model->endVote;
            $link = Yii::app()->createUrl('vote/view', ['id'=>$model->id]);
            
            ?>
    	<tr class="<?= $endVote ? "warning" : "success" ?>">
    		<td><a href="<?= $link ?>" class="sw_dlg"><?= $i ?></a></td>
    		<td><a href="<?= $link ?>" class="sw_dlg"><?= $model->name ?></a></td>
    		<td>
    			<a href="<?= $link ?>" class="sw_dlg">
    		<?php if ($endVote) { ?> 
    			<strong>Голосование завершено!</strong>  <?php 
            } else { ?>    			
            	Голосование проводится с <strong><?= $model->date_start ?></strong> по <strong><?= $model->date_end ?></strong>
            <?php } ?>
            	</a>
            </td>
    	</tr>	                
            <?php  
            $i++;
        }
    ?>
    </table>
</div>