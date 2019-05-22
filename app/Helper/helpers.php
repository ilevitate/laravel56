<?php
/**
 * 全局扩展函数
 */


/**
 * 判断当前系统是否是linux系统
 * @return bool
 */
function isLinux()
{
    if (strpos(PHP_OS, "WIN") !== false)
        return false;
    else
        return true;
}


/**
 * 返回当前请求的ip
 */
function ip()
{
    $client = @$_SERVER['HTTP_CLIENT_IP'];
    $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
    $remote = $_SERVER['REMOTE_ADDR'];

    if (filter_var($client, FILTER_VALIDATE_IP)) {
        return $client;
    } elseif (filter_var($forward, FILTER_VALIDATE_IP)) {
        return $forward;
    } else {
        return $remote === '::1' ? '127.0.0.1' : $remote;
    }
}


/**
 * 判断对象是否为null或空
 * @param $obj
 * @param null $key
 * @return bool
 */
function isNullOrEmpty($obj, $key = null)
{
    if ($obj === null) {
        return true;
    } else if (is_array($obj)) {
        if (isset($key)) {
            return (isset($obj[$key]) == false || $obj[$key] === null || $obj[$key] === '');
        } else {
            return (count($obj) === 0);
        }
    } else {
        return ($obj === '');
    }
}


/**
 * 将二维数组的子数组的指定字段的值作为子数组的key
 * @param $arr
 * @param $field
 * @return array
 */
function useFieldAsKey($arr, $field)
{
    if (!is_array($arr) || !count($arr) || !is_array(current($arr))) {
        return $arr;
    }
    $re = [];
    foreach ($arr as $v) {
        if (!isset($v[$field])) {
            continue;
        }
        $re[$v[$field]] = $v;
    }
    return $re;
}

/**
 * 将数组的值作为key
 * @param $arr
 * @return array
 */
function useValAsKey($arr)
{
    if (!is_array($arr) || !count($arr) || !is_array(current($arr))) {
        return $arr;
    }
    $re = [];
    foreach ($arr as $v) {
        $re[$v] = $v;
    }
    return $re;
}

/**
 * 使用二维数组的子数组的某个字段作为value
 * @param $arr
 * @param $field
 * @return array
 */
function useFieldAsVal($arr, $field)
{
    if (!is_array($arr) || !count($arr) || !is_array(current($arr))) {
        return $arr;
    }
    $re = [];
    foreach ($arr as $k => $v) {
        $re[$k] = $v[$field];
    }
    return $re;
}

/**
 * 获取二维数组的子数组中的某个字段的集合
 * @param $arr
 * @param $childName
 * @return array
 */
function getChildrenFields($arr, $childName)
{
    $firstChild = current($arr);
    if (!is_array($arr) || !count($arr) || (!is_array($firstChild) && !is_object($firstChild))) {
        return [];
    }
    $children = [];
    foreach ($arr as $k => $v) {
        if (is_array($v) || is_object($v)) {
            $val = is_object($v) ? $v->$childName : arrayGet($v, $childName, '');
            $children[$k] = is_numeric($val) ? (float)$val : $val;
        }
    }
    return $children;
}

/**
 * 获取二维数组的指定key的子数组集合
 * @param $arr
 * @param $keys
 * @return array|bool
 */
function getChildrenArrays($arr, $keys)
{
    if (!is_array($arr) || !count($arr) || !is_array(current($arr))) {
        return false;
    }
    $children = [];
    foreach ($arr as $k => $v) {
        if (in_array($k, $keys)) {
            $children[$k] = $v;
        }
    }
    return $children;
}

/**
 * 合并多维数组
 * @param $arr
 * @return array
 */
function unionChildrenArrays($arr)
{
    if (!is_array($arr) || !count($arr) || !is_array(current($arr))) {
        return false;
    }
    $children = [];
    foreach ($arr as $k => $v) {
        $children = array_merge($children, $v);
    }
    return $children;
}

/**
 * 将二维数组按某个字段分组
 * @param $arr
 * @param $field
 * @return array|bool
 */
function departByField($arr, $field)
{
    if (!is_array($arr) || !count($arr) || !is_array(current($arr))) {
        return false;
    }
    $re = [];
    foreach ($arr as $k => $v) {
        if (is_array(current($v))) {
            continue;
        }
        $re[$v[$field]][$k] = $v;
    }
    return $re;
}

