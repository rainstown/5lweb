<?php
import('common_index');
/**
 *    买家的站内支付管理控制器
 *
 *    @author   rainstown
 *    @usage    none
 */
class AccountApp extends MemberbaseApp
{
    var $_trance_key = 'TEST1001'; //交易识别码 ACCOUNT.PAYMEN 一致；
    function index()
    {
        $bank_info = $this->_checkHasBank(); //验证是否有银行;
        $model = & m('bank');

        /* 当前位置 */
        $this->_curlocal(LANG::get('member_center'), 'index.php?app=member',
                         LANG::get('my_account'), 'index.php?app=account',
                         LANG::get('my_account'));

        /* 当前用户中心菜单 */
        $this->_curitem('account');
        $this->_curmenu('info');
        $this->_config_seo('title', Lang::get('member_center') . ' - ' . Lang::get('my_account'));
       

        /* 显示订单列表 */
        $this->assign('bank_info', $bank_info);
        $this->display('account.index.html');
        
        
    }
  
    /*
     * 申请开通账户
     */
    function apply()
    {
        $model = & m('bank');
        $user_id = $this->visitor->get('user_id');
        $bank_info = $model->get($user_id);
        IF(!IS_POST)
        {
            /* 当前位置 */
            $this->_curlocal(LANG::get('member_center'), 'index.php?app=member',
                             LANG::get('my_account'), 'index.php?app=account',
                             LANG::get('my_account'));

            /* 当前用户中心菜单 */
            $this->_curitem('account');
            $this->_curmenu('info');
            $this->_config_seo('title', Lang::get('member_center') . ' - ' . Lang::get('my_account'));
            $this->import_resource(array(
                'script' => array(
                    array(
                        'path' => 'jquery.ui/jquery.ui.js',
                        'attr' => '',
                    ),
                    array(
                        'path' => 'jquery.plugins/jquery.validate.js',
                        'attr' => '',
                    ),
                ),
                'style' =>  'jquery.ui/themes/ui-lightness/jquery.ui.css',
            ));

            /* 显示订单列表 */
            if ($bank_info)
            {
                $this->display('account.edit.html');
            }
            else
            {
                $this->display('account.add.html');
            }
        }
        else
        {
            $model = & m('bank');
            $user_id = $this->visitor->get('user_id');
            $bank_info = $model->get($user_id);
            
            $password = $_POST['payment_password'];
            $old_password = $_POST['old_payment_password'];
            $check_code = $_POST['check_code'];
            
            if (empty($bank_info))
            {
                if(empty($password))
                {
                    $this->show_warning('note_for_password');
                    exit;
                }
              
                
                $model->add (array('bank_id' => $user_id,
                            'password' => md5($password),
                            'create_time' => gmtime())
                            );
            }
            else
            {
                $this->checkPayCheckCode($check_code, 1);
                $this->checkPayPassword($old_password, $user_id);
                $model->edit ( $user_id,
                                array('password' => md5($password),
                                'update_time' => gmtime()
                                )
                            );
            }
            if(isset($_SESSION['code']))
                unset($_SESSION['code']);
            $this->show_message('apply_ok', 'back_list', 'index.php?app=account');
        }
    }
    
    public function reset_pwd()
    {
        $this->_checkHasBank(); //验证是否有银行;
        
        IF(!IS_POST)
        {
           
              /* 当前位置 */
            $this->_curlocal(LANG::get('member_center'), 'index.php?app=member',
                         LANG::get('my_account'), 'index.php?app=account',
                         LANG::get('my_account'));
            /* 当前用户中心菜单 */
            $this->_curitem('account');
            $this->_curmenu('info');
            $this->_config_seo('title', Lang::get('member_center') . ' - ' . Lang::get('my_account'));
            $this->import_resource(array(
                'script' => array(
                    array(
                        'path' => 'dialog/dialog.js',
                        'attr' => 'id="dialog_js"',
                    ),
                    array(
                        'path' => 'jquery.ui/jquery.ui.js',
                        'attr' => '',
                    ),
                    array(
                        'path' => 'jquery.ui/i18n/' . i18n_code() . '.js',
                        'attr' => '',
                    ),
                    array(
                        'path' => 'jquery.plugins/jquery.validate.js',
                        'attr' => '',
                    ),
                    array(
                        'path' => 'jquery.plugins/jquery.validate.add.js',
                        'attr' => '',
                    ),
                ),
                'style' =>  'jquery.ui/themes/ui-lightness/jquery.ui.css',
            ));

           
          $this->display('account.reset_pwd.html');
            
        }
        else
        {
            $model = & m('bank');
            $user_id = $this->visitor->get('user_id');
            $bank_info = $model->get($user_id);
            $check_code = $_POST['check_code'];
            $this->checkPayCheckCode($check_code, 1);
            
            $model->edit ( $user_id,
                            array('password' => md5($password),
                            'update_time' => gmtime()
                            )
                        );
             if(isset($_SESSION['code']))
                unset($_SESSION['code']);
            $this->show_message('apply_ok', 'back_list', 'index.php?app=account');
        }
    }
    
