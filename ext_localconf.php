<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

t3lib_extMgm::addPItoST43($_EXTKEY, 'pi1/class.tx_skinpreview_pi1.php', '_pi1', 'includeLib', 0);

$TYPO3_CONF_VARS['SC_OPTIONS']['ext/templavoila_framework/class.tx_templavoilaframework_lib.php']['assignSkinKey'][] = 'EXT:skinpreview/class.tx_skinpreview_assignskin.php:&tx_skinpreview_assignSkin->main';

?>