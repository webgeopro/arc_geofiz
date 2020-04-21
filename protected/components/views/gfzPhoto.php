<script language="javascript">
    $("document").ready(function () {
        $("ul#photo li a").slimbox({'counterText':'Изображение {x} из {y}'});
    });
</script>
<?if(!Yii::app()->user->isGuest) echo CHtml::link('Редактирование фото', '/file/index', array()).'<br />';?>
<?if(!Yii::app()->user->isGuest) echo CHtml::link('Редактирование категорий', '/photo/index', array()).'<br /><br />';?>
<?if (count($arPhoto)): 
    echo '<ul id="photo">';
    foreach ($arPhoto as $ph):?>
    <li>
        <a href="/uploads/full/<?=$ph['photo']['name']?>" title="<?=$ph['photo']['comment']?>">
            <img src="/uploads/<?=$ph['photo']['name']?>" />
        </a>
    </li>
    <?endforeach;
    echo '</ul>';
endif;?>