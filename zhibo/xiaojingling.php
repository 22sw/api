<?php

//分类  http://api.22ba4.top/missav/missav.php?tag=东京热&page=3
//搜索  http://api.22ba4.top/missav/missav.php?key=美女&page=3
//视频详情 http://api.22ba4.top/missav/missav.php?id=/cn/avsa-306
//女优视频列表 id（dm127/cn/actresses/波多野結衣） 页码 http://api.22ba4.top/missav/actresses.php?sort=/dm127/cn/actresses/波多野結衣&page=2
//女优列表 身高段 罩杯 年龄段 出道时间（2024年前） 页码  https://api.22ba4.top/missav/actresses.php?height=160-165&cup=b&age=20-24&debut=2024&page=1


 

// 解析 GET 请求参数
// 判断是否传入了参数
    $latestdomain ="http://222.186.169.253:300";
	
	
if (isset($_GET['getpingtai'])  ) {
	 
	
	$sourceUrl = $latestdomain . "/zb/index.json?time=" .time();
	
	 // 获取源码
    $sourceCode = file_get_contents($sourceUrl);
    
	// 解密秘钥
$key = "vEg@fe52!fY(de/d";

// 解密函数
function decryptData($encryptedData, $key) {
    // 解密算法和填充方式
    $cipher = "aes-128-ecb";
    // 解密
    $decryptedData = openssl_decrypt($encryptedData, $cipher, $key, OPENSSL_RAW_DATA);
    return $decryptedData;
}

// 解密数据
$decryptedData = decryptData(base64_decode($sourceCode), $key);


      // 将获取的 JSON 数据转换为 PHP 数组
    $dataArray = json_decode($decryptedData, true);

    // 初始化返回对象
    $response = [];

    // 处理视频数组
    if (!empty($dataArray['data'])) {
        $videos = [];
        foreach ($dataArray['data'] as $video) {
            // 修改键名
			 
            $videoItem = [
                'id' => $video['name'],
                'title' => $video['platform'],
                'image' => $video['image'],
                'Number' => $video['anchor']
            ];
            // 添加到视频数组中
            $videos[] = $videoItem;
         
		 }
        // 将处理过的视频数组命名为 videos
        $response['pingtai'] = $videos;
        // 设置 code 为 ok
        $response['code'] = 'ok';
    } else {
        // 视频数组为空，设置 code 为 null
        $response['code'] = 'null';
    }

    // 输出JSON格式数据
    header('Content-Type: application/json');
    echo json_encode($response);
	
	
	
// 判断是否存在GET请求参数
}else if(isset($_GET['pingtai'])  ) {
    // 处理分类列表接口请求
    $pingtai = $_GET['pingtai'];
    $page = $_GET['page'];

     
        
        $sourceUrl = $latestdomain."/zb/" .$pingtai . ".json?time=" . time();
        
         // 获取源码
    $sourceCode = file_get_contents($sourceUrl);
    
	// 解密秘钥
$key = "vEg@fe52!fY(de/d";

// 解密函数
function decryptData($encryptedData, $key) {
    // 解密算法和填充方式
    $cipher = "aes-128-ecb";
    // 解密
    $decryptedData = openssl_decrypt($encryptedData, $cipher, $key, OPENSSL_RAW_DATA);
    return $decryptedData;
}

// 解密数据
$decryptedData = decryptData(base64_decode($sourceCode), $key);
$decryptedData = urldecode($decryptedData);

      // 将获取的 JSON 数据转换为 PHP 数组
    $dataArray = json_decode($decryptedData, true);

    // 初始化返回对象
    $response = [];

    // 处理视频数组
    if (!empty($dataArray['data'])) {
        $videos = [];
        foreach ($dataArray['data'] as $video) {
            // 修改键名
			if(
			    strpos($video['address'],"irpo.cn")===false  &&  strpos($video['address'],"lxhyouth.xyz")===false  &&  
			    strpos($video['address'],".mp4")===false  &&       strpos($video['address'],"liuzhuan.xyz")===false  &&  
			    strpos($video['address'],"aliyuncs.com")===false  &&    strpos($video['address'],"guwbanb.cn")===false  &&
				strpos($video['address'],"myqcloud.com")===false  &&  strpos($video['title'],"放映")===false

				){
            $videoItem = [
                'id' => $video['rtmp'],
                'title' => $video['name'],
                'image' => $video['image']
            ];
            // 添加到视频数组中
            $videos[] = $videoItem;
         }
		 }
        // 将处理过的视频数组命名为 videos
        $response['zhubo'] = $videos;
        // 设置 code 为 ok
        $response['code'] = 'ok';
    } else {
        // 视频数组为空，设置 code 为 null
        $response['code'] = 'null';
    }

    // 输出JSON格式数据
    header('Content-Type: application/json');
    echo json_encode($response);
        
    
} elseif(isset($_GET['id'])) {
    // 处理获取视频详情接口请求
    $id = $_GET['id']; 
    
    $sourceUrl = $latestdomain .$id;
        
         // 获取源码
    $sourceCode = file_get_contents($sourceUrl);

      // 将获取的 JSON 数据转换为 PHP 数组
    $dataArray = json_decode($sourceCode, true);

    // 初始化返回对象
    $response = [];

    // 处理视频数组
    if (!empty($dataArray['zhubo'])) {
        $videos = [];
        foreach ($dataArray['zhubo'] as $video) {
            // 修改键名
			 
            $videoItem = [
                'id' => $video['address'],
                'title' => $video['title'],
                'image' => $video['img']
            ];
            // 添加到视频数组中
            $videos[] = $videoItem;
         
		 }
        // 将处理过的视频数组命名为 videos
        $response['zhubo'] = $videos;
        // 设置 code 为 ok
        $response['code'] = 'ok';
    } else {
        // 视频数组为空，设置 code 为 null
        $response['code'] = 'null';
    }

    // 输出JSON格式数据
    header('Content-Type: application/json');
    echo json_encode($response);
      
	
} 

?>
