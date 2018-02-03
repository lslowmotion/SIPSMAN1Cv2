$("#alphanum").on('keyup keypress change',function(){
    var pattern = /^[a-zA-Z0-9]+$/;
    var txtval = $("#alphanum").val();
    if(!pattern.test(txtval)){
       $(this).val($(this).val().replace(/[^a-zA-Z0-9]+/g,'))
    }
});