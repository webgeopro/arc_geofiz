<?php

//'SImageUploadBehavior' => array(
//    'class' => 'ext.SImageUploadBehavior.SImageUploadBehavior',
//    'fileAttribute' => 'image',
//    'nameAttribute' => 'name',
//    'imagesRequired'=>array(
//        //'width'=>150,'height'=>200,'folder'=>'uploads', //В качестве примера, если нужно всего одно изображение
//        'thumb' => array('width'=>150,'height'=>200,'folder'=>'uploads/1'),
//        'middle' => array('width'=>400,'height'=>250,'folder'=>'uploads/2'),
//        'big' => array('width'=>900,'height'=>600,'folder'=>'uploads/3','smartResize'=>false),
//        'full' => array('resize'=>false,'folder'=>'uploads/4'),
//        ),
//)


//fileAttribute - Атрибут файла в модели, поле файла в форме должно быть с этим именем
//nameAttribute - Опционально, если хотите, что бы у файлов были осмысленные имена. В данном случае используется поле name. Например, если это значение "Новая запись" именем файла будет 25Mar2011_15-45-20novaya_zapis.jpg
//imagesRequired - самое интересное. Количество массивов определяет кол-во копий изображения. Если это просто перечень параметров будет создано 1 изображение. Допустимые параметры:
//-width - ширина изображения, обязательный параметр, нет значения по умолчанию
//-height - высота изображения, обязательный параметр, нет значения по умолчанию
//-folder - папка для загрузки относительно корня приложения, обязательный параметр, нет значения по умолчанию
//-smartResize - флаг, определяющий нужно ли полностью заполнить область изображения, по умолчанию true! Если высота или ширина исходника меньше требуемого изображения, изменений размера не будет.
//-resize - флаг, определяющий нужно ли вообще проводить манипуляции с изображением, по умолчанию true. Если false- просто копия оригинального изображения.


class SImageUploadBehavior extends CActiveRecordBehavior {

	public $fileAttribute;
	public $nameAttribute;
	public $folder = 'uploads';
	public $mkdir = false;
	public $useDateForName = true;

	public $useUrlForName = false;

	public $imagesRequired;

	private $_oldFileName;
	private $_new = false;


	public function beforeSave($event)
	{
		if(!$this->fileAttribute){
			throw new CHttpException(500,Yii::t('yiiext','"fileAttribute" должен быть установлен!'));
		}
		if(!$this->imagesRequired){
			throw new CHttpException(500,Yii::t('yiiext','"imagesRequired" должен(ны)быть установлен!'));
		}

		$model = $this->getOwner();
		$file = CUploadedFile::getInstance($model, $this->fileAttribute);
		$fileAttribute = $this->fileAttribute;

		//Если файл не был загружен, поле с файлом обновлять не нужно.
		if(is_null($file)){
			unset($model->image);
			$this->getOwner()->{$this->fileAttribute} = $this->_oldFileName;
			return;
		}elseif(!$model->isNewRecord && !empty($model->$fileAttribute)){
			$this->deleteImages();
		}

		//Имя будущего изображения
		if($this->nameAttribute){
			$nameAttribute = $this->nameAttribute;
			//Дата наслучай, если такое имя уже есть
			$fileName =	$this->safeFileName($model->$nameAttribute).'.'.$file->getExtensionName();
			$fileName = $this->useDateForName
				? date('dMY_H-i-s').$fileName
				: $fileName;
		}else{
			$fileName = $this->safeFileName($file->getName()).'.'.$file->getExtensionName();
			$fileName = $this->useDateForName
				? date('dMY_H-i-s').$fileName
				: $fileName;
		}

		Yii::import('ext.helpers.CArray');
		Yii::import('ext.image.Image');

		if(!is_array(reset($this->imagesRequired))){
			$this->imagesRequired['fileName'] = $fileName;
			$this->manipulate($file, $this->imagesRequired);
		}
		else{
			foreach($this->imagesRequired as $imageRequired){
				$imageRequired['fileName'] = $fileName;
				$this->manipulate($file, $imageRequired);
			}
		}

		$this->_new = true;

		$model->$fileAttribute = $fileName;
		$model->$fileAttribute = $this->useUrlForName
			? $this->getImageUrl(NULL, true)
			: $fileName;

	}
	//Абсолютный путь к изображеию
	private function getAbsolutePath($folder, $fileName = null){
		return
		Yii::app()->basePath.'/../'.	//Путь к корню приложения
		$this->folder.'/'.
		$folder.'/'.	//Папка из конфигурации
		$fileName;
	}

	//Получение ссылки на изображение
	public function getImageUrl($image = null,$abs = false){
		$model = $this->getOwner();
		if($this->useUrlForName && !$this->_new)
			return $model->{$this->fileAttribute};

		if(!is_array(reset($this->imagesRequired))){
			$folder = $this->imagesRequired['folder'];
		}else{
			if(!$image) return;
			$folder = $this->imagesRequired[$image]['folder'];
		}
		if($folder) $folder .= '/';

		$fileAttribute = $this->fileAttribute;
		$folder = '/'.$this->folder.'/'.$folder.$model->$fileAttribute;
		return $abs ? Yii::app()->baseUrl.$folder : $folder;
	}


	//Удаление всех копий файла для текущей модели
	public function deleteImages(){
		$fileAttribute = $this->fileAttribute;
		$model = $this->getOwner();

		if(!is_array(reset($this->imagesRequired))){
			$imagePath = $this->getAbsolutePath($this->imagesRequired['folder'], $model->$fileAttribute);
			if(file_exists($imagePath)) unlink($imagePath);
		}
		else{
			foreach($this->imagesRequired as $imageRequired){
				$imagePath = $this->getAbsolutePath($imageRequired['folder'], $model->$fileAttribute);
				if(file_exists($imagePath) && !is_dir($imagePath))
					unlink($imagePath);
			}
		}
	}

