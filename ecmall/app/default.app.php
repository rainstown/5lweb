<?php
import('common_index');
import('auction');
import('enum_status');
class DefaultApp extends MallbaseApp
{
    
    function index()
    {
        $this->assign('index_page', 1); // 标识当前页面是首页，用于设置导航状态
        $this->assign('icp_number', Conf::get('icp_number'));
        
        //获取广告内容
       $com = new CommonIndex();
       
       $ad_list = $com->getIndexAd();
       foreach($ad_list as $key => $val )
       {
            $this->assign($key, $val);
       }
       
       //网站公告
      
       $com->set_cate_code('web');
       $web_list = $com->get_article();
       $this->assign('web_list', $web_list['articles']);
       //帮助中心
       $com->set_cate_code('help');
       $help_list = $com->get_article();
       $this->assign('help_list', $help_list['articles']);
       
       //推荐藏品
       //获取第一个藏品类别;
       $recommend = m('recommend');
       $recom_info = $recommend->getInfoFirst(); 
       $rec_goods = array();
      
       if ($recom_info)
       {
           $recom_id = $recom_info['recom_id'];
           //获取推荐的产品;
           $rec_goods = $recommend->get_recommended_goods($recom_id, 15, $default_image = true, $mall_cate_id = 0);
       }
        $this->assign('recom_info', $recom_info);
        $this->assign('rec_goods', $rec_goods);
       
        
        /* 热门搜素 */
        $this->assign('hot_keywords', $this->_get_hot_keywords());

        $this->_config_seo(array(
            'title' => Lang::get('mall_index') . ' - ' . Conf::get('site_title'),
        ));
        
        /* 拍卖内容 */
        $_mod_auction = &m('auction');
        $_mod_auction_goods = &m('auction_goods');
        $db = db();
        //获得正在进行中的条件
        $now_date = get_system_date();
        $where_goods = 'ag.start_time <= "' . $now_date . '" AND ag.end_time >= "' . $now_date . '" AND ag.status = "' . EnumStatus::VALID . '"';
        $where_common_auction = 'a.start_time <= "' . $now_date . '" AND a.end_time >= "' . $now_date . '" AND 
        	EXISTS(SELECT * FROM ' . DB_PREFIX . 'auction_goods AS ag WHERE ag.auction_id = a.auction_id AND ' . $where_goods . ')';
        //获得正在进行中的4个专场拍卖
        /*
        $o_auction = $_mod_auction->find(array(
        	'conditions' => ' type = "' . Auction::TYPE_OFFICAL . '" AND ' . $where . ' AND status="' . EnumStatus::VALID . '"',
        	'limit' => 4,
        	'order' => 'end_time ASC'
        ));
        */
        $sql_o_auction = 'SELECT * FROM ' . DB_PREFIX . 'auction AS a WHERE type="' . Auction::TYPE_OFFICAL . '" AND status="' . EnumStatus::VALID . '" AND ' . $where_common_auction;
        $o_auction = $db->getAllWithIndex($sql_o_auction, 'auction_id');
        $sql_p_auction = 'SELECT * FROM ' . DB_PREFIX . 'auction AS a WHERE type="' . Auction::TYPE_PERSONAL . '" AND status="' . EnumStatus::PASS . '" AND ' . $where_common_auction;
        $p_auction = $db->getAllWithIndex($sql_p_auction, 'auction_id');
        //获得长在进行中的7个个人拍卖
        /*
        $p_auction = $_mod_auction->find(array(
        	'conditions' => 'type="' . Auction::TYPE_PERSONAL . '" AND ' . $where . ' AND status="' . EnumStatus::PASS . '"',
        	'limit' => 7,
        	'order' => 'end_time ASC'
        ));
        */
        //获得拍卖品
        $arr_auction_id = array(0);
        foreach ($o_auction as $auction) {
        	$arr_auction_id[] = $auction['auction_id'];
        }
        foreach ($p_auction as $auction) {
        	$arr_auction_id[] = $auction['auction_id'];
        }
        $sql = 'SELECT ag.auction_id,g.default_image, g.goods_id, g.goods_name, ag.start_time, ag.end_time, ag.curr_price, ag.pay_num FROM ' . DB_PREFIX . 'auction_goods AS ag INNER JOIN ' . DB_PREFIX . 'goods AS g ON ag.goods_id = g.goods_id
        	WHERE ag.auction_id IN (' . implode(',', $arr_auction_id) . ')
        		AND ' . $where_goods . ' AND status="' . EnumStatus::VALID . '"
        		ORDER BY ag.create_time ASC
        	';
        $goods_list = $db->getAll($sql);
        $arr_goods_list = array();
        $now = time();
        foreach ($goods_list as $goods) {
        	$goods['default_image'] = $goods['default_image'] ? $goods['default_image'] : Conf::get('default_goods_image');
        	$end_time_stamp = Auction::getEndTimeStamp($goods['end_time']);
        	$goods['last_time'] = $end_time_stamp - $now;
        	$arr_goods_list[$goods['auction_id']][$goods['goods_id']] = $goods;
        }
        $this->assign('goods_list', $arr_goods_list);
        $this->assign('o_auction', ($o_auction));
        $this->assign('p_auction', ($p_auction));
        
        /* 获得即将开始拍卖品 */
       	$_sql_goods= 'SELECT g.goods_name, ag.curr_price, ag.goods_id, ag.pay_num,ag.auction_id FROM ' . DB_PREFIX . 'auction_goods AS ag INNER JOIN ' . DB_PREFIX. 'goods AS g ON g.goods_id = ag.goods_id
       		INNER JOIN ' . DB_PREFIX . 'auction AS a ON a.auction_id = ag.auction_id
       		WHERE ag.status ="' . EnumStatus::VALID . '" AND (a.status = "' . EnumStatus::VALID . '" OR a.status="' . EnumStatus::PASS . '")';
       	$sql_goods_starting = $_sql_goods . ' AND ag.start_time <= "' . $now_date . '" AND ag.end_time >= "' . $now_date . '" ORDER BY ag.start_time ASC LIMIT 10';
       	$starting_goods_list = $db->getAll($sql_goods_starting);
       	
        /* 获得最多拍卖的拍卖品 */
       	$sql_goods_popular = $_sql_goods . ' AND ag.start_time <= "' . $now_date . '" AND ag.end_time >= "' . $now_date . '" ORDER BY ag.pay_num DESC LIMIT 10';
       	$popular_goods_list = $db->getAll($sql_goods_popular);
       	
       	$this->assign('starting_goods_list', $starting_goods_list);
       	$this->assign('popular_goods_list', $popular_goods_list);
        
        //推荐店铺
        $store_mod = &m('store');
        $page = $this->_get_page(18);
        $conditions = 'state = 1 AND recommended = 1 ';
        $sort  = 'sort_order';
        $order = '';
        $r_stores = $store_mod->find(array(
            'conditions' => $conditions,
            'join'  => 'belongs_to_user',
            'fields'=> 'this.*,member.user_name',
            'limit' => $page['limit'],
            'count' => true,
            'order' => "$sort $order"
        ));
        $r_stores = $this->_stroe_region_name($r_stores);
        $this->assign('r_stores', $r_stores);
        
        //读取论坛信息；
        //最新帖子;
        $sql = 'SELECT t.subject, p.tid , t.views FROM '.DZ_DB.'.'.DZ_PRE.'forum_thread t
                    INNER JOIN  '.DZ_DB.'.'.DZ_PRE.'forum_post p ON t.tid = p.tid AND p.first = 1 ORDER BY t.dateline DESC limit 10 ';
        $dz_news_post = $db->getAll($sql);
        $this->assign('dz_news_post', $dz_news_post);
        
        //最新回复;
        $sql = 'SELECT t.subject, p.tid , t.replies FROM  '.DZ_DB.'.'.DZ_PRE.'forum_thread t
                    INNER JOIN  '.DZ_DB.'.'.DZ_PRE.'forum_post p ON t.tid = p.tid AND p.first = 1 WHERE t.replies > 0 ORDER BY t.lastpost DESC limit 10 ';
        $dz_rep_post = $db->getAll($sql);
        $this->assign('dz_rep_post', $dz_rep_post);
        
        
        $this->assign('page_description', Conf::get('site_description'));
        $this->assign('page_keywords', Conf::get('site_keywords'));
        $this->display('index.html');
    }
    
    
    function mall()
    {
       
        $this->assign('icp_number', Conf::get('icp_number'));
         //获取广告内容
        $com = new CommonIndex();
        $ad_list = $com->getIndexMallAd();
         foreach($ad_list as $key => $val )
        {
             $this->assign($key, $val);
        }
        $this->assign('ppt_num', count($ad_list['ad_list_1'])); //幻灯片数量 
        
        
        //商城广告
       $com->set_cate_code('notice');
       $notice_list = $com->get_article(notice_mall_index);
       $this->assign('notice_list', $notice_list['articles']);
        
        //获取认证店铺;
        $store_mod = &m('store');
        $page = $this->_get_page(40);
        $conditions = 'state = 1 AND certification != ""';
        $sort  = 'sort_order';
        $order = '';
        $c_stores = $store_mod->find(array(
            'conditions' => $conditions,
            'join'  => 'belongs_to_user',
            'fields'=> 'this.*,member.user_name',
            'limit' => $page['limit'],
            'count' => true,
            'order' => "$sort $order"
        ));
        $c_stores = $this->_stroe_region_name($c_stores);
        $this->assign('c_stores', $c_stores);
        
        //推荐店铺
        $store_mod = &m('store');
        $page = $this->_get_page(40);
        $conditions = 'state = 1 AND recommended = 1 ';
        $sort  = 'sort_order';
        $order = '';
        $r_stores = $store_mod->find(array(
            'conditions' => $conditions,
            'join'  => 'belongs_to_user',
            'fields'=> 'this.*,member.user_name',
            'limit' => $page['limit'],
            'count' => true,
            'order' => "$sort $order"
        ));
        $r_stores = $this->_stroe_region_name($r_stores);
        $this->assign('r_stores', $r_stores);
        /* 热门搜素 */
        $this->assign('hot_keywords', $this->_get_hot_keywords());

        $this->_config_seo(array(
            'title' => Lang::get('mall_index') . ' - ' . Conf::get('site_title'),
        ));
        
        /* 取得商品分类 */
        $gcategorys = $this->_list_gcategory();
        $this->assign('gcategorys', $gcategorys);
        
        
        
        
        
        $this->assign('page_description', Conf::get('site_description'));
        $this->assign('page_keywords', Conf::get('site_keywords'));
        $this->display('index_mall.html');
    }
    
