<?php
$fullpath = $fullpath.'/'.$img;

//echo $fullpath; exit;
if (!file_exists($fullpath)){
//	$asset = new AppAsset();
  //  $fullpath = $asset->sourcePath.'/img/no-photo.png';
	//echo $fullpath;
 }


//echo $fullpath;exit;

if(function_exists('finfo_open')&&$mode==0){
$finfo = finfo_open(FILEINFO_MIME_TYPE);

 
try {
    $type = finfo_file($finfo, $fullpath);
   
    if (!$type) {
         throw new Exception('Failed to open uploaded file');
    }
} catch (Exception $er) {
    die();
}
finfo_close($finfo);
} else if (function_exists('mime_content_type')&&$mode==0){
        $type = mime_content_type($fullpath);
} else {
  $imageData = @getimagesize($fullpath);
 	if (!empty($imageData['mime'])) {
  		$type = $imageData['mime'];
  }
}

//$type ='image/png' ;
//echo $fullpath.$type;

//exit;
$response = Yii::$app->getResponse();
$response->headers->set('Content-Type', $type);
$response->headers->set('Access-Control-Allow-Origin', '*');
$response->format = \yii\web\Response::FORMAT_RAW;
//header("Content-type: ".$type);
//header("Content-Disposition: filename=\"".$file['name']."\"");
//$file['rotate']=90;

$resize_w = isset($_GET['w']) && is_numeric($_GET['w'])?$_GET['w']:null;
$resize_h = isset($_GET['h']) && is_numeric($_GET['h'])?$_GET['h']:null;
$colored = isset($_GET['color']) && $_GET['color']=='false'?false:true;
$crop = isset($_GET['crop']) && $_GET['crop']==true?true:false;

if ($file['rotate'] == 0) {
 // readfile($fullpath);
  
  
    if (file_exists($fullpath)) {
      switch ($type)
      {
        case 'image/jpeg':
		 
         
		  if($resize_w && $resize_h){
         $source = img_resize($fullpath, false, $resize_w, $resize_h, $type, $crop);
		 !$colored?imagefilter($source,IMG_FILTER_GRAYSCALE):null;

		 }else{
		   list($w_orig, $h_orig) = getimagesize($fullpath);
		   $source = img_resize($fullpath, false, $w_orig, $h_orig, $type, $crop);
		  !$colored?imagefilter($source,IMG_FILTER_GRAYSCALE):null;
		
		 } 

		  !$colored?imagefilter($source,IMG_FILTER_GRAYSCALE):null;
		
          imagejpeg($source);
          break;
        case 'image/gif':
          
		  
		  if($resize_w && $resize_h){
         $source = img_resize($fullpath, false, $resize_w, $resize_h, $type, $crop);
		 !$colored?imagefilter($source,IMG_FILTER_GRAYSCALE):null;

		 }else{
		   list($w_orig, $h_orig) = getimagesize($fullpath);
		   $source = img_resize($fullpath, false, $w_orig, $h_orig, $type, $crop);
		  !$colored?imagefilter($source,IMG_FILTER_GRAYSCALE):null;
		
		 } 
		  
          !$colored?imagefilter($source,IMG_FILTER_GRAYSCALE):null;
          imagegif($source);
          break;
        case 'image/png':
		 
		 
		 if($resize_w && $resize_h){
         $source = img_resize($fullpath, false, $resize_w, $resize_h, $type, $crop);
		 !$colored?imagefilter($source,IMG_FILTER_GRAYSCALE):null;

		 }else{
		   list($w_orig, $h_orig) = getimagesize($fullpath);
		   $source = img_resize($fullpath, false, $w_orig, $h_orig, $type, $crop);
		  !$colored?imagefilter($source,IMG_FILTER_GRAYSCALE):null;
		
		 } 
       	!$colored?imagefilter($source,IMG_FILTER_GRAYSCALE):null; 
        imagepng($source);
        break;
		
		case 'application/pdf':
		  header("Content-type: application/pdf");
          header("Content-Disposition: inline; filename=".$file['filename']);
          @readfile($fullpath);
		  exit;
		  break;
        default:
         die('Invalid image type');
      }
	  
	  imagedestroy($source); // Free from memory
    }
   
   
} else {
   if (file_exists($fullpath)) {
      switch ($type)
      {
        case 'image/jpeg':
		 
         
		  if($resize_w && $resize_h){
         $source = img_resize($fullpath, false, $resize_w, $resize_h, $type, $crop);
		 !$colored?imagefilter($source,IMG_FILTER_GRAYSCALE):null;

		 }else{
		   list($w_orig, $h_orig) = getimagesize($fullpath);
		   $source = img_resize($fullpath, false, $w_orig, $h_orig, $type, $crop);
		  !$colored?imagefilter($source,IMG_FILTER_GRAYSCALE):null;
		
		 } 

		  !$colored?imagefilter($source,IMG_FILTER_GRAYSCALE):null;
		
          imagejpeg($source);
          break;
        case 'image/gif':
          
		  
		  if($resize_w && $resize_h){
         $source = img_resize($fullpath, false, $resize_w, $resize_h, $type, $crop);
		 !$colored?imagefilter($source,IMG_FILTER_GRAYSCALE):null;

		 }else{
		   list($w_orig, $h_orig) = getimagesize($fullpath);
		   $source = img_resize($fullpath, false, $w_orig, $h_orig, $type, $crop);
		  !$colored?imagefilter($source,IMG_FILTER_GRAYSCALE):null;
		
		 } 
		  
          !$colored?imagefilter($source,IMG_FILTER_GRAYSCALE):null;
          imagegif($source);
          break;
        case 'image/png':
		 
		 
		 if($resize_w && $resize_h){
         $source = img_resize($fullpath, false, $resize_w, $resize_h, $type, $crop);
		 !$colored?imagefilter($source,IMG_FILTER_GRAYSCALE):null;

		 }else{
		   list($w_orig, $h_orig) = getimagesize($fullpath);
		   $source = img_resize($fullpath, false, $w_orig, $h_orig, $type, $crop);
		  !$colored?imagefilter($source,IMG_FILTER_GRAYSCALE):null;
		
		 } 
       	!$colored?imagefilter($source,IMG_FILTER_GRAYSCALE):null; 
        imagepng($source);
		 
        break;
        default:
        die('Invalid image type');
      }
	  
	  imagedestroy($source); // Free from memory
    }
}




