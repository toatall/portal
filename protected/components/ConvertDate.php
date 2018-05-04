<?php

/**
 * @author 8600-90331
 * @deprecated
 */
class ConvertDate extends CApplicationComponent
{
	
    /**
     * Use DateHelper
     * @param unknown $date
     * @param boolean $onlyDate
     * @return NULL|string
     * @deprecated
     */
	public static function find($date, $onlyDate = false)
	{
		if ($date == null) return null;
		
		return date('d.m.Y' . (($onlyDate==false) ? ' H:i:s' : ''), strtotime($date));
	}
	
}