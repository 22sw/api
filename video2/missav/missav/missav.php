<?php

//分类  http://api.22ba4.top/missav/missav.php?tag=东京热&page=3
//搜索  http://api.22ba4.top/missav/missav.php?key=美女&page=3
//视频详情 http://api.22ba4.top/missav/missav.php?id=/cn/avsa-306
//女优视频列表 id（dm127/cn/actresses/波多野結衣） 页码 http://api.22ba4.top/missav/actresses.php?sort=/dm127/cn/actresses/波多野結衣&page=2
//女优列表 身高段 罩杯 年龄段 出道时间（2024年前） 页码  https://api.22ba4.top/missav/actresses.php?height=160-165&cup=b&age=20-24&debut=2024&page=1


 

// 解析 GET 请求参数
// 判断是否传入了参数
    $latestdomain ="https://missav789.com";
	
	
if (isset($_GET['getsort'])) {
    // 定义分类名称与对应数字索引的映射关系
    $categories = [
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
	
    ];

    // 初始化分类数组
    $categoriesArray = [];

    // 遍历分类名称与数字索引的映射关系，构建ID和Title的形式
    foreach ($categories as $title => $index) {
        $categoriesArray[] = [
            'id' => $index,
            'title' => $title
        ];
    }

    // 构建返回对象
    $response = [
        'code' => !empty($categoriesArray) ? 'ok' : 'null',
        'categories' => $categoriesArray
    ];

    // 输出JSON格式数据
    header('Content-Type: application/json');
    echo json_encode($response);
} else if(isset($_GET['sort']) && isset($_GET['page'])) {
    // 处理分类列表接口请求
    $category = $_GET['sort'];
    $page = $_GET['page'];

     
       
        $url = $latestdomain.$category."?page=".$page;
        
        $html = file_get_contents($url);

        // 截取源码
        $start = '<div x-data x-init=';
        $end = '<div class="mb-5';
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
				preg_match('/bg-opacity-75">\n(.*?)\n<\/span>/s', $segment, $duration_match);

                // 检查图片是否为空bg-opacity-75">
                if (!empty($image_match[1])) {
                    // 替换 ID 中的文本 "$latestdomain" 为空
                    $id = str_replace("$latestdomain", "", $id_match[1]);
					
                    $video = array(
                        "id" => isset($id) ? $id : "",
                        "image" => isset($image_match[1]) ? $image_match[1] : "",
                        "date" => isset($duration_match[1]) ? $duration_match[1] : "",
						"title" => isset($title_match[1]) ? $title_match[1] : ""
                    );
                    $videos[] = $video;
                }
            }
        }
        // 输出 JSON 格式数据
        header('Content-Type: application/json');
        
        echo json_encode(array("code" => "OK", "videos" => $videos));
        
    
} elseif(isset($_GET['id'])) {
    // 处理获取视频详情接口请求
    $video_id = $_GET['id'];
    $url = $latestdomain .$video_id;
    
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
	
	
	// 从源码中截取标题
        preg_match('/title" content="(.*?)" \/>/', $html, $titleMatches);
        $title = isset($titleMatches[1]) ? $titleMatches[1] : '';

        // 从源码中截取图片地址
        preg_match("/image\" content=\"(.*?)\"/", $html, $imageMatches);
        $imageUrl = isset($imageMatches[1]) ? $imageMatches[1] : '';


        // 构建返回对象
        $response = [];
        //if (!empty($videoUrl)) {
            $response = [
                'code' => 'ok',
                'title' => $title,
                'image' => $imageUrl,
                'video' => $real_url,
                'recommend' => []
            ];
	
	
	
	 // 获取推荐视频列表
        
            
		 
        header('Content-Type: application/json');
            echo json_encode($response, JSON_UNESCAPED_UNICODE);
      
	
} elseif(isset($_GET['keyword']) && isset($_GET['page'])) {
    // 处理搜索视频列表接口请求
    $key = urlencode($_GET['keyword']);
    $page = $_GET['page'];
    $url = "$latestdomain/cn/search/".$key."?page=".$page;
    $html = file_get_contents($url);

    // 截取源码
   $start = '<div x-data x-init=';
        $end = '<div class="mb-5';
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
			preg_match('/bg-opacity-75">\n(.*?)\n<\/span>/s', $segment, $duration_match);

            // 检查图片和视频ID是否为空
            if(isset($image_match[1]) && !empty($image_match[1]) && isset($id_match[1]) && !empty($id_match[1])) {
				// 替换 ID 中的文本 "$latestdomain" 为空
                $id = str_replace("$latestdomain", "", $id_match[1]);
                // 将视频信息添加到结果数组
                $video = array(
                    "id" => isset($id) ? $id : "",
                    "image" => isset($image_match[1]) ? $image_match[1] : "",
                    "date" => isset($duration_match[1]) ? $duration_match[1] : "",
					"title" => isset($title_match[1]) ? $title_match[1] : ""
                );
                $videos[] = $video;
            }
        }
    }
    // 输出 JSON 格式数据
        header('Content-Type: application/json');
    // 返回结果
    
        echo json_encode(array("code" => "ok", "videos" => $videos));
    
}elseif (isset($_GET['actress'])  && isset($_GET['page'])) {
	$actress = $_GET['actress'];
    $page = $_GET['page'];
	
	$sourceUrl = $latestdomain . "/cn/actresses?page=$page";
	
	 // 获取源码
    $sourceCode = file_get_contents($sourceUrl);

    // 利用正则表达式截取文本
    preg_match('/mx-auto grid(.*?)<nav x-data/s', $sourceCode, $matches);

    // 如果匹配到结果
    if (isset($matches[1])) {
        // 提取内容
        $content = $matches[1];

        // 使用分隔符号“rating positive”分割得到数组
        $categories = explode('</li>', $content);
        
        // 移除数组末尾的空成员
        array_pop($categories);

        // 初始化结果数组
        $result = [];

        // 循环处理每个分类
        foreach ($categories as $category) {
            // 提取图片地址
            preg_match('/<img src="(.*?)"/s', $category, $imageMatches);
		$image = isset($imageMatches[1]) ? ( $domain . $imageMatches[1]) : '';

           // 提取id
           preg_match('/href="(.*?)"/s', $category, $matches);
		    $id = isset($matches[1]) ? urldecode(str_replace($latestdomain,"",$matches[1])) : '';
			
		   // 提取标题
           preg_match('/alt="(.*?)"/s', $category, $matches);
		   $title = isset($matches[1]) ?  $matches[1]  : '';
		   
		   // 提取数量题
           preg_match('/text-nord10">(.*?)</s', $category, $numbermatches);
		   $number = isset($numbermatches[1]) ?  $numbermatches[1]  : '';

           

            // 判断 id 不为空且 image 包含 "/categories/"
            if (!empty($id) ) {
                // 构建分类对象
                $categoryObject = [
                    //'count' => $count,
                    'id' => $id,
					'image' => $image,
                    'title' => $title,
					'number' => $number
                ];

                // 将分类对象添加到结果数组中
                $result[] = $categoryObject;
            }
        }

        // 构建返回对象
        $response = [
            'code' => count($result) > 0 ? 'ok' : null,
            'actresses' => $result
        ];

        // 输出 JSON 格式数据
        header('Content-Type: application/json');
        echo json_encode($response);
    } else {
        // 如果未匹配到结果，则返回错误信息
        echo json_encode(['code' => null]);
    }
	
// 判断是否存在GET请求参数
} elseif(isset($_GET['height']) && isset($_GET['cup']) && isset($_GET['age']) && isset($_GET['debut']) && isset($_GET['page'])) {
    // 处理获取女优列表接口请求
    $height = $_GET['height'];
    $cup = $_GET['cup'];
    $age = $_GET['age'];
    $debut = $_GET['debut'];
    $page = $_GET['page'];
    $url = $latestdomain . "/cn/actresses?height=$height&cup=$cup&age=$age&debut=$debut&page=$page";

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
    foreach($segments as $segment) {
        if(trim($segment) != "") {
            // 提取演员ID、图片、标题
            preg_match('/<a href="(.*?)"/', $segment, $id_match);
            preg_match('/<img src="(.*?)"/', $segment, $image_match);
            preg_match('/alt="(.*?)"/', $segment, $title_match);

            // 检查图片是否为空
            if (!empty($image_match[1])) {
                // 替换 ID 中的文本 "$latestdomain" 为空
                $id = str_replace("$latestdomain", "", $id_match[1]);
                $actress = array(
                    "id" => isset($id) ? urldecode($id) : "",
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
    
        echo json_encode(array("code" => "ok", "videos" => $actresses));
     
} 

?>
