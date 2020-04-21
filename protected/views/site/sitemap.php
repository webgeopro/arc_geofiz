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
            <div id="second-label"><?=$page["label"]?>:</div>
            <!-- ======================================== // Bof: Основной контент -->
            <table id="tabSiteMap">
            <tr><td><a href="/">Главная</a></td><td class="left"><hr /></td></tr>
            <?foreach ($menu as $m):?>
                <tr>
                    <td>
                        <a href="/<?=$m['url']?>"><?=$m['label']?></a>
                    </td>
                    <?if ($m['hasChildren']):?>
                    <td class="left">
                        <hr />
                        <?foreach ($m['children'] as $subm):?>
                        <a href="/<?=$subm['url']?>" ><?=$subm['label']?></a><br />
                        <?endforeach;?>
                    </td>
                    <?else:?>
                    <td>&nbsp;</td>
                    <?endif;?>
                </tr>
            <?endforeach;?>
            <tr><td><a href="/vacancies">Вакансии</a></td><td class="left"><hr /></td></tr>
            <tr><td><a href="/sitemap">Карта сайта</a></td><td class="left"><hr /></td></tr>
            </table>
            <!-- ======================================== // Eof: Основной контент -->
        </td>
        <td id="right-col">
        <?if ($page['id']):
            $this->widget('gfzPhoto', array('pageID'=>$page['id']));
        else:
            $this->widget('gfzPhoto', array('pageName'=>'sitemap'));
        endif;?>
        </td>
    </tr>
</table>
</div>