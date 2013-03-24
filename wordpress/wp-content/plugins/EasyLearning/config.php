<?php
/*----------------------------------------------
	Plugin Configuration
----------------------------------------------*/

define('EL_BASE_PATH', untrailingslashit(dirname(__FILE__)));
define('EL_CORE_PATH', EL_BASE_PATH . '/Admin');

/*----------------------------------------------
	Includes
----------------------------------------------*/

// Include the post-types
require_once(EL_CORE_PATH . '/modules.php');

// Include the plugin classes
require_once(EL_CORE_PATH . '/el-easylearning.class.php');
require_once(EL_CORE_PATH . '/el-admin.class.php');
?>