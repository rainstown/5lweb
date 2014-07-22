<?php echo $this->fetch('member.header.html'); ?>
<script src="<?php echo $this->lib_base . "/" . 'jsAddress.js'; ?>" charset="utf-8"></script>
<script type="text/javascript">
$(function(){
    $('#funds_time_from').datepicker({dateFormat: 'yy-mm-dd'});
    $('#funds_time_to').datepicker({dateFormat: 'yy-mm-dd'});

    $('#account_form').validate({
        errorPlacement: function(error, element){
            $(element).next('.field_notice').hide();
            $(element).after(error);
        },
        success       : function(label){
            label.addClass('validate_right').text('OK!');
        },
        rules : {
            q1 : {
                required   : true
            },
            bank1_name : {
                required   : true
            },
            bank1_user : {
                required   : true
            },     
            bank1_account : {
                required   : true
            },   
            check_code : {
                required   : true
            }
        },
        messages : {
            q1  : {
                required   : '开户行必填',
            },
            bank1_name : {
                required   : '开户行必填',
            },
            bank1_user  : {
                required   : '账户名称',
            },
            bank1_account  : {
                required   : '银行账户必填',
            },
            check_code  : {
               required   : '手机验证码错误'
            }
        }
    });
});
</script>
<div class="content">
    <?php echo $this->fetch('member.menu.html'); ?>
    <div id="right"><?php echo $this->fetch('member.submenu.html'); ?>
        <div class="wrap">
            <div class="public_index table">
                <?php if ($this->_var['auth']['auth_id']): ?>
                 <form method="post" id="account_form">
                <table >
                    <tr class="line tr_bgcolor">
                        <th class="width2" COLSPAN ='4'>银行一信息</th>
                    </tr>
                    <tr class="line tr_bgcolor">
                        <th class="width2">开户行</th>
                        <td colspan='3'>
                            <select name='s1'  id="cmbProvince1"></select><select name='c1'  id="cmbCity1"></select><select name='q1'  id="cmbArea1"></select>
                                    
                            <input type="text" class="text width5" name="bank1_name" value="<?php echo htmlspecialchars($this->_var['auth']['bank1_name']); ?>"/></td>
                    </tr>
                    <tr class="line tr_bgcolor">
                         <th class="width2">账户名称</th>
                        <td><input type="text" class="text width5" name="bank1_user" value="<?php echo htmlspecialchars($this->_var['auth']['bank1_user']); ?>"/></td>
                   
                        <th class="width2">银行账户</th>
                        <td><input type="text" class="text width5" name="bank1_account" value="<?php echo htmlspecialchars($this->_var['auth']['bank1_account']); ?>"/></td>
                        
                    </tr>
                    <tr class="line tr_bgcolor">
                        <th class="width2" COLSPAN ='4'>银行二信息</th>
                    </tr>
                     <tr class="line tr_bgcolor">
                        <th class="width2">开户行</th>
                        <td colspan='3'>
                            <select name='s2'  id="cmbProvince2"></select><select name='c2'  id="cmbCity2"></select><select name='q2'  id="cmbArea2"></select>
                                    
                            <input type="text" class="text width5" name="bank2_name" value="<?php echo htmlspecialchars($this->_var['auth']['bank2_name']); ?>"/></td>
                    </tr>
                   
                    <tr class="line tr_bgcolor">
                        <th class="width2">账户名称</th>
                        <td><input type="text" class="text width5" name="bank2_user" value="<?php echo htmlspecialchars($this->_var['auth']['bank2_user']); ?>"/></td>
                        <th class="width2">银行账户</th>
                        <td><input type="text" class="text width5" name="bank2_account" value="<?php echo htmlspecialchars($this->_var['auth']['bank2_account']); ?>"/></td>
                        
                    </tr>
                    <tr class="line tr_bgcolor">
                        <th class="width2">手机验证码</th>
                        <td colspan ="3" ><input type="text" class="text width2" name="check_code" value=""/> <span><input class="btn1" type="button" ectype="dialog" uri="index.php?app=checkmobcode&act=get_check_code&type=2&ajax" dialog_title="获取验证码" dialog_width="400" value="获取验证码"/></span></td>
                    </tr>
                     <tr class="line">
                         <td colspan ="4" class="scarch_order" style="text-align: center"><input type="submit" class="btn" value="提交" /></td>
                    </tr>
                </table>
                 </form>
                  <?php else: ?>
                 <ul>
                            <li>
                                <p>您未申请会员认证，请先申请<a href="index.php?app=member_auth">认证</a></p>
                  </li>
                 <?php endif; ?> 
                
            </div>
        </div>
    <div class="wrap_bottom"></div>
    </div>
</div>
</div>
<div class="clear"></div>
</div>
<script type="text/javascript">
 addressInit('cmbProvince1', 'cmbCity1', 'cmbArea1', '<?php echo $this->_var['auth']['s1']; ?>', '<?php echo $this->_var['auth']['c1']; ?>', '<?php echo $this->_var['auth']['q1']; ?>');
 addressInit('cmbProvince2', 'cmbCity2', 'cmbArea2', '<?php echo $this->_var['auth']['s2']; ?>', '<?php echo $this->_var['auth']['c2']; ?>', '<?php echo $this->_var['auth']['q2']; ?>');
</script>
<?php echo $this->fetch('footer.html'); ?>