/**
 * 取数组中指定key的元素组合
 * @param array $arr
 * @param array $keys
 * @return array
 */
function arrayOnly(array $arr, array $keys)
{
    foreach ($arr as $k => $v) {
        if (!in_array($k, $keys)) {
            unset($arr[$k]);
        }
    }
    return $arr;
}


/***
 * 计算两个时间的间隔天数
 * @param datetime $date1 减数
 * @param datetime $date2 被减数
 * @return string {$d}天{$h}小时{$m}分
 */
function get_date_diff($date1, $date2)
{
    $a = strtotime($date1);
    $b = strtotime($date2);
    $cle = $a - $b;

    $d = floor($cle / 3600 / 24);
    $h = floor(($cle % (3600 * 24)) / 3600);  //%取余
    $m = floor(($cle % (3600 * 24)) % 3600 / 60);
    return "{$d}天{$h}小时{$m}分";
}


/**
 * 字符串截断函数（超出长度，结尾自动带上...）
 * @param string $string
 * @param int $length
 * @param string $etc
 * @return string
 */
function short($string, $length, $etc = '...')
{
    $result = '';
    $string = html_entity_decode(trim(strip_tags($string)), ENT_QUOTES, 'UTF-8');
    $strlen = strlen($string);
    for ($i = 0; (($i < $strlen) && ($length > 0)); $i++) {
        if ($number = strpos(str_pad(decbin(ord(substr($string, $i, 1))), 8, '0', STR_PAD_LEFT), '0')) {
            if ($length < 1.0) {
                break;
            }
            $result .= substr($string, $i, $number);
            $length -= 1.0;
            $i += $number - 1;
        } else {
            $result .= substr($string, $i, 1);
            $length -= 0.5;
        }
    }
    $result = htmlspecialchars($result, ENT_QUOTES, 'UTF-8');
    if ($i < $strlen) {
        $result .= $etc;
    }
    return $result;
}


/**
 * 对象转数组,使用get_object_vars返回对象属性组成的数组
 * @param $obj
 * @return array
 */
function objectToArray($obj)
{
    $arr = is_object($obj) ? get_object_vars($obj) : $obj;
    if (is_array($arr)) {
        return array_map(__FUNCTION__, $arr);
    } else {
        return $arr;
    }
}

/**
 * 数组转对象
 * @param $arr
 * @return object
 */
function arrayToObject($arr)
{
    if (is_array($arr)) {
        return (object)array_map(__FUNCTION__, $arr);
    } else {
        return $arr;
    }
}

/**
 * 获取用户IP地址
 * @return mixed
 */
function getUserIP()
{
    $unknown = 'unknown';
    if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR'] && strcasecmp($_SERVER['HTTP_X_FORWARDED_FOR'], $unknown)) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } elseif (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], $unknown)) {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    /*
    处理多层代理的情况
    或者使用正则方式：$ip = preg_match("/[\d\.]{7,15}/", $ip, $matches) ? $matches[0] : $unknown;
    */
    if (false !== strpos($ip, ',')) $ip = reset(explode(',', $ip));
    return $ip;
}

/**
 *判断是否是通过手机访问
 */
function isMobile()
{
    // 如果有HTTP_X_WAP_PROFILE则一定是移动设备
    if (isset ($_SERVER['HTTP_X_WAP_PROFILE'])) {
        return true;
    }
    //如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
    if (isset ($_SERVER['HTTP_VIA'])) {
        //找不到为flase,否则为true
        return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;
    }
    //判断手机发送的客户端标志,兼容性有待提高
    if (isset ($_SERVER['HTTP_USER_AGENT'])) {
        $clientkeywords = array (
            'nokia',
            'sony',
            'ericsson',
            'mot',
            'samsung',
            'htc',
            'sgh',
            'lg',
            'sharp',
            'sie-',
            'philips',
            'panasonic',
            'alcatel',
            'lenovo',
            'iphone', 'ipod',
            'blackberry',
            'meizu',
            'android',
            'netfront',
            'symbian',
            'ucweb',
            'windowsce',
            'palm',
            'operamini',
            'operamobi',
            'openwave',
            'nexusone',
            'cldc',
            'midp',
            'wap',
            'mobile'
        );
        // 从HTTP_USER_AGENT中查找手机浏览器的关键字
        if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT']))) {
            return true;
        }
    }
    //协议法，因为有可能不准确，放到最后判断
    if (isset ($_SERVER['HTTP_ACCEPT'])) {
        // 如果只支持wml并且不支持html那一定是移动设备
        // 如果支持wml和html但是wml在html之前则是移动设备
        if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false)
            && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false ||
                (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') <
                    strpos($_SERVER['HTTP_ACCEPT'], 'text/html')))
        ) {
            return true;
        }
    }
    return false;
}

