<?php echo $this->fetch('header.html'); ?>
<?php echo $this->fetch('curlocal.html'); ?>
<style type="text/css">
.auction_selected {font-size:14px; font-weight: bold;}
.auction_selected a {color: #FF0000;}
</style>
<div class="content">
    <div class="left">
        <div class="module_sidebar">
            <h2><b>平台专场拍卖会列表</b></h2>
            <div class="wrap">
                <div class="wrap_child">
                    <div class="classify_list">
                        <ul>
                            <li <?php if ($_GET['type'] == 'O' && $_GET['query'] == ''): ?>class="auction_selected" <?php endif; ?>>
                            	<a href="<?php echo url('app=auction&act=auction_list&type=O'); ?>">全部</a>
                            </li>
                            <li <?php if ($_GET['type'] == 'O' && $_GET['query'] == 'process'): ?>class="auction_selected" <?php endif; ?>>
                            	<a href="<?php echo url('app=auction&act=auction_list&type=O&query=process'); ?>">进行中</a>
                            </li>
                            <li <?php if ($_GET['type'] == 'O' && $_GET['query'] == 'apply'): ?>class="auction_selected" <?php endif; ?>>
                            	<a href="<?php echo url('app=auction&act=auction_list&type=O&query=apply'); ?>">报名中</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <h2><b>个人拍卖会列表</b></h2>
            <div class="wrap">
                <div class="wrap_child">
                    <div class="side_textlist">
                        <ul>
                            <li <?php if ($_GET['type'] == 'P'): ?>class="auction_selected" <?php endif; ?>><a href="<?php echo url('app=auction&act=auction_list&type=P'); ?>">全部</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <h2><b>集市</b></h2>
            <div class="wrap">
                <div class="wrap_child">
                    <div class="side_textlist">
                        <ul>
                            <li <?php if ($_GET['type'] == 'M'): ?>class="auction_selected" <?php endif; ?>><a href="<?php echo url('app=auction&act=goods_list&id=' . $this->_var['market_id']. ''); ?>">全部商品</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="right">
        <div class="shop_text_list">
            <div class="ornament1"></div>
            <div class="ornament2"></div>
            <table>
                <tr>
                    <th class="align1" colspan="5">
                        <div class="table_title">
                        </div>
                        <div class="top_page">
                            <?php echo $this->fetch('page.top2.html'); ?>
                        </div>
                    </th>
                </tr>
                <?php $_from = $this->_var['auctions']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'auction');if (count($_from)):
    foreach ($_from AS $this->_var['auction']):
?>
                <tr>
                    <td><a href="<?php echo url('app=auction&act=goods_list&id=' . $this->_var['auction']['auction_id']. ''); ?>" class="lebioa"><?php echo htmlspecialchars($this->_var['auction']['name']); ?></a></td>
                    <td>总有<a href="<?php echo url('app=auction&act=goods_list&id=' . $this->_var['auction']['auction_id']. ''); ?>" style="padding: 0 2px;background: none;" class="width9"><?php echo $this->_var['auction']['goods_num']; ?></a>件商品
                    <td class="width9">总有<?php echo $this->_var['auction']['pay_num']; ?>出价</td>
                    <td class="width9"><?php echo $this->_var['auction']['create_time']; ?></td>
                    <td>
                    	<?php if ($this->_var['auction']['can_apply']): ?>
                    	<a href="<?php echo url('app=auction&act=apply&id=' . $this->_var['auction']['auction_id']. ''); ?>" style="background: none;color:blue; padding: 0 5px;">报名</a>
                    	<?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; else: ?>
                <tr>
                  <td align="center" colspan="5">没有符合条件的记录</td>
                </tr>
                <?php endif; unset($_from); ?><?php $this->pop_vars();; ?>
            </table>
        </div>

        <?php echo $this->fetch('page.bottom.html'); ?>
    </div>
</div>
<?php echo $this->fetch('footer.html'); ?>