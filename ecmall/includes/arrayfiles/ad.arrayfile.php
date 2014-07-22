<?php
class AdArrayfile extends BaseArrayfile
{

    function __construct()
    {
        $this->AdArrayfile();
    }

    function AdArrayfile()
    {
        $this->_filename = ROOT_PATH . '/data/share.inc.php';
    }

    function get_default()
    {
        return array (
          1 => array (
            'title' => '首页顶部广告',
            'link' => 'http://cang.baidu.com/do/add?it={$title}++++++&iu={$link}&fr=ien#nw=1',
            'type' => 'index',
            'size' => '980 * 80',
            'sort_order' => 255,
            'logo' => 'data/system/baidushoucang.gif',
          ),
          2 => array (
            'title' => '首页轮换广告',
            'link' => 'http://share.renren.com/share/buttonshare.do?link={$link}&title={$title}',
            'type' => 'index',
            'size' => '570 * 190',
            'sort_order' => 255,
            'logo' => 'data/system/renren.gif',
          ),
          3 => array (
            'title' => '首页中间打',
            'link' => 'http://shuqian.qq.com/post?from=3&title={$title}++++++&uri={$link}&jumpback=2&noui=1',
            'type' => 'collect',
            'sort_order' => 255,
            'logo' => 'data/system/qqshuqian.gif',
          ),
          4 => array (
            'title' => Lang::get('kaixinwang'),
            'link' => 'http://www.kaixin001.com/repaste/share.php?rtitle={$title}&rurl={$link}',
            'type' => 'share',
            'sort_order' => 255,
            'logo' => 'data/system/kaixin001.gif',
          ),
        );
    }

    function drop($share_id)
    {
        $share = $this->getOne($share_id);
        if ($share['logo'] && strpos($share['logo'], 'data/system/') === false)
        {
            file_exists(ROOT_PATH . '/' . $share['logo']) && @unlink(ROOT_PATH . '/' . $share['logo']);
        }
        parent::drop($share_id);
    }

    function getAll()
    {
        $data = array();
        if (!file_exists($this->_filename))
        {
            $data = $this->get_default();
        }
        else
        {
            $data = $this->_loadfromfile();
        }
        return $data;
    }

}
?>