    public function bank_info()
    {
        $this->_checkHasBank(); //验证是否有银行;
        $user_id = $this->visitor->get('user_id');
        
         if (!IS_POST)
        {
            /* 当前位置 */
            $this->_curlocal(LANG::get('member_center'), 'index.php?app=member',
                             LANG::get('my_account'), 'index.php?app=bank_info',
                             LANG::get('bank_info'));

            /* 当前用户中心菜单 */
            $this->_curitem('account');
            $this->_curmenu('bank_info');
            $this->_config_seo('title', Lang::get('member_center') . ' - ' . Lang::get('bank_info'));
            $this->import_resource(array(
                'script' => array(
                    array(
                        'path' => 'dialog/dialog.js',
                        'attr' => 'id="dialog_js"',
                    ),
                    array(
                        'path' => 'jquery.ui/jquery.ui.js',
                        'attr' => '',
                    ),
                    array(
                        'path' => 'jquery.ui/i18n/' . i18n_code() . '.js',
                        'attr' => '',
                    ),
                    array(
                        'path' => 'jquery.plugins/jquery.validate.js',
                        'attr' => '',
                    ),
                ),
                'style' =>  'jquery.ui/themes/ui-lightness/jquery.ui.css',
            ));
            //获取认证信息;         
            $model_auth = & m('memberauth');
            $auth_info = $model_auth->get($user_id);
            
             if($auth_info)
            {
                $array_data = explode('-',$auth_info['bank1_name']);
                if (count($array_data) > 3)
                {
                    $auth_info['s1'] = $array_data['0'];
                    $auth_info['c1'] = $array_data['1'];
                    $auth_info['q1'] = $array_data['2'];
                    unset($array_data[0]);unset($array_data[1]);unset($array_data[2]);
                    $auth_info['bank1_name'] = implode('-', $array_data);
                }
                $array_data = explode('-',$auth_info['bank2_name']);
                if (count($array_data) > 3)
                {
                    $auth_info['s2'] = $array_data['0'];
                    $auth_info['c2'] = $array_data['1'];
                    $auth_info['q2'] = $array_data['2'];
                    unset($array_data[0]);unset($array_data[1]);unset($array_data[2]);
                    $auth_info['bank2_name'] = implode('-', $array_data);
                }

            }
            
            $this->assign('auth', $auth_info);    
            /* 显示订单列表 */
            $this->display('account.bank_info.html');
        }
        else 
        {
            $this->checkPayCheckCode($_POST['check_code'], 1);
             $auth_mod  =& m('memberauth');

             $arr_data[] = $_POST['s1'];
                    $arr_data[] = $_POST['c1'];
                    $arr_data[] = $_POST['q1'];
                    $arr_data[] = $_POST['bank1_name'];
                    $bank1_name = implode('-',$arr_data);
                    unset($arr_data);
                    $arr_data[] = $_POST['s2'];
                    $arr_data[] = $_POST['c2'];
                    $arr_data[] = $_POST['q2'];
                    $arr_data[] = $_POST['bank2_name'];
                    $bank2_name = implode('-',$arr_data);
                    
            $auth_id = $this->visitor->get('user_id');
            $data = array(
                'bank1_name'   => $bank1_name,
                'bank1_user'   => $_POST['bank1_user'],
                'bank1_account'=> $_POST['bank1_account'],
                'bank2_name'   =>$bank2_name,
                'bank2_user'   => $_POST['bank2_user'],
                'bank2_account'=> $_POST['bank2_account']
            );
            
             $auth_mod->edit($auth_id, $data);
             if ($auth_mod->has_error())
            {
                $this->show_warning($auth_mod->get_error());
                return;
            }
            if(isset($_SESSION['code']))
                unset($_SESSION['code']);
             // if ($sgrade['need_confirm'])
            //{
                $this->show_message('op_ok',
                    'index', 'index.php?app=account&act=bank_info');
           // }
        }
    }
    
