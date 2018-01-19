<?php
/**
 * 获取日期数组或天数
 * @param  DateTime  $date 当前时间
 * @param  integer $type 返回数据结构  1:天数  2:日期数组
 * @return string|array
 */
function getDays($date,$type = 1){
	$temp = explode('-', $date);
	$year = $temp[0];
	$month = $temp[1];
	if (in_array($month,array('1', '3', '5', '7', '8', '01', '03', '05', '07', '08', '10', '12'))) {//月份为31天的
		$days = '31';
	}elseif ($month == 2) {
		//判断是否是闰年
		if ($year % 400 == 0 || ($year & 4 == 0 && $year % 100 !== 0)) {
			$days = '29';
		}else {
			$days = '28';
		}
	}else {//月份为30天的
		$days = '30';
	}

	if ($type == 2) {
		for ($i=1; $i <= $days ; $i++) {
			$result[] = $year . '-' . $month . '-' . $i;
		}
	}else {
		$result = $days;
	}
	return $result;
}
/**
 * 把秒数转换为时分秒
 * @param  integer $time 秒数
 * @return string
 */
function secToTime($time){
	$result = '00:00:00';
	if ($time > 0 ) {
		$hour = floor($time/3600);
		$minute = floor(($time - 3600 * $hour)/60);
		$second = floor((($times-3600 * $hour) - 60 * $minute) % 60);
		$result = $hour . ':' . $minute . ':' . $second;
	}
	return $result;
}

/**
 * 友好时间
 * @param  integer $time
 * @return bool|string
 */
function getFrindlyDate($time){
	if (!$time)
			return false;
	$fdate = '';
	$d = time() - intval($time);
	$ld = $time - mktime(0, 0, 0, 0, 0, date('Y')); //得出年
	$md = $time - mktime(0, 0, 0, date('m'), 0, date('Y')); //得出月
	$byd = $time - mktime(0, 0, 0, date('m'), date('d') - 2, date('Y')); //前天
	$yd = $time - mktime(0, 0, 0, date('m'), date('d') - 1, date('Y')); //昨天
	$dd = $time - mktime(0, 0, 0, date('m'), date('d'), date('Y')); //今天
	$td = $time - mktime(0, 0, 0, date('m'), date('d') + 1, date('Y')); //明天
	$atd = $time - mktime(0, 0, 0, date('m'), date('d') + 2, date('Y')); //后天
	if ($d == 0) {
			$fdate = '刚刚';
	} else {
			switch ($d) {
					case $d < $atd:
							$fdate = date('Y年m月d日', $time);
							break;
					case $d < $td:
							$fdate = '后天' . date('H:i', $time);
							break;
					case $d < 0:
							$fdate = '明天' . date('H:i', $time);
							break;
					case $d < 60:
							$fdate = $d . '秒前';
							break;
					case $d < 3600:
							$fdate = floor($d / 60) . '分钟前';
							break;
					case $d < $dd:
							$fdate = floor($d / 3600) . '小时前';
							break;
					case $d < $yd:
							$fdate = '昨天' . date('H:i', $time);
							break;
					case $d < $byd:
							$fdate = '前天' . date('H:i', $time);
							break;
					case $d < $md:
							$fdate = date('m月d日 H:i', $time);
							break;
					case $d < $ld:
							$fdate = date('m月d日', $time);
							break;
					default:
							$fdate = date('Y年m月d日', $time);
							break;
			}
	}
	return $fdate;
}

/**
 * 多维数组转一维数组
 * @param  array $array 多维数组
 * @return array        一维数组
 */
function array_multi2single($array) {
	static $result_array = array();
	foreach ($array as $value) {
		if (is_array($value)) {
			array_multi2single($value);
		}else {
			$result_array[] = $value;
		}
	}
	return $result_array;
}

/**
 * 按二维数组下的某个键对应的值进行排序
 * @param  array $arr  二维数组
 * @param  integer|string $keys 二维数组下指定的键
 * @param  string $type 排序方式,默认desc倒序
 * @return array       排序后的数组
 */
function array_sort($arr, $keys, $type = 'desc') {
    $key_value = $new_array = array();
    foreach ($arr as $k => $v) {
        $key_value[$k] = $v[$keys];
    }
    if ($type == 'asc') {
        asort($key_value);
    } else {
        arsort($key_value);
    }
    reset($key_value);
    foreach ($key_value as $k => $v) {
        $new_array[$k] = $arr[$k];
    }
    return $new_array;
}

/**
 * 获取数组中的某一列数据
 * @param  array $array 数组
 * @param  integer|string $key   键
 * @return array
 */
function getArrayColumn($array,$key){
	$arr = array();
	foreach($array as $v){
		$arr[] = $v[$key];
	}
	return $arr;
}

/**
 * 将从数据库中查询的到的手按指定id作为数组的键
 * @param  array $array
 * @param  string $id    id名称
 * @return array
 */
function convert_arr_key($array, $id) {
    $arr = array();
    foreach ($array as $val) {
        $arr[$val[$id]] = $val;
    }
    return $arr;
}

/**
 * 获取url
 * @param  string $str url地址
 * @return array
 */
function parse_url_param($str) {
    $data = array();
    $str = explode('?', $str);
    $str = end($str);
    $parameter = explode('&', $str);
    foreach ($parameter as $val) {
        $tmp = explode('=', $val);
        $data[$tmp[0]] = $tmp[1];
    }
    return $data;
}

 ?>
