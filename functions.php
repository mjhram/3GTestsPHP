<?php
function isEnglish($str) {
	$preg = "/[a-zA-Z]/";  
    if( preg_match($preg,$str) > 0) {
    	return true;
    } else {
    	return false;
    }  	
}
function makeAngle($ang) {
		if($ang != 360)
			$ang = $ang % 360;			

		for($k=1;$k <= floor($ang/90);$k++) {
			$a_ang[] = 90;
		}
		if($ang % 90 != 0)
			$a_ang[] = $ang % 90;
		if(!isset($a_ang)) {
			$a_ang[] = 0;
		}
		
		return $a_ang;
}
function createSmallArc($r, $a1, $a2) {
  // Compute all four points for an arc that subtends the same total angle
  // but is centered on the X-axis
  $a1 = $a1 * 22.0/(7.0*180.0);
  $a2 = $a2 * 22.0/(7.0*180.0);
  
  $a = ($a2 - $a1) / 2.0;
                
  $x4 = $r * Cos($a);
  $y4 = $r * Sin($a);
  $x1 = $x4 ;
  $y1 = -$y4;
  
  $k = 0.5522847498;
  $f = $k * Tan($a);
  
  $x2 = $x1 + $f * $y4;
  $y2 = $y1 + $f * $x4;
  $x3 = $x2;
  $y3 = -$y2;
                
  // Find the arc points actual locations by computing x1,y1 and x4,y4
  // and rotating the control points by a + a1
                
  $ar = $a + $a1;
  $cos_ar = Cos($ar);
  $sin_ar = Sin($ar);

  $points[1] = $r * Cos($a1);
  $points[2] = $r * Sin($a1);
  $points[3] = $x2 * $cos_ar - $y2 * $sin_ar;
  $points[4] = $x2 * $sin_ar + $y2 * $cos_ar;
  $points[5] = $x3 * $cos_ar - $y3 * $sin_ar;
  $points[6] = $x3 * $sin_ar + $y3 * $cos_ar;
  $points[7] = $r * Cos($a2);
  $points[8] = $r * Sin($a2);
	return $points;                  
}

function checkDateFormat($date){  
//match the format of the date  
  if (preg_match ("/^([0-9]{4})-([0-9]{2})-([0-9]{2})$/", $date, $parts)) {    
  //check weather the date is valid of not        
	if(checkdate($parts[2],$parts[3],$parts[1]))          
		return true;        
	else         
		return false;  
  }  else    
		return false;
}

function myIsempty($v) {
	return $v;
}

function make_thumb($src,$dest,$desired_width)
{
  /* read the source image */
  $source_image = imagecreatefromjpeg($src);
  $width = imagesx($source_image);
  $height = imagesy($source_image);
  
  $desired_height = floor($height*($desired_width/$width));
  
  $virtual_image = imagecreatetruecolor($desired_width,$desired_height);
  
  imagecopyresized($virtual_image,$source_image,0,0,0,0,$desired_width,$desired_height,$width,$height);
  
  imagejpeg($virtual_image,$dest);
}

function checkEmail($str)
{
	return preg_match("/^[\.A-z0-9_\-\+]+[@][A-z0-9_\-]+([.][A-z0-9_\-]+)+[A-z]{1,4}$/", $str);
}


function send_mail($from,$to,$subject,$body, $headers)
{
	/*$headers = '';
	$headers .= "From: $from\n";
	$headers .= "Reply-to: $from\n";
	$headers .= "Return-Path: $from\n";
	$headers .= "Message-ID: <" . md5(uniqid(time())) . "@" . $_SERVER['SERVER_NAME'] . ">\n";
	$headers .= "MIME-Version: 1.0\n";
	$headers .= "Date: " . date('r', time()) . "\n";*/

	return(mail($to,$subject,$body,$headers));
}
?>