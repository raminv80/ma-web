<?php

class CSV
{
	function __construct(){
		
	}
	
	function arrayToCSV($array){
		$buf = "";
		foreach($array as $key => $val){
			if(is_array($val)){
				foreach($val as $key => $field){
					$buf.= "$field,";
				}
			}
			$buf.= "\r\n";
		}
		return $buf;
	}
}