     /* 取得商品分类 --  */ 
    function _list_gcategory()
    {
        $cache_server =& cache_server();
        $key = 'page_mall_category';
        $data = $cache_server->get($key);
        if ($data === false)
        {
            $gcategory_mod =& bm('gcategory', array('_store_id' => 0));
            $gcategories = $gcategory_mod->get_list(-1,true);
    
            import('tree.lib');
            $tree = new Tree();
            $tree->setTree($gcategories, 'cate_id', 'parent_id', 'cate_name');
            $data = $tree->getArrayList(0);

            $cache_server->set($key, $data, 3600);
        }

        return $data;
    }

    public function auction()
    {
    	 
    	//获取广告内容
	
    	$com = new CommonIndex();
    	
    	$ad_list = $com->getIndexAuctionAd();
    	$this->assign('ad_list', $ad_list);

    	foreach($ad_list as $key => $val )
    	{
    		$this->assign($key, $val);
    	}
		
        $this->assign('icp_number', Conf::get('icp_number'));
        /* 热门搜素 */
        $this->assign('hot_keywords', $this->_get_hot_keywords());

        $_mod_auction = &m('auction');
        $_mod_auction_goods = &m('auction_goods');
        $db = db();
        //获得正在进行中的条件
        $now_date = get_system_date();
        $where = 'start_time <= "' . $now_date . '" AND end_time >= "' . $now_date . '"';
        //获得正在进行中的4个专场拍卖
        $o_auction = $_mod_auction->find(array(
        	'conditions' => ' type = "' . Auction::TYPE_OFFICAL . '" AND ' . $where . ' AND status="' . EnumStatus::VALID . '"',
        	'limit' => 3,
        	'order' => 'end_time ASC'
        ));
        //获得长在进行中的7个个人拍卖
        $p_auction = $_mod_auction->find(array(
        	'conditions' => 'type="' . Auction::TYPE_PERSONAL . '" AND ' . $where . ' AND status="' . EnumStatus::PASS . '"',
        	'limit' => 7,
        	'order' => 'end_time ASC'
        ));
        //获得拍卖品
        $arr_auction_id = array(0);
        foreach ($o_auction as $auction) {
        	$arr_auction_id[] = $auction['auction_id'];
        }
        foreach ($p_auction as $auction) {
        	$arr_auction_id[] = $auction['auction_id'];
        }
        $sql = 'SELECT ag.auction_id,g.default_image, g.goods_id, g.goods_name, ag.start_time, ag.end_time, ag.curr_price, ag.pay_num FROM ' . DB_PREFIX . 'auction_goods AS ag INNER JOIN ' . DB_PREFIX . 'goods AS g ON ag.goods_id = g.goods_id
        	WHERE ag.auction_id IN (' . implode(',', $arr_auction_id) . ') 
        		AND ' . $where . ' AND status="' . EnumStatus::VALID . '"
        	ORDER BY ag.create_time ASC
        	';
        $goods_list = $db->getAll($sql);
        $arr_goods_list = array();
        $now = time();
       	foreach ($goods_list as  $goods) {
       		$goods['default_image'] = $goods['default_image'] ? $goods['default_image'] : Conf::get('default_goods_image');
       		$end_time_stamp = Auction::getEndTimeStamp($goods['end_time']);
       		$goods['last_time'] = $end_time_stamp - $now;
       		$arr_goods_list[$goods['auction_id']][$goods['goods_id']] = $goods;
       	}
       	//即将开始
       	/*
       	$starting_auction = $_mod_auction->find(array(
       		'conditions' => 'start_time > "' . $now_date . '" AND (status="' . EnumStatus::VALID . '" OR status="' . EnumStatus::PASS . '") ',
       		'limit' => 6,
       		'order' => 'start_time DESC',
       	));
       	*/
       	//拍卖公告
        //帮助中心
       $com = new CommonIndex();
       $com->set_cate_code('auction');
       $articles = $com->get_article();
       $this->assign('articles_list', $articles['articles']);
       	
        $this->_config_seo(array(
            'title' => Lang::get('mall_index') . ' - ' . Conf::get('site_title'),
        ));
        $this->assign('goods_list', $arr_goods_list);
        $this->assign('o_auction', ($o_auction));
        $this->assign('p_auction', ($p_auction));
        //$this->assign('starting', array_values($starting_auction));
        $this->assign('articles', $articles);
       
        $this->assign('page_description', Conf::get('site_description'));
        $this->assign('page_keywords', Conf::get('site_keywords'));
        $this->display('index_auction.html');
    }
    
    function _get_hot_keywords()
    {
        $keywords = explode(',', conf::get('hot_search'));
        return $keywords;
    }
    
    /**
     * 处理店铺地址，返回第二个地区，如果不存在第二地区，返回第一个；
     * @param type $r_stores
     * @return type
     */
    function _stroe_region_name($arr_stores)
    {
         //获取第二个地区
        foreach($arr_stores as $key => $list_data)
        {
          $arr_region = explode('	', $list_data['region_name']);
          if (count($arr_region) >=2)
          {
             $arr_stores[$key]['region_name'] = $arr_region['1']; 
          }
          else
          {
               $arr_stores[$key]['region_name'] = $arr_region['0']; 
          }
        }
        return $arr_stores;
    }
}

?>
