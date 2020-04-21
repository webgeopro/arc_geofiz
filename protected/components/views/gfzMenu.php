<?if (Yii::app()->user->isGuest): // Гостевой вход ?>
<script language="javascript">
    var timeout         = 500;
    var closetimer		= 0;
    var ddmenuitem;

    function jsddm_open()
    {	
        jsddm_canceltimer();
	    jsddm_close();
	    //ddmenuitem = $(this).find('div').eq(0).css('visibility', 'visible');
        //var divName = $(this).attr('name');
        ddmenuitem = $("#sub_"+$(this).attr('name'));//alert(divName);
        $(ddmenuitem).css('display', 'block');
    }

    function jsddm_close()
    {	
        if(ddmenuitem) ddmenuitem.css('display', 'none');
    }

    function jsddm_timer()
    {	
        closetimer = window.setTimeout(jsddm_close, timeout);
    }

    function jsddm_canceltimer()
    {	
        if (closetimer) {	
	       window.clearTimeout(closetimer);
		   closetimer = null;
        }
    }

    $(document).ready(function()
    {	
        $('#tabMenu td ').bind('mouseover', jsddm_open);
	    $('#tabMenu td ').bind('mouseout',  jsddm_timer);
        $('#tabMenu td ').bind('mouseover', jsddm_open);
	    $('#tabMenu td ').bind('mouseout',  jsddm_timer);
    });

document.onclick = jsddm_close;
</script>
<table id="tabMenu"><tr><td><a href="/" id="aMain">&ndash; Главная</a></td>
<?foreach ($arMenu as $m):?>
    <td  name="li<?=$m['id']?>">
        <a href="/<?=$m['url']?>" id="a<?=$m['id']?>" name="li<?=$m['id']?>">&ndash; <?=$m['label']?></a>
    <?if ($m['hasChildren']):?>
    <ul id="sub_li<?=$m['id']?>">
        <?foreach ($m['children'] as $subm):?>
            <li name="li<?=$m['id']?>"><a href="/<?=$subm['url']?>" name="li<?=$m['id']?>">&nbsp;<?=$subm['label']?></a></li>
        <?endforeach;?>
    </ul>
    </td>
    <?endif;
endforeach;?>
<td><a href="/vacancies" id="aVacancies">&ndash; Вакансии</a></td>
<td><a href="/sitemap" id="aSiteMap">&ndash; Карта сайта</a></td>
</tr></table>
<?else: // Вход администратора ?>
<script language="javascript">
    var errors = "";
    $(document).ready(function()
    {	
        $('.aMenuNew').click(function(){
            var parentID = this.name;
            $('#inpItemID').val('0');   // ID пункта меню
            $('#inpMenuName').val('');  // Имя пункта меню (english)
            $('#inpMenuLabel').val(''); // Метка страницы (рус.)
            $('#inpParentID').val(parentID);// Родитель узла
            $('#divMenuEdit').show(100);
            return false;
        });
        /*$('.aMenuDelete').click(function(){
            alert('Click Delete'+this.name);
            return false;
        });*/
        /**
         * Заполняем поля формы редактирования пункта меню и отображаем её
         */
        $('.aMenuEdit').click(function(){
            var itemID = this.name;
            $.post('/site/getItem', {       // Получаем Имя и метку сущ. пункта
                'itemID'   : itemID,
                'dataType' : 'menuItem'
            }, function(data){
                if (data['result'] == 'success') { //Имя и метку и ID пункта меню присваиваем полям формы
                    $('#inpItemID').val(itemID);       // ID пункта меню
                    $('#inpMenuName').val(data['name']);  // Имя пункта меню (english)
                    $('#inpMenuLabel').val(data['label']);// Метка страницы (рус.)
                    $('#inpParentID').val(data['parent_id']);// Метка страницы (рус.)
                }
            }, 'json');
            $('#divMenuEdit').show(100); // Отображаем форму редактирования пункта меню
            return false;
        });
        $('#btnMenuCancel').click(function(){
            $('#divMenuEdit').hide(100);
            return false;
        });
        /**
         * Сохранение измен. или новых значений пункта меню
         */
        $('#btnMenuSave').click(function(){
            if (checkFields()) {
                $('#formMenuSave').submit();
                //$('#divMenuEdit').hide(100);
            } else {
                alert(errors);
            }
            return false;
        });
    });
    function checkFields()
    {
        errors = "";
        if ("" == $("#inpMenuName").val()) {
            errors += " Поле {Имя} пустое";
        } 
        if ("" == $("#inpMenulabel").val()) {
            errors += "\n Поле {Метка} пустое";
        }
        if ("" != errors) return false
        else return true
    }
</script>

<div id="divMenuEdit"><form action="/site/save" method="post" id="formMenuSave">
    <span title="">Метка:</span>
    <input type="text" id="inpMenuName"  name="inpMenuName" /><br class="clear" />
    <span title="">Название:</span>
    <input type="text" id="inpMenuLabel" name="inpMenuLabel" /><br class="clear" />

    <input type="hidden" id="inpItemID" name="inpItemID" value="" />
    <input type="hidden" id="inpParentID" name="inpParentID" value="0" />
    <input type="hidden" id="inpDataType" name="inpDataType" value="menu" />

    <input type="button" value="Сохранить" style="float:left;" id="btnMenuSave" />
    <input type="button" value="Отменить"  style="float:right;" id="btnMenuCancel" />
</form></div>
    
<table id="tabMenu"><tr>
    <td><a href="/">Главная</a></td>
    <?foreach ($arMenu as $m):?>
    <td>
        <a href="/<?=$m['url']?>" id="a<?=$m['id']?>" name="a<?=$m['id']?>"><?=$m['label']?></a>
        <a href="" name="<?=$m['id']?>" class="aMenuEdit" title="редактировать"><img src="/images/add.gif" /></a>
        <a href="/site/delItem?itemID=<?=$m['id']?>" class="aMenuDelete" title="удалить"><img src="/images/close.gif" /></a>
        <ul id="sub_a<?=$m['id']?>" style="display:block;position:relative;width: inherit;">
            <?if ($m['hasChildren']): foreach ($m['children'] as $subm):?>
            <li>
                <a href="/<?=$subm['url']?>" name="a<?=$m['id']?>"><?=$subm['label']?></a>
                <a href="" name="<?=$subm['id']?>" class="aMenuEdit" title="редактировать"><img src="/images/add.gif" /></a>
                <a href="/site/delItem?itemID=<?=$subm['id']?>" name="<?=$subm['id']?>" class="aMenuDelete"title="удалить"><img src="/images/close.gif" /></a>
            </li>
            <?endforeach;endif;?>
            <li><a href="" class="aMenuNew" name="<?=$m['id']?>">+ Добавить</a></li>
        </ul>
    </td>
    <?endforeach;?>
<td><a href="/sitemap">Карта сайта</a></td>
<td><a href="" class="aMenuNew" name="0">+ Добавить</a></td>
<td><a href="/site/logout">Выход</a></td>
</tr></table>
<?endif;?>