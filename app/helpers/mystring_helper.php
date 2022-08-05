<?php 
if(!function_exists('rewrite_url')){
	function rewrite_url($canonical = '', $suffix = TRUE, $fulllink = FALSE){
		$domain = ($fulllink == TRUE)?BASE_URL:'';
		if(!empty($canonical)) return ($suffix == TRUE)?($domain.$canonical.HTSUFFIX):($domain.$canonical);
	}
}
if(!function_exists('replace')){
	function replace($str){
		if(empty($str)){
			return 0;
		}
	    return (int)str_replace('.','',$str);
	}
}
if(!function_exists('pre')){
	function pre($list, $exit = 'die'){
	    echo "<pre>";
	    print_r($list);
	    if($exit == 'die')
	    {
	        die();
	    }
	}
}
if(!function_exists('random')){
	function random($leng = 168, $char = FALSE){
		if($char == FALSE) $s = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*()';
		else $s = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
		mt_srand((double)microtime() * 1000000);
		$salt = '';
		for ($i=0; $i<$leng; $i++){
			$salt = $salt . substr($s, (mt_rand()%(strlen($s))), 1);
		}
		return $salt;
	}
}

if(!function_exists('operatotime')){
	function operatotime($time, $val = 0, $type = 'd'){
		$data['H'] = 0;
		$data['i'] = 0;
		$data['s'] = 0;
		$data['d'] = 0;
		$data['m'] = 0;
		$data['Y'] = 0;
		$data[$type] = $val;
		$dateint = mktime(gettime($time, 'H') - $data['H'], gettime($time, 'i') - $data['i'], gettime($time, 's') - $data['s'], gettime($time, 'm') - $data['m'], gettime($time, 'd') - $data['d'], gettime($time, 'Y') - $data['Y']);
		return date('d/m/Y', $dateint); // 02/12/2016	
	}
}


if(!function_exists('convert_time')){
	function convert_time($time = '', $type = '-'){
		if($time == ''){
			return '0000-00-00 00:00:00';
		};
		$time = str_replace( '/', '-', $time );
		$current = explode('-', $time);
		$time_stamp = $current[2].'-'.$current[1].'-'.$current[0].' 00:00:00';
		return $time_stamp;
	}
}

if(!function_exists('gettime')){
	function gettime($time, $type = 'H:i - d/m/Y'){
		if($type == 'datetime'){
			$type = 'Y-m-d H:i:s';
		}
		if($type == 'date'){
			$type = 'Y-m-d';
		}		
		return gmdate($type, strtotime($time) + 7*3600);
	}
}

if(!function_exists('settime')){
	function settime($time, $type = 'd-m-Y H:i:s'){
		if($type == 'date'){
			$type = 'd-m-Y';
		}		
		return gmdate($type, strtotime($time) + 7*3600);
	}
}


if(!function_exists('password_encode')){
	function password_encode($password = '', $salt = ''){
		return md5(md5(md5($password).$salt));
	}
}

if(!function_exists('merge_time')){
	function merge_time($date = '', $hour = ''){
		$formatData =  explode('/', $date);
		$date  = $formatData[2].'-'.$formatData[1].'-'.$formatData[0];
		$hour = $hour.':00';
		
		return $date.' '.$hour;
	}
}


if(!function_exists('get_day_of_week')){
	function get_day_of_week($date = '', $lang = 'Vi'){
		$d = new DateTime($date);

		$intlDays = [
		    'Monday' => 'Thứ 2',
		    'Tuesday' => 'Thứ 3',
		    'Wednesday' => 'Thứ 4',
		    'Thursday' => 'Thứ 5',
		    'Friday' => 'Thứ 6',
		    'Saturday' => 'Thứ 7',
		    'Sunday' => 'Chủ nhật'
		];

		if($lang == 'En'){
			$intlDays = [
			    'Monday' => 'Mon',
			    'Tuesday' => 'Tue',
			    'Wednesday' => 'Wed',
			    'Thursday' => 'Thu',
			    'Friday' => 'Fri',
			    'Saturday' => 'Sat',
			    'Sunday' => 'Sun'
			];
		}
		$dayofweek = $d->format('l');
		$dayofweek = str_replace(array_keys($intlDays), $intlDays, $dayofweek);

		return $dayofweek;
	}
}
