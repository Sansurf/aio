<?php
/**
 * Здесь собраны функции доступные глобально в проекте.
 * Этот файл подключен в:  __DIR__.'/index.php'
 */

/**
 * Позволяет преобразовать первую букву строки в строчный вид в кодировке UTF-8
 * @param string $str
 * @return string
 */
function ucfirst_utf8($str)
{
    return mb_substr(mb_strtoupper($str, 'utf-8'), 0, 1, 'utf-8') . mb_substr($str, 1, mb_strlen($str)-1, 'utf-8');
}

/**
 * Выводит содержание объекта в удобном виде
 * @param $arr
 */
function debug($arr)
{
    echo '<pre>' . print_r($arr, true) . '</pre>';
}