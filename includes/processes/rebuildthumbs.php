<?php
if($_SERVER['REMOTE_ADDR'] == '150.101.230.130'){
  ini_set('max_execution_time', 3600);
  include 'includes/class/crop.image.to.square.class.php';
  $count = 1;
  $dir = $_SERVER['DOCUMENT_ROOT']."uploads";
  if ($handle = opendir($dir)) {
  	while (false !== ($entry = readdir($handle))) {
  		if ($entry != "." && $entry != "..") {
  			try{
  			$crop = new Crop_Image_To_Square;
  			$crop->source_image = 'uploads/'.$entry;
  			$crop->save_to_folder = 'thumbs/';
  			$crop->save_to_folderm = 'medium/';
  			/* left, center or right; If none is set, center will be used as default */
  			$process = $crop->crop('center');
  			if($process['result'])
  			{
  				echo ''.$count.'<br/>';
  				//echo 'The rectangle image (<em>'.$process['new_file_path'].'</em>) was cropped.<br/>';
  				$count++;
  			}
  			}catch(Exception $e){
  				echo 'Exception throw: '.$e.'<br/>';
  				continue;
  			}
  		}
  	}
  	closedir($handle);
  }
}
