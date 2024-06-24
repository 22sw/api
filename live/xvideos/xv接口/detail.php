<?php

//https://api.22ba4.top/missav/detail.php?id=/82696/swag-av3/


if(isset($_GET['id'])) {
    // 获取参数
    $videoId = isset($_GET['id']) ? $_GET['id'] : '';

// 设置流上下文选项
$context = stream_context_create([
    'http' => [
        'header' => "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7\r\n" .
"Accept-Language: zh-CN,zh;q=0.9,en;q=0.8,en-GB;q=0.7,en-US;q=0.6\r\n" .

"Cookie: session_ath=black;html5_pref=%7B%22SQ%22%3Afalse%2C%22MUTE%22%3Afalse%2C%22VOLUME%22%3A1%2C%22FORCENOPICTURE%22%3Afalse%2C%22FORCENOAUTOBUFFER%22%3Afalse%2C%22FORCENATIVEHLS%22%3Afalse%2C%22PLAUTOPLAY%22%3Atrue%2C%22CHROMECAST%22%3Afalse%2C%22EXPANDED%22%3Afalse%2C%22FORCENOLOOP%22%3Afalse%7D; xv_nbview=1; html5_networkspeed=41543; PHPSESSID=1bbtf4u7c3s98mcgpe85lrp3ua; has_visited=1; service=girls; language=en; BILLING_TEST_SUB_GROUP_4=NEW; BILLING_TEST_GROUP_4=GROUP_B%3A%3Av8; _gcl_au=1.1.448349762.1713712556; _gid=GA1.2.701795849.1713712563; source_code=default; layout04=1; started=1713712560; screen_name=guestUser_789719694; params=dG9rZW49MzY3MDMmdG9rZW5fZW5jPVFGQlNSVm89JmN0aT04Jm1vZGVsX2lkPTEzMTU1NTgmaG9zdD1jaGF0MDAzLnZzMy5jb20mcG9ydD0xJnNpdGVpZD0xMjQ3NTgmY2hhdF9wb3J0X2ZsYXNoPTEmdmlkZW9faG9zdD12aWRlby1ncHUwMDYtdHNzLW55LnZzMy5jb20mdmlkZW9fcG9ydD0wJmF1ZGlvX3BvcnQ9MCZ4bWxfcG9ydD00MTIwMQ%3D%3D; mp_code=d4vn3; _ga=GA1.2.317487428.1713712563; _ga_EGYWBHZHQV=GS1.1.1713717324.2.1.1713717324.0.0.0; zone-cap-3614151=1%3B1713718773; last_views=%5B%2278888921-1713627446%22%2C%2280609231-1713627592%22%2C%2280771513-1713627602%22%2C%2280396141-1713627654%22%2C%2276667963-1713629209%22%2C%2277434611-1713629241%22%2C%2270483157-1713633301%22%2C%2264098415-1713634338%22%2C%2280343687-1713712285%22%2C%2280116107-1713713722%22%2C%2280079983-1713718765%22%2C%229779334-1713718771%22%5D; pending_thumb=%7B%22t%22%3A%5B%5D%2C%22s%22%3A%5B%5D%2C%22p%22%3A%5B%5D%2C%22r%22%3A%5B%5D%7D; session_token=2978f5c7644b63d9XT5D_s3TMwcTx2TzhodrYN5IQYjlPBk3ilLq8vqIygnUXoPOZ-gugFx-c8mAiQ4XgK0ceqeHin7zJw8w9EnV925AsnV4TQTdu1c0_jMA9C8BVA4hyuqaeQ3OB8Yo28-VEKSTZIeko9B_qcHCb2pvpSR-7_E6dSgVpDC3nEI3UXf1v-tX72VhjMFxplrtQD5bBFY9vF8X-fUYCtKDUNQaWmz7WUrV8dyczl0UKczfoJe95rSgGhDKHy-bx8ib8YReRJB3DKFz8koF6t2M2WQChg%3D%3D\r\n" .
"Host: www.xvideos.com\r\n" .
"Sec-Fetch-Site: same-origin\r\n" .
"User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/123.0.0.0 Safari/537.36 Edg/123.0.0.0\r\n" ,
    ]
]);

        // 构建目标网站的 URL
        $sourceUrl = "https://www.xvideos.com$videoId";
      
        // 获取源码
    //$sourceCode = file_get_contents($sourceUrl);
    $sourceCode = file_get_contents($sourceUrl, false, $context);

        // 从源码中截取标题
        preg_match('/html5player.setVideoTitle\(\'(.*?)\'\)/', $sourceCode, $titleMatches);
        $title = isset($titleMatches[1]) ? $titleMatches[1] : '';

        // 从源码中截取图片地址
        preg_match('/html5player.setThumbUrl169\(\'(.*?)\'\)/', $sourceCode, $imageMatches);
        $imageUrl = isset($imageMatches[1]) ? $imageMatches[1] : '';

        // 从源码中截取时长
        preg_match('/duration\"\>(.*?)<\/span>/', $sourceCode, $durationMatches);
        $duration = isset($durationMatches[1]) ? $durationMatches[1] : '';

        // 从源码中截取画质
        preg_match('/video-hd-mark\"\>(.*?)<\/span>/', $sourceCode, $qualityMatches);
        $quality = isset($qualityMatches[1]) ? $qualityMatches[1] : '';

        // 从源码中截取视频地址
        preg_match('/html5player.setVideoUrlLow\(\'(.*?)\'\)/', $sourceCode, $url3gpMatches);
        $url3gp = isset($url3gpMatches[1]) ? $url3gpMatches[1] : '';
		
		preg_match('/html5player.setVideoUrlLow\(\'(.*?)\'\)/', $sourceCode, $mp4lowMatches);
        $mp4low = isset($mp4lowMatches[1]) ? $mp4lowMatches[1] : '';
		
		preg_match('/html5player.setVideoUrlHigh\(\'(.*?)\'\)/', $sourceCode, $mp4HighMatches);
        $mp4High = isset($mp4HighMatches[1]) ? $mp4HighMatches[1] : '';
		
		preg_match('/html5player.setVideoHLS\(\'(.*?)\'\)/', $sourceCode, $m3u8Matches);
        $m3u8 = isset($m3u8Matches[1]) ? $m3u8Matches[1] : '';
		
		// 获取m3u8多地址
		$m3u8muit = file_get_contents($m3u8);
		//替换hls.m3u8
		$m3u8head = preg_replace('/hls.m3u8/', 'hls-', $m3u8, 1);
		
		
		
		
		preg_match('/1080p-(.*?).m3u8/', $m3u8muit, $url1080pMatches);
        $url1080p = isset($url1080pMatches[1]) ? $url1080pMatches[1] : '';
		if (!empty($url1080p)) {
		$url1080p =$m3u8head . '1080p-'. $url1080p . '.m3u8';
		}elseif (strpos($m3u8muit, "1080p") !== false) {
   		 $url1080p =$m3u8head . '1080p.m3u8';;
		} else {
 		  $url1080p=null;
		}
		
		
		preg_match('/720p-(.*?).m3u8/', $m3u8muit, $url720pMatches);
        $url720p = isset($url720pMatches[1]) ? $url720pMatches[1] : '';
		if (!empty($url720p)) {
			$url720p =$m3u8head . '720p-'. $url720p . '.m3u8';
		}elseif (strpos($m3u8muit, "720p") !== false) {
   		 $url720p =$m3u8head . '720p.m3u8';;
		} else {
 		  $url720p=null;
		}
		
		preg_match('/480p-(.*?).m3u8/', $m3u8muit, $url480pMatches);
        $url480p = isset($url480pMatches[1]) ? $url480pMatches[1] : '';
		if (!empty($url480p)) {
		$url480p =$m3u8head . '480p-' . $url480p . '.m3u8';
		}elseif (strpos($m3u8muit, "480p") !== false) {
   		 $url480p =$m3u8head . '480p.m3u8';;
		} else {
 		  $url480p=null;
		}
		
		preg_match('/360p-(.*?).m3u8/', $m3u8muit, $url360pMatches);
        $url360p = isset($url360pMatches[1]) ? $url360pMatches[1] : '';
		if (!empty($url360p)) {
		$url360p =$m3u8head . '360p-'. $url360p . '.m3u8';
		}elseif (strpos($m3u8muit, "360p") !== false) {
   		 $url360p =$m3u8head . '360p.m3u8';;
		} else {
 		  $url360p=null;
		}

         preg_match('/250p-(.*?).m3u8/', $m3u8muit, $url250pMatches);
        $url250p = isset($url250pMatches[1]) ? $url250pMatches[1] : '';
		if (!empty($url250p)) {
		$url250p =$m3u8head . '250p-'. $url250p . '.m3u8';
		}elseif (strpos($m3u8muit, "250p") !== false) {
   		 $url250p =$m3u8head . '250p.m3u8';;
		} else {
 		  $url250p=null;
		}
		
        

        // 构建返回对象
        $response = [];
        if (!empty($url3gp)) {
            $response = [
                'code' => 'ok',
                'title' => $title,
                'image' => $imageUrl,
				'duration' => $duration,
				'quality' => $quality,
                '3gp' => $url3gp,
				'mp4low' => $mp4low,
				'mp4High' => $mp4High,
                'm3u8' => $m3u8,
                '1080p' => $url1080p,
				'720p' => $url720p,
                '480p' => $url480p,
                '360p' => $url360p,
				'250p' => $url250p,
				
				
                'related' => []
            ];
        } else {
            $response = [
                'code' => null
            ];
        }

        // 获取推荐视频列表
        preg_match('/related=(.*?);window/s', $sourceCode, $matches);
        if (isset($matches[1])) {
            $recommendationsText = $matches[1];
			
			//echo $recommendationsText;
			
            // Remove the first '<div class="' occurrence
           // $recommendationsText = preg_replace('/<div class="/', '', $recommendationsText, 1);
            // Split by 'views'
            $recommendations = explode('"ut"', $recommendationsText);
            // Get the actual count
            $actualCount = count($recommendations) - 1;
            $recommendationList = [];
            foreach ($recommendations as $recommendation) {
                preg_match('/if":"(.*?)"/', $recommendation, $imageMatches);
                $image = isset($imageMatches[1]) ? $imageMatches[1] : '';
				$image = str_replace('\\/', '/', $image);
				
                preg_match('/"u":"(.*?)"/', $recommendation, $idMatches);
                $id = isset($idMatches[1]) ? $idMatches[1] : '';
				$id = str_replace('\\/', '/', $id);

                preg_match('/tf":"(.*?)"/', $recommendation, $titleMatches);
                $title = isset($titleMatches[1]) ? $titleMatches[1] : '';
				

                if (!empty($id)) {
                    $recommendationObject = [
                        'image' => $image,
                        'id' => $id,
                        'title' => $title
                    ];
                    $recommendationList[] = $recommendationObject;
                }
            }
            // Add recommendations to the response
            $response['related'] = $recommendationList;
            // Return the updated response
			 header('Content-Type: application/json');
            echo json_encode($response, JSON_UNESCAPED_UNICODE);
        } else {
            // Return the response without recommendations if not found
			 // 输出 JSON 格式数据
        header('Content-Type: application/json');
            echo json_encode($response, JSON_UNESCAPED_UNICODE);
        }
   
}


?>
