<?php 
	$persent = (date('H')*60+date('i') - 9*60) / (9*60) * 100;
	
	if ($persent>100)
	{
		$persent=100;
	}
	elseif ($persent < 0)
	{
		$persent=0;
	}
?>
<div class="progress progress-info progress-striped" style="width:250px;" title="Рабочее вреия">
	<div class="bar" style="width:<?= $persent ?>%;"></div>
</div>