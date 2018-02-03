$("#alpha").on('keyup keypress change',function(){
    var pattern = /^[a-zA-Z.,' ]+$/;
    var txtval = $("#alpha").val();
    if(!pattern.test(txtval)){
       $(this).val($(this).val().replace(/[^a-zA-Z.,' ]+/g,''))
    }
});