     function pay_list()
    {
         $this->_checkHasBank(); //验证是否有银行;
         
        $model = & m('bank');
        $user_id = $this->visitor->get('user_id');
        $bank_info = $model->get($user_id);
        
        /* 获取支付列表 - 获取我交易记录(收入和支出) */
        $this->_get_pays();

        /* 当前位置 */
        $this->_curlocal(LANG::get('member_center'), 'index.php?app=member',
                         LANG::get('my_account'), 'index.php?app=account',
                         LANG::get('my_account'));

        /* 当前用户中心菜单 */
        $this->_curitem('account');
        $this->_curmenu('all');
        $this->_config_seo('title', Lang::get('member_center') . ' - ' . Lang::get('all'));
        $this->import_resource(array(
            'script' => array(
                array(
                    'path' => 'dialog/dialog.js',
                    'attr' => 'id="dialog_js"',
                ),
                array(
                    'path' => 'jquery.ui/jquery.ui.js',
                    'attr' => '',
                ),
                array(
                    'path' => 'jquery.ui/i18n/' . i18n_code() . '.js',
                    'attr' => '',
                ),
                array(
                    'path' => 'jquery.plugins/jquery.validate.js',
                    'attr' => '',
                ),
            ),
            'style' =>  'jquery.ui/themes/ui-lightness/jquery.ui.css',
        ));
        
        /* 显示订单列表 */
        $this->display('account_pay.list.html');
    }
    
    
    /**
     *    订单支付详情
     *
     *    @author    rainstown
     *    @return    void
     */
    function pay_order()
    {
         /*取返回参数*/
        $attach        =$_GET['attach'];            
        $mch_id        =$_GET['mch_id'];                      //订单ID
        $mch_desc      =$_GET['mch_desc'];           
        $mch_name      =$_GET['mch_name'];                    //订单编号
        $mch_price     =$_GET['mch_price'];                       // 订单金额
        $mch_returl    =$_GET['mch_returl'];                      // 接收财付通返回结果的URL
        $mch_vno       =$_GET['mch_vno'];                  // 交易号(订单号)，由商户网站产生(建议顺序累加)
        $show_url      =$_GET['show_url'];           
        $transport_desc=$_GET['transport_desc'];     
        $transport_fee =$_GET['transport_fee'];      
        $version       =$_GET['version'];                         //版本号 2              
        $sign          =$_GET['sign'];                           // MD5签名  
        
         /* 交易参数 */
        /* 生成一个随机扰码 */
        $rand_num = rand(1,9);
        for ($i = 1; $i < 10; $i++)
        {
            $rand_num .= rand(0,9);
        }
        $sign_new = md5("attach=" . $attach . "&mch_id=" . $mch_id . "&key=".$this->_trance_key);
        
        $params = array(
            'attach'            => $attach,               //重新随机数
            'mch_id'            => $mch_id,                 //订单ID
            'mch_desc'          => $mch_desc,
            'mch_name'          => $mch_name,               //订单编号
            'mch_price'         => $mch_price,                  // 订单金额
            'mch_returl'        => $mch_returl,                 // 接收财付通返回结果的URL
            'mch_vno'           => $mch_vno,             // 交易号(订单号)，由商户网站产生(建议顺序累加)
            'show_url'          => $show_url,
            'transport_desc'    => $transport_desc,
            'transport_fee'     => $transport_fee,
            'version'           => $version,                    //版本号 2
            'sign'              => $sign_new,                       // MD5签名
        );
        
        
         /* 检查数字签名是否正确 */
        $sign_text = "attach=" . $attach . "&mch_id=" . $mch_id . "&mch_desc=" . $mch_desc . "&mch_name=" . $mch_name . "&mch_price=" . $mch_price ."&mch_returl=" . $mch_returl . "&mch_vno=" . $mch_vno . "&show_url=" . $show_url . "&version=" . $version . "&key=".$this->_trance_key; 
        
         
        $sign_md5 = md5($sign_text);
        if ($sign_md5 != $sign)
        {
            /* 若本地签名与网关签名不一致，说明签名不可信 */
            $this->_error('sign_inconsistent');
            return false;
        }
        else
        {
            //获取账户的可用余额和订单余额是否匹配
            $model_bank = & m('bank');
            $bank_info = $model_bank->get($this->visitor->get('user_id'));
            if (!isset($bank_info['money']))
            {//为开通虚拟账户交易
               $this->show_warning('has_no_bank');
               return ;
            }
            
            $common = new CommonIndex();
            $bank_info = $common->getUserBankCanUseMoney($this->visitor->get('user_id'));
            $account_info = $bank_info['money'];
        }
        // 获取显示订单信息
        $order_id = isset($_GET['mch_id']) ? intval($_GET['mch_id']) : 0;
        $model_order =& m('order');
        //$order_info  = $model_order->get("order_id={$order_id} AND buyer_id=" . $this->visitor->get('user_id'));
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

        /* 团购信息 */
        if ($order_info['extension'] == 'groupbuy')
        {
            $groupbuy_mod = &m('groupbuy');
            $group = $groupbuy_mod->get(array(
                'join' => 'be_join',
                'conditions' => 'order_id=' . $order_id,
                'fields' => 'gb.group_id',
            ));
            $this->assign('group_id',$group['group_id']);
        }
        
        $this->import_resource(array(
            'script' => array(
                array(
                    'path' => 'dialog/dialog.js',
                    'attr' => 'id="dialog_js"',
                ),
                array(
                    'path' => 'jquery.ui/jquery.ui.js',
                    'attr' => '',
                ),
                array(
                    'path' => 'jquery.ui/i18n/' . i18n_code() . '.js',
                    'attr' => '',
                ),
                array(
                    'path' => 'jquery.plugins/jquery.validate.js',
                    'attr' => '',
                ),
            ),
            'style' =>  'jquery.ui/themes/ui-lightness/jquery.ui.css',
        ));
        /* 当前位置 */
        $this->_curlocal(LANG::get('member_center'), 'index.php?app=member',
                         LANG::get('account'), 'index.php?app=account',
                         LANG::get('pay_list'));

        /* 当前用户中心菜单 */
        $this->_curitem('account');
        $this->_curmenu('order_list');
        $this->_config_seo('title', Lang::get('member_center') . ' - ' . Lang::get('account'));

        /* 调用相应的订单类型，获取整个订单详情数据 */
        $order_type =& ot($order_info['extension']);
        $order_detail = $order_type->get_order_detail($order_id, $order_info);
        foreach ($order_detail['data']['goods_list'] as $key => $goods)
        {
            empty($goods['goods_image']) && $order_detail['data']['goods_list'][$key]['goods_image'] = Conf::get('default_goods_image');
        }
        $this->assign('order', $order_info);
        $this->assign($order_detail['data']);
        $this->assign('account_info', $account_info);
        $this->assign('params', $params);
        
        //支付信息;
        $this->display('account_pay.form.html');
    }
    
