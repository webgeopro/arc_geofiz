<?php

/**
 * This is the model class for table "pages".
 *
 * The followings are the available columns in table 'pages':
 * @property integer $id
 * @property integer $parent_id
 * @property string $label
 * @property string $name
 * @property string $content
 * @property string $num
 */
class Pages extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Pages the static model class
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
		return 'pages';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('parent_id', 'numerical', 'integerOnly'=>true),
			array('label, name, num', 'length', 'max'=>30),
			array('content', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, parent_id, label, name, content, num', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => '№ записи',
			'parent_id' => 'Корневая запись',
			'label' => 'Страница',
			'name' => 'Название',
			'content' => 'Содержимое',
			'num' => 'Порядок',
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

		$criteria->compare('id',$this->id);
		$criteria->compare('parent_id',$this->parent_id);
		$criteria->compare('label',$this->label,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('content',$this->content,true);
		$criteria->compare('num',$this->num,true);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
	/**
	 * Получение иерархического списка меню 
	 * @return array
	 */
	public function getMenu()
	{
		$req = Yii::app()->db->createCommand(
            "SELECT m1.id,m1.label, m1.name AS url, m2.id IS NOT NULL AS hasChildren "
            . "FROM pages AS m1 LEFT JOIN pages AS m2 ON m1.id=m2.parent_id "
            . "WHERE m1.parent_id = 0 AND m1.name != 'main' "
            . "GROUP BY m1.id ORDER BY m1.label ASC"
        );
        $children = $req->queryAll();
        /*$res = str_replace(
            '"hasChildren":"0"',
            '"hasChildren":false',
           CTreeView::saveDataAsJson($children)
        );*/

        return $children;
	}
    
    public function getSubMenu($parentID=null)
	{
		$req = Yii::app()->db->createCommand(
            "SELECT id, label, name AS url "
            . "FROM pages "
            . "WHERE parent_id = $parentID "
            . "ORDER BY id ASC"
        );
        $children = $req->queryAll();
        
        return $children; #return $res; #$res = CTreeView::saveDataAsJson($children);
	}
}