<?php

$array1  = array(2, 4, 56, 3, 3);
$current = 0;

foreach ($array1 as $element) {
    $current++;
    outputProgress($current, count($array1));
}
echo "<br>";

#Second progress
$array2  = array(2, 4, 66, 54);
$current = 0;

foreach ($array2 as $element) {
    $current++;
    outputProgress($current, count($array2));
}
echo "<br>";

function outputProgress($current, $total) {
    echo "<span style='position: absolute;z-index:$current;background:#FFF;'>" . round($current / $total * 100) . "% </span>";
    myFlush();
    sleep(1);
}

/**
 * Flush output buffer
 */
function myFlush() {
    echo(str_repeat(' ', 256));
    if (@ob_get_contents()) {
        @ob_end_flush();
    }
    flush();
}


?>