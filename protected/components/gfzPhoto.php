<?php
class gfzPhoto extends CWidget {
	
    public $pageID; # ID страницы
    public $pageName; # Названия статичных страниц
    
    public function run() {
        if ($this->pageID) {
            $arPhoto = PhotoCategory::model()->with('photo')->findAllByAttributes(array('category' => $this->pageID));
        } elseif ($this->pageName) {
            // Продумать !!!
            $arPhoto = PhotoCategory::model()->with('photo')->findAllByAttributes(array('category' => $this->pageID));
        }
        $this->render('gfzPhoto', array(
            'pageID'   => $this->pageID,
            'pageName' => $this->pageName,
            'arPhoto'  => $arPhoto,
        ));
	}
}