<?php

/**
 * Хелпер для работы с датами и веременем
 * @author oleg
 * @see CComponent
 */
class DateHelper extends CComponent
{
    /**
     * Формат даты
     * @var string
     */
    public $formatDate = 'd.m.Y';

    /**
     * Формат времени
     * @var string
     */
    public $formatTime = 'H:i:s';

    /**
     * Формат даты и времени
     * @var string
     */
    public $formatDateTime = 'd.m.Y H:i:s';

    /**
     * Привести к формату даты
     * @param $date
     * @return false|string
     * @throws Exception
     */
    public function asDate($date = null)
    {
        if ($date == null) {
            return date($this->formatDate, time());
        }
        return date($this->formatDate, strtotime($date));
    }

    public function maxDate()
    {
        $date = new DateTime();
        $date->setDate(2038, 1, 1);
        return $date->format($this->formatDate);
    }

    /**
     * Привести к формату времени
     * @param $time
     * @return false|string
     */
    public function asTime($time)
    {
        if ($time == null) {
            return null;
        }
        return date($this->formatTime, strtotime($time));
    }

    /**
     * Привести к формату дата, время
     * @param $dateTime
     * @return false|string
     */
    public function asDateTime($dateTime)
    {
        if ($dateTime == null) {
            return null;
        }
        return date($this->formatDateTime, strtotime($dateTime));
    }

    /**
     * Приветсти к формату дата и текущее время
     * @param $date
     * @return false|string
     */
    public function asDateWithCurrentTime($date)
    {
        if ($date == null) {
            return  null;
        }
        return date($this->formatDateTime, strtotime($date . ' ' . date($this->formatTime)));
    }

    /**
     * Привести к формату дата и верхняя граница времени (23:59:59)
     * @param $date
     * @return false|string
     */
    public function asDateWithHighTime($date)
    {
        if ($date == null) {
            return null;
        }
        return date($this->formatDateTime, strtotime($date . ' 23:59:59'));
    }

    /**
     * Сравнить 2 даты в формате дата
     * @param $date1
     * @param $date2
     * @return bool
     */
    public function equalsDate($date1, $date2)
    {
        if ($date1 == null || $date2 == null) {
            return false;
        }
        return date($this->formatDate, strtotime($date1)) == date($this->formatDate, strtotime($date2));
    }

    public function dateDiff($date1, $date2)
    {
        $d1 = date_create($date1);
        $d2 = date_create($date2);
        return date_diff($d1, $d2);
    }

    public function dateDiffDays($date1, $date2 = null)
    {
        if ($date2 == null)
        {
            $date2 = date($this->formatDateTime, strtotime('now'));
        }
        $diff = $this->dateDiff($date1, $date2);
        return $diff->d;
    }

    /**
     * init
     */
    public function init() { }




	/**
	 * Спсиок месяцев
	 * @var array
	 * @uses arrayMonths()
	 */
	private static $_monthNames = [
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
    ];
	
	/**
	 * Список дней недели
	 * @var array
	 * @uses weekByNumber()
	 */
	private static $_weekNames = [
        1 => 'Понедельник',
        2 => 'Вторник',
        3 => 'Среда',
        4 => 'Четверг',
        5 => 'Пятница',
        6 => 'Суббота',
        7 => 'Воскресение'
	];
	
	/**
	 * Список месяцев (на рус.)
	 * @return string[]
	 * @author tvog17
	 * @todo Where used?
	 * @deprecated
	 */
	public static function arrayMonths()
	{
		return self::$_monthNames;
	}
	
	/**
	 * Наименование месяца по его номеру
	 * @param int $number день месяца
	 * @return null|string
	 * @uses Conference::getDateStartFormat()
	 */
	public static function monthByNumber($number)
	{
		return (isset(self::$_monthNames[$number]) ? self::$_monthNames[$number] : null);
	}
	
	/**
	 * Наименование дня недели по номеру
	 * @param int $numer день недели
	 * @return NULL|string
	 * @uses Conference::getDateStartFormat()
	 */
	public static function weekByNumber($numer)
	{
		return (isset(self::$_weekNames[$numer]) ? self::$_weekNames[$numer] : null);
	}
	
	/**
	 * Преобразование даты и времени 
	 * в формат ДД.ММ.ГГГГ ЧЧ:ММ:СС
	 * @param string $date
	 * @return string
	 * @uses Comment::afterFind()
	 * @uses Department::afterFind()
	 * @uses Menu::afterFind()
	 * @uses Conference::afterFind()
	 * @uses News::afterFind()
	 * @uses Ifns::afrerFind()
	 * @uses Group::afterFind()
	 * @uses RatingData::afterFind()
	 * @uses RatingMain::afterFind()
	 * @uses Telephone::afterFind()
	 * @uses Tree::afterFind()
	 * @uses User::afterFind()
	 * @uses Module::afterFind()
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
	 * @uses Conference::explodeDateTime()
	 */
	public static function explodeDate($date)
	{
		return date('d.m.Y', strtotime($date));
	}
	
	/**
	 * Извлечение только времени
	 * @param string $time
	 * @return string
	 * @uses Conference::explodeDateTime()
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
	 * @todo where used?
	 * @deprecated
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
	 * @uses Conference::beforeSave()
	 */
	public static function implodeDateTime($date, $time)
	{
		return date('d.m.Y H:i:s', strtotime($date.' '.$time));
	}
	
	
	
	
}