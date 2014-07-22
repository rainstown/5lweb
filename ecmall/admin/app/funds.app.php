<?php

/**
 *    合作伙伴控制器
 *
 *    @author    Garbin
 *    @usage    none
 */
class FundsApp extends BackendApp
{
    /**
     *    管理
     *
     *    @author    Garbin
     *    @param    none
     *    @return    void
     */
    function index()
    {
        $search_options = array(
            'seller_name'   => Lang::get('seller_name'),
            'buyer_name'   => Lang::get('buyer_name'),
            'pay_name'   => Lang::get('pay_name'),
        );
        /* 默认搜索的字段是店铺名 */
        $field = 'seller_name';
        array_key_exists($_GET['field'], $search_options) && $field = $_GET['field'];
        $conditions = $this->_get_query_conditions(array(array(
                'field' => $field,       //按用户名,店铺名,支付方式名称进行搜索
                'equal' => 'LIKE',
                'name'  => 'search_name',
            ),array(
                'field' => 'status',
                'equal' => '=',
                'type'  => 'numeric',
            ),array(
                'field' => 'create_time',
                'name'  => 'add_time_from',
                'equal' => '>=',
                'handler'=> 'gmstr2time',
            ),array(
                'field' => 'create_time',
                'name'  => 'add_time_to',
                'equal' => '<=',
                'handler'   => 'gmstr2time_end',
            ),array(
                'field' => 'money',
                'name'  => 'order_amount_from',
                'equal' => '>=',
                'type'  => 'numeric',
            ),array(
                'field' => 'money',
                'name'  => 'order_amount_to',
                'equal' => '<=',
                'type'  => 'numeric',
            ),
        ));
        $model_order =& m('funds');
        $page   =   $this->_get_page(10);    //获取分页信息
        //更新排序
        if (isset($_GET['sort']) && isset($_GET['order']))
        {
            $sort  = strtolower(trim($_GET['sort']));
            $order = strtolower(trim($_GET['order']));
            if (!in_array($order,array('asc','desc')))
            {
             $sort  = 'create_time';
             $order = 'desc';
            }
        }
        else
        {
            $sort  = 'create_time';
            $order = 'desc';
        }
        $orders = $model_order->find(array(
            'conditions'    => '1=1 ' . $conditions,
            'limit'         => $page['limit'],  //获取当前页的数据
            'order'         => "$sort $order",
            'count'         => true             //允许统计
        )); //找出所有商城的合作伙伴
        $page['item_count'] = $model_order->getCount();   //获取统计的数据
        $this->_format_page($page);
        $this->assign('filtered', $conditions? 1 : 0); //是否有查询条件
        $this->assign('order_status_list', array(
            PAY_ACCEPTED => Lang::get('pay_accepted'),
            PAY_FINISHED => Lang::get('pay_finished'),
            PAY_CANCELED => Lang::get('pay_canceled')
        ));
        $this->assign('search_options', $search_options);
        $this->assign('page_info', $page);          //将分页信息传递给视图，用于形成分页条
        $this->assign('orders', $orders);
        $this->import_resource(array('script' => 'inline_edit.js,jquery.ui/jquery.ui.js,jquery.ui/i18n/' . i18n_code() . '.js',
                                      'style'=> 'jquery.ui/themes/ui-lightness/jquery.ui.css'));
        $this->display('funds.index.html');
    }

