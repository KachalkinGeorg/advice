[textarea]
<form name="form" method="post" action="">
{bbcodes}
<textarea name="content" id="content" style="width: 95%;" rows="8"></textarea><br/>{author}<br/>
[captcha]
Проверочный код:<br/>
<input type="text" name="vcode" maxlength="5" size="30" /> <img src="{admin_url}/captcha.php" />
<br/>
[/captcha]
<input type="submit" name="add_advice" class="btn" value="Отправить"/>
<input type="hidden" name="ip" value="{ip}"/>
</form>
[/textarea]