/**
 * 优化过的ajaxReturn方法，输出文档类型。默认为json，支持json xml html文档格式
 * @param $data
 * @param string $type
 */
function ajaxReturn($data, $type = 'JSON')
{
    if (strtoupper($type) == 'JSON') {
        // 返回JSON数据格式到客户端 包含状态信息
        header('Content-Type:text/json; charset=utf-8');
        exit(json_encode($data));
    } else if (strtoupper($type) == 'XML') {
        // 返回xml格式数据
        header('Content-Type:text/xml; charset=utf-8');
        exit(xml_encode($data));
    } else {
        // 返回html文档
        header('Content-Type:text/html; charset=utf-8');
        exit($data);
    }

}

/**
 * xml转数组
 * @param $xml
 * @return mixed
 */
function xmlToArray($xml)
{
    //禁止引用外部xml实体
    libxml_disable_entity_loader(true);
    $xmlstring = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
    $val = json_decode(json_encode($xmlstring), true);
    return $val;
}

/**
 * 判断是否微信内访问
 * @return bool
 */
function isWeChatRequest()
{
    $user_agent = $_SERVER['HTTP_USER_AGENT'];
    return strpos($user_agent, 'MicroMessenger') !== false;
}

/**
 * 获取用户端浏览器
 * @return string
 */
function getUserBrowser()
{
    if (empty($_SERVER['HTTP_USER_AGENT'])) {
        return '';
    }

    $agent = $_SERVER['HTTP_USER_AGENT'];
    $browser = '';
    $browser_ver = '';

    if (preg_match('/MSIE\s([^\s|;]+)/i', $agent, $regs)) {
        $browser = 'Internet Explorer';
        $browser_ver = $regs[1];
    } elseif (preg_match('/FireFox\/([^\s]+)/i', $agent, $regs)) {
        $browser = 'FireFox';
        $browser_ver = $regs[1];
    } elseif (preg_match('/Trident\/([\d\.]+);(.*?)rv:([\d\.]+)/i', $agent, $regs)) {
        $browser_ver = $regs[3];
        $browser = 'Internet Explorer';
    } elseif (preg_match('/Maxthon/i', $agent, $regs)) {
        $browser = '(Internet Explorer ' . $browser_ver . ') Maxthon';
        $browser_ver = '';
    } elseif (preg_match('/NetCaptor\s([^\s|;]+)/i', $agent, $regs)) {
        $browser_ver = $regs[1];
        $browser = '(Internet Explorer ' . $browser_ver . ') NetCaptor';
    } elseif (preg_match('/Opera[\s|\/]([^\s]+)/i', $agent, $regs)) {
        $browser = 'Opera';
        $browser_ver = $regs[1];
    } elseif (preg_match('/Chrome\/([^\s]+)/i', $agent, $regs)) {
        $browser = 'Chrome';
        $browser_ver = $regs[1];
    } elseif (preg_match('/safari\/([^\s]+)/i', $agent, $regs)) {
        $browser = 'Safari';
        $browser_ver = $regs[1];
    } elseif (preg_match('/OmniWeb\/(v*)([^\s|;]+)/i', $agent, $regs)) {
        $browser = 'OmniWeb';
        $browser_ver = $regs[2];
    } elseif (preg_match('/Netscape([\d]*)\/([^\s]+)/i', $agent, $regs)) {
        $browser = 'Netscape';
        $browser_ver = $regs[2];
    } elseif (preg_match('/Lynx\/([^\s]+)/i', $agent, $regs)) {
        $browser = 'Lynx';
        $browser_ver = $regs[1];
    } elseif (preg_match('/AppleWebKit\/([\d\.]+)/i', $agent, $regs)) {
        $browser = 'AppleWebKit';
        $browser_ver = $regs[1];
    }
    if (!empty($browser)) {
        return addslashes($browser . ' ' . $browser_ver);
    } else {
        return '未知浏览器';
    }
}

/**
 * 获取用户端操作系统
 * @return string
 */
