<?php
function getID($prefix)
{
$number = uniqid();
$varray = str_split($number);
$len = sizeof($varray);
$id = array_slice($varray, $len - 8, $len);
$id = implode(",", $id);
$id = str_replace(",", "", $id);
$id = "$prefix" . $id;
return $id;
}
