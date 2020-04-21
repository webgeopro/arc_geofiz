<script language="javascript">
    $("document").ready(function () {
        $("td#right-col a").slimbox({'counterText':'Изображение {x} из {y}'});
    });
</script>
<div id="content">
<div id="logo-top"></div>
<div id="mainmenu">
    <?$this->widget('gfzMenu', array('pageID'=>$page['id']))?>
    <?$this->widget('CTreeView', array('data' => Pages::model()->findAll()));?>
</div>
<div id="find">
    <?/*<input type="text" class="find" />
    <input type="button" value="Найти" />
    <input type="button" value="Карта сайта" />*/?>
    <div class="yandexform" style="width:500px; float:right; padding:0; margin:0;" 
        onclick="return {
            'bg': '#ffffff', 'language': 'ru', 'encoding': 'utf-8', 'suggest': false, 'tld': 'ru', 
            'site_suggest': false, 'webopt': false, 'fontsize': 14, 'arrow': false, 'fg': '#000000', 
            'logo': 'rb', 'websearch': false, 'type': 2}">
        
        <form action="http://yandex.ru/sitesearch" method="get" target="">
            <input type="hidden" name="searchid" value="1827261"/>
            <input name="text"/>
            <input type="submit" value="Найти"/>
        </form>
    </div>
    <script type="text/javascript" src="http://site.yandex.net/load/form/1/form.js" charset="utf-8"></script>
</div>
<div id="first-word">
    <div id="wordPhoto"></div>
    <div id="wordText">
        <br />
        <span>Предприятие ООО Востокгеофизика</span> - самостоятельная, молодая, динамично<br /> 
        развивающаяся компания, основанная в 2007 году, в настоящее время уже занимает одну из<br /> 
        лидирующих позиций в Красноярском крае, <br />
        Иркутской области в сфере предоставления услуг в области геофизических исследований скважин<br /> 
        в процессе бурения скважин, при контроле за разработкой нефтегазовых месторождений ПВР, ГТИ, ВСП<br /> 
        и сейсморазведки 2D,3D.
    </div>
</div>
<div id="main-line"></div>
<table id="table-content" cellpadding="0" cellspacing="0">
    <tr>
        <td>
            <?if (Yii::app()->user->isGuest):?>
                <?=$page['content']?>
            <?else:?>
                <form action="/site/save" id="formContent" method="post">
                    <?=CHtml::activeTextArea($page, $field, array('rows'=>6, 'cols'=>60))?> <?//die(print_r($page));?>
                    <?$this->widget('application.extensions.editor.editor', array('name'=>$name, 'type'=>'fckeditor', 'height'=>$height))?>
                    <input type="hidden" id="inpPageID" name="inpPageID" value="<?=$page['id']?>" />
                    <input type="hidden" id="inpDataType" name="inpDataType" value="content" />
                    <br /><br />
                    <?#<input type="button" id="btnSaveContent" value="Сохранить" />?>
                </form>
            <?endif;?>
        </td>
        <td id="right-col">
            <h2>НОВОСТИ</h2>
            <?if(!Yii::app()->user->isGuest) echo CHtml::link('Редактирование', '/news/index', array()).'<br /><br />';?>
            <?$first = array_shift($news);?>
            <div class="news-empty"><?=$first['short']?><br /><a href="" name="<?=$first['id']?>" title="читать"> >>> </a></div>
            <?foreach($news as $n):?>
            <div class="news"><?=$n['short']?><br /><a href="" name="<?=$n['id']?>"> >>> </a></div>
            <?endforeach;?>
        </td>
    </tr>
</table>
</div>