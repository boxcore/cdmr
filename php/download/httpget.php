<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="zh-cn">
<head>
<title>PHP远程文件下载工具 Ver 1.00 Bete Bacysoft.cn 开发</title>
<meta http-equiv="Content-Type" content="text/html; charset=gbk" />
<meta http-equiv="Content-Language" content="gbk" />
<meta name="author" content="Bacysoft.cn" />
<meta name="keywords" content="PHP 远程 文件下载" />
<meta name="description" content="本工具有PHP编程实现，可以实现远程服务器通过http url下载指定文件到当前服务器。本工具由Bacysoft.cn开发。更多信息请访问：http://www.Bacysoft.cn" />
</head>
<?php
set_time_limit (24 * 60 * 60); 
$act = $_POST['act'];
if ($act == "getfile"){

	$tt = time();

	$url = $_POST['url'];
	$newfname = basename($url);

	$file = fopen ($url, "rb");

	if ($file){
		$newf = fopen ($newfname, "wb");
		if ($newf)
			while(!feof($file)) {
				fwrite($newf, fread($file, 1024 * 8 ), 1024 * 8 );
			}
	} 
	if ($file) fclose($file);
	if ($newf) 	fclose($newf);

	$tt = time()-$tt;

	echo "文件 ".$newfname." 下载完毕！共计:".filesize($newfname)." 字节。<br/>";
	echo "一共耗时 $tt 秒，平均下载速度为:".filesize($newfname)/1024/$tt." KB/s";
	exit;
}

$pass = $_REQUEST['password'];

if ($pass == "thunje654321"){
	$_SESSION['login'] = true;
}
if ($pass == "logout"){
	$_SESSION['login'] = false;
}

if ($_SESSION['login'] == true){
?>
<body>
	<form method="post" target="insideframe">
		Http Url:<input name="url" size="50" />
		<input name="act" value="getfile" type="hidden" />
		<input name="submit" type="submit" />
		<a href="httpget.php?password=logout" >退出</a>
	</form>
	<iframe name="insideframe" width="100%" height="100" ></iframe>
</body>
</html>
<?php
}else{
?>
<body>
	<form method="post">
		Password:<input name="password" type="password" />
		<input type="submit" />
	</form>
</body>
</html>
<?php
}
?>