<?php
 function postData($url, $post = null)
         {
            $context = array();

           if (is_array($post))
            {
                ksort($post);

               $context['http'] = array
                ( 
                'timeout'=>160,
                'method' => 'get',
                'content' => http_build_query($post, '', '&'),
                );
            }
            var_dump($context);
           return file_get_contents($url, false, stream_context_create($context));
         }
         
  function setMsg($mob, $sms)
    {
       //$mob = '15325818303';
       echo $sms;
       $sms=iconv("utf-8","gb2312", $sms);
       echo $sms;
       $url = 'http://ums.zj165.com:8888/sms/Api/Send.do?SpCode=003916&LoginName=hz_dzkj2&Password=dz1333&UserNumber='.$mob.'&MessageContent='.$sms;
       $rs = file_get_contents($url);
       echo $rs;
       exit;
       $ch = curl_init();
        curl_setopt ($ch, CURLOPT_URL, $url);
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1); 
        curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT,10);

        $rs = curl_exec($ch); 
        curl_close($ch);
        var_dump($rs);
        echo '<br>';  echo '<br>';  echo '<br>';  echo '<br>';
        /*
        $options = array(
   	 'http'=>array(
            'method'=>"get",
            'header'=>
              "Content-type: application/xhtml+xml\r\n",
              "User-Agent:Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; Trident/5.0)\r\n",
              "Accept-Language:zh-CN\r\n",
              "Connection:Keep-Alive\r\n",
              'content'=>http_build_query(array('SpCode'=>'003916', 'LoginName'=>'hz_dzkj2', 'Password' => 'dz1333', 'UserNumber' => $mob, 'MessageContent'=>$sms))
        ));
        */
        /*
         $options = array(
   	 'http'=>array(
            'method'=>"get",
            'header'=>
              "Content-type: application/xhtml+xml\r\n",
              "User-Agent:Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; Trident/5.0)\r\n",
              "Accept-Language:zh-CN\r\n",
              "Connection:Keep-Alive\r\n",
              'content'=>http_build_query(array('wd'=>'003916'))
        ));
        $context = stream_context_create($options);
        //$url = 'http://ums.zj165.com:8888/sms/Api/Send.do';
        $url ="http://www.baidu.com?wk=123";
        $rs = file_get_contents($url);
        */
        $post= array('SpCode'=>'003916', 'LoginName'=>'hz_dzkj2', 'Password' => 'dz1333', 'UserNumber' => $mob, 'MessageContent'=>$sms);
        $url = 'http://ums.zj165.com:8888/sms/Api/Send.do';
        $rs = postData($url, $post);
        var_dump($rs);
        //$rs = 'result=1&description=发送内容与模板不符;';
        $arr_rs = explode('&', $rs);
        $success = 0;
        foreach($arr_rs as $val)
        {
            $arr_r = explode('=', $val);
            if ($arr_r[0] == 'result')
            {
               if ($arr_r[1] == 0)
               {//发送成功
                  $success = 1;
                  break;
               }
            }
        }
       // $msg = date('Y-m-d H:i:s'). ': MOB:'. $mob .'MSG:'.$rs;
         //ini_set('user_agent','Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 2.0.50727;)'); 
        //file_put_contents(ROOT_PATH . "/data/mob_".date('Y-m-d').".log.php", "\r\n" . $msg . ";\r\n", FILE_APPEND);
        return $success;
    }
    $sms = '欢迎注册三多九如会员，注册手机验证码：wedfty';
   echo  setMsg('15325818303', $sms);
?>
