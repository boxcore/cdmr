<?php

// exit;

/**
 * 发送post请求
 * @param string $url 请求地址
 * @param array $post_data post键值对数据
 * @return string
 */
function send_post($url, $post_data) {
 
    $postdata = http_build_query($post_data);
    $options = array(
        'http' => array(
            'method' => 'POST',
            'header' => 'Content-type:application/x-www-form-urlencoded',
            'content' => $postdata,
            'timeout' => 15 * 60 // 超时时间（单位:s）
        )
    );
    $context = stream_context_create($options);
    $result = file_get_contents($url, false, $context);
 
    return $result;
}


$post_data = array(
    'start_date' => '2015-01-01',
    'end_date' => '2015-01-01'
);
$str = send_post('http://testsvn.37tj.wan.liebao.cn//u_reg.php?action=login_game', $post_data);
print_r($str);



/**
 * Socket版本
 * 使用方法：
 * $post_string = "app=socket&amp;version=beta";
 * request_by_socket('blog.snsgou.com', '/restServer.php', $post_string);
 */
function request_by_socket($remote_server,$remote_path,$post_string,$port = 80,$timeout = 30) {
    $socket = fsockopen($remote_server, $port, $errno, $errstr, $timeout);
    if (!$socket) die("$errstr($errno)");
    fwrite($socket, "POST $remote_path HTTP/1.0");
    fwrite($socket, "User-Agent: Socket Example");
    fwrite($socket, "HOST: $remote_server");
    fwrite($socket, "Content-type: application/x-www-form-urlencoded");
    // fwrite($socket, "Content-length: " . （strlen($post_string) + 8） . "");
    fwrite($socket, "Accept:*/*");
    fwrite($socket, "");
    fwrite($socket, "mypost=$post_string");
    fwrite($socket, "");
    $header = "";
    while ($str = trim(fgets($socket, 4096))) {
        $header .= $str;
    }
 
    $data = "";
    while (!feof($socket)) {
        $data .= fgets($socket, 4096);
    }
 
    return $data;
}


/**
 * Curl版本
 * 使用方法：
 * $post_string = "app=request&version=beta";
 * request_by_curl('http://blog.snsgou.com/restServer.php', $post_string);
 */
function request_by_curl($remote_server, $post_string) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $remote_server);
    curl_setopt($ch, CURLOPT_POSTFIELDS, 'mypost=' . $post_string);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERAGENT, "snsgou.com's CURL Example beta");
    $data = curl_exec($ch);
    curl_close($ch);
 
    return $data;
}
 

/**
 * 发送HTTP请求
 *
 * @param string $url 请求地址
 * @param string $method 请求方式 GET/POST
 * @param string $refererUrl 请求来源地址
 * @param array $data 发送数据
 * @param string $contentType
 * @param string $timeout
 * @param string $proxy
 * @return boolean
 */
function send_request($url, $data, $refererUrl = '', $method = 'GET', $contentType = 'application/json', $timeout = 30, $proxy = false) {
    $ch = null;
    if('POST' === strtoupper($method)) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HEADER,0 );
        curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        if ($refererUrl) {
            curl_setopt($ch, CURLOPT_REFERER, $refererUrl);
        }
        if($contentType) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:'.$contentType));
        }
        if(is_string($data)){
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        } else {
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        }
    } else if('GET' === strtoupper($method)) {
        if(is_string($data)) {
            $real_url = $url. (strpos($url, '?') === false ? '?' : ''). $data;
        } else {
            $real_url = $url. (strpos($url, '?') === false ? '?' : ''). http_build_query($data);
        }
 
        $ch = curl_init($real_url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:'.$contentType));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        if ($refererUrl) {
            curl_setopt($ch, CURLOPT_REFERER, $refererUrl);
        }
    } else {
        $args = func_get_args();
        return false;
    }
 
    if($proxy) {
        curl_setopt($ch, CURLOPT_PROXY, $proxy);
    }
    $ret = curl_exec($ch);
    $info = curl_getinfo($ch);
    $contents = array(
            'httpInfo' => array(
                    'send' => $data,
                    'url' => $url,
                    'ret' => $ret,
                    'http' => $info,
            )
    );
 
    curl_close($ch);
    return $ret;
}