function img_resize($target, $newcopy=false, $w=null, $h=null, $type=null, $crop=false) {
    list($w_orig, $h_orig) = getimagesize($target);
	
	$w>=$w_orig?$w=$w_orig:null; 
	$h>=$h_orig?$h=$h_orig:null;
	
	
	
	//$crop=true;
	$r = $w_orig / $h_orig;
	if ($crop) {
        if ($w_orig > $h_orig) {
			
			$crop_x     =   ceil(($w_orig - $h_orig) / 2);
            $crop_y     =   0;
			
            $w_orig = ceil($w_orig-($w_orig*abs($r-$w/$h)));
			
        } else {
			$crop_x     =   0;
            $crop_y     =   ceil(($h_orig - $w_orig) / 2);
			
            $h_orig = ceil($h_orig-($h_orig*abs($r-$w/$h)));
		
        }
        //$newwidth = $w;
        //$newheight = $h;
    } else {
        if ($w/$h > $r) {
            $w = $h*$r;
        } else {
            $h = $w/$r;    
        }
		 $crop_y     =   0;
		 $crop_x     =   0;
    }
	
	
	
	
	/*
	
    $scale_ratio = $w_orig / $h_orig;
    if (($w / $h) > $scale_ratio) {
           $w = $h * $scale_ratio;
    } else {
           $h = $w / $scale_ratio;
    }
	
	*/
	
	
	
	
    $img = "";
	
	  
   
    if ($type == 'image/gif'){ 
	    $img = imagecreatefromgif($target);
	    $tci = imagecreatetruecolor($w, $h);
		imagecopyresampled($tci, $img, 0, 0, $crop_x, $crop_y, $w, $h, $w_orig, $h_orig);
        $background = imagecolorallocate($tci, 0, 0, 0); 
        imagecolortransparent($tci, $background);
       // $r = @imagegif($resized_image,$file_name);
	  
    } else if($type == 'image/png'){ 
	    $img = imagecreatefrompng($target);
	    $tci = imagecreatetruecolor($w, $h);
		//$tci = cropAlign($tci, $w, $w, $horizontalAlign = 'center', $verticalAlign = 'middle');
	    imagealphablending($tci, FALSE);
        imagesavealpha($tci, TRUE);
		imagecopyresampled($tci, $img, 0, 0, $crop_x, $crop_y, $w, $h, $w_orig, $h_orig);
		
		
		
       // $r = @imagepng($resized_image,$file_name); 
    } else { 
        $img = imagecreatefromjpeg($target);
		//$tci = imagescale($img , $w, $h);
	    $tci = imagecreatetruecolor($w, $h);
		//$tci = cropAlign($tci, $w, $h, $horizontalAlign = 'center', $verticalAlign = 'middle');
         
        imagecopyresampled($tci, $img, 0, 0, $crop_x, $crop_y, $w, $h, $w_orig, $h_orig);
		
		
      
        
    }

    $newcopy?imagejpeg($tci, $newcopy, 100):null;
	
	return $tci;
}



function cropAlign($image, $cropWidth, $cropHeight, $horizontalAlign = 'center', $verticalAlign = 'middle') {
    $width = imagesx($image);
    $height = imagesy($image);
    $horizontalAlignPixels = calculatePixelsForAlign($width, $cropWidth, $horizontalAlign);
    $verticalAlignPixels = calculatePixelsForAlign($height, $cropHeight, $verticalAlign);
    return imageCrop($image, [
        'x' => $horizontalAlignPixels[0],
        'y' => $verticalAlignPixels[0],
        'width' => $horizontalAlignPixels[1],
        'height' => $verticalAlignPixels[1]
    ]);
}


function calculatePixelsForAlign($imageSize, $cropSize, $align) {
    switch ($align) {
        case 'left':
        case 'top':
            return [0, min($cropSize, $imageSize)];
        case 'right':
        case 'bottom':
            return [max(0, $imageSize - $cropSize), min($cropSize, $imageSize)];
        case 'center':
        case 'middle':
            return [
                max(0, floor(($imageSize / 2) - ($cropSize / 2))),
                min($cropSize, $imageSize),
            ];
        default: return [0, $imageSize];
    }
}
?>
