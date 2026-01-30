<?php

function truncate($string, $length='' , $k = ''){
	if( $length == '') $length = 320;
	if( $k == '') $k = '...';
    settype($string, 'string');
    settype($length, 'integer');
    for($a = 0; $a < $length AND $a < strlen($string); $a++){
        $output .= $string[$a];
    }
        if( strlen($string) > $length)
	   $output .= $k;
    return($output);
}

?>
