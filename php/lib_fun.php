<?php
// include_once $_SERVER['DOCUMENT_ROOT']."/lim/GTools.php";
// $g_tools = new GTools();
// $g_tools->debug_flag = true;

class GTools {

	public $debug_flag = false;		// true 디버깅, false 노 디버깅

	public $db;
	function __construct() {
		global $db;
		$this->db = $db;
	}


	// $g_tools->timeInit();
	// $g_tools->timeInit('sql');
	// $ret_debug['time_spent']['01_text_0']	= $g_tools->spendTime();
	// $ret_debug['time_spent']['01_text_0']	= $g_tools->spendTime('sql');
	// $ret_debug['time_spent']['00_sum']		= $g_tools->spendTime('sum');
	public $time_temp = array();
	public function timeInit( $time_line = 'main' ) {
		$time = microtime(true);
		if ( empty( $this->time_temp ) ) {
			$this->time_temp = [
				$time_line	=> [ 'last' => $time, 'now'	=> $time ],
				'sum'		=> [ 'last' => $time, 'now'	=> $time ]
			];
		} else {
			$this->time_temp[$time_line] = [ 'last' => $time, 'now'	=> $time ];
		}
		return number_format($time, 8);
	}
	public function spendTime( $time_line = 'main' ) {
		$this->time_temp[$time_line]['now'] = microtime(true);
		$spends = $this->time_temp[$time_line]['now'] - $this->time_temp[$time_line]['last'];
		$this->time_temp[$time_line]['last'] = $this->time_temp[$time_line]['now'];
		return number_format($spends, 8);
	}

	public function calcRound( $number, $type="round", $conv=1 ) {
		$number = $number / $conv;
		if ( $type === "round" ) { $number = round($number); }
		if ( $type === "ceil"  ) { $number = ceil($number);  }
		if ( $type === "floor" ) { $number = floor($number); }
		$number = $number * $conv;
		return $number;
	}

	public function secondToTimeStr( $total_time ) {
		$days = floor($total_time/86400); 
		$time = $total_time - ($days*86400); 
		$hours = floor($time/3600); 
		$time = $time - ($hours*3600); 
		$min = floor($time/60); 
		$sec = $time - ($min*60); 
		
		$str = '';
		if($days==0&&$hours==0&&$min==0) {
			$str = $sec."초";
		} else if ($days==0&&$hours==0) {
			$str = "{$min}분 {$sec}초";
		} else if ($days==0) {
			$str = "{$hours}시간 {$min}분 {$sec}초";
		} else {
			$str = "{$days}일 {$hours}시간 {$min}분 {$sec}초";
		}
		return $str;
	}

	# utf-8 문자열을 주어진 바이트로 자르기
	public function getSubstring($str, $length)
	{
		$str = trim($str);

		if (strlen($str) <= $length)
			return $str;

		$strArr = preg_split("//u", $str, -1, PREG_SPLIT_NO_EMPTY);
		$cutStr = '';

		foreach ($strArr as $s) {
			$len1 = strlen($s);
			$len2 = strlen($cutStr) + $len1;

			if ($len2 > $length)
				break;
			else
				$cutStr .= $s;
		}

		return $cutStr;
	}

	// $g_tools->isUserGroup()
	// if ( $g_tools->isUserGroup() ) { $debug_flag = true; }
	public function isUserGroupPhp( $line ) {
		$result = 0;
		if ( $line === "master" ) {
			$result =	$_SESSION['m_id'] == "mb_0";
		}
		if ( $line === "admin" ) {
			$result =	$_SESSION['m_id'] == "mb_0"
					||	$_SESSION['m_id'] == "mb_1";
		}
		if ( $line === "goods_detail" ) {
			$result =	false
					||	$_SESSION['m_id'] == "mb_0"
					||	$_SESSION['m_id'] == "mb_2"
					||	$_SESSION['m_id'] == "mb_4"
					||	false;
		}
		return (int)$result;
	}

	// $g_tools->notLoginedExit();
	public function notLoginedExit() {
		if ( !$_SESSION['m_id'] ) { echo '{"status":"please login","msg":"로그인후 이용 가능 합니다."}'; exit; }
	}

	// $g_tools->notAdminExit();
	public function notAdminExit() {
		if ( !$_SESSION['m_id'] ) { echo '{"status":"please login as admin","msg":"관리자로 로그인 하십시오."}'; exit; }
		if ( !$this->isUserGroupPhp( "master" ) ) { echo '{"status":"no authority","msg":"열람 권한이 없습니다."}'; exit; }
	}

	// if ( $g_tools->isTimeTo("2023-11-01 18:03:00") <= 0 ) { echo "ok"; } else { echo "yet"; }
	public function isTimeTo( $time ) {
		return strtotime( $time ) - strtotime( date('Y-m-d H:i:s') );
	}

