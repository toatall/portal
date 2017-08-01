<?php

class ConvertDate extends CApplicationComponent
{
	
	public static function find($date, $onlyDate = false)
	{
		if ($date == null) return null;
		
		return date('d.m.Y' . (($onlyDate==false) ? ' H:i:s' : ''), strtotime($date));
	}
	
}