    /**
     * 支付订单操作
     */
    function pay_action()
    {
         /*取返回参数*/
        $attach        =$_POST['attach'];            
        $mch_id        = intval($_POST['mch_id']);                      //订单ID
        $mch_desc      =$_POST['mch_desc'];           
        $mch_name      =$_POST['mch_name'];                    //订单编号
        $mch_price     =$_POST['mch_price'];                       // 订单金额
        $mch_returl    =$_POST['mch_returl'];                      // 接收财付通返回结果的URL
        $mch_vno       =$_POST['mch_vno'];                  // 交易号(订单号)，由商户网站产生(建议顺序累加)
        $show_url      =$_POST['show_url'];           
        $transport_desc=$_POST['transport_desc'];     
        $transport_fee =$_POST['transport_fee'];      
        $version       =$_POST['version'];                         //版本号 2              
        $sign          =$_POST['sign'];                           // MD5签名  
        
        $password      =$_POST['payment_password'];                           // 支付密码 
        $check_code    =$_POST['check_code'];                       // 手机验证码
        // 验证密码： 
        $this->checkPayPassword($password, $this->visitor->get('user_id'));
        // 验证手机验证码;
        $this->checkPayCheckCode($check_code);
         /* 交易参数 */
        $sign_text = "attach=" . $attach . "&mch_id=" . $mch_id . "&key=".$this->_trance_key;
        $sign_md5 = md5($sign_text);
        if ($sign_md5 != $sign)
        {
            /* 若本地签名与网关签名不一致，说明签名不可信 */
            $this->_error('sign_inconsistent');
            return false;
        }
        else
        {
            //获取账户的可用余额和订单余额是否匹配
            $model_bank = & m('bank');
            $bank_info = $model_bank->get($this->visitor->get('user_id'));
            if (empty($bank_info))
            {
                 $this->show_warning('has_no_bank');
                exit;
            }
            $common = new CommonIndex();
            $bank_info = $common->getUserBankCanUseMoney($this->visitor->get('user_id'));
            
            $account_info = isset($bank_info['money']) ? $bank_info['money']: 0;
        }
        
        // 获取显示订单信息
        $order_id = $mch_id;
        $model_order =& m('order');
        //$order_info  = $model_order->get("order_id={$order_id} AND buyer_id=" . $this->visitor->get('user_id'));
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
        
        /**
         * 如果是拍卖信息，更新拍卖纪录为已支付
         */
        if ($order_info['extension'] == 'auction')
        {
            $auction_mod = &m('auction_records');
            $auction_mod->updateToPay($order_id);
        }
        
        if ($order_info['status'] != ORDER_PENDING)
        {// 非待支付状态. 提示错误
            $this->show_warning('no_such_order');
            return ;
        }
        
        /* 调用相应的订单类型，获取整个订单详情数据 */
        $pay_name = '';
        $order_type =& ot($order_info['extension']);
        $order_detail = $order_type->get_order_detail($order_id, $order_info);
        foreach ($order_detail['data']['goods_list'] as $key => $goods)
        {
             $pay_name = $goods['goods_name'];
             break;
        }
        if ($order_detail['data']['goods_list'] > 0)
        {
           //$pay_name .= '等商品';
        }
        
        $order_amount = $order_info['order_amount'];
        if ($account_info < $order_amount )
        {//余额不足
            $this->show_warning('payment_has_no_more_money');
            return ; 
        }
        
        //支付 $order_amount -- 虚拟账户
        $bank_id = $this->visitor->get('user_id');
        $model_bank = &m('bank');
        $model_bank->payAccountToCautionMoney($bank_id, $order_amount); 
        if ($model_bank->has_error())
        {
            $this->pop_warning($model_bank->get_error());
            return;
        }
        
        $buyer_id = $bank_id;
        $buyer_name = $order_info['buyer_name'];
        $seller_id = $order_info['seller_id'];
        $seller_name = $order_info['seller_name'];
        /* 记录订单操作日志 */
        $pay_log =& m('pay');
        $pay_log->add(array(
            'order_id'  => $order_id,
            'type'      => PAY_TYPE_GOOD,
            'buyer_id'  => $buyer_id,
            'buyer_name' => $buyer_name,
            'seller_id'   => $seller_id,
            'seller_name'   => $seller_name,
            'money'     =>  $order_amount,
            'status'     =>  PAY_ACCEPTED,
            'pay_name'      => $pay_name,
            'create_time'=> gmtime(),
            'create_user'=> $bank_id
        ));
      
         /* 将验证结果传递给订单类型处理 */   //更新订单状态 --> 已经付款;
        $model_order =& m('order');
        $data = array('status' => ORDER_ACCEPTED);
         $data['pay_time']  = gmtime();
        $where = "order_id = {$order_id}";
        $where .= ' AND status=' . ORDER_PENDING;   //只有待付款的订单才会被修改为已付款
        $model_order->edit($where, $data);

        /* 发送邮件给卖家，提醒付款成功 */
        
        $model_member =& m('member');
        $seller_info  = $model_member->get($order_info['seller_id']);

        $mail = get_mail('toseller_online_pay_success_notify', array('order' => $order_info));
        $this->_mailto($seller_info['email'], addslashes($mail['subject']), addslashes($mail['message']));
        //
        $this->_sendmail(true);
         
        //跳转到相应页面;
        $cmd_no         = $mch_name;
        $retcode        = '';
        $status         = 20;
        $total_fee      = $order_amount;
        $trade_price    = '';
        $buyer_id       = $from_user;
        $chnid          = $_GET['chnid'];
        $cft_tid        = '';
        
        
        $sign_txt = "attach=" . $attach . "&buyer_id=" . $buyer_id . "&cft_tid=" . $cft_tid . "&chnid=" . $chnid . "&cmdno=" . $cmd_no . "&mch_vno=" . $mch_vno . "&retcode=" . $retcode . "&seller=" .$seller . "&status=" . $status . "&total_fee=" . $total_fee . "&trade_price=" . $trade_price . "&transport_fee=" . $transport_fee . "&version=" . $version . "&key=".$this->_trance_key;
        $sign = md5($sign_txt);
        if ($_SESSION['order_code'])
        {
            unset($_SESSION['order_code']);
        }
         header('Location:' .$show_url."&".$sign_txt.'&sign='.$sign);
    }
    

