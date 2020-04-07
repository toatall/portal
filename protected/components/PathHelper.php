<?php

/**
 * Class PathHelper
 */
class PathHelper
{
    /**
     * Подготовка пути
     * @param $path изначальный путь
     * @param array $params параметры для подстановки
     * @return string|string[]
     */
    public static function preparePath($path, $params = [])
    {
        foreach ($params as $key => $value)
        {
            $path = str_replace($key, $value, $path);
        }
        return $path;
    }

}