$("#num").on('keyup keypress change',function(){
    var pattern = /^[0-9]+$/;
    var txtval = $("#num").val();
    if(!pattern.test(txtval)){
       $(this).val($(this).val().replace(/[^0-9]+/g,''))
    }
});