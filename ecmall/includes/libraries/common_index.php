<?php
/**
 * 各首页通用模块数据
 */
class CommonIndex extends Object
{
    private $_cate_ids;
    public function set_cate_ids($ids)
    {
        $this->_cate_ids = $ids;
    }
    public function set_cate_code($code)
    {
        $acategory_mod = &m('acategory');
        $this->_cate_ids = $acategory_mod->get_ACC($code);
    }
    
    public function get_article($type='')
    {
        $article_mod = &m('article');
        $conditions = '';
        $per = '7';
        switch ($type)
        {
            case 'new' : $sort_order = 'add_time DESC,sort_order ASC';
                $per=5;
            break;
            
            case 'notice_mall_index':
                 $sort_order = 'sort_order ASC, add_time DESC';
                 $page = array('limit' => "0, 1", 'curr_page' => 1, 'pageper' => $page_per);   //获取分页信息
             break;
            default : $sort_order = 'sort_order ASC,add_time DESC';
            $per=7;
             $page = array('limit' => "0, 7", 'curr_page' => 1, 'pageper' => $page_per);   //获取分页信息
            break;
        }
        
        !empty($this->_cate_ids)&& $conditions = ' AND cate_id ' . db_create_in($this->_cate_ids);

        $articles = $article_mod->find(array(
            'conditions'  => 'if_show=1 AND store_id=0 AND code = ""' . $conditions,
            'limit'   => $page['limit'],
            'order'   => $sort_order,
            'count'   => true   //允许统计
        )); //找出所有符合条件的文章
        $page['item_count'] = $article_mod->getCount();
        foreach ($articles as $key => $article)
        {
            $articles[$key]['target'] = $article[link] ? '_blank' : '_self';
        }
        return array('page'=>$page, 'articles'=>$articles);
    }
    
    /**
     * 获取广告内容
     * @param type $type
     * @return type
     */
    public function getAdList($type)
    {
         $ad_mod = &m('ad'); 
         $sort_order = ' sort_order asc ';
         $ads = $ad_mod->find(array(
            'conditions'  => 'type = "' . $type .'"',
            'limit'   => $page['limit'],
            'order'   => $sort_order
        ));
        return $ads;
    }
    
    public function getIndexAd()
    {
        $ad_list_1 = $this->getAdList(1000);
        $ad_list_2 = $this->getAdList(1010);
        $ad_list_3 = $this->getAdList(1020);
        $ad_list_4 =  $this->getAdList(1030);
        $ad_list_5 =  $this->getAdList(1040);
        return array('ad_list_1'=>$ad_list_1 , 'ad_list_2'=>$ad_list_2 ,'ad_list_3'=>$ad_list_3 ,'ad_list_4'=>$ad_list_4,'ad_list_5'=>$ad_list_5  );
    }
    
    public function getIndexMallAd()
    {
        $ad_list_1 = $this->getAdList(3000);
        $ad_list_2 = $this->getAdList(3010);
        $ad_list_3 = $this->getAdList(3020);
        $ad_list_4 =  $this->getAdList(3030);
        $ad_list_5 =  $this->getAdList(3040);
        return array('ad_list_1'=>$ad_list_1 , 'ad_list_2'=>$ad_list_2 ,'ad_list_3'=>$ad_list_3 ,'ad_list_4'=>$ad_list_4, 'ad_list_5'=>$ad_list_5);
    }
     public function getIndexAuctionAd()
    {
        $ad_list_1 = $this->getAdList(4000);
        $ad_list_2 = $this->getAdList(4010);
        $ad_list_3 = $this->getAdList(4020);
        $ad_list_4 = $this->getAdList(4030);
        return array('ad_list_1'=>$ad_list_1 , 'ad_list_2'=>$ad_list_2 ,'ad_list_3'=>$ad_list_3,'ad_list_4'=>$ad_list_4  );
    }


    public function get_type_select()
    {
        $types = array('1000' => '首页-顶部', '1010' => '首页-幻灯片',  '1020' => '首页-论坛区域', '1030'=>'首页-拍卖区域','1040'=>'首页-底部',
                        '3000' => '商城-幻灯片', '3010' => '商城-幻灯片右上 (190 * 170)',  '3020' => '商城-幻灯片右下 (190 * 80)',  '3030' => '商城-幻灯片底部(250 * 80)', '3040' => '商城-推荐店铺底部',
                        '4000' => '拍卖-幻灯片', '4010' => '拍卖-幻灯片右上 (190 * 170)',  '4020' => '拍卖-幻灯片右下 (190 * 80)',  '4030' => '拍卖-专场头部',
                        );
        return $types;
    }
    
    /**
     * 获取用户的可用金额
     * @param type $user_id
     */
    public function getUserBankCanUseMoney($user_id)
    {
         $model = & m('bank');
         $bank_info = $model->get($user_id);
         if (empty($bank_info))
         {
             $bank_info['money'] = 0;
             
         }
         else
         {
             //获取有效的拍卖纪录保证金;
             $auction_records = & m('auction_records');
             $auction_keep_money = $auction_records->getNotPayMoney($user_id);
             if ($auction_keep_money > 0)
             {
                $bank_info['money'] = $bank_info['money'] - $auction_keep_money;
                $bank_info['caution_money'] =  $bank_info['caution_money'] + $auction_keep_money;
             }
         }
         return $bank_info;
    }
}
?>