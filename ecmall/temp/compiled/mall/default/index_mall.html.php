<?php echo $this->fetch('header.html'); ?>

<div class="main gg_980_80">
	<div class="fl menu">
		<div class="leftment">
			<ul><li><b>所有藏品 >></b></li>
                        <?php $_from = $this->_var['gcategorys']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'gcategory');if (count($_from)):
    foreach ($_from AS $this->_var['gcategory']):
?>
                        <li ><a href="<?php echo url('app=search&cate_id=' . $this->_var['gcategory']['id']. ''); ?>" class="hover"><?php echo htmlspecialchars($this->_var['gcategory']['value']); ?></a></li>    
                                <?php $_from = $this->_var['gcategory']['children']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'child');if (count($_from)):
    foreach ($_from AS $this->_var['child']):
?>
                                <!-- <a href="<?php echo url('app=search&cate_id=' . $this->_var['child']['id']. ''); ?>"><?php echo htmlspecialchars($this->_var['child']['value']); ?></a>
                                -->
                                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                                
                            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
			
			</ul>
		
		</div>
	
	</div>
	<div class="fr menuright">
		<div class="fl">
			<div class="menuright_banner fl">
			
			
			
			<div class="picb_1 picb_3">
                                <div class="picb_2 picb_4">
                                <script language="javascript" type="text/javascript">
                                //<![CDATA[
                                var _t1 = 0; //打开页面时等待图片载入的时间，单位为秒，可以设置为0
                                var _t2 = 5; //图片轮转的间隔时间
                                var _tnum = "<?php echo $this->_var['ppt_num']; ?>"; //焦点图个数
                                _tnum = _tnum > 0 ? _tnum : 4;
                                var _tn = 1;//当前焦点
                                var _tl =null;
                                _tt1 = setTimeout('change_img()',_t1*1000);
                                function change_img(){
                                setFocus(_tn);
                                _tt1 = setTimeout('change_img()',_t2*1000);
                                }
                                function setFocus(i){
                                 if (i > _tnum) {
                                    _tn = 1;
                                    i = 1;
                                }
                                _tl?document.getElementById('focusPic'+_tl).style.display='none':'';
                                document.getElementById('focusPic'+i).style.display='block';
                                $('#index_page').find('strong').parent().html($('#index_page').find('strong').html());
                                $('#index_page'+i).html('<strong>'+i+'</strong>');
                              
                                _tl=i;
                                _tn++;
                                
                                }
                                //]]>
                                </script>
                                
                                            <div >
                                                <?php $_from = $this->_var['ad_list_1']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'ad');$this->_foreach['foo'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['foo']['total'] > 0):
    foreach ($_from AS $this->_var['ad']):
        $this->_foreach['foo']['iteration']++;
?>
                                                    <span id="focusPic<?php echo $this->_foreach['foo']['iteration']; ?>" <?php if ($this->_foreach['foo']['iteration'] == 1): ?>style="display:blank;"<?php else: ?>style="display:none;"<?php endif; ?>>
                                                        <a href="<?php echo $this->_var['ad']['link']; ?>" title="<?php echo $this->_var['ad']['title']; ?>" class="i_photo_a"  target="_blank"><img src="<?php echo $this->_var['ad']['logo']; ?>" ></a>
                                                    </span>
                                                <?php endforeach; else: ?>
                                                        <span id="focusPic1">
                                                        <a href="#" class="i_photo_a" target="_blank">
                                                        <img src="<?php echo $this->res_base . "/" . 'images/01.jpg'; ?>"  /></a>
                                                        </span>
                                                        <span id="focusPic2" style="display:none;">
                                                            <a href="#" class="i_photo_a" target="_blank">
                                                            <img src="<?php echo $this->res_base . "/" . 'images/02.jpg'; ?>"  /></a>
                                                        </span>
                                                        <span id="focusPic3"style="display:none;">
                                                            <a href="#" class="i_photo_a" target="_blank">
                                                            <img src="<?php echo $this->res_base . "/" . 'images/03.jpg'; ?>"  /></a>
                                                        </span>
                                                        <span id="focusPic4" style="display:none;">
                                                            <a href="#" class="i_photo_a" target="_blank">
                                                            <img src="<?php echo $this->res_base . "/" . 'images/04.jpg'; ?>"  /></a>
                                                        </span>
                                                
                                                <?php endif; unset($_from); ?><?php $this->pop_vars();; ?>
                                                <div id="index_page">
                                                    <?php $_from = $this->_var['ad_list_1']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'ad');$this->_foreach['foo'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['foo']['total'] > 0):
    foreach ($_from AS $this->_var['ad']):
        $this->_foreach['foo']['iteration']++;
