<form method="post" action="">
	<div class="panel-body" style="font-family: Franklin Gothic Medium;text-transform: uppercase;color: #9f9f9f;">Настройки плагина</div>
	<table class="table table-striped">
      <tr>
        <td class="col-xs-6 col-sm-6 col-md-7">
		  <h6 class="media-heading text-semibold">Выберите каталог из которого плагин будет брать шаблоны для отображения</h6>
		  <span class="text-muted text-size-small hidden-xs"><b>Шаблон сайта</b> - плагин будет пытаться взять шаблоны из общего шаблона сайта; в случае недоступности - шаблоны будут взяты из собственного каталога плагина<br /><b>Плагин</b> - шаблоны будут браться из собственного каталога плагина</span>
		</td>
        <td class="col-xs-6 col-sm-6 col-md-5">
		  {{ localsource }}
        </td>
      </tr>
      <tr>
        <td class="col-xs-6 col-sm-6 col-md-7">
		  <h6 class="media-heading text-semibold">Отображать BB-коды?</h6>
		  <span class="text-muted text-size-small hidden-xs">Разрешить использовать BB-коды при написании сообщений.</span>
		</td>
        <td class="col-xs-6 col-sm-6 col-md-5">
			<select name="ubbcodes">{{ ubbcodes }}</select>
        </td>
      </tr>
      <tr>
        <td class="col-xs-6 col-sm-6 col-md-7">
		  <h6 class="media-heading text-semibold">Минимальное количество символов:</h6>
		  <span class="text-muted text-size-small hidden-xs">Укажите минимальное количество символов, которое должно присутствовать в отзыве для того чтобы отзыв мог быть добавлен в новости.</span>
		</td>
        <td class="col-xs-6 col-sm-6 col-md-5">
			<input name="minlength" type="text" size="4" value="{{ minlength }}" />
        </td>
      </tr>
      <tr>
        <td class="col-xs-6 col-sm-6 col-md-7">
		  <h6 class="media-heading text-semibold">Максимальное количество символов:</h6>
		  <span class="text-muted text-size-small hidden-xs">Укажите максимальное количество символов, которое может использовать пользователь при написании отзыва в новости</span>
		</td>
        <td class="col-xs-6 col-sm-6 col-md-5">
			<input name="maxlength" type="text" size="4" value="{{ maxlength }}" />
        </td>
      </tr>
      <tr>
        <td class="col-xs-6 col-sm-6 col-md-7">
		  <h6 class="media-heading text-semibold">Разрешить оставлять отзывы гостям?</h6>
		  <span class="text-muted text-size-small hidden-xs">Включение или отключение отзывов для всех новостей<br>Да - гости смогут оставлять свое мнение<br>Нет - гости не могут оставлять отзыв</span>
		</td>
        <td class="col-xs-6 col-sm-6 col-md-5">
			<select name="guests">{{ guests }}</select>
        </td>
      </tr>
      <tr>
        <td class="col-xs-6 col-sm-6 col-md-7">
		  <h6 class="media-heading text-semibold">Защита от ботов</h6>
		  <span class="text-muted text-size-small hidden-xs">Отображать CAPTCHA для гостей<br>Да - CAPTCHA включена<br>Нет - защита отключена</span>
		</td>
        <td class="col-xs-6 col-sm-6 col-md-5">
			<select name="ecaptcha">{{ ecaptcha }}</select>
        </td>
      </tr>
      <tr>
        <td class="col-xs-6 col-sm-6 col-md-7">
		  <h6 class="media-heading text-semibold">Количество отзывов на страницу</h6>
		  <span class="text-muted text-size-small hidden-xs">Укажите сколько отзывов выводить на одну страницу внутри новости.</span>
		</td>
        <td class="col-xs-6 col-sm-6 col-md-5">
			<input name="perpage" type="text" size="4" value="{{ perpage }}" />
        </td>
      </tr>
      <tr>
        <td class="col-xs-6 col-sm-6 col-md-7">
		  <h6 class="media-heading text-semibold">Порядок сортировки отзывов</h6>
		  <span class="text-muted text-size-small hidden-xs">Выберите порядок сортировки отзывов</span>
		</td>
        <td class="col-xs-6 col-sm-6 col-md-5">
			<select name="order">{{ order }}</select>
        </td>
      </tr>
	</table>
	<div class="panel-footer" align="center">
		<button type="submit" name="submit" class="btn btn-outline-primary">Сохранить</button>
	</div>
</form>