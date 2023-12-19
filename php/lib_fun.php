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

	public function getPath( $folder, $mb_id, $group="", $img_position="" ) {
		if ( empty( $folder ) || empty( $mb_id ) ) { return; }

		$protocol		= 'http';
		$domain			= "a.b.com";
		$path_mb_id		= "data/{$folder}/4/{$mb_id}";
		$path_group		= !empty($group)		? "{$path_mb_id}/{$group}"			: $path_mb_id;
		$relative		= !empty($img_position)	? "{$path_group}/{$img_position}"	: $path_group;

		$absolute		= "{$_SERVER['DOCUMENT_ROOT']}/{$relative}"; # /home/wn_scrap/www/data/occ_img_v3/4/doc2327/FN20231004141359_9060/goods_detail
		@mkdir($absolute, 0755, true);

		return [ "protocol" => $protocol, "domain" => $domain, "relative" => $relative, "absolute" => $absolute ];
	}

	public function getImgName( $absolute, $mb_id, $goods_or_id, $fl_no ) {
		if ( empty( $absolute ) || empty( $mb_id ) ) { return; }

		$img_name = $mb_id;
		if ( !empty($goods_or_id) )	{ $img_name = "{$img_name}_{$goods_or_id}"; }
		if ( !empty($fl_no) )		{ $img_name = "{$img_name}_{$fl_no}"; }

		# add _num {
		$index_arr = $this->getIndexContinue( $absolute, $img_name );

		$index_last = $index_arr['index_continue'];
		$num = $index_last < 1000  ? sprintf( '%03d', $index_last )  : $index_last;
		return [
			"img_name" => "{$img_name}_{$num}",
			"index_arr" => $index_arr
		];
		# add _num }
	}

	public function getIndexContinue( $dir, $img_name, $tail = ".jpg" ) {
		if ( empty( $dir ) || empty( $img_name ) ) { return; }

		// 핸들 획득
		$handle  = opendir($dir);

		$file_arr = array();
		while (false !== ($filename = readdir($handle))) {
			if($filename == "." || $filename == ".."){ continue; }
			if( stripos( $filename, $img_name ) === false ){ continue; }
			if ( is_file( "{$dir}/{$filename}" ) ){
				$file_arr[] = $filename;
			}
		}

		// 핸들 해제
		closedir($handle);
		
		// 정렬, 역순으로 정렬하려면 rsort 사용
		// sort($file_arr);

		$img_index_arr = array();
		foreach ( $file_arr as $k1 => $filename ) {
			$filename = str_replace( "{$img_name}_", "", $filename );
			$filename = str_replace( $tail, "", $filename );
			$img_index_arr[] = (int)$filename;
		}

		$index_continue = 1 + max( $img_index_arr );

		return [
			"file_arr" => $file_arr,
			"img_index_arr" => $img_index_arr,
			"index_continue" => $index_continue,
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


}

$g_tools = new GTools();
// $g_tools->debug_flag = true;




