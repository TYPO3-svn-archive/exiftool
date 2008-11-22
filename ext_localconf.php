<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');


$GLOBALS ['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass'][] = 'EXT:exiftool/class.tx_exiftool_tcemainprocdm.php:tx_exiftool_tcemainprocdm';

require_once(t3lib_extMgm::extPath('exiftool').'class.tx_exiftool_tcemainprocdm.php');


?>