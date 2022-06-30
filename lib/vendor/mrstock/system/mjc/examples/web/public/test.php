<?php
header('Content-type:text/html;charset=utf-8');
?>
登录
<form action="/index.php?c=user&a=login" method="post" target="_blank">
用户名：<input name="username"/><br/>
密码：<input name="password"/><br/>
<input name="提交" type="submit" /><br/>
</form>

验证码
<form action="/index.php?c=verification&a=send" method="post" target="_blank">
手机号：<input name="mobile" /><br/>
type：<input name="type" /> （1：注册；2：找回密码；3：第三方验证）<br/>
<input name="提交" type="submit" /><br/>
</form>

注册
<form action="/index.php?c=user&a=reg" method="post" target="_blank">
手机号<input name="mobile"/><br/>
验证码：<input name="code"/><br/>
密码：<input name="password"/><br/>
确认密码：<input name="password_confirm"/><br/>
邀请码：<input name="inviter_code"/><br/>
<input name="提交" type="submit" /><br/>
</form>

找回密码
<form action="/index.php?c=password&a=find" method="post" target="_blank">
用户名：<input name="mobile"/><br/>
验证码：<input name="code"/><br/>
密码：<input name="password"/><br/>
确认密码：<input name="password_confirm"/><br/>
<input name="提交" type="submit" /><br/>
</form>

退出登录
<form action="/index.php?c=member&a=logout" method="post" target="_blank">
用户ID：<input name="member_id"/><br/>
key：<input name="key"/><br/>
<input name="提交" type="submit" /><br/>
</form>