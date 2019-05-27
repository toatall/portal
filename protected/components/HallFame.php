<?php

/**
 * Доска почета
 * @author alexeevich
 */
class HallFame {

    /**
     * Uri-ссылка, где размещены материалы 
     * @var string
     */
    const URL_IMAGES = '/repository/board_fame/';

    /**
     * Каталог, где размещены материалы
     * @var string
     */
    const PATH_IMAGES = 'E:/WWW/portal' . self::URL_IMAGES;

    /**
     * Интервал смены картинок
     * @var integer
     */
    const INTERVAL_CHANGE = 10;

    /**
     * Допустимые расширения изображений
     * @var array
     */
    const FIND_EXTENSIONS = [
        'JPG',
        'JPEG',
        'BMP',
        'PNG',
        'GIF',
    ];

    /**
     * Год
     * @var string
     */
    private $year;

    /**
     * Файлы изображений
     * @var array
     */
    private $files;

    /**
     * Список годов
     * @var array
     */
    private $years = [];

    /**
     * Поиск изображений за указанный период и вывод их в виде списка
     * @param string $year год
     */
    public function __construct($year = null) {
        if (!$this->scanDirYears())
            return false;

        $this->year = $year;

        // определение периода
        if (empty($year)) {
            $this->year = date('Y') - 1;
        } elseif (!isset($this->years[$this->year])) {
            $this->year = 'default';
        }

        // поиск файлов
        $this->loadFiles($this->year);
    }

    /**
     * Поиск каталогов с годами
     * @return boolean
     * @uses __construct()
     */
    private function scanDirYears() {
        $path = self::PATH_IMAGES;

        if (file_exists($path)) {
            $dh = opendir($path);
            while (false !== ($filename = readdir($dh))) {
                if ($filename === '.' || $filename === '..')
                    continue;

                if (is_dir($path . $filename)) {
                    $this->years[$filename] = $filename;
                }
            }
        }
        return (count($this->years) > 0);
    }

    /**
     * Список изображений
     * @return array
     * @uses SiteController:actionHallFame()
     */
    public function showPhoto() {
        return $this->files;
    }

    /**
     * Поиск изображений по маске $this->dateFilter
     * Если не удолось найти изображение, то нужно подгрузить файлы
     */
    private function loadFiles($year) {
        $this->files = []; // очистка файлов

        if (!file_exists(self::PATH_IMAGES . $year . '/')) {
            $year = 'default/';
        }

        $path = self::PATH_IMAGES . $year . '/'; // формирование пути

        if (file_exists($path)) {
            $dh = opendir($path);
            while (false !== ($filename = readdir($dh))) {
                if ($filename === '.' || $filename === '..')
                    continue;

                $ext = explode('.', $filename);
                if (is_array($ext)) {
                    $ext = end($ext);
                }

                if (in_array(strtoupper($ext), self::FIND_EXTENSIONS)) {
                    $this->files[] = [
                        'image' => self::URL_IMAGES . $year . '/' . iconv('windows-1251', 'utf-8', $filename),
                        'label' => null,
                    ];
                }
            }
        } else {
            //exit($path);
        }
    }

    /**
     * Список годов
     * @return array
     * @uses SiteController::actionHallFame()
     */
    public function getYears() {
        return $this->years;
    }

    /**
     * Год
     * @return string
     * @uses SiteController::actionHallFame()
     */
    public function getYear() {
        return $this->year;
    }

}
