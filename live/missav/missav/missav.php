<?php

//分类  http://api.22ba4.top/missav/missav.php?tag=东京热&page=3
//搜索  http://api.22ba4.top/missav/missav.php?key=美女&page=3
//视频详情 http://api.22ba4.top/missav/missav.php?id=/cn/avsa-306

// 网站分类和对应ID的映射关系
$categories = array(

    // 中文字幕
    "中文字幕" => "/dm264/cn/chinese-subtitle",
    //VR
    "VR" => "/cn/genres/VR",
    //素人
    "素人siro" => "/dm23/cn/siro",
    "素人luxu" => "/dm20/cn/luxu",
    "素人gana" => "/dm17/cn/gana",
    "素人maan" => "/dm14/cn/maan",
    "素人scute" => "/dm22/cn/scute",
    "素人ara" => "/dm19/cn/ara",
    //无码影片
    "无码流出" => "/dm548/cn/uncensored-leak",
    "FC2" => "/dm92/cn/fc2",
    "HEYZO" => "/dm396/cn/heyzo",
    "东京热" => "/dm29/cn/tokyohot",
    "一本道" => "/dm32774/cn/1pondo",
    "Caribbeancom" => "/dm58571/cn/caribbeancom",
    "caribbeancompr" => "/dm934/cn/caribbeancompr",
    "10musume" => "/dm29862/cn/10musume",
    "pacopacomama" => "/dm497/cn/pacopacomama",
    "gachinco" => "/dm108/cn/gachinco",
    "xxxav" => "/dm25/cn/xxxav",
    "人妻斩" => "/dm24/cn/marriedslash",
    "顽皮4610" => "/dm19/cn/naughty4610",
    "顽皮0930" => "/dm21/cn/naughty0930",

    //国产AV
    "麻豆传媒" => "/dm30/cn/madou",
    "TWAV" => "/dm17/cn/twav",
    "Furuke" => "/dm14/cn/furuke"


	
	
	
	
	
);

// 解析 GET 请求参数
if(isset($_GET['tag']) && isset($_GET['page'])) {
    // 处理分类列表接口请求
    $tag = $_GET['tag'];
    $page = $_GET['page'];

    if(array_key_exists($tag, $categories)) {
        $category = $categories[$tag];
        $url = "https://missav789.com".$category."?page=".$page;
        
        $html = file_get_contents($url);

        // 截取源码
        $start = '<div class="grid';
        $end = '<nav x-data';
        $startPos = strpos($html, $start);
        $endPos = strpos($html, $end);
        $length = $endPos - $startPos;
        $html = substr($html, $startPos, $length);

        // 替换第一个 "alt=" 为空
        $html = preg_replace('/alt="/', '', $html, 1);

        // 分割文本
        $segments = explode('<div @mouseenter=', $html);
        $videos = array();

        // 提取视频信息
        foreach($segments as $segment) {
            if(trim($segment) != "") {
                // 替换第一个 "alt=" 和第一个 "data-src=" 为空
                $segment = preg_replace('/alt="/', '', $segment, 1);
                $segment = preg_replace('/data-src="/', '', $segment, 1);
                
                preg_match('/<a href="(.*?)"/', $segment, $id_match);
                preg_match('/data-src="(.*?)"/', $segment, $image_match);
                preg_match('/alt="(.*?)"/', $segment, $title_match);

                // 检查图片是否为空
                if (!empty($image_match[1])) {
                    // 替换 ID 中的文本 "https://missav789.com" 为空
                $id = str_replace("https://missav789.com", "", $id_match[1]);
				
                    $video = array(
                        "id" => isset($id) ? $id : "",
                        "image" => isset($image_match[1]) ? $image_match[1] : "",
                        "title" => isset($title_match[1]) ? $title_match[1] : ""
						
						
                    );
                    $videos[] = $video;
                }
            }
        }
        // 输出 JSON 格式数据
        header('Content-Type: application/json');
        // 返回结果
        if (!empty($videos)) {
            echo json_encode(array("code" => "OK", "data" => $videos));
        } else {
            echo json_encode(array("code" => "null", "data" => array()));
        }
    } else {
        echo json_encode(array("code" => "null", "data" => array(), "error" => "Invalid category"));
    }
} elseif(isset($_GET['id'])) {
    // 处理获取视频详情接口请求
    $video_id = $_GET['id'];
    $url = "https://missav789.com".$video_id;
    
    $html = file_get_contents($url);

    // 截取源码
    $start = 'm3u8';
    $end = "'";
    $startPos = strpos($html, $start);
    $endPos = strpos($html, $end, $startPos);
    $length = $endPos - $startPos;
    $source = substr($html, $startPos, $length);

    // 获取播放地址散件数组
    $parts = explode('|', $source);

    // 拼接真实播放地址
    $real_url = $parts[8] . '://' . $parts[7] . '.' . $parts[6] . '/' . $parts[5] . '-' . $parts[4] . '-' . $parts[3] . '-' . $parts[2] . '-' . $parts[1] . '/' . $parts[10] . '/' . $parts[9] . '.' . $parts[0];
    // 输出 JSON 格式数据
        header('Content-Type: application/json');
    // 返回真实播放地址
    $response = array("code" => empty($real_url) ? "null" : "ok", "m3u8" => $real_url);
    echo json_encode($response);
} elseif(isset($_GET['key']) && isset($_GET['page'])) {
    // 处理搜索视频列表接口请求
    $key = urlencode($_GET['key']);
    $page = $_GET['page'];
    $url = "https://missav789.com/cn/search/".$key."?page=".$page;
    $html = file_get_contents($url);

    // 截取源码
    $start = '<div class="grid';
    $end = '<nav x-data';
    $startPos = strpos($html, $start);
    $endPos = strpos($html, $end);
    $length = $endPos - $startPos;
    $html = substr($html, $startPos, $length);

    // 分割文本
    $segments = explode('<div @mouseenter=', $html);
    $videos = array();

    // 提取视频信息
    foreach($segments as $segment) {
        if(trim($segment) != "") {
            // 替换第一个 "alt=" 和第一个 "data-src=" 为空
            $segment = preg_replace('/alt="/', '', $segment, 1);
            $segment = preg_replace('/data-src="/', '', $segment, 1);
            
            preg_match('/<a href="(.*?)"/', $segment, $id_match);
            preg_match('/data-src="(.*?)"/', $segment, $image_match);
            preg_match('/alt="(.*?)"/', $segment, $title_match);

            // 检查图片和视频ID是否为空
            if(isset($image_match[1]) && !empty($image_match[1]) && isset($id_match[1]) && !empty($id_match[1])) {
				// 替换 ID 中的文本 "https://missav789.com" 为空
                $id = str_replace("https://missav789.com", "", $id_match[1]);
                // 将视频信息添加到结果数组
                $video = array(
                    "id" => isset($id) ? $id : "",
                    "image" => isset($image_match[1]) ? $image_match[1] : "",
                    "title" => isset($title_match[1]) ? $title_match[1] : ""
                );
                $videos[] = $video;
            }
        }
    }
    // 输出 JSON 格式数据
        header('Content-Type: application/json');
    // 返回结果
    if (!empty($videos)) {
        echo json_encode(array("code" => "ok", "data" => $videos));
    } else {
        echo json_encode(array("code" => "null", "data" => array()));
    }
} else {
    // 返回错误信息
    echo json_encode(array("error" => "Invalid request"));
}

?>
