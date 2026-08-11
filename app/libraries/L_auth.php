<?php defined('BASEPATH') OR exit('No direct script access allowed');

class L_auth {

	function date_now()
    {
    	date_default_timezone_set('UTC');
    	return gmdate('Y-m-d', time()+60*60*7);
    }

    function date_time_now()
    {
    	date_default_timezone_set('UTC');
    	return gmdate('Y-m-d H:i:s', time()+60*60*7);
    }

    function date_upload()
    {
    	date_default_timezone_set('UTC');
    	return gmdate('Ymd', time()+60*60*7);
    }

    function file_size($filesize)
    {
		if(is_numeric($filesize)){
		$decr = 1024; $step = 0;
		$prefix = array('Byte','KB','MB','GB','TB','PB');
			while(($filesize / $decr) > 0.9){
			    $filesize = $filesize / $decr;
			    $step++;
			}
			return round($filesize,2).' '.$prefix[$step];
		}else{
			return 'NaN';
		}
	}

	function filter_number($data)
    {
    	$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data ?? '');
        return preg_replace("/[^0-9 ]/", "", $data);
    }

    function filter_text($data)
    {
    	$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data ?? '');
        return preg_replace("/[^a-zA-Z ]/", "", $data);
    }

    function filter_text_number($data)
    {
    	$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data ?? '');
        return preg_replace("/[^a-zA-Z0-9]/", "", $data);
    }

	function filter_text_random($data) {
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data ?? '');
		return preg_replace("/[^a-zA-Z0-9.-_ ]/", "", $data);
	}

	function filter_input($data) {
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data ?? '');
		return $data;
	}

	function filter_array($input){
        $ganti = array('[', ']');
        $baru = array('', '');
        return str_replace($ganti, $baru, $input);
    }

    function rand_str1($length = 10, $specialCharacters = TRUE)
	{
		$digits = '';
		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
		if($specialCharacters === TRUE){$chars .= "!_?=/&+,.";}
		for($i=0;$i<$length; $i++){
			$x = mt_rand(0, strlen($chars) -1);
			$digits .= $chars[$x];
		}
		return $digits;
	}

	function rand_str2($length = 10){
		$randstr = '';
		srand((double)microtime()*1000000);
		for($i=0;$i<$length;$i++){
		    $n = rand(48,120);
		    while (($n >= 58 && $n <= 64) || ($n >= 91 && $n <= 96)){
		        $n = rand(48,120);
		    }
		    $randstr .= chr($n);
		}
		return $randstr;
	}

	function encrypt_pass($password, $salt)
	{
		return sha1(md5($password).$salt);
	}

}