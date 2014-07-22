<?php
$_CACHE['apps'] = array (
  1 => 
  array (
    'appid' => '1',
    'type' => 'ECMALL',
    'name' => 'ecmall',
    'url' => 'http://www.ecmall.tpl',
    'authkey' => 'D749q9keWdpdseXby437R5y4n1YdAf5fV1D0D3O1K6B3z9ac9fM0vaW9P0Serf00',
    'ip' => '',
    'viewprourl' => '',
    'apifilename' => 'uc.php',
    'charset' => 'utf-8',
    'dbcharset' => 'utf-8',
    'synlogin' => '1',
    'recvnote' => '1',
    'extra' => '',
    'tagtemplates' => '<?xml version="1.0" encoding="ISO-8859-1"?>
<root>
	<item id="template"><![CDATA[<dl><dt>{goods_name}</dt><dd><a href="{url}"><img src="{image}"></a></dd><dd>{goods_price}</dd></dl>]]></item>
	<item id="fields">
		<item id="goods_name"><![CDATA[商品名称]]></item>
		<item id="uid"><![CDATA[用户ID]]></item>
		<item id="username"><![CDATA[用户名]]></item>
		<item id="dateline"><![CDATA[日期]]></item>
		<item id="url"><![CDATA[URL地址]]></item>
		<item id="image"><![CDATA[图片]]></item>
		<item id="goods_price"><![CDATA[商品价格]]></item>
	</item>
</root>',
    'allowips' => '',
  ),
  2 => 
  array (
    'appid' => '2',
    'type' => 'DISCUZX',
    'name' => 'Discuz!',
    'url' => 'http://www.ecmall.tpl/dz',
    'authkey' => 'rdZcBagb7f1fzeC3X41ey2155fS6Xb34I1R17e7fbao2c7E7O9w1Yc89Fd7cAbL6',
    'ip' => '',
    'viewprourl' => '',
    'apifilename' => 'uc.php',
    'charset' => 'utf-8',
    'dbcharset' => 'utf8',
    'synlogin' => '1',
    'recvnote' => '1',
    'extra' => 
    array (
      'apppath' => '',
      'extraurl' => 
      array (
      ),
    ),
    'tagtemplates' => '<?xml version="1.0" encoding="ISO-8859-1"?>
<root>
	<item id="template"><![CDATA[<a href="{url}" target="_blank">{subject}</a>]]></item>
	<item id="fields">
		<item id="subject"><![CDATA[标题]]></item>
		<item id="uid"><![CDATA[用户 ID]]></item>
		<item id="username"><![CDATA[发帖者]]></item>
		<item id="dateline"><![CDATA[日期]]></item>
		<item id="url"><![CDATA[主题地址]]></item>
	</item>
</root>',
    'allowips' => '',
  ),
);

?>