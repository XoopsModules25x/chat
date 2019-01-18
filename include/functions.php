<?php

// Автор: andrey3761
// Копирайт: xoops.ws

// Функции

/**
 * Возварщает конфигурацию модуля
 *
 * @param string $option module option's name
 * @param string $repmodule
 * @return bool
 */
// Устарела
/**
 * @param string $option
 * @param string $repmodule
 * @return bool|mixed
 */
function chat_getmoduleoption($option, $repmodule = 'chat')
{
    global $xoopsModuleConfig, $xoopsModule;
    static $tbloptions = [];
    if (is_array($tbloptions) && array_key_exists($option, $tbloptions)) {
        return $tbloptions[$option];
    }

    $retval = false;
    if (isset($xoopsModuleConfig)
        && (is_object($xoopsModule) && $xoopsModule->getVar('dirname') == $repmodule
            && $xoopsModule->getVar('isactive'))) {
        if (isset($xoopsModuleConfig[$option])) {
            $retval = $xoopsModuleConfig[$option];
        }
    } else {
        /** @var \XoopsModuleHandler $moduleHandler */
        $moduleHandler = xoops_getHandler('module');
        $module        = $moduleHandler->getByDirname($repmodule);

        /** @var \XoopsConfigHandler $configHandler */
        $configHandler = xoops_getHandler('config');
        if ($module) {
            $moduleConfig = $configHandler->getConfigsByCat(0, $module->getVar('mid'));
            if (isset($moduleConfig[$option])) {
                $retval = $moduleConfig[$option];
            }
        }
    }
    $tbloptions[$option] = $retval;

    return $retval;
}

/**
 * @param string $text
 * @param string $uname
 * @return bool
 */
function chat_memsg($text = '', $uname = '')
{
    $ret = false !== mb_strpos($text, $uname) ? true : false;

    return $ret;
}
