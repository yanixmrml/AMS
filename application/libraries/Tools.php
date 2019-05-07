<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tools {
    
    public $days = array("M","T","W","H","F","S","A");
    
    // Encrypt Function
    function mc_encrypt($encrypt, $key){
        $encrypt = serialize($encrypt);
        $iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_CBC), MCRYPT_DEV_URANDOM);
        $key = pack('H*', $key);
        $mac = hash_hmac('sha256', $encrypt, substr(bin2hex($key), -32));
        $passcrypt = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $encrypt.$mac, MCRYPT_MODE_CBC, $iv);
        $encoded = base64_encode($passcrypt).'|'.base64_encode($iv);
        return $encoded;
    }
    
    // Decrypt Function
    function mc_decrypt($decrypt, $key){
        $decrypt = explode('|', $decrypt.'|');
        $decoded = base64_decode($decrypt[0]);
        $iv = base64_decode($decrypt[1]);
        if(strlen($iv)!==mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_CBC)){ return false; }
        $key = pack('H*', $key);
        $decrypted = trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, $decoded, MCRYPT_MODE_CBC, $iv));
        $mac = substr($decrypted, -64);
        $decrypted = substr($decrypted, 0, -64);
        $calcmac = hash_hmac('sha256', $decrypted, substr(bin2hex($key), -32));
        if($calcmac!==$mac){ return false; }
        $decrypted = unserialize($decrypted);
        return $decrypted;
    } 
    
    
    function makephoto($photo)
    {
        $dir = BASEPATH;
        $directory = str_replace('system','pics',$dir);
        $TEMPFILE = tempnam($directory,"TMP");
        $TEMPFILE .= ".jpg";
        if($photo)
        {
            $blob_data = ibase_blob_info($photo);
            $BLOBID = ibase_blob_open($photo);
            $FILEID = fopen($TEMPFILE,"w");
            $image = ibase_blob_get($BLOBID,$blob_data[0]);
            while ($data = ibase_blob_get($BLOBID,$blob_data[0])) 
            {
                $image .= $data;
            }
            fputs($FILEID,$image);
            fclose($FILEID);
            ibase_blob_close($BLOBID);
            //$TEMPFILE = "" . $TEMPFILE;
            $ROW = explode("\\",$TEMPFILE); // \ - for Windows , / - Unix
            if(count($ROW)>=2){
				$src = $ROW[count($ROW)-2] . '/' . $ROW[count($ROW)-1];
            }else{
				$src = null;
			}
			return $src;
        }
    }
    
    function formatDateTime($datetime,$format){
	
	if(isset($datetime) && $datetime!=NULL && $datetime!="" && $datetime!="0000-00-00 00:00:00"){
	    $st = strtotime($datetime);
	    $date = new DateTime("@$st",new DateTimeZone("UTC"));
	    return date_format($date,$format); // "D, F j, Y"
	}else{
	    return "N / A";
	}
    }
    
    function formatDate($date_param,$format){
	if(isset($date_param) && $date_param!=NULL && $date_param!="" && $date_param!="0000-00-00 00:00:00"){
	    $st = strtotime($date_param);
	    $date = new DateTime("@$st",new DateTimeZone("UTC"));
	    return date_format($date,$format); // "D, F j, Y"
	}else{
	    return "N / A";
	}
    }
    
    function days_diff($d1,$d2){
	$x1 = $this->days($d1);
	$x2 = $this->days($d2);
	
	if($x1 && $x2){
	    return abs($x1 - $x2);
	}
    }
    
    function days($x){
	if(get_class($x)!= 'DateTime'){
	    $st = strtotime($x);
	    $x = new DateTime("@$st",new DateTimeZone("UTC"));
	}
	$y = $x->format('Y') - 1;
	$days = $y * 365;
	$z = (int) ($y/4);
	$days += $z;
	$z -(int)($y / 100);
	$days -= $z;
	$z = (int) ($y / 400);
	$days += $z;
	$days += $x->format('z');
	return $days;
    }
    /*
    function getImage($width, $height, $image){
        $im = imagecreatefromstring($image);
        $new_image = imagecreatetruecolor($width, $height);
        $x = imagesx($im);
        $y = imagesy($im);
        imagecopyresampled($new_image, $im, 0, 0, 0, 0, $width, $height, $x, $y);
        imagedestroy($im);
        imagejpeg($new_image, null, 85);
        
        $this->output->set_header(“Content-type: image/jpg“);
        $this->output->set_output($new_image); 
        return $new_image;
    }*/
}