<?php

class SiteController extends Controller
{
	public $layout='//layouts/main';
    /**
	 * Declares class-based actions.
	 */
	public function actions()
	{
        return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		$cs = Yii::app()->clientScript;
        //$page = trim($_GET['page']);
        $page =  Yii::app()->request->getParam('page');

        if ($page and Pages::model()->exists('name=:nameID', array(':nameID'=>$page))) {
            $cs->registerCSSFile('/css/second.css');
            $this->layout = '//layouts/second'; 
            $page = Pages::model()->findByAttributes(array('name'=>$page));
            $this->pageTitle = 'Геофизика :: '.$page['label'];
            $this->render('page', array(
                'page' => $page,
                'name' => 'Pages_content', 
                'height'=> '1000', 
                'field' => 'content',
		        'element_id'=> $page['id'],
            ));	   
        } else {
            $cs->registerCSSFile('/css/index.css');
            $this->pageTitle = 'Геофизика :: Красноярск';
            $this->render('index', array(
                'page' => Pages::model()->findByAttributes(array('name' => 'main')),
                'news' => News::model()->findAll(array('order'=>'date', 'limit'=>6)),
                'name' => 'Pages_content', 
                'height'=> '1000', 
                'field' => 'content',
		        'element_id'=> $page['id'],
            ));
        }
	}

	public function actionPage()
	{
		$cs = Yii::app()->clientScript;
        $cs->registerCSSFile('/css/index.css');
        
		$this->render('index', array(
            'page' => Pages::model()->findByAttributes(array('name' => 'main')),
            'news' => News::model()->findAll(array('order'=>'date', 'limit'=>6)),
        ));
	}


	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
	    if($error=Yii::app()->errorHandler->error)
	    {
	    	if(Yii::app()->request->isAjaxRequest)
	    		echo $error['message'];
	    	else
	        	$this->render('error', $error);
	    }
	}

	/**
	 * Displays the contact page
	 */
	public function actionContact()
	{
		$model=new ContactForm;
		if(isset($_POST['ContactForm']))
		{
			$model->attributes=$_POST['ContactForm'];
			if($model->validate())
			{
				$headers="From: {$model->email}\r\nReply-To: {$model->email}";
				mail(Yii::app()->params['adminEmail'],$model->subject,$model->body,$headers);
				Yii::app()->user->setFlash('contact','Thank you for contacting us. We will respond to you as soon as possible.');
				$this->refresh();
			}
		}
		$this->render('contact',array('model'=>$model));
	}

	/**
	 * Displays the contact page
	 */
	public function actionVacancies()
	{
		$cs = Yii::app()->clientScript;
        $cs->registerCSSFile('/css/second.css');
        $cs->registerCSSFile('/css/vacancies.css');
		//$xml = new ContactForm;
        $this->layout = '//layouts/second';
        $content['label'] = 'Вакансии';//die('path'.Yii::app()->basePath);
		$this->render('vacancies',array('page'=>$content, 'base'=>Yii::app()->basePath.'\..', ));
        
	}

    /**
	 * Displays the contact page
	 */
	public function actionSiteMap()
	{
		$cs = Yii::app()->clientScript;
        $cs->registerCSSFile('/css/second.css');
        $cs->registerCSSFile('/css/sitemap.css');
        $this->layout = '//layouts/second';
        $content['label'] = 'Карта сайта';

        $arMenu = Pages::model()->getMenu();
        foreach ($arMenu as $k=>$m) {
            if ($m['hasChildren']){
                $arMenu[$k]['children'] = Pages::model()->getSubMenu($m['id']);
            }
        }
		$this->render('sitemap',array('page'=>$content, 'menu'=>$arMenu,));

	}

    /**
	 * Displays the login page
	 */
	public function actionLogin()
	{
		$model=new LoginForm;

		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		// collect user input data
		if(isset($_POST['LoginForm']))
		{
			$model->attributes=$_POST['LoginForm'];
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login())
				$this->redirect(Yii::app()->user->returnUrl);
		}
		// display the login form
		$this->render('login',array('model'=>$model));
	}

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}

	/**
	 * Сохранение данных. 
	 */
	public function actionSave()
	{#die(print_r($_POST));
		if ( Yii::app()->request->isAjaxRequest and !Yii::app()->user->isGuest) {
            $pageID = (int)$_POST['inpPageID'];
            switch ($_POST['inpDataType']){ // Определяем тип данных (пункт меню / содержимое страниц)
                case 'content':
                    $data   = trim($_POST['Pages']['content']);
                    $model = Pages::model()->findByPk($pageID);
                    $model->content = $data;
                    break;
                case 'menu':
                    $data = trim($_POST['menuContent']);
                    break;
                default:
                    die('errorDataType');
            }

            if ($model->validate()){
                if ($model->save()){ // Сохранение данных
                    die('success');
                } else {
                    die('errorSave');
                }
            } else {
                die('errorValidate');
            }

        } elseif (!Yii::app()->user->isGuest) { // Заглушка для сохранения через fckeditor
            if ('content' == $_POST['inpDataType']){
                $pageID = (int)$_POST['inpPageID'];
                if ($pageID) {
                    $model = Pages::model()->findByPk($pageID);
                } else {die('STOP');
                    $model = new Pages;
                }
                $data   = trim($_POST['Pages']['content']);
                //$model = Pages::model()->updateByPk($pageID, array('content'=>$data,));
                $model = Pages::model()->findByPk($pageID);
                $model->content = $data;
            } elseif ('menu' == $_POST['inpDataType']){
                $itemID = (int)$_POST['inpItemID'];
                $parentID = (int)$_POST['inpParentID'];
                if ($itemID) {
                    $model = Pages::model()->findByPk($itemID);
                } else {
                    $model = new Pages;
                    $model->parent_id = $parentID;
                }
                $model->name = trim($_POST['inpMenuName']);
                $model->label = trim($_POST['inpMenuLabel']);
            }
            #die("{$model->id} - {$model->parent_id} - {$model->name} - {$model->label}");
            if ($model->validate()){
                $model->save();
            }
            $this->redirect("/{$model->name}");
        } else {
            die('errorAccess');
        }
        die('error');
	}

    /**
	 * Получение атрибутов для меню
	 */
	public function actionGetItem()
	{
		if ( Yii::app()->request->isAjaxRequest and !Yii::app()->user->isGuest) {
            //die(print_r($_POST));
            $dataType = trim($_POST['dataType']);
            $itemID = (int)$_POST['itemID'];
            if ('menuItem' == $dataType and $itemID) {
                $item = Pages::model()->findByPk($itemID);
                if (!empty($item->name)){
                    $out['result'] = 'success';
                    $out['name']   = $item['name'];
                    $out['label']  = $item['label'];
                    $out['parent_id']  = $item['parent_id'];
                }

                die(json_encode($out));
            }
        }
	}

       /**
	 * Получение атрибутов для меню
	 */
	public function actionDelItem()
	{
		if ( !Yii::app()->user->isGuest) {
            $itemID = (int)$_GET['itemID'];
            $item = Pages::model()->findByPk($itemID);
            $redirect = $item['name'];
            if ($item->delete()) {
                Pages::model()->deleteAll('parent_id=:parentID', array(':parentID'=>$itemID));
            }
        }
        $this->redirect("/{$redirect}");
    }
}