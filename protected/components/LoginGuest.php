<?php

/**
 * Класс для получения информации о пользователе
 * 
 * Информация о пользователе берется из переменной $_SERVER['AUTH_USER']
 * В связи с чем получение информации возможно только если включена Windows аутентефикация
 * 
 * @author oleg
 * @version 02.12.2016
 * @since 1.0
 *
 */
class LoginGuest
{
	
    /**
     * Логин пользователя
     * @var string
     */
	private $LoginName = 'guest';
	
	/**
	 * Логин с доменом
	 * @var string
	 */
	private $FullLogin = 'guest';
	
	/**
	 * ФИО
	 * @var string
	 */	
	private $AD_displayName = '';
	
	/**
	 * Отдел
	 * @var string
	 */
	private $AD_department = '';
	
	/**
	 * Должность
	 * @var string
	 */
	private $AD_post = '';
	
	/**
	 * Код организации
	 * @var string
	 */
	private $AD_company = '';
	
	/**
	 * ip-адрес клиента
	 * @var string
	 */
	private $IP = '';
	
	/**
	 * Имя хоста клиента
	 * @var string
	 */
	private $Host = '';	
	
	/**
	 * Создание экземпляра класса
	 * @return LoginGuest
	 */
	public static function create()
	{
		return new self;		
	}
	
	/**
	 * Запуск процедуры получения информации о пользователе
	 */
	public function run()
	{
		if (session_id() !== null)
			session_start();
		
		// если пользователь уже прошел данную процедуру, то пропускаем
		if (isset($_SESSION['auth_login']))
		{
			return;
		}
		
		// информация о хосте и ip-адресе клиента
		$this->getRemoteAddress();
		
		// если не существует переменной AUTH_USER, т.е. выключен режим windows-аутентефикации в IIS
		// то заносим информацию как пользователь-гость
		if (!isset($_SERVER['AUTH_USER']))
		{			
			return $this->saveSession();
		}
		
		$authUser = $_SERVER['AUTH_USER'];
		$authUser = split('[\]', $authUser);
		if (count($authUser) < 2) return $this->saveSession();
		$this->LoginName = $authUser[1];
		$this->FullLogin = $_SERVER['AUTH_USER'];
		
		// получение информации из AD
		$this->getADInformation();
		
		// сохранение данных в сессии
		$this->saveSession();
	}
	
	/**
	 * Получение информации о IP и хосте пользователя
	 * @uses run()
	 */
	private function getRemoteAddress()
	{
		$this->IP = $_SERVER['REMOTE_ADDR'];
		$this->Host = gethostbyaddr($this->IP);
	}
	
	/**
	 * Поиск пользователя в AD и сохранение информации
	 * @uses run()
	 */
	private function getADInformation()
	{
		require_once 'LDAPInfo.php';
		$infoAD = LDAPInfo::getInfoAD($this->LoginName);
		if (!$infoAD)
			return $this->saveSession();
		
		$this->AD_displayName = isset($infoAD['displayname'][0]) ? $infoAD['displayname'][0] : $this->AD_displayName;
		$this->AD_department = isset($infoAD['department'][0]) ? $infoAD['department'][0] : $this->AD_department;
		$this->AD_post = isset($infoAD['title'][0]) ? $infoAD['title'][0] : $this->AD_post;
		$this->AD_company = isset($infoAD['company'][0]) ? $infoAD['company'][0] : $this->AD_company;
		
	}
	
	
	/**
	 * Получение организации пользователя из его логина
	 * @return string|NULL
	 */
	private function getOrganization()
	{
		if ((strlen($this->LoginName) >= 4))
		{
			$org = substr($this->LoginName, 0, 4);
			if (is_numeric($org))
				return $org;
		}		
		return null;
	}
	
		
	/**
	 * Сохранение сессии
	 * @uses run()
	 */
	private function saveSession()
	{		
		$_SESSION['auth_login'] = $this->LoginName;
		$_SESSION['auth_login_full'] = $this->FullLogin;
		$_SESSION['auth_fio'] = $this->AD_displayName;
		$_SESSION['auth_department'] = $this->AD_department;
		$_SESSION['auth_post'] = $this->AD_post;
		$_SESSION['auth_company'] = $this->AD_company;
		$_SESSION['auth_IP'] = $this->IP;
		$_SESSION['auth_host'] = $this->Host;
		
		$org = $this->getOrganization();
		if ($org !== null)
			$_SESSION['organization'] = $org;
	}
	
	
	
}