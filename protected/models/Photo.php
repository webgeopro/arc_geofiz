<?php

/**
 * This is the model class for table "photo".
 *
 * The followings are the available columns in table 'photo':
 * @property string $id
 * @property string $name
 * @property string $comment
 */
class Photo extends CActiveRecord
{
	public $image; // Загрузка файлов
    /**
	 * Returns the static model of the specified AR class.
	 * @return Photo the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'photo';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name', 'length', 'max'=>100),
			array('comment', 'length', 'max'=>300),
            array('image', 'file', 'types'=>'jpg, gif, png', 'maxSize' => 4194304), //1048576
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name, comment', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
            'category' => array(self::HAS_MANY, 'PhotoCategory', 'photo_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => '№ записи',
			'name' => 'Файл',
			'comment' => 'Комментарий',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('comment',$this->comment,true);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
    
    /**
     * Сохраняем файл изображения после сохранения записи
     */
    public function afterSave()
    {
        
    }
    
    /**
     * Удаление файлов изображений.
     */
    protected function afterDelete() 
    {
    	if($this->image) {
	        $img = 'uploads/'.$this->image;
	        $imgFull = 'uploads/full/'.$this->image;
	        #die("<br> $img <br> $imgFull");
	        if(file_exists($img)) unlink($img);
	        if(file_exists($imgFull)) unlink($imgFull);
    	}
        
        return true;
    }
    
    /**
     * Поведение для обработки фотографий. Создание уменьшенных изображений.
     */
    /*public function behaviors()
    {   #$userID = Yii::app()->user->getId();
        return array(
            'SImageUploadBehavior' => array(
                'class' => 'ext.SImageUploadBehavior',
                //'fileAttribute' => 'image',
                'fileAttribute' => 'name',
                #'nameAttribute' => 'name',
                #'mkdir'         => true, Не применяется в моем расширении (Старое)
                'imagesRequired'=>array(
                    //'thumb' => array('width'=>235,'height'=>176,'folder'=>'uploads'),
                    'full' => array('resize'=>false,'folder'=>'uploads/full'),
                    ),
            ),

        );
    }*/
    
    /**
     * Изменение размера изображения + вращение
     */
    public function resize()
    {
        Yii::import('application.extensions.image.Image');

        $original  = Yii::app()->params['uploadPath'].'/full';
        $thumbnail = Yii::app()->params['uploadPath'];

        if (file_exists($original)) {
            $image = new Image($original);

            $image->resize(235, 235, Image::AUTO); // worked
            $image->save($thumbnail);

        }
    }
}