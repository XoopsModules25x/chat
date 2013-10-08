<?php

// Автор: andrey3761
// Копирайт: xoops.ws

// Функции

/**
 * Возварщает конфигурацию модуля
 *
 * @param string $option	module option's name
 */
// Устарела
function chat_getmoduleoption($option, $repmodule='chat')
{
	global $xoopsModuleConfig, $xoopsModule;
	static $tbloptions= Array();
	if(is_array($tbloptions) && array_key_exists($option,$tbloptions)) {
		return $tbloptions[$option];
	}

	$retval = false;
	if (isset($xoopsModuleConfig) && (is_object($xoopsModule) && $xoopsModule->getVar('dirname') == $repmodule && $xoopsModule->getVar('isactive'))) {
		if(isset($xoopsModuleConfig[$option])) {
			$retval= $xoopsModuleConfig[$option];
		}
	} else {
		$module_handler =& xoops_gethandler('module');
		$module =& $module_handler->getByDirname($repmodule);
		$config_handler =& xoops_gethandler('config');
		if ($module) {
		    $moduleConfig =& $config_handler->getConfigsByCat(0, $module->getVar('mid'));
	    	if(isset($moduleConfig[$option])) {
	    		$retval= $moduleConfig[$option];
	    	}
		}
	}
	$tbloptions[$option]=$retval;
	return $retval;
}

function chat_memsg($text = '', $uname = '') {
	$ret = strpos($text, $uname) !== false ? true : false;
	return $ret;
}

?>