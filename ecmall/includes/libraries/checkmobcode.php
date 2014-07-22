<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class CheckmobcodeService
{
    
    /**
     * 发送支付密码;
     * @param type $order_sn
     * @param type $mob
     * @return boolean
     */
    public function sendPayCode($order_sn, $mob)
    {
        if(empty($order_sn))
        {
            return false;
        }
        $code = $this->getCode();
        $_SESSION['order']['code'] = $code;
        $sms = '您于'.date("Y-m-d,H:i:s").'在三多九如官网的订单：' . $order_sn . '进行支付操作，交易验证码：'.$code;
        //$sms = urlencode($sms);
        //echo $sms;
       return $this->setMsg($mob, $sms);
    }
    
    
    public function sendCheckCode($type, $mob)
    {
        $sms = '';
        $code = $this->getCode();
        switch ($type)
        {
            case 1://取现
                $sms = '您于' . date("Y-m-d,H:i:s") .'在三多九如官网进行了提现申请，交易验证码：';
                break;
            case 2://修改银行信息
                $sms = '您于' . date("Y-m-d,H:i:s") .'在三多九如官网，进行了银行账户变更修改，变更验证码：';
                break;
            case 3://修改支付密码;
                $sms = '您于' . date("Y-m-d,H:i:s") .'在三多九如官网，进行了支付密码修改，变更验证码：';
                break;
            case 'reg'://注册
                 $sms = '欢迎注册三多九如会员，注册手机验证码：';
                break;
            
        }
        if (empty($sms))
        {
            return '0';
        }
         $_SESSION['code'] = $code;
        $sms =  $sms . $code;
        //$sms = urlencode($sms);
        //echo $sms;
       return $this->setMsg($mob, $sms);
    }
 
    public function setMsg($mob, $sms)
    {
        $sms=iconv("utf-8","gb2312", $sms); //服务器是gb2312编码；
        $url = 'http://ums.zj165.com:8888/sms/Api/Send.do?SpCode=003916&LoginName=hz_dzkj2&Password=dz1333&UserNumber='.$mob.'&MessageContent='.$sms;
        $rs = file_get_contents($url);
        //$arr_rs = explode('&', $rs);
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
        $msg = date('Y-m-d H:i:s'). ': MOB:'. $mob .'MSG:'.$rs;
         //ini_set('user_agent','Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 2.0.50727;)'); 
        file_put_contents(ROOT_PATH . "/data/mob_".date('Y-m-d').".log.php", "\r\n" . $msg . ";\r\n", FILE_APPEND);
        return $success;
    }
    
    public function getCode()
    {
        return $this->_randomkeys(6);
    }
            
    private function _randomkeys($length)
    {
        $pattern='1234567890abcdefghijklmnopqrstuvwxyz';
        for($i=0;$i<$length;$i++)
        {
            $key .= $pattern{mt_rand(0,35)};    //生成php随机数
        }
        return $key;
    }
}
?>