    /**
     * 充值
     */
    public function funds_in()
    {
        $this->_checkHasBank(); //验证是否有银行;
         
        $model = & m('bank');
        $user_id = $this->visitor->get('user_id');
        $bank_info = $model->get($user_id);
        
        /* 获取支付列表 - 获取我交易记录(收入和支出) */
        $this->_get_funds(FUNDS_IN);

        /* 当前位置 */
        $this->_curlocal(LANG::get('member_center'), 'index.php?app=member',
                         LANG::get('my_account'), 'index.php?app=account',
                         LANG::get('funds_in'));

        /* 当前用户中心菜单 */
        $this->_curitem('account');
        $this->_curmenu('funds_in');
        $this->_config_seo('title', Lang::get('member_center') . ' - ' . Lang::get('funds_in'));
        $this->import_resource(array(
            'script' => array(
                array(
                    'path' => 'dialog/dialog.js',
                    'attr' => 'id="dialog_js"',
                ),
                array(
                    'path' => 'jquery.ui/jquery.ui.js',
                    'attr' => '',
                ),
                array(
                    'path' => 'jquery.ui/i18n/' . i18n_code() . '.js',
                    'attr' => '',
                ),
                array(
                    'path' => 'jquery.plugins/jquery.validate.js',
                    'attr' => '',
                ),
            ),
            'style' =>  'jquery.ui/themes/ui-lightness/jquery.ui.css',
        ));
        
        /* 显示订单列表 */
        $this->display('account.funds_in.html');
    }
    