	// $g_tools->printJsonPre( $json );
	public function printJsonPre( $json ) {
		$arr = json_decode($json, true);
		$this->printArrPre( $arr );
	}

	// $g_tools->printArrPre( $arr );
	public function printArrPre( $arr ) {
		echo "<pre>".PHP_EOL; print_r($arr); echo PHP_EOL."</pre>";
	}

	// $g_tools->hiddenPrint( $arr );
	// $g_tools->hiddenPrint( $arr, [ mark_str => "", change_line => false ] );
	// echo '<span style="display:none;" cunlim>'.'</span>';
	public function hiddenPrint( $arr, $configs = [] ) {
		$configs['mark_str']    = $configs['mark_str']      ?:  "cunlim";
		$configs['change_line'] = $configs['change_line']   ?:  false;
		if ( $configs['change_line'] ) {
			echo '<span style="display:none;" '.$configs['mark_str'].'>'.PHP_EOL;
			print_r( $arr );
			echo PHP_EOL.'</span>'.PHP_EOL;
		} else {
			echo '<span style="display:none;" '.$configs['mark_str'].'>';
			print_r( $arr );
			echo '</span>'.PHP_EOL;
		}
	}


	public function dbSelect( $sql ) {
		$ret = array();
		$res = $this->db->query($sql);
		while( $row = $this->db->fetch( $res, 1 ) ) { $ret[] = $row; }
		return $ret;
	}

	public function itemJson( &$item ) {
		$field_json = [
			'field_04', 'field_07',
			'field_11', 'field_13', 'field_16'
		];
		foreach ( $field_json as $k2 => $v2 ) {
			if ( empty( $item[$v2] ) ) { continue; }
			$item["{$v2}_json"] = json_decode( $item[$v2], true );
			if ( !empty( $item["{$v2}_json"] ) ) { unset( $item[$v2] ); }
		}
	}

	// $file :
	//   "name": "img_name_01.gif",
	//   "type": "image/gif",
	//   "tmp_name": "/tmp/php2XixzP",
	//   "error": 0,
	//   "size": 112012
	// $file_rslt = $g_tools->fileUploader( $_FILES['file'], ["img_v5", "4", $mb_id, "company"], [$mb_id], "img1" );
	// $file_rslt = $g_tools->fileUploader( $_FILES['upload'], ["img_v3", "4", $mb_id, $group, "goods_detail"], [$mb_id, $goods_or_id, $fl_no] );
	public function fileUploader( $file, $dir_arr, $name_arr, $file_type = "img1" ) {
		if ( !$file['name'] ) { return; }

		$ext = pathinfo( $file['name'], PATHINFO_EXTENSION );

		// exit( '{"status":"has invalid img ext","msg":"JPG, JPEG, PNG, GIF만 가능합니다."}' );
		if (	$file_type === "img1"
			&&	strcasecmp($ext, "jpg")		!== 0
			&&	strcasecmp($ext, "jpeg")	!== 0
			&&	strcasecmp($ext, "png")		!== 0
			&&	strcasecmp($ext, "gif")		!== 0
		) { return; }

		$paths			= $this->getPath( $dir_arr );
		$file_new_arr	= $this->getFileName( $name_arr, $paths['absolute'] );
		$file_new		= "{$file_new_arr['file_new']}.{$ext}";

		$file_url		= "{$paths['protocol']}://{$paths['domain']}{$paths['relative']}/{$file_new}";
		$file_target	= "{$paths['absolute']}/{$file_new}";

		$move_result	= move_uploaded_file( $file["tmp_name"], $file_target );
		// if ( !$file_rslt['move_result'] ) {}

		return [
			// "file_new"		=> $file_new,
			"file_url"			=> $file_url,
			"move_result"		=> $move_result,
			// "file_target"	=> $file_target,
			"debug" => [
				"file"			=> $file,
				"ext"			=> $ext,
				"paths"			=> $paths,
				"file_new_arr"	=> $file_new_arr,
			]
		];
	}

	/**
	 * $g_tools->getPath();
	 *   relative: "/data",
	 *   absolute: "/home/proj/www/data"
	 * 
	 * $g_tools->getPath(["img_v3", "4", "mb_id_01"]);
	 * $g_tools->getPath(["img_v3/4", "mb_id_01"]);
	 *   relative: "/data/img_v3/4/mb_id_01",
	 *   absolute: "/home/proj/www/data/img_v3/4/mb_id_01"
	 */
	public function getPath( $dir_arr = [] ) {
		if ( !$dir_arr || !is_array( $dir_arr ) ) { $dir_arr = []; }

		foreach ( $dir_arr as $k1 => $dir ) {
			if ( !$dir ) { unset( $dir_arr[$k1] ); }
		}

		array_unshift( $dir_arr, "/data" );
		$relative = implode('/', $dir_arr);

		$absolute = "{$_SERVER['DOCUMENT_ROOT']}{$relative}";

		$mkdir = @mkdir($absolute, 0755, true);

		return [
			"protocol"	=> "http",
			"domain"	=> "a.b.com",
			"relative"	=> $relative,
			"absolute"	=> $absolute,
			"mkdir"		=> $mkdir,
		];
	}

