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

	// $g_tools->isUserGroup()
	// if ( $g_tools->isUserGroup() ) { $debug_flag = true; }
	public function isUserGroup( $line = 1 ) {
		$result = 0;
		if ( $line == 1 ) {
			$result =	$_SESSION['m_id'] == "mb_0";
		}
		if ( $line == 4 ) {
			$result =	$_SESSION['m_id'] == "mb_0"
					||	$_SESSION['m_id'] == "mb_1"
					||	$_SESSION['m_id'] == "mb_2"
					||	$_SESSION['m_id'] == "mb_3";
		}
		if ( $line == 'goods_detail' ) {
			$result =	$_SESSION['m_id'] == "mb_0"
					||	$_SESSION['m_id'] == "mb_2"
					||	$_SESSION['m_id'] == "mb_4";
		}
		return (int)$result;
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


	public function dbSelect( $sql ) {
		$ret = array();
		$res = $this->db->query($sql);
		while( $row = $this->db->fetch($res) ) {
			foreach( $row as $k2 => $v2 ) {
				if ( is_int( $k2 ) ) { unset( $row[$k2] ); }
			}
			$ret[] = $row;
		}
		return $ret;
	}

	public function deleteIntField( &$row ) {
		foreach( $row as $k1 => $v1 ) {
			if ( is_int( $k1 ) ) { unset( $row[$k1] ); }
		}
	}


}

$g_tools = new GTools();
// $g_tools->debug_flag = true;