    public function funds_out()
    {
        $this->_checkHasBank(); //验证是否有银行;
        $user_id = $this->visitor->get('user_id');
        if (!IS_POST)
        {
           
            $common = new CommonIndex();
            $bank_info = $common->getUserBankCanUseMoney($user_id);
            $this->assign('bank_info', $bank_info); 
             //获取认证信息;

            $model_auth = & m('memberauth');
            $auth_info = $model_auth->get($user_id);
            $this->assign('auth', $auth_info); 

            /* 获取支付列表 - 获取我交易记录(收入和支出) */
            $this->_get_funds(FUNDS_OUT);

            /* 当前位置 */
            $this->_curlocal(LANG::get('member_center'), 'index.php?app=member',
                             LANG::get('my_account'), 'index.php?app=account',
                             LANG::get('funds_out'));

            /* 当前用户中心菜单 */
            $this->_curitem('account');
            $this->_curmenu('funds_out');
            $this->_config_seo('title', Lang::get('member_center') . ' - ' . Lang::get('funds_out'));
            $this->import_resource(array(
                'script' => array(
                    array(
                        'path' => 'dialog/dialog.js',
                        'attr' => 'id="dialog_js"',
                    ),
                    array(
                        'path' => 'jquery.ui/jquery.ui.js',
                        'attr' => '',
                    ),
                    array(
                        'path' => 'jquery.ui/i18n/' . i18n_code() . '.js',
                        'attr' => '',
                    ),
                    array(
                        'path' => 'jquery.plugins/jquery.validate.js',
                        'attr' => '',
                    ),
                ),
                'style' =>  'jquery.ui/themes/ui-lightness/jquery.ui.css',
            ));

            /* 显示订单列表 */
            $this->display('account.funds_out.html');
        }
        else
        {
            $bank_info = $_POST['bank_info'];
            $model_auth = & m('memberauth');
            $auth_info = $model_auth->get($user_id);
            if (empty($auth_info) || ($bank_info!=1 && $bank_info !=2 ))
            {
                 $this->show_warning('error_not_has');
                exit;
            }
            if ($bank_info == 1)
            {
                $bank_name = $auth_info['bank1_name'];
                $bank_user = $auth_info['bank1_user'];
                $bank_account = $auth_info['bank1_account'];
            }
            else if ($bank_info == 2)
            {
                $bank_name = $auth_info['bank2_name'];
                $bank_user = $auth_info['bank2_user'];
                $bank_account = $auth_info['bank2_account'];
            }
            if (empty($bank_name) || empty($bank_user) || empty($bank_account))
            {//银行账户信息错误;
                $this->show_warning('error_bank_info');
                exit; 
            }
            $this->checkPayCheckCode($_POST['check_code'], 1);
             
            $money = $_POST['money'];
            
            $common = new CommonIndex();
            $bank_info = $common->getUserBankCanUseMoney($user_id); 
            
            if ($money > $bank_info['money'] || empty($money) || empty($bank_info['money']) )
            {
                 $this->show_warning('error_bank_has_no_money'); 
                  exit; 
            }
            
            $order_sn = $user_id . '-'. gmtime();
            
             // 金额 到 保证账户;
             $bank_model->payAccountToCautionMoney($user_id, $money);
              
            //验证金额
             if ($bank_model->has_error())
            {
                $this->show_warning($auth_mod->get_error());
                return;
            }
            
            $m_funds = m('funds');
            $user_name = $this->visitor->get('user_name');
            $funds_name = '取现申请';
            $funds_id = $m_funds->add(
                    array(
                        'order_sn' => $order_sn,
                        'in_out' => FUNDS_OUT,
                        'type' =>  FUNDS_BANK,  //用户申请 u 
                        'money' => $money,
                        'user_id' => $user_id,
                        'user_name' => $user_name,
                        'funds_name' => $funds_name,
                        'name' => $bank_name,
                        'account' => $bank_account,
                        'account_name' => $bank_user,
                        'status' => PAY_ACCEPTED,   // 10 已提交， 0取消， 20 已付款， 40 已完成;
                        'create_time' =>  gmtime(),
                        'create_user' => $user_id
                    )
           );
            
           if ($funds_id)
           {
                //验证金额
                $m_funds_log = m('fundslog');
                $m_funds_log->add(
                        array(
                            'funds_id' => $funds_id,
                            'type' => 'A', // a, e
                            'status' => '20',
                            'money' => $money,
                            'user_id' => $user_id,
                            'user_name' => $user_name,
                            'create_time' =>  gmtime(),
                            'create_user' => $user_id
                        )
               );
           }
           
           $this->show_message('apply_ok', 'back_list', 'index.php?app=account&act=funds_out');
        }
    }
    
