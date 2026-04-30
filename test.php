<?php
$html = file_get_contents('http://roti.test/');
$pos = strpos($html, 'const products =');
if ($pos !== false) {
    echo substr($html, $pos, 800);
} else {
    echo "NO PRODUCTS STRING FOUND";
}