function getUserOs()
{
    $agent = $_SERVER['HTTP_USER_AGENT'];
    if (preg_match('/win/i', $agent) && preg_match('/98/i', $agent)) {
        $os = 'Windows 98';
    } else if (preg_match('/win/i', $agent) && preg_match('/NT 6.0/i', $agent)) {
        $os = 'Windows Vista';
    } else if (preg_match('/win/i', $agent) && preg_match('/NT 6.1/i', $agent)) {
        $os = 'Windows 7';
    } else if (preg_match('/win/i', $agent) && preg_match('/NT 6.2/i', $agent)) {
        $os = 'Windows 8';
    } else if (preg_match('/win/i', $agent) && preg_match('/NT 10.0/i', $agent)) {
        $os = 'Windows 10';#添加win10判断
    } else if (preg_match('/win/i', $agent) && preg_match('/NT 5.1/i', $agent)) {
        $os = 'Windows XP';
    } else if (preg_match('/win/i', $agent) && preg_match('/NT 5/i', $agent)) {
        $os = 'Windows 2000';
    } else if (preg_match('/win/i', $agent) && preg_match('/NT/i', $agent)) {
        $os = 'Windows NT';
    } else if (preg_match('/win/i', $agent) && preg_match('/32/i', $agent)) {
        $os = 'Windows 32';
    } else if (preg_match('/iPhone OS ([\d_]+)/i', $agent, $m)) {
        $os = 'IOS ' . str_replace('_', '.', $m[1]);
    } else if (preg_match('/Android ([\d\.]+)/i', $agent, $m)) {
        $os = 'Android ' . $m[1];
    } else if (preg_match('/unix/i', $agent)) {
        $os = 'Unix';
    } else if (preg_match('/linux/i', $agent)) {
        $os = 'Linux';
    } else if (preg_match('/sun/i', $agent) && preg_match('/os/i', $agent)) {
        $os = 'SunOS';
    } else if (preg_match('/ibm/i', $agent) && preg_match('/os/i', $agent)) {
        $os = 'IBM OS/2';
    } else if (preg_match('/Mac/i', $agent) && preg_match('/PC/i', $agent)) {
        $os = 'Macintosh';
    } else if (preg_match('/PowerPC/i', $agent)) {
        $os = 'PowerPC';
    } else if (preg_match('/AIX/i', $agent)) {
        $os = 'AIX';
    } else if (preg_match('/HPUX/i', $agent)) {
        $os = 'HPUX';
    } else if (preg_match('/NetBSD/i', $agent)) {
        $os = 'NetBSD';
    } else if (preg_match('/BSD/i', $agent)) {
        $os = 'BSD';
    } else if (preg_match('/OSF1/i', $agent)) {
        $os = 'OSF1';
    } else if (preg_match('/IRIX/i', $agent)) {
        $os = 'IRIX';
    } else if (preg_match('/FreeBSD/i', $agent)) {
        $os = 'FreeBSD';
    } else if (preg_match('/teleport/i', $agent)) {
        $os = 'teleport';
    } else if (preg_match('/flashget/i', $agent)) {
        $os = 'flashget';
    } else if (preg_match('/webzip/i', $agent)) {
        $os = 'webzip';
    } else if (preg_match('/offline/i', $agent)) {
        $os = 'offline';
    } else {
        $os = '未知操作系统';
    }
    return $os;
}



/**
 * 字符串截取
 * @param $Str
 * @param $Length
 * @return bool|string
 */
function mySubstr($Str, $Length)
{
    $i = 0;
    $l = 0;
    $ll = strlen($Str);
    $s = $Str;
    $f = true;
    while ($i <= $ll) {
        if (ord($Str{$i}) < 0x80) {
            $l++;
            $i++;
        } else if (ord($Str{$i}) < 0xe0) {
            $l++;
            $i += 2;
        } else if (ord($Str{$i}) < 0xf0) {
            $l += 2;
            $i += 3;
        } else if (ord($Str{$i}) < 0xf8) {
            $l += 1;
            $i += 4;
        } else if (ord($Str{$i}) < 0xfc) {
            $l += 1;
            $i += 5;
        } else if (ord($Str{$i}) < 0xfe) {
            $l += 1;
            $i += 6;
        }
        if (($l >= $Length - 1) && $f) {
            $s = substr($Str, 0, $i);
            $f = false;
        }
        if (($l > $Length) && ($i < $ll)) {
            $s = $s . '...';
            break; //如果进行了截取，字符串末尾加省略符号“...”
        }
    }
    return $s;
}
