<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');

t3lib_extMgm::addService($_EXTKEY,  'metaExtract' /* sv type */,  'tx_exiftool_sv1' /* sv key */,
		array(

			'title' => 'exiftool - iptc',
			'description' => 'Use external exiftool for reading meta-data.',

			'subtype' => 'image:iptc',

			'available' => TRUE,
			'priority' => 60,
			'quality' => 90,

			'os' => 'unix',
			'exec' => 'perl',

			'classFile' => t3lib_extMgm::extPath($_EXTKEY).'sv1/class.tx_exiftool_sv1.php',
			'className' => 'tx_exiftool_sv1',
		)
	);
?>