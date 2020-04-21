<div id="logo-top"></div>
<div id="mainmenu">
    <?$this->widget('gfzMenu', array('pageID'=>$pageID))?>
    <?$this->widget('CTreeView', array('data' => Pages::model()->findAll()));?>
</div>
<div id="find">
    <input type="text" class="find" />
    <input type="button" value="Найти" />
    <input type="button" value="Карта сайта" />
</div>
<div id="first-word">
    <div id="wordPhoto"></div>
    <div id="wordText">
        <br />
        <span>
        в процессе бурения скважин, при контроле за разработкой нефтегазовых месторождений ПВР, ГТИ, ВСП<br /> 
        и сейсморазведки 2D,3D.
    </div>
</div>
<div id="main-line"></div>
<table id="table-content" cellpadding="0" cellspacing="0">
    <tr>
        <td><?=$page['content']?></td>
        <td id="right-col">
            <h2>НОВОСТИ</h2>
            <?$first = array_shift($news);?>
            <div class="news-empty"><?=$first['short']?><br /><a href="" name="<?=$first['id']?>" title="читать"> >>> </a></div>
            <?foreach($news as $n):?>
            <div class="news"><?=$n['short']?><br /><a href="" name="<?=$n['id']?>"> >>> </a></div>
            <?endforeach;?>
        </td>
    </tr>
</table>