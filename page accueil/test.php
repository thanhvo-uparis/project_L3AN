<?php
                            $chars = '0123456789';
    $string = '';
    for($i=0; $i<8; $i++){
        $string .= $chars[rand(0, strlen($chars)-1)];
    }
    var_dump($string);
    //echo $string;
?>