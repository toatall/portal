<?php
/**
 * @var $this CController
 * @var $model[] Conference
 * @var $notFoundMsg string
 */
?>
<?php
	if (count($model)>0):	
?>
	<tr>
		<td>
<?php 
		foreach ($model as $m):		
			$members = str_replace('<br />', ', ', $m->members_people);
?>
        <span style="font-size: 18px;">
            <a href="<?= $this->createUrl('conference/view',
                    array('id'=>$m->id)) ?>" class="show-modal-dialog" data-toggle="popover" title="<?= $m->theme ?>" data-content="<?= $members ?>" targets="_blank">
                <span class="label label-<?= (date('Hi',strtotime($m->date_start)) > date('Hi') ? 'default' : 'success') ?>">
                    <?= date('H:i',strtotime($m->date_start)) ?> <?= ($m->time_start_msk ? '(МСК)' : '') ?>
                </span>
            </a>
        </span>&nbsp;
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