    /**
     *    获取订单列表
     *
     *    @author    Garbin
     *    @return    void
     */
    function _get_pays()
    {
        $page = $this->_get_page(20);
        $model_pay =& m('pay');

        $con = array(
            array(      //按订单状态搜索
                'field' => 'type',
                'name'  => 'type',
                'handler' => 'order_status_translator',
            ),
            array(      //按店铺名称搜索
                'field' => 'pay_name',
                'equal' => 'LIKE',
            ),
            array(      //按下单时间搜索,起始时间
                'field' => 'create_time',
                'name'  => 'add_time_from',
                'equal' => '>=',
                'handler'=> 'gmstr2time',
            ),
            array(      //按下单时间搜索,结束时间
                'field' => 'create_time',
                'name'  => 'add_time_to',
                'equal' => '<=',
                'handler'=> 'gmstr2time_end',
            )
        );
        
        $conditions = $this->_get_query_conditions($con);
        //echo $conditions;
        /* 查找订单 */
        $user_id = $this->visitor->get('user_id');
        
        $pays = $model_pay->findAll(array(
            'conditions'    => "( buyer_id=" . $user_id . " OR  ( seller_id = " . $user_id . " AND status != 20 AND status != 0 ) ) {$conditions}",
            'fields'        => 'this.*',
            'count'         => true,
            'limit'         => $page['limit'],
            'order'         => 'create_time DESC'
        ));
        $page['item_count'] = $model_pay->getCount();
        $this->assign('types', array('all'     => Lang::get('all_orders'),
                                     'pending' => Lang::get('pending_orders'),
                                     'submitted' => Lang::get('submitted_orders'),
                                     'accepted' => Lang::get('accepted_orders'),
                                     'shipped' => Lang::get('shipped_orders'),
                                     'finished' => Lang::get('finished_orders'),
                                     'canceled' => Lang::get('canceled_orders')));
        $this->assign('type', $_GET['type']);
        $this->assign('user_id', $user_id);
        $this->assign('pays', $pays);
        $this->_format_page($page);
        $this->assign('page_info', $page);
    }
    
