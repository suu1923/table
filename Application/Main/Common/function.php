<?php

/**
 * 接口返回正确格式
 */
function onOk($data = '')
{
    $result = ['errno' => 0, 'errmsg' => '成功','count'=>null, 'data' => $data];
    return $result;
}
/**
 * @param $errmsg
 * @param int $errno
 * 接口返回错误格式
 */
function onError($errmsg, $errno = 1)
{
    $result = ['errno' => $errno, 'errmsg' => $errmsg];
    return $result;
}

/**
 * 判断数组是不是只有一个元素
 * 是 true 否false
 */
function checkArrOnly($arr)
{
	$count = count($arr);
	return $count;
}

function getUserId(){
    return $_SESSION['uid'];
}

function getID(){
    return md5(uniqid());
}