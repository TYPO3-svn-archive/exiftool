<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2006 Benoit Norrin <info@dlcube.com>
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
*/


class tx_exiftool_tcemainprocdm 
{

	function processDatamap_postProcessFieldArray ($status, $table, $id, &$fieldArray, &$reference) 
	{
		global $FILEMOUNTS, $BE_USER, $TYPO3_CONF_VARS;
// TODO: take care of UTF-8/latin1 conversion if needed - exiftool param: -L
		if ($table == 'tx_dam') {
			if ($status == 'update') {
			// t3lib_div::debug(dirname(dirname($GLOBALS['_SERVER']['SCRIPT_FILENAME'])));
			// TODO: check if this assumtion is right - especially for fe-editing!
			$absolutePath = dirname(dirname($GLOBALS['_SERVER']['SCRIPT_FILENAME']));
		
				// t3lib_div::debug($id);
				// Änderungen: t3lib_div::debug($fieldArray);
				$file = $absolutePath.'/'.$reference->checkValue_currentRecord['file_path'].$reference->checkValue_currentRecord['file_name'];
				t3lib_div::debug($file);
				// checkValue_currentRecord			
				// t3lib_div::debug($reference);
				// [datamap][tx_dam][$id]
				$this->info['exec'] = 'perl';
				// $service_conf['properties']['exiftoolparams'] = mb_strlen($service_conf['properties']['exiftoolparams']) > 0?$service_conf['properties']['exiftoolparams']:'  -S -iptc:all ';
				$this->info['params'] = ' '.t3lib_extMgm::extPath('exiftool').'exiftool/exiftool '.$service_conf['properties']['exifparams'].' ';
				// TODO: read from configuration
				$page_id = 44;
				$service_conf = t3lib_BEfunc::getModTSconfig($page_id,'tx_exiftool_sv1');
				
				$this->info['params'] .= ' -keywords="'.addslashes($fieldArray['keywords']).'"';
				$cmd = t3lib_exec::getCommand($this->info['exec']).$this->info['params'].' '.$file.'';
				$output = array();
				$ret = -1;
				exec($cmd, $output, $ret);
				t3lib_div::debug($cmd);
				
			}
		}
		/*
		 
  [historyRecords] => Array
        (
            [tx_dam:3] => Array
                (
                    [oldRecord] => Array
                        (
                            [keywords] => test93
                        )

                    [newRecord] => Array
                        (
                            [keywords] => test94
                        )

                )

        )

		 
		 */
		
		
	}

}

?>