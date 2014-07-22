<?php
/**
 *  @Created By ECMall PhpCacheServer
 *  @Time:2013-12-15 23:49:17
 */

if(filemtime(__FILE__) + 86400 < time())return false;

return array (
  1 => 
  array (
    'appid' => '1',
    'type' => 'ECMALL',
    'name' => 'ecmall',
    'url' => 'http://www.ecmall.tpl',
    'tagtemplates' => 
    array (
      'template' => '<dl><dt>{goods_name}</dt><dd><a href="{url}"><img src="{image}"></a></dd><dd>{goods_price}</dd></dl>',
      'fields' => 
      array (
        'goods_name' => '商品名称',
        'uid' => '用户ID',
        'username' => '用户名',
        'dateline' => '日期',
        'url' => 'URL地址',
        'image' => '图片',
        'goods_price' => '商品价格',
      ),
    ),
    'viewprourl' => '',
    'synlogin' => '1',
  ),
  2 => 
  array (
    'appid' => '2',
    'type' => 'DISCUZX',
    'name' => 'Discuz!',
    'url' => 'http://www.ecmall.tpl/dz',
    'tagtemplates' => 
    array (
      'template' => '<a href="{url}" target="_blank">{subject}</a>',
      'fields' => 
      array (
        'subject' => '标题',
        'uid' => '用户 ID',
        'username' => '发帖者',
        'dateline' => '日期',
        'url' => '主题地址',
      ),
    ),
    'viewprourl' => '',
    'synlogin' => '1',
  ),
);

?>