	/**
	 * $g_tools->getFileName( [$mb_id, $goods_or_id, $fl_no], $paths['absolute'] );
	 *   mb_id_01_604291659841_1492095_9ZYh
	 * $g_tools->getFileName( [$mb_id, $goods_or_id, $fl_no] );
	 *   mb_id_01_604291659841_1492095
	 * $g_tools->getFileName( [], $paths['absolute'] );
	 *   img_9ZYh
	 * $g_tools->getFileName();
	 *   img
	 */
	public function getFileName( $name_arr = [], $path = "" ) {
		$name_arr = $name_arr  ?:  [];

		foreach ( $name_arr as $k1 => $v1 ) {
			if ( !$v1 ) { unset( $name_arr[$k1] ); continue; }
			$name_arr[$k1] = str_replace("/", "_", $v1)  ?:  "";
		}

		$file_new = implode('_', $name_arr)  ?:  "img";

		if ( empty( $path ) ) {
			return [ "file_new" => $file_new ];	# code 없이 강제 덮어쓰기
		}

		# add _code {
		$file_uniq = $this->getFileNameUniqueCode( $path, $file_new );
		return [
			"file_new"  => $file_uniq['file_code'],
			"file_uniq" => $file_uniq
		];
		# add _code }

		/*
		# add _num {
		$index_arr = $this->getIndexContinue( $path, $file_new );

		$index_last = (int)$index_arr['index_continue'];
		$num = $index_last < 1000  ? sprintf( '%03d', $index_last )  : $index_last;
		return [
			"file_new"  => $file_new  ? "{$file_new}_{$num}"  : $num,
			"index_arr" => $index_arr
		];
		# add _num }
		*/
	}

	public function getFileNameUniqueCode( $path, $file_new = "" ) {
		$path_file = "{$path}/{$file_new}";

		if ( !$path ) {
			$code_unique = $this->getRandStr(4);
		} else {
			for ( $i = 0; $i < 10; $i ++ ) {
				$code_unique_arr[]	= $code_unique	= $this->getRandStr(4);
				$file_pattern_arr[]	= $file_pattern	= $file_new  ? "{$path_file}_{$code_unique}*"  : "{$path_file}*{$code_unique}*";
				$file_glob_arr[]	= $file_glob	= glob( $file_pattern );
				$file_exist = false;
				foreach ( $file_glob as $v2 ) {
					$file_exist = is_file( $v2 );	# file 만 검사
					if ( $file_exist ) { break; }
				}
				$file_exist_arr[]	= $file_exist;
				if ( $file_exist ) { continue; }	# 동명 파일 존재시 다른 code 시도
				break;								# 동명 파일 없을시 해당 code 사용
			}
		}

		$file_code = $file_new == ""  ? $code_unique  : "{$file_new}_{$code_unique}";

		return [
			"file_code"			=> $file_code,
			"code_unique"		=> $code_unique,
			"code_unique_arr"	=> $code_unique_arr,	# for debug
			"file_pattern_arr"	=> $file_pattern_arr,	# for debug
			"file_glob_arr"		=> $file_glob_arr,		# for debug
			"file_exist_arr"	=> $file_exist_arr,		# for debug
		];
	}

	public function getRandStr( $length = 4 ) {
		// $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';	# 62 ^ 4 = 14,776,336
		$characters = '23456789abcdefghijkmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ';			# 56 ^ 4 =  9,834,496

		$charactersLength = strlen($characters);
		$randomString = '';
		for( $i = 0; $i < $length; $i ++ ){
			$randomString .= $characters[ rand(0, $charactersLength - 1) ];
		}
		// return "abcd";	# for debug
		return $randomString;
	}

