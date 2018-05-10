<?php
	if (count($model)>0):	
?>
	<tr>
		<td>
<?php 
		foreach ($model as $m):		
			$members = str_replace('<br />', ', ', $m->members_people);
			
?>
				<small>
					<a href="<?php echo Yii::app()->controller->createUrl('conference/view',
							array('id'=>$m->id)) ?>" class="sw_dlg" data-toggle="popover" title="<?= $m->theme ?>" data-content="<?= $members ?>" targets="_blank">
						<span class="label label-<?= (date('Hi',strtotime($m->date_start)) > date('Hi') ? 'default' : 'success') ?>">
							<?= date('H:i',strtotime($m->date_start)) ?> <?= ($m->time_start_msk ? '(МСК)' : '') ?>
						</span>
					</a>
				</small>
<?php
		endforeach;	
?>
	</td>
		</tr>
<?php
	else:
?>
	<tr>
		<td><small><?= $notFoundMsg ?></small></td>
	</tr>
<?php
	endif;
