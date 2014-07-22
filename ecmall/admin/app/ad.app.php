<?php

/**
 *    合作伙伴控制器
 *
 *    @author    Garbin
 *    @usage    none
 */
import('common_index');
class AdApp extends BackendApp
{
    var $_ad_mod;

    function __construct()
    {
        $this->AdApp();
    }

    function AdApp()
    {
        parent::BackendApp();

        $this->_ad_mod =& m('ad');
        $this->_common = new CommonIndex();
    }

    /**
     *    管理
     *
     *    @author    Garbin
     *    @param    none
     *    @return    void
     */
    function index()
    {
        $conditions = $this->_get_query_conditions(array(array(
                'field' => 'title',         //可搜索字段title
                'equal' => 'LIKE',          //等价关系,可以是LIKE, =, <, >, <>
            ),
        ));
        $page   =   $this->_get_page(10);    //获取分页信息
        $ads = $this->_ad_mod->find(array(
            'conditions'    => $conditions,
            'limit'         => $page['limit'],  //获取当前页的数据
            'order'         => 'type ASC , sort_order,ad_id ASC',
            'count'         => true             //允许统计
        )); //找出所有商城的合作伙伴
        foreach ($ads as $key => $ad)
        {
            $ad['logo']&&$ads[$key]['logo'] = dirname(site_url()) . '/' . $ad['logo'];
        }
        $page['item_count'] = $this->_ad_mod->getCount();   //获取统计的数据
        $types = $this->_common->get_type_select();
        $this->_format_page($page);
        $this->import_resource(array('script' => 'inline_edit.js'));
        $this->assign('filtered', $conditions? 1 : 0); //是否有查询条件
        $this->assign('page_info', $page);          //将分页信息传递给视图，用于形成分页条
        $this->assign('types',$types);
        $this->assign('ads', $ads);
        $this->display('ad.index.html');
    }
    /**
     *    新增
     *
     *    @author    Garbin
     *    @return    void
     */
    function add()
    {
        if (!IS_POST)
        {
            /* 显示新增表单 */
            $ad = array(
            'sort_order'    => '255',
            'link'          => 'http://',
            );
            $this->assign('ad' , $ad);
            $this->import_resource('jquery.plugins/jquery.validate.js');
            $types = $this->_common->get_type_select();
            $this->assign('types' , $types);
            $this->display('ad.form.html');
        }
        else
        {
            $data = array();
            $data['title']      =   $_POST['title'];
            $data['link']       =   $_POST['link'];
            $data['type']       =   $_POST['type'];
            $data['remark']       =   $_POST['remark'];
            $data['sort_order'] =   $_POST['sort_order'];

            if (!$ad_id = $this->_ad_mod->add($data))  //获取ad_id
            {
                $this->show_warning($this->_ad_mod->get_error());

                return;
            }

            /* 处理上传的图片 */
            $logo       =   $this->_upload_logo($ad_id);
            if ($logo === false)
            {
                return;
            }
            $logo && $this->_ad_mod->edit($ad_id, array('logo' => $logo)); //将logo地址记下

            $this->show_message('add_ad_successed',
                'back_list',    'index.php?app=ad',
                'continue_add', 'index.php?app=ad&amp;act=add'
            );
        }
    }

    /**
     *    编辑
     *
     *    @author    Garbin
     *    @return    void
     */
    function edit()
    {
        $ad_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        if (!$ad_id)
        {
            $this->show_warning('no_such_ad');

            return;
        }
        if (!IS_POST)
        {
            $find_data     = $this->_ad_mod->find($ad_id);
            if (empty($find_data))
            {
                $this->show_warning('no_such_ad');

                return;
            }
            $ad    =   current($find_data);
            if ($ad['logo'])
            {
                $ad['logo']  =   dirname(site_url()) . "/" . $ad['logo'];
            }
           $types = $this->_common->get_type_select();
            $this->assign('types' , $types);
            
            $this->assign('ad', $ad);
            $this->import_resource('jquery.plugins/jquery.validate.js');
            $this->display('ad.form.html');
        }
        else
        {
            $data = array();
            $data['title']      =   $_POST['title'];
            $data['link']       =   $_POST['link'];
            $data['type']       =   $_POST['type'];
             $data['remark']       =   $_POST['remark'];
            $data['sort_order'] =   $_POST['sort_order'];
            $logo               =   $this->_upload_logo($ad_id);
            $logo && $data['logo'] = $logo;
            if ($logo === false)
            {
                return;
            }
            $rows = $this->_ad_mod->edit($ad_id, $data);
            if ($this->_ad_mod->has_error())    //有错误
            {
                $this->show_warning($this->_ad_mod->get_error());

                return;
            }

            $this->show_message('edit_ad_successed',
                'back_list',     'index.php?app=ad',
                'edit_again', 'index.php?app=ad&amp;act=edit&amp;id=' . $ad_id);
        }
    }

    //异步修改数据
   function ajax_col()
   {
       $id     = empty($_GET['id']) ? 0 : intval($_GET['id']);
       $column = empty($_GET['column']) ? '' : trim($_GET['column']);
       $value  = isset($_GET['value']) ? trim($_GET['value']) : '';
       $data   = array();

       if (in_array($column ,array('title', 'sort_order')))
       {
           $data[$column] = $value;
           $this->_ad_mod->edit($id, $data);
           if(!$this->_ad_mod->has_error())
           {
               echo ecm_json_encode(true);
           }
       }
       else
       {
           return ;
       }
       return ;
   }

    function drop()
    {
        $ad_ids = isset($_GET['id']) ? trim($_GET['id']) : 0;
        if (!$ad_ids)
        {
            $this->show_warning('no_such_ad');

            return;
        }
        $ad_ids = explode(',', $ad_ids);//获取一个类似array(1, 2, 3)的数组
        if (!$this->_ad_mod->drop($ad_ids))    //删除
        {
            $this->show_warning($this->_ad_mod->get_error());

            return;
        }

        $this->show_message('drop_ad_successed');
    }

    /* 更新排序 */
    function update_order()
    {
        if (empty($_GET['id']))
        {
            $this->show_warning('Hacking Attempt');
            return;
        }

        $ids = explode(',', $_GET['id']);
        $sort_orders = explode(',', $_GET['sort_order']);
        foreach ($ids as $key => $id)
        {
            $this->_ad_mod->edit($id, array('sort_order' => $sort_orders[$key]));
        }

        $this->show_message('update_order_ok');
    }

    /**
     *    处理上传标志
     *
     *    @author    Garbin
     *    @param     int $ad_id
     *    @return    string
     */
    function _upload_logo($ad_id)
    {
        $file = $_FILES['logo'];
        if ($file['error'] == UPLOAD_ERR_NO_FILE) // 没有文件被上传
        {
            return '';
        }
        import('uploader.lib');             //导入上传类
        $uploader = new Uploader();
        $uploader->allowed_type(IMAGE_FILE_TYPE); //限制文件类型
        $uploader->addFile($_FILES['logo']);//上传logo
        if (!$uploader->file_info())
        {
            $this->show_warning($uploader->get_error() , 'go_back', 'index.php?app=ad&amp;act=edit&amp;id=' . $ad_id);
            return false;
        }
        /* 指定保存位置的根目录 */
        $uploader->root_dir(ROOT_PATH);

        /* 上传 */
        if ($file_path = $uploader->save('data/files/mall/ad', $ad_id))   //保存到指定目录，并以指定文件名$ad_id存储
        {
            return $file_path;
        }
        else
        {
            return false;
        }
    }
    
   
}

?>