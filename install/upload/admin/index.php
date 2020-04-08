<?php
// Version
define('VERSION', '3.0.3.1');

// Configuration
if (is_file('config.php')) {
	require_once('config.php');
}

// Install
if (!defined('DIR_APPLICATION')) {
	header('Location: ../install/index.php');
	exit;
}

//VirtualQMOD
require_once('../vqmod/vqmod.php');
$readOnlySystem = (getenv('LAMBDA_ENVIRONMENT') === 'true');
VQMod::bootup(false, true, $readOnlySystem);

// VQMODDED Startup
require_once(VQMod::modCheck(DIR_SYSTEM . 'startup.php'));

start('admin');
