<ul class="thumbnails">	
	<?php foreach ($model as $m): ?>
	<li class="span3 thumbnail text-center">
		<div class="page-header">
			<strong><?= $m->periodName ?></strong>
		</div>		
		<div class="thumb-rating">		
			<?= $m->fileDownload ?>		
		</div>
	</li>
	<?php endforeach; ?>
</ul>
