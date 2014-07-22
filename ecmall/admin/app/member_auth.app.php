<?php

/* 申请认证 */
class Member_authApp extends BackendApp
{
    var $_mod;

    function __construct()
    {
        $this->Member_authApp();
    }

    function Member_authApp()
    {
        parent::__construct();
        $this->_mod =& m('memberauth');
    }
    
    function index() {
       $conditions = " 1 ";
        $filter = $this->_get_query_conditions(array(
            array(
                'field' => 'auth_name',
                'equal' => 'like',
            ),
            array(
                'field' => 'auth_state',
            ),
        ));
        $owner_name = trim($_GET['auth_name']);
        if ($owner_name)
        {
            $filter .= " AND (user_name LIKE '%{$owner_name}%' OR auth_name LIKE '%{$owner_name}%') ";
        }
        //更新排序
        if (isset($_GET['sort']) && isset($_GET['order']))
        {
            $sort  = strtolower(trim($_GET['sort']));
            $order = strtolower(trim($_GET['order']));
            if (!in_array($order,array('asc','desc')))
            {
                $sort  = 'sort_order';
                $order = '';
            }
        }
        else
        {
            $sort  = 'auth_id';
            $order = 'desc';
        }

        $this->assign('filter', $filter);
        $conditions .= $filter;
        $page = $this->_get_page();
        $stores = $this->_mod->find(array(
            'conditions' => $conditions,
            'join'  => 'belongs_to_user',
            'fields'=> 'this.*, member.user_name',
            'limit' => $page['limit'],
            'count' => true,
            'order' => "$sort $order"
        ));
       
            
        $auth_state = array(
            STORE_APPLYING  => LANG::get('wait_verify'),
            STORE_OPEN      => Lang::get('open'),
            STORE_CLOSED    => Lang::get('close'),
        );
        foreach ($stores as $key => $store)
        {
            $stores[$key]['state_name'] = $auth_state[$store['auth_state']];
        }
        $this->assign('auth_state', $auth_state);
        $this->assign('stores', $stores);

        $page['item_count'] = $this->_mod->getCount();
        $this->import_resource(array('script' => 'inline_edit.js'));
        $this->_format_page($page);
        $this->assign('filtered', $filter? 1 : 0); //是否有查询条件
        $this->assign('page_info', $page);

        $this->display('member_auth.index.html');
    }
    
    function edit()
    {
        $id = empty($_GET['id']) ? 0 : intval($_GET['id']);
        if (!IS_POST)
        {
            /* 是否存在 */
            $store = $this->_mod->get_info($id);
            if (!$store)
            {
                $this->show_warning('store_empty');
                return;
            }
            if ($store['certification'])
            {
                $certs = explode(',', $store['certification']);
                foreach ($certs as $cert)
                {
                    $store['cert_' . $cert] = 1;
                }
            }
            $this->assign('store', $store);


            $this->assign('auth_states', array(
                STORE_OPEN   => Lang::get('open'),
                STORE_CLOSED => Lang::get('close'),
            ));


            /* 导入jQuery的表单验证插件 */
            $this->import_resource(array(
                'script' => 'jquery.plugins/jquery.validate.js,mlselection.js'
            ));
            $this->assign('enabled_subdomain', ENABLED_SUBDOMAIN);
            $this->display('member_auth.form.html');
        }
        else
        {
            $id = empty($_POST['id']) ? 0 : intval($_POST['id']);
            $store_info = $this->_mod->get_info($id);
            
            $data = array(
                'auth_name'   => $_POST['auth_name'],
                'auth_card'   => $_POST['auth_card'],
                'auth_addr'   => $_POST['auth_addr'],
                'bank1_name'   => $_POST['bank1_name'],
                'bank1_user'   => $_POST['bank1_user'],
                'bank1_account'=> $_POST['bank1_account'],
                'bank2_name'   => $_POST['bank2_name'],
                'bank2_user'   => $_POST['bank2_user'],
                'bank2_account'=> $_POST['bank2_account'],
                'auth_state'        => $_POST['auth_state'],
            );
            $data['auth_state'] == STORE_CLOSED && $data['close_reason'] = $_POST['close_reason'];
            $old_info = $this->_mod->get_info($id); // 修改前的店铺信息
            $this->_mod->edit($id, $data);

            /* 如果修改了店铺状态，通知店主 */
            
            if ($old_info['state'] != $data['state'])
            {
                $ms =& msecmall();
                if ($data['state'] == STORE_CLOSED)
                {
                    // 关闭认证
                    $subject = Lang::get('close_member_notice');
                    //$content = sprintf(Lang::get(), $data['close_reason']);
                    $content = get_msg('tomember_member_closed_notify',array('reason' => $data['close_reason']));
                }
                else
                {
                    // 开启认证
                    $subject = Lang::get('open_member_notice');
                    $content = Lang::get('tomember_store_opened_notify');
                }
                $ms->pm->send(MSG_SYSTEM, $old_info['store_id'], '', $content);
                $this->_mailto($old_info['email'], $subject, $content);
            }
            

            $ret_page = isset($_GET['ret_page']) ? intval($_GET['ret_page']) : 1;
            $this->show_message('edit_ok',
                'back_list',    'index.php?app=member_auth&page=' . $ret_page,
                'edit_again',   'index.php?app=member_auth&amp;act=edit&amp;id=' . $id
            );
        }
    }


