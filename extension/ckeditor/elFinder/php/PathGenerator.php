<?php

/**
 * Получение пути для размещения файлов (изображений)
 * 
 * @author _alexeevich_@list.ru
 * 
 */
class PathGenerator
{
	
	// const
	const ROOT_PATH = '../../../../files_static/';
	const ROOT_URL  = '/files_static/';
	
	const SHARE_PATH = 'share/';


	
	// private field
	private $rootPath;
	private $rootUrl;
	
	private $pathFile;
	private $pathImage;




	/**
	 * Создание объекта PathGenerator
	 *
	 * @param string $rootPath
	 * @return PathGenerator
	 */
	public function __construct()
	{
		$this->rootPath = self::ROOT_PATH;	
		$this->rootUrl = self::ROOT_URL;
		$this->pathFile = $this->getPathFile();
		$this->pathImage = $this->getPathImage();
	}
	
	
	/**
	 * Магический метод получения защищенного поля
	 * @param string $name
	 * @return mixed
	 */
	public function __get($name)
	{
		if (isset($this->$name))
			return $this->$name;
	}
	
	
	
		
	/**
	 * Получения пути для размещения файлов
	 * Путь формируется на основе логина пользователя и его организации
	 * Если путь не существует, то попытка его создать. 
	 * В случае ошибок создания каталога возвращается общий каталог  
	 * 
	 * @return string
	 */
	private function getPathFile()
	{
		return $this->getPath('file/');
				
	}
	
	
	/**
	 * Получения пути для размещения изображений
	 * Путь формируется на основе логина пользователя и его организации
	 * Если путь не существует, то попытка его создать.
	 * В случае ошибок создания каталога возвращается общий каталог
	 * 
	 * @return string
	 */
	private function getPathImage()
	{
		return $this->getPath('image/');
	
	}
	
	
	/**
	 * Получение пути
	 * @param string $typeDir - тип каталога (file, image...)
	 * @return string
	 */
	private function getPath($typeDir)
	{
		// путь для файла
		$p = $typeDir . $this->getUserPath();
		
		// если нет данных о пользователе в сесии, то возвращаем общий кталаог (шару)
		if ($p === null)
			return $typeDir . self::SHARE_PATH;
	
		// проверка пути, если не существует, то попробуем создать
		if (!file_exists(self::ROOT_PATH . $p))
		{
			// если не удалось создать, то передаем общий каталог (шару)
			if (!mkdir(self::ROOT_PATH . $p, null, true))
			{
				return $typeDir . self::SHARE_PATH;
			}
		}
	
		return $p;
	}
		
	
	/**
	 * Получение пути в виде организации и логина пользователя из сессии
	 * @return string
	 */
	private function getUserPath()
	{
		try {
			$fileClass = $_SERVER['DOCUMENT_ROOT'] . '\\protected\components\userInfo\UserInfo.php';
			if (!file_exists($fileClass))
				return null;
			
			require_once $fileClass;
			
			$org = UserInfo::inst()->orgCode;
			$login = UserInfo::inst()->userLogin;
			
			if (!empty($org) && !empty($login))
				return $org . '/' .$login;
			
			return null;
				
		}
		catch (Exception $ex)
		{
			// except
		}		
	}
			
	
}