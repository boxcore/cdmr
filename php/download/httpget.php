<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="zh-cn">
<head>
<title>PHPԶ���ļ����ع��� Ver 1.00 Bete Bacysoft.cn ����</title>
<meta http-equiv="Content-Type" content="text/html; charset=gbk" />
<meta http-equiv="Content-Language" content="gbk" />
<meta name="author" content="Bacysoft.cn" />
<meta name="keywords" content="PHP Զ�� �ļ�����" />
<meta name="description" content="��������PHP���ʵ�֣�����ʵ��Զ�̷�����ͨ��http url����ָ���ļ�����ǰ����������������Bacysoft.cn������������Ϣ����ʣ�http://www.Bacysoft.cn" />
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

	echo "�ļ� ".$newfname." ������ϣ�����:".filesize($newfname)." �ֽڡ�<br/>";
	echo "һ����ʱ $tt �룬ƽ�������ٶ�Ϊ:".filesize($newfname)/1024/$tt." KB/s";
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
		<a href="httpget.php?password=logout" >�˳�</a>
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