    function drop()
    {
        $id = isset($_GET['id']) ? trim($_GET['id']) : '';
        if (!$id)
        {
            $this->show_warning('no_store_to_drop');
            return;
        }

        $ids = explode(',', $id);
        foreach ($ids as $id)
        {
            $this->_drop_store_image($id); // 注意这里要先删除图片，再删除店铺，因为删除图片时要查店铺信息
        }
        if (!$this->_mod->drop($ids))
        {
            $this->show_warning($this->_mod->get_error());
            return;
        }

        /* 通知店主 */
        $user_mod =& m('member');
        $users = $user_mod->find(array(
            'conditions' => "user_id" . db_create_in($ids),
            'fields'     => 'user_id, user_name, email',
        ));
        foreach ($users as $user)
        {
            $ms =& ms();
            $subject = Lang::get('drop_store_notice');
            $content = get_msg('toseller_store_droped_notify');
            $ms->pm->send(MSG_SYSTEM, $user['user_id'], $subject, $content);
            $this->_mailto($user['email'], $subject, $content);
        }

        $this->show_message('drop_ok');
    }
    
     /* 删除店铺相关图片 */
    function _drop_store_image($id)
    {
        $files = array();

        /* 申请店铺时上传的图片 */
        $store = $this->_mod->get_info($id);
        for ($i = 1; $i <= 3; $i++)
        {
            if ($store['image_' . $i])
            {
                $files[] = $store['image_' . $i];
            }
        }

        /* 删除 */
        foreach ($files as $file)
        {
            $filename = ROOT_PATH . '/' . $file;
            if (file_exists($filename))
            {
                @unlink($filename);
            }
        }
    }

   
    /* 查看并处理店铺申请 */
    function view()
    {
        $id = empty($_GET['id']) ? 0 : intval($_GET['id']);
        if (!IS_POST)
        {
            /* 是否存在 */
            $store = $this->_mod->get_info($id);
            if (!$store)
            {
                $this->show_warning('Hacking Attempt');
                return;
            }

          
            $this->assign('store', $store);

            $this->display('member_auth.view.html');
        }
        else
        {
            $ret_page = isset($_GET['ret_page']) ? intval($_GET['ret_page']) : 1;
            /* 批准 */
            if (isset($_POST['agree']))
            {
                $this->_mod->edit($id, array(
                    'auth_state'      => STORE_OPEN,
                    'add_time'   => gmtime()
                ));
                  
                $content = get_msg('tomember_member_passed_notify');
                $ms =& ms();
                $ms->pm->send(MSG_SYSTEM, $id, '', $content);
                $store_info = $this->_mod->get_info($id);
                $this->send_feed('store_created', array(
                    'user_id'   =>  $store_info['store_id'],
                    'user_name'   => $store_info['user_name'],
                    'store_url'   => SITE_URL . '/' . url('app=store&id=' . $store_info['store_id']),
                    'seller_name'   => $store_info['store_name'],
                ));
                
                //$this->_hook('after_opening', array('user_id' => $id));
                $this->show_message('agree_ok',
                    'edit_the_store', 'index.php?app=member_auth&amp;act=edit&amp;id=' . $id,
                    'back_list', 'index.php?app=member_auth&page=' . $ret_page
                );
            }
            /* 拒绝 */
            elseif (isset($_POST['reject']))
            {
                $reject_reason = trim($_POST['reject_reason']);
                if (!$reject_reason)
                {
                    $this->show_warning('input_reason');
                    return;
                }
                 
                $content = get_msg('tomember_member_refused_notify', array('reason' => $reject_reason));
                $ms =& msecmall();
                $ms->pm->send(MSG_SYSTEM, $id, '', $content);
                
                $this->_drop_store_image($id); // 注意这里要先删除图片，再删除店铺，因为删除图片时要查店铺信息
                $this->_mod->drop($id);
                $this->show_message('reject_ok',
                    'back_list', 'index.php?app=member_auth&page=' . $ret_page
                );
            }
            else
            {
                $this->show_warning('Hacking Attempt');
                return;
            }
        }
    }
   
}

?>
