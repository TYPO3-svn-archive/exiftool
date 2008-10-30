<?php

echo 'Hello World';

$output = array();

// exec('ls -l', $output, $return);
exec('./exiftool -ver', $output, $return);

echo '<pre>'.print_r($output, true).'</pre>';
// string exec  ( string $command  [, array &$output  [, int &$return_var  ]] )

?> 
