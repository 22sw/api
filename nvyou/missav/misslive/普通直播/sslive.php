<?php


// 示例 http://api.22ba4.top/missav/sslive.php?primaryTag=girls&key=love&sort=topic&page=1

// 搜索接口  $sort    全部-all 消费菜单-tipMenu 主题-topic  用户名-username
// 主标签 $primaryTag 女主播-girls  男主播-men  情侣-couples 变性人-trans
// $key  搜索词
//页码 


if(isset($_GET['primaryTag']) && isset($_GET['key'])&& isset($_GET['sort'])&& isset($_GET['page'])) {
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