?>
                                                    <a href="javascript:setFocus(<?php echo $this->_foreach['foo']['iteration']; ?>);" id="index_page<?php echo $this->_foreach['foo']['iteration']; ?>"><strong><?php echo $this->_foreach['foo']['iteration']; ?></strong></a>
                                                    <?php endforeach; else: ?>
                                                        <a href="javascript:setFocus(1);" id="index_page1"><strong>1</strong></a>
                                                        <a href="javascript:setFocus(2);" id="index_page2">2</a>
                                                        <a href="javascript:setFocus(3);" id="index_page3">3</a>
                                                        <a href="javascript:setFocus(4);" id="index_page4">4</a>
                                                    <?php endif; unset($_from); ?><?php $this->pop_vars();; ?>
                                                </div>
                                            </div>
                                </div>
                            </div>
                        </div>
	
			
			<div class="fl menuright_1">
                             <?php $_from = $this->_var['ad_list_2']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'ad');$this->_foreach['foo'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['foo']['total'] > 0):
    foreach ($_from AS $this->_var['ad']):
        $this->_foreach['foo']['iteration']++;
?>
                                <?php if ($this->_foreach['foo']['iteration'] == 1): ?>
                                <div class="menu_190_170 fl"><a href="<?php echo $this->_var['ad']['link']; ?>" title="<?php echo $this->_var['ad']['title']; ?>"  target="_blank"><img width="190" height="170" src="<?php echo $this->_var['ad']['logo']; ?>" ></a></div>
                                <?php endif; ?>
                             <?php endforeach; else: ?>
                                <div class="menu_190_170 fl"><img src="<?php echo $this->res_base . "/" . 'images/gg_2.jpg'; ?>"></div>
                            <?php endif; unset($_from); ?><?php $this->pop_vars();; ?>
                             <?php $_from = $this->_var['ad_list_3']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'ad');$this->_foreach['foo'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['foo']['total'] > 0):
    foreach ($_from AS $this->_var['ad']):
        $this->_foreach['foo']['iteration']++;
?>
                                <?php if ($this->_foreach['foo']['iteration'] == 1): ?>
                                <div class="fl menu_190_80 padding_top"><a href="<?php echo $this->_var['ad']['link']; ?>" title="<?php echo $this->_var['ad']['title']; ?>"  target="_blank"><img width="190" height="80" src="<?php echo $this->_var['ad']['logo']; ?>" ></a></div>
                                <?php endif; ?>
                              <?php endforeach; else: ?>
                                <div class="fl menu_190_80 padding_top"><img src="<?php echo $this->res_base . "/" . 'images/gg_1.jpg'; ?>"></div>
                            <?php endif; unset($_from); ?><?php $this->pop_vars();; ?>
				
		 	</div>	
			<div class="fl menuright_2">
			<b>商城公告</b>
			<ul>
                        <?php $_from = $this->_var['notice_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'article');$this->_foreach['foo'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['foo']['total'] > 0):
    foreach ($_from AS $this->_var['article']):
        $this->_foreach['foo']['iteration']++;
?>
                        <li><a <?php if ($this->_var['article']['link']): ?>target="_blank"<?php endif; ?> href="<?php echo url('app=article&act=view&article_id=' . $this->_var['article']['article_id']. ''); ?>" ><?php echo sub_str(strip_tags($this->_var['article']['content']),100); ?></a></li>
                    <?php endforeach; else: ?>
                        <li>没有符合条件的记录</li>
                    <?php endif; unset($_from); ?><?php $this->pop_vars();; ?>
                        </ul>
			
			</div>
		
		</div>
		<div class="fl menuright_3">
			<ul>
                         <?php $_from = $this->_var['ad_list_4']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'ad');$this->_foreach['foo'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['foo']['total'] > 0):
    foreach ($_from AS $this->_var['ad']):
        $this->_foreach['foo']['iteration']++;