    function in_funds()
    {
        if(IS_POST)
        {// 表达提交;
            $user_name = $_POST['user_name'];
            $money = $_POST['money'];
            $type = 'P';
            $funds_name = $_POST['funds_name'];
            $name = $_POST['name'];
            $account = $_POST['account'];
            $account_name = $_POST['account_name'];
            $remark = $_POST['remark'];
            
            if (empty($user_name) || empty($money)|| empty($type)|| empty($funds_name))
            {//信息不全;
                $this->show_warning('请输入用户名，金额，资金项目');
                 exit;
            }
            //验证用户名是否存在;
             $ms =& ms();
            $info = $ms->user->get($user_name, true);
            if (empty($info))
            {
                  $this->show_warning('用户不存在');
                  exit;
            }
            $order_sn = $info['user_id'] . '-'. gmtime();
            //验证金额
            $m_funds = m('funds');
            $funds_id = $m_funds->add(
                    array(
                        'order_sn' => $order_sn,
                        'in_out' => 'I',
                        'type' => $type,
                        'money' => $money,
                        'user_id' => $info['user_id'],
                        'user_name' => $user_name,
                        'funds_name' => $funds_name,
                        'name' => $name,
                        'account' => $account,
                        'account_name' => $account_name,
                        'remark' => $remark,
                        'status' => '40',
                        'create_time' =>  gmtime(),
                        'create_user' => $_SESSION['admin_info']['user_id']
                    )
                    );
            //验证充值名称;
            $user_id = $_SESSION['admin_info']['user_id'];
            $user_name = $_SESSION['admin_info']['user_name'];
            $m_funds_log = &m('fundslog');
             $m_funds_log->add(
                    array(
                        'funds_id' => $funds_id,
                        'type' => 'E', // a, e
                        'status' => '40',
                        'money' => $money,
                        'user_id' => $user_id,
                        'remark' => $remark,
                        'user_name' => $user_name,
                        'create_time' =>  gmtime(),
                        'create_user' => $user_id
                    )
            );
             
            $m_bank = & m('bank');
            $m_bank->addMoney($info['user_id'], $money);
            $this->show_message('add_ok',
                'back_list',    'index.php?app=funds',
                'continue_add', 'index.php?app=funds&amp;act=in_funds'
            );
        }
        else
        {
          $this->display('funds.in_funds.form.html');
        }
    }
    /**
     *    查看
     *
     *    @author    Garbin
     *    @param    none
     *    @return    void
     */
    function view_funds()
    {
         $funds_id = isset($_GET['funds_id']) ? intval($_GET['funds_id']) : 0;
        if (!$funds_id)
        {
            $this->show_warning('no_such_info');
            return;
        }

        /* 获取订单信息 */
        $model_funds =& m('funds');
        $info = $model_funds->get(array(
            'conditions'    => $funds_id,
        ));

        if (!$info)
        {
            $this->show_warning('no_such_info');
            return;
        }
            
        if(!IS_POST)
        {
            $this->assign('info', $info);
            if ($info['status'] == PAY_ACCEPTED &&  $info['in_out'] == FUNDS_OUT)
            {//提现申请，且是提交状态
                 $this->assign('auth_states', array(
                    STORE_OPEN   => Lang::get('status_pass'),
                    STORE_CLOSED => Lang::get('status_deny'),
                ));

                $this->display('funds.out.check.html');
            }
            else
            {
                $this->display('funds.view.html');
            }
        }
        else
        {
            if ($info['status']!=PAY_ACCEPTED )
            {// 不是待处理状态,提示错误;
                $this->show_warning('info_has_be_deal');
                return;
            }
            // 处理意见内容没写
            var_dump($_POST);
            if (!isset($_POST['auth_state']) || ($_POST['auth_state']!= 2 && $_POST['auth_state']!=1))
            {
                 $this->show_warning('no_such_info');
                return;
            }
            
            $bank_id = $info['user_id'];
            $money = $info['money'];
            if ($_POST['auth_state'] == 1)
            {//提现申请审核通过
                $bank_mod = & m('bank');
                $bank_mod->payCautionMoney($bank_id, $money);
                if ($bank_mod->has_error())
                {
                    $this->show_warning($bank_mod->get_error());
                    return;
                }
                $status = '40';
            }
            else if ($_POST['auth_state'] == 2)
            {//提现申请审核被拒绝
                $bank_mod = & m('bank');
                $bank_mod->payCautionMoneyToAccount($bank_id, $money);
                if ($bank_mod->has_error())
                {
                    $this->show_warning($bank_mod->get_error());
                    return;
                }
                $status = '0';
            }
             //更新状态;
             $user_id = $_SESSION['admin_info']['user_id'];
             $user_name = $_SESSION['admin_info']['user_name'];
             $m_funds = &m('funds');
             $m_funds->edit($funds_id, array('status' => $status, 'remark'=>$remark, 'update_time'=>  gmtime(), 'update_user'=>$user_id));
             //添加操作记录;
            $m_funds_log = &m('fundslog');
            $m_funds_log->add(
                    array(
                        'funds_id' => $funds_id,
                        'type' => 'E', // a, e
                        'status' => $status,
                        'money' => $money,
                        'user_id' => $user_id,
                        'remark' => $remark,
                        'user_name' => $user_name,
                        'create_time' =>  gmtime(),
                        'create_user' => $user_id
                    )
            );
            $this->show_message('add_ok',
                'back_list',    'index.php?app=funds'
            ); 
        }
    }
    
   
}
?>
