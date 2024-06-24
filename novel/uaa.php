<?php

// 获取所有分类 https://api.yujiameimei.com/155/155.php?getsort
// 获取某个分类下视频列表   https://api.yujiameimei.com/155/155.php?sort=14&page=1
// 搜索  https://api.yujiameimei.com/xiangjiao/155.php?keyword=美女&page=1
// 获取视频详情  https://api.yujiameimei.com/155/155.php?id=62634


 



	$latestdomain ="https://www.uaa003.com";
	//https://www.uaa.com  https://www.uaa001.com  https://www.uaa004.com
	
 
	
	



// 判断是否传入了参数
if (isset($_GET['getsort'])) {
	
    $sourceUrl = $latestdomain . "/api/novel/app/novel/categories";
   
    $sourceCode = file_get_contents($sourceUrl);

    // 将获取的 JSON 数据转换为 PHP 数组
$dataArray = json_decode($sourceCode, true);

// 初始化返回对象
$response = [];

// 解析数据并修改键名
if (!empty($dataArray)) {
    $categories = [];
    foreach ($dataArray['model'] as $category) {
		//if ( $category['des'] !='成人图片'  && $category['des'] !='情色小说' ){
        $categoryItem = [
             'sortid' => $category['id'],
			'id' => $category['name'],
            'title' => $category['name'],
            'sort' => $category['sort'],
            'date' => $category['updateTime']
            
        ];
        $categories[] = $categoryItem;
   // }
    }
    // 将处理过的数组命名为 categories
    $response['categories'] = $categories;
    // 设置 code 为 ok 或 null
    $response['code'] = !empty($categories) ? 'ok' : 'null';
} else {
    // 数据为空，设置 code 为 null
    $response['code'] = 'null';
}

// 输出 JSON 格式数据
header('Content-Type: application/json');
echo json_encode($response);


https://www.uaa.com/api/novel/app/novel/search?category=%E9%83%BD%E5%B8%82%E6%BF%80%E6%83%85&excludeTags=&finished=&includeTags=&keyword=&orderType=2&page=1&searchType=1&size=40&source=&space=

} else if (isset($_GET['sort']) && isset($_GET['page'])) {
	
	
    $category = isset($_GET['sort']) ? urlencode($_GET['sort']) : '';
    $page = isset($_GET['page']) ? intval($_GET['page']) : 1;

     // 构建目标网站的 URL
$sourceUrl = $latestdomain . "/api/novel/app/novel/search?category={$category}&excludeTags=&finished=&includeTags=&keyword=&orderType=2&page={$page}&searchType=1&size=40&source=&space=";
   
    // 获取源码
	$sourceCode = file_get_contents($sourceUrl,false,$context);

	// 将获取的 JSON 数据转换为 PHP 数组
    $dataArray = json_decode($sourceCode, true);

    // 初始化返回对象
    $response = [];

    if (!empty($dataArray)) {
    $categories = [];
    foreach ($dataArray['model']['data'] as $category) {
		//if ($category['need_vip']!=1 ){
        $categoryItem = [
            'id' => $category['id'],
            'title' => $category['title'],
            'image' => $category['coverUrl'],
			 'chapter' => $category['latestUpdate'],
			 
            'date' => $category['updateTime']
        ];
        $categories[] = $categoryItem;
   // }
   }
    // 将处理过的数组命名为 categories
    $response['list'] = $categories;
    // 设置 code 为 ok 或 null
    $response['code'] = !empty($categories) ? 'ok' : 'null';
} else {
    // 数据为空，设置 code 为 null
    $response['code'] = 'null';
}

// 输出 JSON 格式数据
header('Content-Type: application/json');
echo json_encode($response);



	
} else if(isset($_GET['keyword']) && isset($_GET['page']) ) {
	
	
    $keyword = isset($_GET['keyword']) ? urlencode($_GET['keyword']) : '';
    $page = isset($_GET['page']) ? $_GET['page'] : '';
    // 构建目标网站的 URL
  
$sourceUrl = "$latestdomain/api/novel/app/novel/search?category=&excludeTags=&finished=&includeTags=&keyword=$keyword&orderType=0&page=$page&searchType=1&size=40&source=&space="; 

	 
    // 获取源码
	$sourceCode = file_get_contents($sourceUrl,false,$context);
 // 将获取的 JSON 数据转换为 PHP 数组
    $dataArray = json_decode($sourceCode, true);

    // 初始化返回对象
    $response = [];

    if (!empty($dataArray)) {
    $categories = [];
    foreach ($dataArray['model']['data'] as $category) {
		//if ($category['need_vip']!=1 ){
        $categoryItem = [
            'id' => $category['id'],
            'title' => $category['title'],
            'image' => $category['coverUrl'],
			 'chapter' => $category['latestUpdate'],
			 
            'date' => $category['updateTime']
        ];
        $categories[] = $categoryItem;
   // }
   }
    // 将处理过的数组命名为 categories
    $response['list'] = $categories;
    // 设置 code 为 ok 或 null
    $response['code'] = !empty($categories) ? 'ok' : 'null';
} else {
    // 数据为空，设置 code 为 null
    $response['code'] = 'null';
}

// 输出 JSON 格式数据
header('Content-Type: application/json');
echo json_encode($response);
	
	
} else if(isset($_GET['id']) ) {
	
    $id = isset($_GET['id']) ? $_GET['id'] : '';
	
    $sourceUrl = $latestdomain . "/api/novel/app/novel/catalog/" .$id;
   

   
	   $sourceCode = file_get_contents($sourceUrl );
	  
	   
	
	
	// 将获取的 JSON 数据转换为 PHP 数组
    $dataArray = json_decode($sourceCode, true);

    // 初始化返回对象
    $response = [];

    if (!empty($dataArray)) {
    // 只提取第一个菜单项的 id 值
    if (!empty($dataArray['model']['menus']) && isset($dataArray['model']['menus'][0]['id'])) {
        $chapterid = $dataArray['model']['menus'][0]['id'];
        // 设置 code 为 ok
        //$response['code'] = 'ok';
    } else {
        // 如果没有菜单项或没有 id，设置 code 为 null
        $response['code'] = 'null';
    }
} else {
    // 数据为空，设置 code 为 null
    $response['code'] = 'null';
}


	$chapterUrl =$latestdomain. '/novel/chapter?id=' . $chapterid;
	
	


    // 初始化返回对象
    $response = [];
    
    // 设置 code
    //$response['code'] = !empty($dataArray['list'][0]) ? 'ok' : 'null';
$response['code'] ='ok';
// 处理视频数组
//if (!empty($dataArray['list'][0])) {
    // 从 data 对象中直接获取 httpurl 的值作为视频播放地址
    // 设置视频对象
  
   
    $response['id'] = $chapterUrl;
    $response['image'] = '';
    $response['title'] = '';
	
	$response['novel'] = '
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="refresh" content="2;url=' . htmlspecialchars($chapterUrl) . '">
    <title>跳转中...</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin-top: 50px;
        }
        #countup {
            font-size: 20px;
            font-weight: bold;
        }
    </style>
    <script>
        let countup = 0;
        function updateCountup() {
            countup++;
            document.getElementById("countup").textContent = countup;
        }
        setInterval(updateCountup, 1000);
        window.onload = updateCountup;
    </script>
</head>
<body>
    <p>沉浸模式载入中，耐心等待...</p>
    <p>已等待 <span id="countup">0</span> 秒。</p>
    <!--<p>如果您的浏览器没有自动跳转，请 <a href="' . htmlspecialchars($chapterUrl) . '">点击这里</a>。</p>-->
</body>
</html>
';


 


// 输出JSON格式数据
header('Content-Type: application/json');
echo json_encode($response);
	
	
}

?>
