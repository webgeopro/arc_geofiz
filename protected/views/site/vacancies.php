<div id="content">
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
<div id="mainmenu">
    <?$this->widget('gfzMenu', array('pageID'=>$page['id']))?>
    <?#$this->widget('CTreeView', array('data' => Pages::model()->findAll()));?>
</div>
<div id="main-line"></div>
<table id="table-content" cellpadding="0" cellspacing="0">
    <tr>
        <td style="vertical-align: top;">
            <div id="second-label"><?=$page['label']?>:</div>
            <?if (Yii::app()->user->isGuest):
                $xml = simplexml_load_file($base.'/uploads/vacancies.xml');?>
                <div id="divVacDate">Текущие вакансии: ( <?=$xml['date']?> )</div>
                <?foreach ($xml as $vac): $vacCnt++;?>
                <ul>
                    <li>
                        Должность:
                        <div><?=$vac->position?></div>
                    </li>
                    <li>
                        Подразделение:
                        <div><?=$vac->department?></div>
                    </li>
                    <li>
                        График, режим, характер, условия работы:
                        <div><?=$vac->schedule?></div>
                    </li>
                    <li>
                        Требования:
                        <div><?=$vac->requirements?></div>
                    </li>
                </ul>
                <?endforeach;?>
                <div id="divVacCnt">Всего вакансий: <?=$vacCnt?></div>
            <?else:die('Stop. Edit block.');?>
                <form action="/site/save" id="formContent" method="post">
                    <?=CHtml::activeTextArea($page, $field, array('rows'=>6, 'cols'=>60))?>
                    <?$this->widget('application.extensions.editor.editor', array('name'=>$name, 'type'=>'fckeditor', 'height'=>$height))?>
                    <input type="hidden" id="inpPageID" name="inpPageID" value="<?=$page['id']?>" />
                    <input type="hidden" id="inpDataType" name="inpDataType" value="content" />
                    <br /><br />
                    <?#<input type="button" id="btnSaveContent" value="Сохранить" />?>
                </form>
            <?endif;?>
        </td>
        <td id="right-col">
        <?if ($page['id']):
            $this->widget('gfzPhoto', array('pageID'=>$page['id']));
        else:
            $this->widget('gfzPhoto', array('pageName'=>'vacancies'));
        endif;?>
        </td>
    </tr>
</table>
</div>