	public function getIndexContinue( $path, $file_new = "" ) {
		if ( empty( $path ) ) { return; }

		// 핸들 획득
		$handle  = opendir($path);

		$file_arr = array();
		while (false !== ($file_ori = readdir($handle))) {
			if($file_ori == "." || $file_ori == ".."){ continue; }
			if( $file_new && stripos( $file_ori, $file_new ) === false ){ continue; }
			if( !is_file( "{$path}/{$file_ori}" ) ){ continue; }
			$file_arr[] = $file_ori;
		}

		// 핸들 해제
		closedir($handle);

		// 정렬, 역순으로 정렬하려면 rsort 사용
		// sort($file_arr);

		// $file_index_arr = array();
		foreach ( $file_arr as $k1 => $file_ori ) {
			if ( !$file_new ) {
				preg_match('/[0-9]+[\.]/', $file_ori, $matches);	# 001.jpg -> 001.
				$matches_arr[] = mb_substr( $matches[0], 0, strlen($matches[0]) - 1, "utf-8" );
			} else {
				$file_ori = str_replace( $file_new, "", $file_ori );	# mb_id_01_123456789012_1234567_001.jpg -> _001.jpg / _123456789012_1234567_001.jpg
				preg_match('/[\_][0-9]+[\.]/', $file_ori, $matches);	# _001.jpg / _123456789012_1234567_001.jpg -> _001.
				$matches_arr[] = mb_substr( $matches[0], 1, strlen($matches[0]) - 2, "utf-8" );
			}
			// $file_ori = preg_replace( '/\..+$/', '', $file_ori ) ?:  $file_ori;		# 확장자 제거
			// $file_index_arr[] = (int)$file_ori;
		}

		$index_continue = 1 + max( $matches_arr );

		return [
			"index_continue" => $index_continue,
			"file_arr" => $file_arr,
			"matches_arr" => $matches_arr
		];
	}

	public function analBase64Str( $data ) {
		# data:image/alicdn;base64,/9j/4AAQSkZJRgABAQAAAQABAAD/2wBDAAIBAQEBAQIBAQECAgICAgQ...
		# data:image/jpeg;base64,/9j/4AAQSkZJRgABAQAAAQABAAD/2wBDAAIBAQEBAQIBAQECAgICAgQ...

		$img_str = explode( "base64," ,$data );
		unset( $img_str[0] );
		$img_str = implode( "base64,", $img_str );
		// $img_bin = base64_decode($img_str);

		# 이미지 파일만 허용.
		$ext  = explode(";", $data)[0];
		$ext  = explode("/", $ext)[1];

		$is_img = true;

		if($ext == "alicdn" || $ext == "domain") {
			$ext = "jpg";
		}
		if($ext != "jpg" && $ext != "jpeg" && $ext != "png" && $ext != "gif" ) {
			$is_img = false;
		}

		return [ "ext" => $ext, "img_str" => $img_str, "is_img" => $is_img ];
	}

	/**
	 * $sql_btw = $g_tools->getSqlBetween();
	 * $sql_btw = $g_tools->getSqlBetween("d");
	 * $sql_btw = $g_tools->getSqlBetween("H", "2024-01-05 13:51:45");
	 */
	public function getSqlBetween( $unit="d", $date_str="" ) {
		$ret = [];
		$date_str = $date_str  ?:  date('Y-m-d H:i:s');

		$map = [
			"Y" => [ "delta" => "+1 years",		"format" => "Y",		"date_s1_add" => "-01" ],
			"m" => [ "delta" => "+1 months",	"format" => "Y-m", ],
			"d" => [ "delta" => "+1 days",		"format" => "Y-m-d", ],
			"H" => [ "delta" => "+1 hours",		"format" => "Y-m-d H",	"date_s1_add" => ":00" ],
			"i" => [ "delta" => "+1 minutes",	"format" => "Y-m-d H:i", ],
		];
		$delta			= $map[ $unit ]['delta']		?:  "+1 days";
		$format			= $map[ $unit ]['format']		?:  "Y-m-d";
		$date_s1_add	= $map[ $unit ]['date_s1_add']	?:  "";

		$timestamp_s1 = strtotime( $date_str );
		$date_s1 = date( $format, $timestamp_s1 );
		$date_s1 .= $date_s1_add;
		$timestamp_s2 = strtotime( $date_s1 );
		$date_s2 = date( "Y-m-d H:i:s", $timestamp_s2 );
		$timestamp_e1 = strtotime( $delta, $timestamp_s2 ) - 1;
		$date_e1 = date( "Y-m-d H:i:s", $timestamp_e1 );


		// $ret['debug']['param']['date_str'] = $date_str;
		// $ret['debug']['param']['unit'] = $unit;
		// $ret['debug']['param']['now'] = date( 'Y-m-d H:i:s' );
		// $ret['debug']['delta']  = $delta;
		// $ret['debug']['format'] = $format;
		// $ret['debug']['date_s1'] = $date_s1;
		$ret['start'] = $date_s2;
		$ret['end']   = $date_e1;

		return $ret;
	}


}

$g_tools = new GTools();
// $g_tools->debug_flag = true;




