<?php echo $this->fetch('member.header.html'); ?>
<style>
.borline td {padding:10px 0px;}
.ware_list th {text-align:left;}
.bgwhite {background: #FFFFFF;}
</style>
<div class="content">
    <?php echo $this->fetch('member.menu.html'); ?>
    <div id="right">
        <?php echo $this->fetch('member.submenu.html'); ?>
        <div class="eject_con bgwhite">
            <div class="add">
                <form method="post" id="account_form">
                    <ul>
                        <li><h3>账目管理:</h3>
                            <h3><?php echo price_format($this->_var['bank_info']['money']); ?></h3>
                        </li>
                        <li><h3>保证金:</h3>
                            <h3><?php echo price_format($this->_var['bank_info']['caution_money']); ?></h3>
                        </li>
                        <li><h3><a href="index.php?app=account&act=apply">修改支付密码</a></h3>
                        </li>
                    </ul>
                    
                </form>
            </div>
        </div>
    </div>
    <div class="clear"></div>
</div>
<?php echo $this->fetch('footer.html'); ?>
