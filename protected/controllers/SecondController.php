<?php

class SiteController extends Controller
{
	//public $layout='//layouts/second';
    public $defaultAction = 'second';
    /**
	 * Declares class-based actions.
	 */
	public function actions()
	{ 
	    $action = trim($_GET['action']);#die($action);
        if (!empty($action) and Pages::model()->exists('name=:nameID', array(':nameID'=>$action))) {
            $this->render('index', array(
                'page' => Pages::model()->findByAttributes(array('name'=>$action)),
            ));	   
        } else {
            die('NO ACTION');
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
    }

    public function actionSecond()
	{
	    die('DEF action');
	}
    /**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
	    if($error=Yii::app()->errorHandler->error)
	    {
	    	if(Yii::app()->request->isAjaxRequest)
	    		echo 'message='.$error['message'];
	    	else
	        	$this->render('error', $error);
	    }
	}
}