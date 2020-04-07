<?php
/**
 * @var $modelConference Conference
 * @var $modelVksUfns Conference
 * @var $modelVksFns
 */
?>
<style>
	div.popover {
		width:300px;
	}
</style>

<ul class="dropdown-menu dropdown-menu-main">
	<li class="nav-header">Собрания</li>
	<li>
		<p style="padding: 0 15px;">   
	  	<?php 
			echo $this->renderPartial('_today_rows', [
		  		'model'=>$modelConference,
		  		'notFoundMsg'=>'Сегодня собраний нет',
	  		], true);  
		?>
		</p>
	</li>
	<li class="nav-header">ВКС с ФНС</li>
	<li>
		<p style="padding: 0 15px;">   
	  	<?php 
			echo $this->renderPartial('_today_rows', [
		  		'model'=>$modelVksFns,
		  		'notFoundMsg'=>'Сегодня видеоконференций нет',
	  		], true);  
		?>
		</p>
	</li>
	<li class="nav-header">ВКС с УФНС</li>
	<li>
		<p style="padding: 0 15px;">   
	  	<?php 
			echo $this->renderPartial('_today_rows', [
		  		'model'=>$modelVksUfns,
		  		'notFoundMsg'=>'Сегодня видеоконференций нет',
	  		], true);  
		?>
		</p>
	</li>
</ul>			

<script type="text/javascript">	
	$(function() {
		$("[data-toggle='popover']").popover({trigger: 'hover'});
	});
</script>

<?php
