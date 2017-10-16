<?php

class HallFame
{
    // каталог с файлами
    const PATH_IMAGES = 'repository/board_fame/';
    // интервал смены картинок
    const INTERVAL_CHANGE = 10; 
    // периоды
    /*const YEARS = [
        '2015'=>'2015',
        '2016'=>'2016',
    ];*/
    // расширения
    const FIND_EXTENSIONS = [
        'JPG',
        'JPEG',
        'BMP',
        'PNG',
        'GIF',
    ];
    
    private $year;
    private $files; 
    
    private $years = [];
    
    
    /**
     * Поиск изображений за указанный период и вывод их в виде списка
     */ 
    public function __construct($year=null)
    {
        if (!$this->scanDirYears())
            return false;
        
        $this->year = $year;
        
        // определение периода
        if (empty($year))
        {
            $this->year = date('Y')-1;
        }
        elseif (!isset($this->years[$this->year]))
        {            
            return false;
        }
        
        // поиск файлов
        $this->loadFiles($this->year);
    }
    
    
    private function scanDirYears()
    {
        $path = self::PATH_IMAGES;
        
        if (file_exists($path))
        {
            $dh  = opendir($path);
            while (false !== ($filename = readdir($dh))) 
            {
                if ($filename === '.' || $filename === '..')
                    continue;
                
                if (is_dir($path . $filename))
                {
                    $this->years[$filename] = $filename;
                }
            }
        }
        
        return (count($this->years) > 0);
    }
    
    public function showPhoto()
    {    
        return $this->files;
    }
    
    
    /**
     * Поиск изображений по маске $this->dateFilter
     * Если не удолось найти изображение, то нужно подгрузить файлы
     * 
     */
    private function loadFiles($year)
    {
        $this->files = []; // очистка файлов
        
        $path = self::PATH_IMAGES . $year . '/'; // формирование пути

        if (file_exists($path))
        {               
            $dh  = opendir($path);
            while (false !== ($filename = readdir($dh))) 
            {
                if ($filename === '.' || $filename === '..')
                    continue;
                
                $ext = end(explode('.', $filename));
                    
                if (in_array(strtoupper($ext), self::FIND_EXTENSIONS))
                {
                    $this->files[] = [
                        'image' => '/' . $path . iconv('windows-1251', 'utf-8', $filename),
                        'label' => 'Доска почета  ' . $year,
                    ];
                }
            }                       
        }
    }
    
    public function getYears()
    {
        return $this->years;
    }
    
    
    public function getYear()
    {
        return $this->year;
    }
    
    
    
}