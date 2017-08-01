<?php
	
	if (count($model) == 0):
?>
	Нет данных
<?php
	endif;
	
	foreach ($model as $m)
	{
?>	
	<div class="row alert alert-<?= ($m->date_delete == null ? 'default' : 'danger') ?>">	
		<div id="comment-body-<?= $m->id ?>">			
			<div class="span2 "><?= User::profileByLogin($m->username) ?></div>
			<div class="span8">
				<?php if ($m->date_delete == null): ?>
				<h5><?= $m->date_create ?></h5>
				<p style="text-align: justify;"><?= $m->comment ?></p>
				<p><?= $m->buttonDelete ?></p>
				<?php else: ?>
				Комментарий удален
				<?php endif; ?>
			</div>			
		</div>
	</div>
<?php 
	}
?>
