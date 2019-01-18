<?php

// Автор: andrey3761
// Копирайт: xoops.ws

// defined('XOOPS_ROOT_PATH') || die('Restricted access');

// Подключаем языковой файл
xoops_loadLanguage('error', $GLOBALS['xoopsModule']->dirname());

// Возвращает массив 'вес приоритета' => 'название приоритета'
return [
    1 => _MD_CHAT_ERROR_1,
    2 => _MD_CHAT_ERROR_2,
    3 => _MD_CHAT_ERROR_3,
    4 => _MD_CHAT_ERROR_4,
    5 => _MD_CHAT_ERROR_5,
];
