<?php
// andrey3761

if (!defined('XOOPS_ROOT_PATH')) {
    die('XOOPS root path not defined');
}
$moduleDirName = basename( dirname( __FILE__ ) ) ;

$modversion['name'] = _MI_CHAT_NAME;
$modversion['version'] = 2.1;
$modversion['description'] = _MI_CHAT_DESC;
$modversion['credits'] = "radio-hobby.org";
$modversion['author'] = 'andrey3761';
$modversion['nickname'] = '';
$modversion['help'] = 'page=help';
$modversion['license'] = 'GNU GPL 2.0';
$modversion['license_url'] = "www.gnu.org/licenses/gpl-2.0.html/";
$modversion['official'] = 0;
$modversion['image'] = "images/slogo.png";
$modversion['dirname'] = "chat";
$modversion['dirmoduleadmin'] = 'Frameworks/moduleclasses';
$modversion['icons16'] = 'Frameworks/moduleclasses/icons/16';
$modversion['icons32'] = 'Frameworks/moduleclasses/icons/32';

// О модуле
$modversion["module_website_url"] = "xoops.ws";
$modversion["module_website_name"] = "XOOPS.WebSite";
$modversion["release_date"] = "2011/08/13";
$modversion["module_status"] = "Final";
$modversion["author_website_url"] = "radio-hobby.org";
$modversion["author_website_name"] = "andrey3761";
$modversion['min_php']='5.2';
$modversion['min_xoops']="2.3";

// Файл базы данных
$modversion['sqlfile']['mysql'] = "sql/mysql.sql";
//$modversion['sqlfile']['postgresql'] = "sql/pgsql.sql";

// Таблицы
$modversion['tables'][0] = "chat_delmsg";
$modversion['tables'][1] = "chat_message";
$modversion['tables'][2] = "chat_online";

// Имеет админку
$modversion['hasAdmin'] = 1;
$modversion['adminindex'] = "admin/index.php";
$modversion['adminmenu'] = "admin/menu.php";

// Меню
$modversion['hasMain'] = 1;
// Отображать меню в админке
$modversion['system_menu'] = 1;

// Search
$modversion['hasSearch'] = 0;

// Comments
$modversion['hasComments'] = 0;

// Templates
$i = 1;
$modversion['templates'][$i]['file'] = 'chat_index.html';
$modversion['templates'][$i]['description'] = '';
$i++;
$modversion['templates'][$i]['file'] = 'chat_admin_index.html';
$modversion['templates'][$i]['description'] = '';
$i++;

// Конфигурация
$i = 1;
$modversion['config'][$i]['name'] = 'interval';
$modversion['config'][$i]['title'] = '_MI_CHAT_INTERVAL';
$modversion['config'][$i]['description'] = '_MI_CHAT_INTERVALDSC';
$modversion['config'][$i]['formtype'] = 'textbox';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = 5000;
$i++;
$modversion['config'][$i]['name'] = 'timedelmsg';
$modversion['config'][$i]['title'] = '_MI_CHAT_TIMEDELMSG';
$modversion['config'][$i]['description'] = '_MI_CHAT_TIMEDELMSGDSC';
$modversion['config'][$i]['formtype'] = 'textbox';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = 60;
$i++;
// Начальный лимит сообщений
$modversion['config'][$i]['name'] = 'startlimitmsg';
$modversion['config'][$i]['title'] = '_MI_CHAT_STARTLIMITMSG';
$modversion['config'][$i]['description'] = '_MI_CHAT_STARTLIMITMSGDSC';
$modversion['config'][$i]['formtype'] = 'textbox';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = 50;
$i++;
// Через сколько удалить пользователя из списка онлайн
$modversion['config'][$i]['name'] = 'ttlonline';
$modversion['config'][$i]['title'] = '_MI_CHAT_TTLONLINE';
$modversion['config'][$i]['description'] = '_MI_CHAT_TTLONLINEDSC';
$modversion['config'][$i]['formtype'] = 'textbox';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = 15;
$i++;
// Через сколько поставить польователя статус "неактивен"
$modversion['config'][$i]['name'] = 'timesleep';
$modversion['config'][$i]['title'] = '_MI_CHAT_TIMESLEEP';
$modversion['config'][$i]['description'] = '_MI_CHAT_TIMESLEEPDSC';
$modversion['config'][$i]['formtype'] = 'textbox';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = 60;
$i++;

// Notification
$modversion['hasNotification'] = 0;