     /**
     *    获取订单列表
     *
     *    @author    Garbin
     *    @return    void
     */
    public function _get_funds($in_out)
    {
        $page = $this->_get_page(20);
        $model_pay =& m('funds');
        
        $con = array(
            array(      //按店铺名称搜索
                'field' => 'funds_name',
                'equal' => 'LIKE',
            ),
            array(      //按下单时间搜索,起始时间
                'field' => 'create_time',
                'name'  => 'add_time_from',
                'equal' => '>=',
                'handler'=> 'gmstr2time',
            ),
            array(      //按下单时间搜索,结束时间
                'field' => 'create_time',
                'name'  => 'add_time_to',
                'equal' => '<=',
                'handler'=> 'gmstr2time_end',
            )
        );
        
        $conditions = $this->_get_query_conditions($con);
        //echo $conditions;
        /* 查找订单 */
        $user_id = $this->visitor->get('user_id');
        $pays = $model_pay->findAll(array(
            'conditions'    => "user_id = " . $user_id . " AND in_out = '" . $in_out . "' {$conditions}",
            'fields'        => 'this.*',
            'count'         => true,
            'limit'         => $page['limit'],
            'order'         => 'create_time DESC'
        ));
        $page['item_count'] = $model_pay->getCount();
        $this->assign('type', $_GET['type']);
        $this->assign('user_id', $user_id);
        $this->assign('pays', $pays);
        $this->_format_page($page);
        $this->assign('page_info', $page);
    }

    /*三级菜单*/
    function _get_member_submenu()
    {
        $array = array(
            array(
                'name' => 'info',
                'url' => 'index.php?app=account',
            ),
            array(
                'name' => 'all',
                'url' => 'index.php?app=account&act=pay_list&amp;type=all_orders',
            ),
            array(
                'name' => 'funds_in',
                'url' => 'index.php?app=account&act=funds_in',
            ),
            array(
                'name' => 'funds_out',
                'url' => 'index.php?app=account&act=funds_out',
            ),
            
            array(
                'name' => 'bank_info',
                'url' => 'index.php?app=account&act=bank_info',
            )
             
             
        
        );
        return $array;
    }
    
    
    /**
     * 验证支付密码
     * @param type $pwd
     * @param type $user_id
     */
    function checkPayPassword($pwd, $user_id)
    {
        $m = &m('bank');
        $info = $m->get($user_id);
        if ($info['password'] != md5($pwd))
        {//密码错误
            $this->show_warning('error_for_password');
            exit;
        }
    }
    
    /**
     * 确认验证码是否正确
     * @param type $code
     */
    function checkPayCheckCode($code, $type = '')
    {
        if ($type)
        {
            if (!isset($_SESSION['code']) || $code != $_SESSION['code'])
            {//手机验证码错误
               $this->show_warning('error_for_check_code');
               exit;
            }
        }
        else
        {
            if (!isset($_SESSION['order']['code']) || $code != $_SESSION['order']['code'])
            {//手机验证码错误
               $this->show_warning('error_for_check_code');
               exit;
            }
        }
    } 
    
    function _checkHasBank()
    {
        $model = & m('bank');
        $user_id = $this->visitor->get('user_id');
        $bank_info = $model->get($user_id);
        
        if (empty($bank_info))
        {
            header('Location: index.php?app=account&act=apply');
            exit;
        }
        else
        {
            $common = new CommonIndex();
            $bank_info = $common->getUserBankCanUseMoney($user_id);
            return $bank_info;
        }
    }
    
    
    

}

?>
