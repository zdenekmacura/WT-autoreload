<?php
/**
 * @package    Joomla.Site
 *
 * @copyright  Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
header("Access-Control-Allow-Origin: *");

/**
 * Define the application's minimum supported PHP version as a constant so it can be referenced within the application.
 */
define('JOOMLA_MINIMUM_PHP', '5.3.10');

if (version_compare(PHP_VERSION, JOOMLA_MINIMUM_PHP, '<'))
{
	die('Your host needs to use PHP ' . JOOMLA_MINIMUM_PHP . ' or higher to run this version of Joomla!');
}

// Saves the start time and memory usage.
$startTime = microtime(1);
$startMem  = memory_get_usage();

/**
 * Constant that is checked in included files to prevent direct access.
 * define() is used in the installation folder rather than "const" to not error for PHP 5.2 and lower
 */
define('_JEXEC', 1);

if (file_exists(__DIR__ . '/defines.php'))
{
	include_once __DIR__ . '/defines.php';
}

if (!defined('_JDEFINES'))
{
	define('JPATH_BASE', __DIR__);
	require_once JPATH_BASE . '/includes/defines.php';
}

require_once JPATH_BASE . '/includes/framework.php';

// Set profiler start time and memory usage and mark afterLoad in the profiler.
JDEBUG ? $_PROFILER->setStart($startTime, $startMem)->mark('afterLoad') : null;

defined('_JEXEC') or die;
// Include the syndicate functions only once
require_once dirname(__FILE__) . '/helper.php';

$post = BrrrHelper::getPostArray();
$get = BrrrHelper::getGetArray();

if (isset($post["loadpage"])) {
	$page = BrrrHelper::loadPage($post["loadpage"]);
	exit(json_encode($page));
}

if (isset($post["prihlaseni"])) {
	$userInfos = BrrrHelper::checkLogin($post["email"],$post["password"]);
	if (sizeof($userInfos) == 1  ) {
		$userInfo = $userInfos[0];
		$userInfo['status'] = 'success';
		exit(json_encode($userInfo));
	} else {
		exit(json_encode(array("status" => "failed")));
	}
}

if (isset($get["registrace"])) {
	$getmaxuserid = BrrrHelper::getMaxUserId();
	$newuserresult = BrrrHelper::createAccount($get["fullname"],$get["email"],$get["password"]);
	if ($getmaxuserid + 1 == $newuserresult) {
		$status = array("status" => "success","userid" => $newuserresult,); 
                exit(json_encode($status));
	} else {
		echo "fail";
	}

}

if (isset($get["getwifitemps"]) && ($get["getwifitemps"]=='yes') && isset($get["userid"])) {
	$TSbyUserIdList = BrrrHelper::getTSByUserId($get["userid"]);
        echo json_encode($TSbyUserIdList);
}



 
/*$password = modHelloWorldHelper::getPassword($params);
/$userid = modHelloWorldHelper::getUserID($params);*/
//echo $userid + "userid" + $get["data"];
/*$post = modHelloWorldHelper::getPostArray();
$registeredWT = modHelloWorldHelper::getRegisteredWT($userid);
if ($userid > 0 ) {
	$wifiid = modHelloWorldHelper::getWifiID($params,$userid);
	if ( strlen($wifiid) > 0 ) $resultsetwifiid = modHelloWorldHelper::setWifiID($params,$userid,$wifiid);
	if ( strlen($post["wifiid-local"]) > 0 ) {
	$resultsetwifilocation = modHelloWorldHelper::setWifiSetup($post,$registeredWT['0']->ssid);
	}
	if ( strlen($registeredWT['0']->ssid) > 0 ) {
	 $resultWifiIdLocal = modHelloWorldHelper::getWifiIdLocal($registeredWT['0']->ssid);
	}
}*/
?>


