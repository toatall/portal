<ul class="thumbnails">	
	<?php foreach ($model as $m): ?>
	<li class="col-sm-3 col-md-2 thumbnail text-center" style="margin-right: 15px;">
		<div class="page-header">
			<strong><?= $m->periodName ?></strong>
		</div>		
		<div class="thumb-rating">		
			<?= $m->fileDownload ?>		
		</div>
	</li>
	<?php endforeach; ?>
</ul>
