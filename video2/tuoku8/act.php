<?php


//女优视频列表 id（dm127/cn/actresses/波多野結衣） 页码 http://api.22ba4.top/missav/actresses.php?actid=/dm127/cn/actresses/波多野結衣&page=2
//女优列表 身高段 罩杯 年龄段 出道时间（2024年前） 页码  https://api.22ba4.top/missav/actresses.php?height=160-165&cup=b&age=20-24&debut=2024&page=1



// 判断是否存在GET请求参数
if (isset($_GET['height']) && isset($_GET['cup']) && isset($_GET['age']) && isset($_GET['debut']) && isset($_GET['page'])) {
    // 处理获取女优列表接口请求
    $height = $_GET['height'];
    $cup = $_GET['cup'];
    $age = $_GET['age'];
    $debut = $_GET['debut'];
    $page = $_GET['page'];
    $url = "https://missav789.com/cn/actresses?height=$height&cup=$cup&age=$age&debut=$debut&page=$page";

    // 获取HTML内容
    $html = file_get_contents($url);

    // 截取源码
    $start = 'mx-auto grid';
    $end = '<nav x-data';
    $startPos = strpos($html, $start);
    $endPos = strpos($html, $end);
    $length = $endPos - $startPos;
    $html = substr($html, $startPos, $length);

    // 分割文本
    $segments = explode('</li>', $html);
    $actresses = array();

    // 提取女优信息
    foreach ($segments as $segment) {
        if (trim($segment) != "") {
            // 提取演员ID、图片、标题
            preg_match('/<a href="(.*?)"/', $segment, $id_match);
            preg_match('/<img src="(.*?)"/', $segment, $image_match);
            preg_match('/alt="(.*?)"/', $segment, $title_match);

            // 检查图片是否为空
            if (!empty($image_match[1])) {
                // 解码 ID 中的 URL 编码
                $id = isset($id_match[1]) ? urldecode($id_match[1]) : "";
                // 替换 ID 中的文本 "https://missav789.com" 为空
                $id = str_replace("https://missav789.com", "", $id);
                $actress = array(
                    "id" => $id,
                    "image" => isset($image_match[1]) ? urldecode($image_match[1]) : "",
                    "title" => isset($title_match[1]) ? urldecode($title_match[1]) : ""
                );
                $actresses[] = $actress;
            }
        }
    }
    // 输出 JSON 格式数据
    header('Content-Type: application/json');
    // 返回结果
    if (!empty($actresses)) {
        echo json_encode(array("code" => "ok", "data" => $actresses));
    } else {
        echo json_encode(array("code" => "null", "data" => array()));
    }
} elseif (isset($_GET['actid']) && isset($_GET['page'])) {
    // 处理获取指定女优作品接口请求
    $actid = $_GET['actid'];
    $page = $_GET['page'];
    $url = "https://missav789.com$actid?page=$page";

    // 获取HTML内容
    $html = file_get_contents($url);

    // 截取源码
    $start = 'mx-auto grid';
    $end = '<nav x-data';
    $startPos = strpos($html, $start);
    $endPos = strpos($html, $end);
    $length = $endPos - $startPos;
    $html = substr($html, $startPos, $length);

    // 分割文本
    $segments = explode('@mouseenter=', $html);
    $actress_works = array();

    // 提取女优作品信息
    foreach ($segments as $segment) {
        if (trim($segment) != "") {
            // 替换第一个 "alt=" 和第一个 "data-src=" 为空
            $segment = preg_replace('/alt="/', '', $segment, 1);
            $segment = preg_replace('/data-src="/', '', $segment, 1);

            // 提取视频ID、图片、标题
            preg_match('/<a href="(.*?)"/', $segment, $id_match);
            preg_match('/data-src="(.*?)"/', $segment, $image_match);
            preg_match('/alt="(.*?)"/', $segment, $title_match);

            // 检查图片是否为空
            if (!empty($image_match[1])) {
                // 解码 ID 中的 URL 编码
                $id = isset($id_match[1]) ? urldecode($id_match[1]) : "";
                // 替换视频ID中的 "https://missav789.com" 为空
                $id = str_replace("https://missav789.com", "", $id);
                $actress_work = array(
                    "id" => $id,
                    "image" => isset($image_match[1]) ? urldecode($image_match[1]) : "",
                    "title" => isset($title_match[1]) ? urldecode($title_match[1]) : ""
                );
                $actress_works[] = $actress_work;
            }
        }
    }
    // 输出 JSON 格式数据
    header('Content-Type: application/json');
    // 返回结果
    if (!empty($actress_works)) {
        echo json_encode(array("code" => "ok", "data" => $actress_works));
    } else {
        echo json_encode(array("code" => "null", "data" => array()));
    }
} else {
    // 返回错误信息
    echo json_encode(array("error" => "Invalid request"));
}

?>
