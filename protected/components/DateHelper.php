<?php


class DateHelper extends CComponent
{
	
	private static $_monthNames = array(
			'01' => 'Январь',
			'02' => 'Февраль',
			'03' => 'Март',
			'04' => 'Апрель',
			'05' => 'Май',
			'06' => 'Июнь',
			'07' => 'Июль',
			'08' => 'Август',
			'09' => 'Сентябрь',
			'10' => 'Октябрь',
			'11' => 'Ноябрь',
			'12' => 'Декабрь',				
		);
	
	private static $_weekNames = array(
			1 => 'Понедельник',
			2 => 'Вторник',
			3 => 'Среда',
			4 => 'Четверг',
			5 => 'Пятница',
			6 => 'Суббота',
			7 => 'Воскресение'
	);
	
	
	/**
	 * Список месяцев (на рус.)
	 * @return string[]
	 * @author tvog17
	 */
	public static function arrayMonths()
	{
		return self::$_monthNames;
	}
	
	
	/**
	 * Наименование месяца по его номеру
	 * @param unknown $number
	 * @return NULL|string
	 */
	public static function monthByNumber($number)
	{
		return (isset(self::$_monthNames[$number]) ? self::$_monthNames[$number] : null);
	}
	
	
	/**
	 * Наименование дня недели по номеру
	 * @param int(1-7) $numer
	 * @return NULL|string
	 * @author tvog17
	 */
	public static function weekByNumber($numer)
	{
		return (isset(self::$_weekNames[$numer]) ? self::$_weekNames[$numer] : null);
	}
	
	
	/**
	 * Извлечение даты и времени (через пробел)
	 * @param string $date
	 * @return string
	 */
	public static function explodeDateTime($date)
	{
		if ($date == null)
			return null;
		return date('d.m.Y H:i:s',strtotime($date));
	}
	
	
	/**
	 * Извлечение только даты
	 * @param string $date
	 * @return string
	 */
	public static function explodeDate($date)
	{
		return date('d.m.Y',strtotime($date));
	}
	
	
	/**
	 * Извлечение только времени
	 * @param string $time
	 * @return string
	 */
	public static function explodeTime($time)
	{
		return date('H:i:s',strtotime($time));
	}
	
	
	/**
	 * Извлечение по формату php (@see php date function)
	 * @param string $date
	 * @param string $format
	 * @return string
	 */
	public static function explodeFormat($date, $format)
	{
		return date($format,strtotime($date));
	}
	
	
	/**
	 * Сбор даты и времени из отдельных переменных
	 * @param string $date
	 * @param string $time
	 * @return string
	 */
	public static function implodeDateTime($date, $time)
	{
		return date('d.m.Y H:i:s', strtotime($date.' '.$time));
	}
	
	
	
	
}