<?php

// include_once $_SERVER['DOCUMENT_ROOT']."/lib/library.php";
// include_once('./common.php');
// include_once("/home/common/git/git_ls_al.php");


$ret = [];
$cd_root = "cd {$_SERVER['DOCUMENT_ROOT']}/../";

exec("{$cd_root}; git status;", $git_status);
$ret_debug['git_status'] = $git_status;
$ret['git_status'] = htmlspecialchars( implode( PHP_EOL, $git_status ) );

// exec("git status --short | sed 's/.* www/ls -al \"www/g' | sed 's/$/\"/g' > www/lim/log/git_ls_al.sh", $output, $return_var);
exec("{$cd_root}; git status --short;", $git_status_short);
$ret_debug['git_status_short'] = $git_status_short;
$ret['git_status_short'] = implode( PHP_EOL, $git_status_short );


$git_ls_al = [];
$ls_al = [];

foreach ( $git_status_short as $k1 => $v1 ) {
	$git_ls_al[ $k1 ]['x'] = mb_substr($v1, 0, 1, "UTF-8");
	$git_ls_al[ $k1 ]['y'] = mb_substr($v1, 1, 1, "UTF-8");

	if ( $git_ls_al[ $k1 ]['x'] === " " && $git_ls_al[ $k1 ]['y'] !== " " || $git_ls_al[ $k1 ]['y'] === "?" ) { continue; }

	$file = mb_substr($v1, 3, null, "UTF-8");
	if ( stripos( $file, " -> " ) === false ) {
		$git_ls_al[ $k1 ]['file']		= trim( $file );
	} else {
		$file = explode( " -> ", $file );
		$git_ls_al[ $k1 ]['file']		= trim( $file[1] );
		$git_ls_al[ $k1 ]['file_from']	= trim( $file[0] );
	}

	exec("{$cd_root}; ls -al {$git_ls_al[ $k1 ]['file']};", $ls_al_arr);
	$git_ls_al[ $k1 ]['ls_al_arr'] = $ls_al_arr[0];
	$ls_al = $ls_al_arr[0];

	$ls_al_explode = $git_ls_al[ $k1 ]['ls_al_explode'] = explode( " ", $ls_al );
	foreach ( $ls_al_explode as $k2 => $v2 ) { if ( $k2 < 5 ) { unset( $ls_al_explode[$k2] ); } }
	$ls_al = trim( implode( " ", $ls_al_explode ) );

	if ( stripos( $file, " -> " ) !== false ) {
		$ls_al = str_replace( $git_ls_al[ $k1 ]['file'], "{$git_ls_al[ $k1 ]['file_from']} -> {$git_ls_al[ $k1 ]['file']}", $ls_al );
	}

	if ( !$ls_al ) {
		$ls_al = "deleted :    {$git_ls_al[ $k1 ]['file']}";
	}

	$git_ls_al[ $k1 ]['ls_al'] = $ls_al;
	$ls_al = null;
	$ls_al_arr = null;
}

$ret_debug['git_ls_al'] = $git_ls_al;
$ret['git_ls_al'] = implode( PHP_EOL, array_column( $git_ls_al, 'ls_al' ) );

$ret['work_time'] = "[김실장] discription".PHP_EOL.PHP_EOL.PHP_EOL."from ~ ".date("Y-m-d H:i").PHP_EOL.PHP_EOL;


// $ret['debug'] = $ret_debug;
// header('Content-Type: application/json; charset=utf-8');
// echo json_encode( $ret, JSON_UNESCAPED_UNICODE ); exit;

?>




<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>git commit message</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

	<style>
		* { font-family: consolas; }
		.pree { white-space: pre; }
	</style>
</head>
<body>

<div class="alert alert-light" role="alert">
	<h4>git commit message temp</h4>
	<hr>
	<textarea class="form-control" rows="12"><?= $ret['work_time']; ?><?= $ret['git_ls_al']; ?></textarea>
</div>

<div class="alert alert-secondary" role="alert">
	<h4>git status --short;</h4>
	<hr>
	<div class="pree"><?= $ret['git_status_short']; ?></div>
</div>

<div class="alert alert-secondary" role="alert">
	<h4>git status;</h4>
	<hr>
	<div class="pree"><?= $ret['git_status']; ?></div>
</div>

</body>
</html>



