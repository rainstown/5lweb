<?php

/* 申请认证 */
class Member_authApp extends MallbaseApp
{

    function index()
    {
        $step = isset($_GET['step']) ? intval($_GET['step']) : 1;

        /* 只有登录的用户才可申请 */
        if (!$this->visitor->has_login)
        {
            $this->login();
            return;
        }

        /* 已申请过或已有店铺不能再申请 */
        $auth_mod =& m('memberauth');
        $auth = $auth_mod->get($this->visitor->get('user_id'));
        if ($auth)
        {
            if ($auth['auth_state'] != 1 )
            {
                $this->show_message('user_has_application',
                            'index', 'index.php?app=member&act=auth');
                return;
            }
            else
            {
                if ($step != 1)
                {
                    $this->show_warning('user_has_application');
                    return;
                }                
            }
        }
        
        switch ($step)
        {
            case 1:
               
                if (!IS_POST)
                {
                    $region_mod =& m('region');
                    $this->assign('site_url', site_url());
                    //$this->assign('regions', $region_mod->get_options(0));
                  //  $this->assign('scategories', $this->_get_scategory_options());

                    /* 导入jQuery的表单验证插件 */
                    $this->import_resource(array('script' => 'mlselection.js,jquery.plugins/jquery.validate.js'));

                    $this->_config_seo('title', Lang::get('member_auth') . ' - ' . Conf::get('site_title'));
                    
                    if($auth)
                    {
                        $array_data = explode('-',$auth['bank1_name']);
                        if (count($array_data) > 3)
                        {
                            $auth['s1'] = $array_data['0'];
                            $auth['c1'] = $array_data['1'];
                            $auth['q1'] = $array_data['2'];
                            unset($array_data[0]);unset($array_data[1]);unset($array_data[2]);
                            $auth['bank1_name'] = implode('-', $array_data);
                        }
                        $array_data = explode('-',$auth['bank2_name']);
                        if (count($array_data) > 3)
                        {
                            $auth['s2'] = $array_data['0'];
                            $auth['c2'] = $array_data['1'];
                            $auth['q2'] = $array_data['2'];
                            unset($array_data[0]);unset($array_data[1]);unset($array_data[2]);
                            $auth['bank2_name'] = implode('-', $array_data);
                        }
                        
                    }
                    $this->assign('auth', $auth);
                    $this->display('member_auth_apply.html');
                }
                else
                {
                    $auth_mod  =& m('memberauth');

                    $auth_id = $this->visitor->get('user_id');
                   
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
                    
                    $data = array(
                        'auth_id'     => $auth_id,
                        'auth_name'   => $_POST['auth_name'],
                        'auth_card'   => $_POST['auth_card'],
                        'auth_addr'   => $_POST['auth_addr'],
                        'bank1_name'   => $bank1_name,
                        'bank1_user'   => $_POST['bank1_user'],
                        'bank1_account'=> $_POST['bank1_account'],
                        'bank2_name'   => $bank2_name,
                        'bank2_user'   => $_POST['bank2_user'],
                        'bank2_account'=> $_POST['bank2_account'],
                        'auth_state'        => 0,
                        'create_time'     => gmtime(),
                    );
                    $image = $this->_upload_image($auth_id);
                    if ($this->has_error())
                    {
                        $this->show_warning($this->get_error());

                        return;
                    }
                    
                    /* 判断是否已经申请过 */
                    $state = $this->visitor->get('auth_state');
                    if ($state != '' && $state == STORE_APPLYING)
                    {
                        $auth_mod->edit($auth_id, array_merge($data, $image));
                    }
                    else
                    {
                        $auth_mod->add(array_merge($data, $image));
                    }
                    
                    if ($auth_mod->has_error())
                    {
                        $this->show_warning($auth_mod->get_error());
                        return;
                    }

                     // if ($sgrade['need_confirm'])
                    //{
                        $this->show_message('op_ok',
                            'index', 'index.php?app=member');
                   // }
                    /*
                    else
                    {
                        $this->send_feed('store_created', array(
                            'user_id'   => $this->visitor->get('user_id'),
                            'user_name'   => $this->visitor->get('user_name'),
                            'store_url'   => SITE_URL . '/' . url('app=store&id=' . $store_id),
                            'seller_name'   => $data['store_name'],
                        ));
                        $this->_hook('after_opening', array('user_id' => $store_id));
                        $this->show_message('store_opened',
                            'index', 'index.php');
                    }
                    */
                }
                break;
            default:
                header("Location:index.php?app=member_auth");
                break;
        }
    }

    /* 上传图片 */
    function _upload_image($id)
    {
        import('uploader.lib');
        $uploader = new Uploader();
        $uploader->allowed_type(IMAGE_FILE_TYPE);
        $uploader->allowed_size(SIZE_STORE_CERT); // 400KB

        $data = array();
        for ($i = 1; $i <= 2; $i++)
        {
            $file = $_FILES['image_' . $i];
            if ($file['error'] == UPLOAD_ERR_OK)
            {
                if (empty($file))
                {
                    continue;
                }
                $uploader->addFile($file);
                if (!$uploader->file_info())
                {
                    $this->_error($uploader->get_error());
                    return false;
                }

                $uploader->root_dir(ROOT_PATH);
                $dirname   = 'data/files/mall/application';
                $filename  = 'member_auth_' . $id . '_' . $i;
                $data['image_' . $i] = $uploader->save($dirname, $filename);
            }
        }
        return $data;
    }
}

?>
