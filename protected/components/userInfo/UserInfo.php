<?php

require_once dirname(__FILE__) . '\UserAuth.php';


/**
 * Получение информации о пользователе
 * из массива $_SERVER и ActiveDirectory
 * и сохранение в сесии
 * 
 * Разработано под web-сервер IIS с включением
 * Windows-аутентификации (имя пользователя должно
 * находится в массиве $_SERVER['AUTH_USER'])
 * 
 * @author tvog17
 * @version 12.07.2017
 */
class UserInfo 
{
	
	// экземпляр класса UserAuth
	private static $instance = null;
	
	
	// создание / возвращение $instance
	public static function inst()
	{
		if (self::$instance === null)
			self::$instance = new UserAuth();
		return self::$instance;
	}
	
	
}