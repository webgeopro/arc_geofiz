$("document").ready(function () {
    $('#btnSaveContent').click(function(){
        $("#formContent").ajaxSubmit({
            dataType: 'json',
            success : showResponse
        });
        return false;
    });
    /*$('.btnSaveContent').click(function(){ // Отключено
        $.post(
            '/site/save',
            {
                'pageContent' : $('#Pages_content').val(),
                'pageID'      : $('#inpPageID').val(),
                'dataType'    : $('#inpDataType').val()
            },
            function(data){
                if (data == 'success') {
                    //alert('К сожалению, произошла ошибка при обработке запроса.')
                    alert('Изменения сохранены.')
                }
            });
        return false;
    });*/
});
function showResponse(data)
{
    if ('success' == data.result) {
        alert('Изменения сохранены.')
    } else {
        //alert('При сохранении обнаружены ошибки.');
    }
}