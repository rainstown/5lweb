<?php
import('checkmobcode');
/**
 *    手机验证码
 *
 *    @author   rainstown
 *    @usage    none
 */
class CheckmobcodeApp extends MemberbaseApp
{
  
    public function getUserPhone()
    {
        $model = & m('member');
        $user_id = $this->visitor->get('user_id');
        $member_info = $model->get($user_id);
        
        $phone_mob = $member_info['phone_mob'];
        
        if(!is_mobile($phone_mob))
        {
             $this->show_warning('phone_mob_error');
        }
        return $phone_mob;
        
    }
    //获取交易订单的识别吗
    function get_pay_code()
    {
       $phone_mob =  $this->getUserPhone();
       $order_id = intval($_GET['order_id']); 
       $model_order = &m('order');
       $order_info = $model_order->get(array(
            'fields'        => "*, order.add_time as order_add_time",
            'conditions'    => "order_id={$order_id} AND buyer_id=" . $this->visitor->get('user_id'),
            'join'          => 'belongs_to_store',
            ));
            
        if (!$order_info)
        {
            $this->show_warning('no_such_order');
            return;
        }
        if ($order_info['status'] != ORDER_PENDING)
        {// 非待支付状态. 提示错误
            $this->show_warning('no_such_order');
            return ;
        }
        if(isset($_SESSION['order']['time']))
        {
            $time_s = time() - $_SESSION['order']['time'];
          if( $time_s < 60)
          {
                $this->show_warning('sms_check_time_limit');
                return ;
          }
        }

        //充值发送时间
        
        
        $sms = new CheckmobcodeService();
        $error = $sms->sendPayCode($order_info['status']['order_sn'], $phone_mob);
       
        if ($error == 1)
        {
            $this->show_warning('sms_check_code_success'); 
        }
        else
        {
             $this->show_warning('sms_check_code_fail');
        }
        /* 显示订单列表 */
        //$this->display('account.index.html');
    }
    
    function get_check_code()
    {
        $phone_mob =  $this->getUserPhone();
        $type = intval($_GET['type']);  //验证码类型;
        
        if(isset($_SESSION['time']))
        {
            $time_s = time() - $_SESSION['time'];
          if( $time_s < 60)
          {
               $this->show_warning('sms_check_time_limit');
                return ;
          }
        }
        
        $sms = new CheckmobcodeService();
        $error = $sms->sendCheckCode($type, $phone_mob);
        if ($error == 1)
        {
            $_SESSION['time'] = time();
            $this->show_warning('sms_check_code_success'); 
        }
        else
        {
             $this->show_warning('sms_check_code_fail');
        }
    }
    
    
    /**
     * 获取注册验证码
     */
    public function get_register_code()
    {
        $key = $_GET['key'];
        $phone_mob = $_GET['mob'];
        
        if(isset($_SESSION['time']))
        {
            $time_s = time() - $_SESSION['time'];
          if( $time_s < 60)
          {
               $this->show_warning('sms_check_time_limit');
                return ;
          }
        }
        
         if(!is_mobile($phone_mob))
        {
             $this->show_warning('phone_mob_error');
             return ;
        }

        $md5_key = md5(md5(session_id()) + 'TEST_1001'); //防止盗发
        if ($key != $md5_key)
        {
            echo '非法操作';
        }
        else
        {
            $_SESSION['reg_mob'] = $phone_mob;
            $sms = new CheckmobcodeService();
            $type = 'reg';
            $error = $sms->sendCheckCode($type, $phone_mob);
            
            if ($error == 1)
            {
                $_SESSION['time'] = time();
                $this->show_warning('sms_check_code_success'); 
            }
            else
            {
                 $this->show_warning('sms_check_code_fail');
            }
        }
    }
    
    
    
    
  

}

