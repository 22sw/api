<?php

// 获取所有分类 https://api.yujiameimei.com/xiangjiao/xj.php?getsort
// 获取某个分类下视频列表   https://api.yujiameimei.com/xiangjiao/xj.php?sort=14&page=1
// 搜索  https://api.yujiameimei.com/xiangjiao/xj.php?keyword=美女&page=1
// 获取视频详情  https://api.yujiameimei.com/xiangjiao/xj.php?id=62634


 $cookie ='xxx_api_auth=6631653435663161653466643661353837643133373834343861636563353363';
// 获取当前时间的十三位时间戳
        list($t1, $t2) = explode(' ', microtime());
        $str_time = sprintf('%u', (floatval($t1) + floatval($t2)) * 1000);
		
		
        //$latestdomain = 'https://678actainrhb.cjvapixj.com';
        $latestdomain = 'http://43.135.90.103:8099';
 
 function aes_encrypt($data, $key, $iv) {
    return bin2hex(openssl_encrypt($data, 'aes-128-cbc', $key, OPENSSL_RAW_DATA, $iv));
}

function aes_decrypt($data, $key, $iv) {
    return openssl_decrypt(hex2bin($data), 'aes-128-cbc', $key, OPENSSL_RAW_DATA, $iv);
}

$key = "625202f9149maomi";
$iv = "5efd3f6060emaomi";


// 判断是否传入了参数
if (isset($_GET['getsort'])) {
    // 定义分类名称与对应数字索引的映射关系
    $categories = [
	    
		'广场' => "listAll0",
		'最新' => "listAll",
        '热门' => "listHot"
		
		
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
	
	
} else if (isset($_GET['sort']) && isset($_GET['page'])) {
	
	
   $category = isset($_GET['sort']) ? $_GET['sort'] : '';
   $page = isset($_GET['page']) ? intval($_GET['page']) : 1;

if($category==="listAll0"){
	
	$page = rand(1, 30000);
	$category="listAll";
}


$data = json_encode(["page" => $page, "perPage" => 10]);

// 加密数据并进行HEX编码
$encrypted_data = aes_encrypt($data, $key, $iv);
$sig = md5("data=" . $encrypted_data . "maomi_pass_xyz");

// 发送POST请求
$url = "$latestdomain/api/videos/$category";
$post_fields = "data=" . $encrypted_data . "&sig=" . $sig;

$options = [
    CURLOPT_URL => $url,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => $post_fields,
    CURLOPT_RETURNTRANSFER => true
];

$ch = curl_init();
curl_setopt_array($ch, $options);
$response = curl_exec($ch);
curl_close($ch);

// 解密返回的数据
$sourceCode = aes_decrypt($response, $key, $iv);
//$sourceCode = json_decode($decrypted_response, true);


		
    //echo $sourceUrl;
    // 获取源码
    //$sourceCode = file_get_contents($sourceUrl);

    // 将获取的 JSON 数据转换为 PHP 数组
    $dataArray = json_decode($sourceCode, true);

    // 初始化返回对象
    $response = [];

    // 处理视频数组
    if (!empty($dataArray['data']['list'])) {
        $videos = [];
        foreach ($dataArray['data']['list'] as $video) {
            // 修改键名
		//	if ($video['isvip']!= 1) {
            $videoItem = [
                'id' => $video['mv_play_url'],
                'title' => $video['mv_title'],
                'image' => $video['mv_img_url'],
                'date' => $video['mv_created'],
				'upnum' => $video['mv_like'],
               // 'downcount' => $video['downcount_total'],
                'commentcount' => $video['mv_comment'],
                'playcount' => $video['playcount_total'],
                'nickname' => $video['mu_name'],
                'avatar' => $video['mu_avatar'], 
                //'mobi' => $video['mobi'],
                'uid' => $video['mu_id']
            ];
			
			  
	
            // 添加到视频数组中
            $videos[] = $videoItem;
     //  }
		 }
        // 将处理过的视频数组命名为 videos
        $response['videos'] = $videos;
        // 设置 code 为 ok
        $response['code'] = 'ok';
    } else {
        // 视频数组为空，设置 code 为 null
        $response['code'] = 'null';
    }

    // 输出JSON格式数据
   header('Content-Type: application/json');
    echo json_encode($response);
}  else if(isset($_GET['id']) ) {
    
    $id = isset($_GET['id']) ? $_GET['id'] : '';
$new_domain = "owerbsd.hxqqzu.xyz";

// 使用正则表达式匹配并替换URL中的域名或IP地址
$updated_url = preg_replace('/(http:\/\/|https:\/\/)[^\/]+/', '${1}' . $new_domain, $id);

      
    $response['video'] = $updated_url;
      
        


// 设置返回状态码
$response['code'] = $id ? 'ok' : 'error';

// 输出JSON格式数据
header('Content-Type: application/json');
echo json_encode($response);


	
	
}

?>
