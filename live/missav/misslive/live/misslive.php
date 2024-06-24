<?php


//示例  http://api.22ba4.top/missav/live.php?primaryTag=女主播&parentTag=新主播&page=1
//   http://api.22ba4.top/missav/sslive.php?primaryTag=girls&key=love&sort=topic&page=1



//https://zh.stripchat.com/
//$url = "https://zh.myavlive.com/




// 主标签名称和标签地址的映射关系
$primaryTagMapping = array(
    "女主播" => "girls",
    "情侣" => "couples",
    "男主播" => "men",
    "变性人" => "trans"
);

// 父标签名称和标签地址的映射关系
$parentTagMapping = array(
    "中文" => "tagLanguageChinese",
    "乌克兰" => "tagLanguageUkrainian",
    "新主播" => "autoTagNew",
    "美国" => "tagLanguageUSModels",
    "日本" => "tagLanguageJapanese",
    "韩国" => "tagLanguageKorean",
    "非洲" => "tagLanguageAfrican",
    "西班牙" => "tagLanguageSpanishSpeaking",
    "越南" => "tagLanguageVietnamese",
    "哥伦比亚" => "tagLanguageColombian",
    "鲜嫩青年" => "ageYoung",
    "vr" => "autoTagVr",
	"鞭打" => "doSpanking",
	 "高潮" => "doOrgasm",
	 "潮吹" => "doSquirt",
	 "大屁股" => "specificsBigAss",
	 "少女18+" => "ageTeen",
	 "电臀舞" => "doTwerk",
	  "高清" => "autoTagHd",
	 "学生" => "subcultureStudent",
	 "付费" => "privatePriceEight",
    "玩具" => "doDildoOrVibrator"
);