	//Создание изображения на основе параметров
	private function manipulate($file, $options){
		//Первым делом валидация
		$this->validateOptions($options);

		$targetFolder = $options['folder'];
		$fileName = $options['fileName'];

		//Путь будущего изображения
		$path = $this->getAbsolutePath($targetFolder ,$fileName);

		if($this->mkdir && !file_exists(dirname($path)))
			mkdir(dirname($path), 0777, true);

		//Если изменять размеры не нужно - просто сделаем копию изображения
		if(isset($options['resize']) && !$options['resize']){
			copy($file->getTempName(), $path);
			return;
		}

		//Ширина и высота требуемого изображения
		$targetWidth = $options['width'];
		$targetHeight = $options['height'];
		//Ширина и высота загруженного изображения
		list($uploadedWidth, $uploadedHeight) = getimagesize($file->getTempName());

		//Если изменять размеры не нужно - просто сделаем копию изображения
		if(isset($options['smartResize']) && !$options['smartResize']){
			//Если требуемое изображение больше загруженного, его не нужно изменять
			if($targetWidth>$uploadedWidth && $targetHeight>$uploadedHeight){
				copy($file->getTempName(), $path);
			}else{
				//Изображение для манипуляции берется из временной папки
				$image = new Image($file->getTempName());
				//Манипуляция
				$image->resize($targetWidth, $targetHeight, Image::AUTO)->sharpen(1)->quality(95)->save($path);
			}
			return;
		}

		//Отношение сторон загруженного и требуемого изображения
		$uploadedRatio = $uploadedWidth/$uploadedHeight;
		$targetRatio = $targetWidth/$targetHeight;

		//Сравниваем отношения и считаем координаты для кадрирования(если нарисовать на бумаге алгоритм становится очевидным :))
		if($uploadedRatio>$targetRatio){
			$cropHeight	= $uploadedHeight;
			$cropWidth	= $uploadedHeight*$targetRatio;
			$cropLeft	= ($uploadedWidth - $uploadedHeight*$targetRatio)*0.5;
			$cropTop	= 0;
		}
		else{
			$cropHeight	= $uploadedWidth/$targetRatio;
			$cropWidth	= $uploadedWidth;
			$cropLeft	= 0;
			$cropTop	= ($uploadedHeight - $uploadedWidth/$targetRatio)*0.2;
		}
		//Изображение для манипуляции берется из временной папки
		$image = new Image($file->getTempName());
		//Манипуляция
		$image->crop($cropWidth, $cropHeight, $cropTop, $cropLeft)
				->resize($targetWidth, $targetHeight, Image::NONE)
				->sharpen(1)->quality(95)->save($path);


	}

	public function beforeDelete(){
		$this->deleteImages();
	}

	//Валидация опций создаваемого изображения
	private function validateOptions($options){
		if(!is_array($options))
			throw new CHttpException(500,Yii::t('yiiext','Конфигурацией изображения должен быть массив'));
		if(!isset($options['folder']))
			throw new CHttpException(500,Yii::t('yiiext','Папка для загрузки не установлена'));
		if(isset($options['resize']) && $options['resize']===false) return;
		if(!isset($options['width']) || !isset($options['height']))
			throw new CHttpException(500,Yii::t('yiiext','Параметры изображений установлены неправильно'));
	}

	//У файлов должны быть безопасные имена
	private function safeFileName($string) {
		$converter = array(
			'а' => 'a',   'б' => 'b',   'в' => 'v',
			'г' => 'g',   'д' => 'd',   'е' => 'e',
			'ё' => 'e',   'ж' => 'zh',  'з' => 'z',
			'и' => 'i',   'й' => 'y',   'к' => 'k',
			'л' => 'l',   'м' => 'm',   'н' => 'n',
			'о' => 'o',   'п' => 'p',   'р' => 'r',
			'с' => 's',   'т' => 't',   'у' => 'u',
			'ф' => 'f',   'х' => 'h',   'ц' => 'c',
			'ч' => 'ch',  'ш' => 'sh',  'щ' => 'sch',
			'ь' => '\'',  'ы' => 'y',   'ъ' => '\'',
			'э' => 'e',   'ю' => 'yu',  'я' => 'ya',

			'А' => 'A',   'Б' => 'B',   'В' => 'V',
			'Г' => 'G',   'Д' => 'D',   'Е' => 'E',
			'Ё' => 'E',   'Ж' => 'Zh',  'З' => 'Z',
			'И' => 'I',   'Й' => 'Y',   'К' => 'K',
			'Л' => 'L',   'М' => 'M',   'Н' => 'N',
			'О' => 'O',   'П' => 'P',   'Р' => 'R',
			'С' => 'S',   'Т' => 'T',   'У' => 'U',
			'Ф' => 'F',   'Х' => 'H',   'Ц' => 'C',
			'Ч' => 'Ch',  'Ш' => 'Sh',  'Щ' => 'Sch',
			'Ь' => '\'',  'Ы' => 'Y',   'Ъ' => '\'',
			'Э' => 'E',   'Ю' => 'Yu',  'Я' => 'Ya',
		);
		$str = strtr($string, $converter);
		$str = strtolower($str);
		$str = preg_replace('~[^-a-z0-9_]+~u', '_', $str);
		$str = trim($str, "-");

		return $str;
	}

	public function afterFind($event)
	{
		$this->_oldFileName = $this->getOwner()->{$this->fileAttribute};
	}
}