?>
                            <li <?php if ($this->_foreach['foo']['iteration'] % 3 == 0): ?> class="menupadding" <?php endif; ?>><a href="<?php echo $this->_var['ad']['link']; ?>" title="<?php echo $this->_var['ad']['title']; ?>"  target="_blank"><img width="250" height="80" src="<?php echo $this->_var['ad']['logo']; ?>" ></a></li>
                        <?php endforeach; else: ?>
                            <li><img src="<?php echo $this->res_base . "/" . 'images/250_80.jpg'; ?>" ></li>
                            <li><img src="<?php echo $this->res_base . "/" . 'images/250_80.jpg'; ?>" ></li>
                            <li class="menupadding"><img src="<?php echo $this->res_base . "/" . 'images/250_80.jpg'; ?>" ></li>
                            <li><img src="<?php echo $this->res_base . "/" . 'images/250_80.jpg'; ?>" ></li>
                            <li><img src="<?php echo $this->res_base . "/" . 'images/250_80.jpg'; ?>" ></li>
                            <li class="menupadding"><img src="<?php echo $this->res_base . "/" . 'images/250_80.jpg'; ?>" ></li>
                        <?php endif; unset($_from); ?><?php $this->pop_vars();; ?>
			</ul>
		
		
		</div>
	
	
	</div>


</div>

<div class="clear" ></div>

<div class="main padding_top">
	<div class="tjdp_1 fl "></div>
	<div class="tjdp_2 fl">
		<ul>
                    <?php $_from = $this->_var['r_stores']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'stores');$this->_foreach['foo'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['foo']['total'] > 0):
    foreach ($_from AS $this->_var['stores']):
        $this->_foreach['foo']['iteration']++;
?>
                    <li><span><a href="<?php echo url('app=store&id=' . $this->_var['stores']['store_id']. ''); ?>"><img src="<?php echo ($this->_var['stores']['store_logo'] == '') ? 'data/system/default_store_logo.gif' : $this->_var['stores']['store_logo']; ?>" width="100" height="100"></a></span><h2><a href="<?php echo url('app=store&id=' . $this->_var['stores']['store_id']. ''); ?>"><?php echo $this->_var['stores']['store_order']; ?>、<?php echo $this->_var['stores']['store_name']; ?></a></h2><h3><?php echo $this->_var['stores']['region_name']; ?> <?php echo $this->_var['stores']['user_name']; ?></h3></li>
                    <?php endforeach; else: ?>
                     <li><span><img src="<?php echo $this->res_base . "/" . 'images/100_50.jpg'; ?>" ></span><h2>没有符合条件的记录</h2></li>
                    <?php endif; unset($_from); ?><?php $this->pop_vars();; ?>
                </ul>
	
	</div>

</div>




 <?php $_from = $this->_var['ad_list_5']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'ad');$this->_foreach['foo'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['foo']['total'] > 0):
    foreach ($_from AS $this->_var['ad']):
        $this->_foreach['foo']['iteration']++;
?>
    <div class="main gg_980_80"><a href="<?php echo $this->_var['ad']['link']; ?>" title="<?php echo $this->_var['ad']['title']; ?>" class="gg_980_80"  target="_blank"><img src="<?php echo $this->_var['ad']['logo']; ?>" ></a></div>
 <?php endforeach; else: ?>
    <div class="main gg_980_80"><img src="<?php echo $this->res_base . "/" . 'images/adv_1.jpg'; ?>" ></div>
<?php endif; unset($_from); ?><?php $this->pop_vars();; ?>


<div class="clear" ></div>


<div class="main ">
	<div class="tjdp_1 fl tjdp_3"></div>
	<div class="tjdp_2 fl tjdp_4">
		<ul>
                     <?php $_from = $this->_var['c_stores']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'stores');$this->_foreach['foo'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['foo']['total'] > 0):
    foreach ($_from AS $this->_var['stores']):
        $this->_foreach['foo']['iteration']++;
?>
                    <li><span><a href="<?php echo url('app=store&id=' . $this->_var['stores']['store_id']. ''); ?>"><img src="<?php echo ($this->_var['stores']['store_logo'] == '') ? 'data/system/default_store_logo.gif' : $this->_var['stores']['store_logo']; ?>" width="100" height="50"></a></span><h2><a href="<?php echo url('app=store&id=' . $this->_var['stores']['store_id']. ''); ?>"><?php echo $this->_var['stores']['store_order']; ?>、<?php echo $this->_var['stores']['store_name']; ?></a></h2><h3><?php echo $this->_var['stores']['region_name']; ?> <?php echo $this->_var['stores']['user_name']; ?></h3></li>
                    <?php endforeach; else: ?>
                     <li><span><img src="<?php echo $this->res_base . "/" . 'images/100_50.jpg'; ?>" ></span><h2>没有符合条件的记录</h2></li>
                    <?php endif; unset($_from); ?><?php $this->pop_vars();; ?>
		</ul>
	
	</div>

</div>



<?php echo $this->fetch('footer.html'); ?>