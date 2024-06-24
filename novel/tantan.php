<?php

// 获取所有分类 https://api.yujiameimei.com/155/155.php?getsort
// 获取某个分类下视频列表   https://api.yujiameimei.com/155/155.php?sort=14&page=1
// 搜索  https://api.yujiameimei.com/xiangjiao/155.php?keyword=美女&page=1
// 获取视频详情  https://api.yujiameimei.com/155/155.php?id=62634




    //森林
	//$latestdomain ="https://slapibf.com";
	//Jkun
	// $latestdomain ="https://www.jkunzyapi.com";
	//探探
	$latestdomain ="https://apittzy.com";
	//155
   // $latestdomain ="https://155api.com";
   //老鸭
	//$latestdomain ="https://api.apilyzy.com";
 



// 判断是否传入了参数
if (isset($_GET['getsort'])) {
	
    $sourceUrl = "$latestdomain/api.php/provide/art/?ac=list";
   
    $sourceCode = file_get_contents($sourceUrl);

    // 将获取的 JSON 数据转换为 PHP 数组
    $dataArray = json_decode($sourceCode, true);

    // 初始化返回对象
    $response = [];

    // 解析数据并修改键名
    if (!empty($dataArray)) {
        $categories = [];
        foreach ($dataArray['class']  as $category) { 
            $categoryItem = [
                'id' => $category['type_id'],
                'type_pid' => $category['type_pid'],
                'title' => $category['type_name']  
            ];
            $categories[] = $categoryItem;
   
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


} else if (isset($_GET['sort']) && isset($_GET['page'])) {
	
    $category = isset($_GET['sort']) ? $_GET['sort'] : '';
    $page = isset($_GET['page']) ? intval($_GET['page']) : 1;

     // 构建目标网站的 URL
        $sourceUrl = "$latestdomain/api.php/provide/art/?ac=list&t=$category&pg=$page&limit=20";
      
    // 获取源码
    $sourceCode = file_get_contents($sourceUrl);

    // 将获取的 JSON 数据转换为 PHP 数组
    $dataArray = json_decode($sourceCode, true);

    // 初始化返回对象
    $response = [];

    // 处理视频数组
    if (!empty($dataArray['list'])) {
        $videos = [];
        foreach ($dataArray['list'] as $video) {
            // 修改键名 
            $videoItem = [
                'id' => $video['art_id'],
                'title' => $video['art_name'],
                'image' => $video['art_pic'],
                'date' => $video['art_time'],
                'type_id' => $video['type_id'] 
            ];
            // 添加到视频数组中
            $videos[] = $videoItem;
         
		 }
        // 将处理过的视频数组命名为 videos
        $response['list'] = $videos;
        // 设置 code 为 ok
        $response['code'] = 'ok';
    } else {
        // 视频数组为空，设置 code 为 null
        $response['code'] = 'null';
    }

    // 输出JSON格式数据
    header('Content-Type: application/json');
    echo json_encode($response);
	
} else if(isset($_GET['keyword']) && isset($_GET['page']) ) {
    $keyword = isset($_GET['keyword']) ? urlencode($_GET['keyword']) : '';
    $page = isset($_GET['page']) ? $_GET['page'] : '';
    // 构建目标网站的 URL
  
    $sourceUrl = "$latestdomain/api.php/provide/art/?ac=list&wd=$keyword&pg=$page";
     
    $sourceCode = file_get_contents($sourceUrl);
    
	
    // 将获取的 JSON 数据转换为 PHP 数组
    $dataArray = json_decode($sourceCode, true);

    // 初始化返回对象
    $response = [];
    
    // 处理视频数组
    if (!empty($dataArray['list'])) {
        $videos = [];
        foreach ($dataArray['list'] as $video) {
            // 修改键名 
            $videoItem = [
                'id' => $video['art_id'],
                'title' => $video['art_name'],
                'image' => $video['art_pic'],
                'date' => $video['art_time'],
                'type_id' => $video['type_id'] 
            ];
            // 添加到视频数组中
            $videos[] = $videoItem;
         
		 }
        // 将处理过的视频数组命名为 videos
        $response['list'] = $videos;
        // 设置 code 为 ok
        $response['code'] = 'ok';
    } else {
        // 视频数组为空，设置 code 为 null
        $response['code'] = 'null';
    }

    // 输出JSON格式数据
    header('Content-Type: application/json');
    echo json_encode($response);
	
	
} else if(isset($_GET['id']) ) {
	
    $id = isset($_GET['id']) ? $_GET['id'] : '';
   
	$sourceUrl = "$latestdomain/api.php/provide/art/?ac=detail&ids=$id";
    $sourceCode = file_get_contents($sourceUrl );
       
	 
    // 将获取的 JSON 数据转换为 PHP 数组
    $dataArray = json_decode($sourceCode, true); 
	
    // 初始化返回对象
    $response = [];
    
    // 设置 code
    $response['code'] = !empty($dataArray['list'][0]) ? 'ok' : 'null';

// 处理视频数组
if (!empty($dataArray['list'][0])) {
    // 从 data 对象中直接获取 httpurl 的值作为视频播放地址
    // 设置视频对象
  
   
    $response['id'] = $dataArray['list'][0]['art_id'];
    $response['image'] = $dataArray['list'][0]['art_pic'];
    $response['title'] = $dataArray['list'][0]['art_name'];
    $response['novel'] =str_replace("gif\"/>","gif\" alt=\"\" style=\"max-width:100%;\">",str_replace("jpg\"/>","jpg\" alt=\"\" style=\"max-width:100%;\">", $dataArray['list'][0]['art_content']));
	
	
}


 


// 输出JSON格式数据
header('Content-Type: application/json');
echo json_encode($response);
	
	
}

?>