// 判断是否传入了参数
if (isset($_GET['getsort'])) {
    // 定义分类名称与对应数字索引的映射关系
    $categories = [
	 "女主播" => "girls",
    "情侣" => "couples",
    "男主播" => "men",
    "变性人" => "trans",
    "中文" => "tagLanguageChinese",
    "乌克兰" => "tagLanguageUkrainian",
    "新主播" => "autoTagNew",
    "美国" => "tagLanguageUSModels",
    "日本" => "tagLanguageJapanese",
    "韩国" => "tagLanguageKorean",
    "非洲" => "tagLanguageAfrican",
    "西班牙" => "tagLanguageSpanishSpeaking",
    "越南" => "tagLanguageVietnamese",
    "哥伦比亚" => "tagLanguageColombian",
    "鲜嫩青年" => "ageYoung",
    "vr" => "autoTagVr",
	"鞭打" => "doSpanking",
	 "高潮" => "doOrgasm",
	 "潮吹" => "doSquirt",
	 "大屁股" => "specificsBigAss",
	 "少女18+" => "ageTeen",
	 "电臀舞" => "doTwerk",
	  "高清" => "autoTagHd",
	 "学生" => "subcultureStudent",
	 "付费" => "privatePriceEight",
    "玩具" => "doDildoOrVibrator"
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
// 获取主标签、筛选标签和父标签参数
$primaryTag = isset($_GET['primaryTag']) ? $_GET['primaryTag'] : "";
$parentTag = isset($_GET['parentTag']) ? $_GET['parentTag'] : "";

$page = isset($_GET['page']) ? $_GET['page'] : "";
$sort = isset($_GET['sort']) ? $_GET['sort'] : "";
// 获取主标签、筛选标签和父标签对应的地址
//$primaryTagValue = isset($primaryTagMapping[$primaryTag]) ? $primaryTagMapping[$primaryTag] : "";
if($sort ==="girls"){
	$primaryTagValue = "girls";
	$parentTagValue= "";
}elseif($sort ==="men"){
	
	$primaryTagValue = "men";
	$parentTagValue= "";
	
}elseif($sort ==="couples"){
	
	$primaryTagValue = "couples";
	$parentTagValue= "";
}elseif($sort ==="trans"){
	
	$primaryTagValue = "trans";
	$parentTagValue= "";
}else{
	
	$primaryTagValue = "girls";
	$parentTagValue= $sort;
}



//$parentTagValue = isset($parentTagMapping[$parentTag]) ? $parentTagMapping[$parentTag] : "";
//$parentTagValue= $sort;

// 计算偏移量


$offset = ($page - 1) * 60;

// 构建筛选标签，添加额外的引号
$filterGroupTags = '[["' . $parentTagValue . '"]]';



// 构建采集网站的URL
$url = "https://zh.myavlive.com/api/front/models?limit=60&offset={$offset}&primaryTag=$primaryTagValue&filterGroupTags=$filterGroupTags&sortBy=stripRanking&parentTag=$parentTagValue&userRole=guest&uniq=h5xzj2kg4iw76bu8";
   // echo $url;
// 获取网站数据
$html = file_get_contents($url);
 
// 将JSON数据解码为PHP数组
$data = json_decode($html, true);

// 初始化响应数据
$responseData = array();

// 设置默认code为null
$responseData['code'] = null;

// 如果models数组不为空，则将code设置为ok
if (!empty($data['models'])) {
    $responseData['code'] = 'ok';

    // 循环处理models数组内的每个成员
    foreach ($data['models'] as $key => &$model) {
        // 如果isOnline为false，则移除该成员
        if (isset($model['isOnline']) && $model['isOnline'] === false) {
            unset($data['models'][$key]);
            continue;
        }

        // 获取hlsPlaylist成员id的键值
        if (isset($model['hlsPlaylist'])) {
            preg_match('/hls\/(.*?)\//', $model['hlsPlaylist'], $matches);
            if (isset($matches[1])) {
                // 替换到目标URL的主播id处
                $model['hlsPlaylist'] = "https://edge-hls.doppiocdn.net/hls/" . $matches[1] . "/master/" . $matches[1] . "_auto.m3u8?playlistType=lowLatency";
            }
        }

        // 保留指定键，并对键名进行修改
        $model = array_intersect_key($model, array_flip(['gender', 'avatarUrl', 'country', 'previewUrlThumbBig', 'username', 'hlsPlaylist', 'status', 'viewersCount','id',  'isOnline', 'topic', 'activity']));
        $model['user_id'] = $model['id']; // 将id键名改为user_id
        $model['id'] = $model['hlsPlaylist']; // 添加id键
        $model['title'] = $model['username']; // 添加title键
        $model['image'] = $model['previewUrlThumbBig']; // 添加image键
        $model['date'] = $model['viewersCount']  . "个观众"; // 添加date键
    }

    // 重新索引数组键值
    $data['models'] = array_values($data['models']);
}

// 将响应数据设置到$responseData数组中
$responseData['videos'] = $data['models'];

// 设置响应头为JSON格式
header('Content-Type: application/json');

// 输出数据
echo json_encode($responseData);

} elseif(isset($_GET['primaryTag']) && isset($_GET['key'])&& isset($_GET['sort'])&& isset($_GET['page'])) {
// 解析请求参数
$primaryTag = isset($_GET['primaryTag']) ? $_GET['primaryTag'] : '';
$key = isset($_GET['key']) ? $_GET['key'] : '';
$sort = isset($_GET['sort']) ? $_GET['sort'] : '';
$page = isset($_GET['page']) ? $_GET['page'] : '';
// 计算偏移量
$offset = ($page - 1) * 12;
// 构建目标 URL
$targetUrl = "https://zh.myavlive.com/api/front/v4/models/search/group/{$sort}?query={$key}&limit=12&offset=$offset&primaryTag={$primaryTag}&uniq=bhy4gd7qm3ausnr6";

// 获取目标网站数据
$data = file_get_contents($targetUrl);

if ($data === false) {
    // 处理请求失败的情况
    $response = array('error' => 'Failed to fetch data from the target website.');
    header('Content-Type: application/json');
    echo json_encode($response, JSON_PRETTY_PRINT); // 使用 JSON_PRETTY_PRINT 使返回的 JSON 数据格式整齐
} else {
    // 解析 JSON 数据
    $decodedData = json_decode($data, true);

    if ($decodedData === null) {
        // 处理 JSON 解析失败的情况
        $response = array('error' => 'Failed to parse JSON data from the target website.');
        header('Content-Type: application/json');
        echo json_encode($response, JSON_PRETTY_PRINT); // 使用 JSON_PRETTY_PRINT 使返回的 JSON 数据格式整齐
    } else {
        // 修改 hlsPlaylist 数据并重新构建 JSON
        foreach ($decodedData['models'] as &$model) {
            if (isset($model['isOnline']) && $model['isOnline'] === true) {
                // 只处理在线的主播数据

                // 如果 hlsPlaylist 不存在，创建一个新数组
                if (!isset($model['hlsPlaylist'])) {
                    $model['hlsPlaylist'] = array();
                }

                // 修改 hlsPlaylist 内容
                $hlsUrl = "https://edge-hls.doppiocdn.net/hls/{$model['id']}/master/{$model['id']}_auto.m3u8?playlistType=lowLatency";
                $model['hlsPlaylist'] = $hlsUrl;

                // 只保留指定键
                $model = array_intersect_key($model, array_flip(['gender', 'avatarUrl', 'country', 'previewUrlThumbBig', 'previewUrlThumbSmall', 'username', 'hlsPlaylist', 'status', 'viewersCount', 'id', 'isOnline']));
            } else {
                // 不在线的主播移除
                unset($model);
            }
        }

        // 重新构建 JSON
        $response = array('models' => array_values(array_filter($decodedData['models'])));
        header('Content-Type: application/json');
        echo json_encode($response, JSON_PRETTY_PRINT); // 使用 JSON_PRETTY_PRINT 使返回的 JSON 数据格式整齐
    }
}
} else if (isset($_GET['id'])) {
	$id = $_GET['id'];
	$response = [
        'code' =>  'ok',
	    'video' => $id
        //'playlist' => $result
        ];

        header('Content-Type: application/json');
        echo json_encode($response);
	
	
	
}elseif(isset($_GET['primaryTag']) && isset($_GET['key'])&& isset($_GET['sort'])&& isset($_GET['page'])) {
// 解析请求参数
$primaryTag = isset($_GET['primaryTag']) ? $_GET['primaryTag'] : '';
$key = isset($_GET['key']) ? $_GET['key'] : '';
$sort = isset($_GET['sort']) ? $_GET['sort'] : '';
$page = isset($_GET['page']) ? $_GET['page'] : '';
// 计算偏移量
$offset = ($page - 1) * 12;
// 构建目标 URL
$targetUrl = "https://zh.myavlive.com/api/front/v4/models/search/group/{$sort}?query={$key}&limit=12&offset=$offset&primaryTag={$primaryTag}&uniq=bhy4gd7qm3ausnr6";

// 获取目标网站数据
$data = file_get_contents($targetUrl);

if ($data === false) {
    // 处理请求失败的情况
    $response = array('error' => 'Failed to fetch data from the target website.');
    header('Content-Type: application/json');
    echo json_encode($response, JSON_PRETTY_PRINT); // 使用 JSON_PRETTY_PRINT 使返回的 JSON 数据格式整齐
} else {
    // 解析 JSON 数据
    $decodedData = json_decode($data, true);

    if ($decodedData === null) {
        // 处理 JSON 解析失败的情况
        $response = array('error' => 'Failed to parse JSON data from the target website.');
        header('Content-Type: application/json');
        echo json_encode($response, JSON_PRETTY_PRINT); // 使用 JSON_PRETTY_PRINT 使返回的 JSON 数据格式整齐
    } else {
        // 修改 hlsPlaylist 数据并重新构建 JSON
        foreach ($decodedData['models'] as &$model) {
            if (isset($model['isOnline']) && $model['isOnline'] === true) {
                // 只处理在线的主播数据

                // 如果 hlsPlaylist 不存在，创建一个新数组
                if (!isset($model['hlsPlaylist'])) {
                    $model['hlsPlaylist'] = array();
                }

                // 修改 hlsPlaylist 内容
                $hlsUrl = "https://edge-hls.doppiocdn.net/hls/{$model['id']}/master/{$model['id']}_auto.m3u8?playlistType=lowLatency";
                $model['hlsPlaylist'] = $hlsUrl;

                // 只保留指定键
                $model = array_intersect_key($model, array_flip(['gender', 'avatarUrl', 'country', 'previewUrlThumbBig', 'previewUrlThumbSmall', 'username', 'hlsPlaylist', 'status', 'viewersCount', 'id', 'isOnline']));
            } else {
                // 不在线的主播移除
                unset($model);
            }
        }

        // 重新构建 JSON
        $response = array('models' => array_values(array_filter($decodedData['models'])));
        header('Content-Type: application/json');
        echo json_encode($response, JSON_PRETTY_PRINT); // 使用 JSON_PRETTY_PRINT 使返回的 JSON 数据格式整齐
    }
}
}

?>
