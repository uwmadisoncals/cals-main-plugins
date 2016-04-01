<?php

$echo = $_GET['echo'];
if ($_GET['cals_uwds-q']!=''){ 
	//request was sent from directory search form (user either pressed "Enter" or JS is disabled in browser)
	$name = urlencode($_GET['cals_uwds-q']);

} else if ($_GET['s']!=''){    
	//request was sent from site search form 
	$name = urlencode($_GET['s']);
	
} else if ($_GET['q']!=''){
	//request was sent from site Google CSE search form 
	$name = urlencode($_GET['q']);
}
else {
	//request was sent via AJAX or shortcode
	$name = $_GET['name'];
}

$handle = @fopen("http://www.wisc.edu/directories/json/?name=".$name."&division=COLLEGE%20OF%20AGRICULTURAL%20%26%20LIFE%20SCIENCES", "r");

if ($handle) {
    while (!feof($handle)) {
        $buffer = fgets($handle, 4096);
        
		if($echo==1){ 
			echo $buffer;
		} else {
			return $buffer;
		}
    }
    fclose($handle);
}
?>
