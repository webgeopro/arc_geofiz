<?php
class gfzMenu extends CWidget {
	
    public $pageID; # ID страницы
    
    public function run() {
        $arMenu = Pages::model()->getMenu();#die(print_r($arMenu));
        foreach ($arMenu as $k=>$m) {
            if ($m['hasChildren']){
                $arMenu[$k]['children'] = Pages::model()->getSubMenu($m['id']);
            }
        }
            
        $this->render('gfzMenu', array(
            'pageID' => $this->pageID,
            'arMenu' => $arMenu,
        ));
	}
}