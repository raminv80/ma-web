<?php 

$dir = $_SERVER['DOCUMENT_ROOT'].'uploads/images';
$imgarray_jpg = rglob($dir, '*.jpg');
$imgarray_png = rglob($dir, '*.png');
$imgarray_gif = rglob($dir, '*.gif');
$imgarray=array_merge($imgarray_jpg,$imgarray_png,$imgarray_gif  ); 
$buf = 'var tinyMCEImageList = new Array(';

foreach($imgarray as $img){
	$filename = substr($img, strrpos($img, '/')+1,strlen($img));
	$url = substr($img, strlen($_SERVER['DOCUMENT_ROOT']));
	$buf .='["'.$filename.'","'.$url.'"],';
}
$buf = trim($buf,',').');';

// Make output a real JavaScript file!
header('Content-type: text/javascript'); // browser will now recognize the file as a valid JS file

// prevent browser from caching
header('pragma: no-cache');
header('expires: 0'); // i.e. contents have already expired

// Now we can send data to the browser because all headers have been set!
echo $buf;


/**
* Recursive version of glob
*
* @return array containing all pattern-matched files.
*
* @param string $sDir      Directory to start with.
* @param string $sPattern  Pattern to glob for.
* @param int $nFlags       Flags sent to glob.
*/
function rglob($sDir, $sPattern, $nFlags = NULL)
{
	
 //$sDir = escapeshellcmd($sDir);

 // Get the list of all matching files currently in the
 // directory.

 $aFiles = glob("$sDir/$sPattern", $nFlags);

 //$aFiles = glob("$sDir/*", GLOB_ONLYDIR);
 
 // Then get a list of all directories in this directory, and
 // run ourselves on the resulting array.  This is the
 // recursion step, which will not execute if there are no
 // directories.

 foreach (glob("$sDir/*", GLOB_ONLYDIR) as $sSubDir)
 {
  $aSubFiles = rglob($sSubDir, $sPattern, $nFlags);
  $aFiles = array_merge($aFiles, $aSubFiles);
 }

 // The array we return contains the files we found, and the
 // files all of our children found.
 if(!is_array($aFiles)){
 	$aFiles = array();
 }
 return $aFiles;
}  

?>