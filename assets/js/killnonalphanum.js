$(".alpha").on('keyup keypress change',function(){
    var pattern = /^[a-zA-Z]+$/;
    var txtval = $(this).val();
    if(!pattern.test(txtval)){
       $(this).val($(this).val().replace(/[^a-zA-Z]+/g,''))
    }
});
$(".alphasp").on('keyup keypress change',function(){
    var pattern = /^[a-zA-Z ]+$/;
    var txtval = $(this).val();
    if(!pattern.test(txtval)){
       $(this).val($(this).val().replace(/[^a-zA-Z ]+/g,''))
    }
});
$(".nama").on('keyup keypress change',function(){
    var pattern = /^[a-zA-Z '-]+$/;
    var txtval = $(this).val();
    if(!pattern.test(txtval)){
       $(this).val($(this).val().replace(/[^a-zA-Z '-]+/g,''))
    }
});
$(".pengarang").on('keyup keypress change',function(){
    var pattern = /^[a-zA-Z '-,&]+$/;
    var txtval = $(this).val();
    if(!pattern.test(txtval)){
       $(this).val($(this).val().replace(/[^a-zA-Z '-,&]+/g,''))
    }
});

$(".alphanumspsym").on('keyup keypress change',function(){
    var pattern = /^[a-zA-Z0-9 ,.':-]+$/;
    var txtval = $(this).val();
    if(!pattern.test(txtval)){
       $(this).val($(this).val().replace(/[^a-zA-Z0-9 ,.:-]+/g,''))
    }
});
$(".alphanumsp").on('keyup keypress change',function(){
    var pattern = /^[a-zA-Z0-9 ]+$/;
    var txtval = $(this).val();
    if(!pattern.test(txtval)){
       $(this).val($(this).val().replace(/[^a-zA-Z0-9 ]+/g,''))
    }
});

$(".alphanum").on('keyup keypress change',function(){
    var pattern = /^[a-zA-Z0-9]+$/;
    var txtval = $(this).val();
    if(!pattern.test(txtval)){
       $(this).val($(this).val().replace(/[^a-zA-Z0-9]+/g,''))
    }
});

$(".alphaspcomma").on('keyup keypress change',function(){
    var pattern = /^[a-zA-Z ,]+$/;
    var txtval = $(this).val();
    if(!pattern.test(txtval)){
       $(this).val($(this).val().replace(/[^a-zA-Z ,]+/g,''))
    }
});

$(".num").on('keyup keypress change',function(){
    var pattern = /^[0-9]+$/;
    var txtval = $(this).val();
    if(!pattern.test(txtval)){
       $(this).val($(this).val().replace(/[^0-9]+/g,''))
    }
});
$(".numd").on('keyup keypress change',function(){
    var pattern = /^[0-9,.]+$/;
    var txtval = $(this).val();
    if(!pattern.test(txtval)){
       $(this).val($(this).val().replace(/[^0-9,.]+/g,''))
    }
});
$(".numdot").on('keyup keypress change',function(){
    var pattern = /^[0-9.]+$/;
    var txtval = $(this).val();
    if(!pattern.test(txtval)){
       $(this).val($(this).val().replace(/[^0-9.]+/g,''))
    }
});


$("#password").on({
  keydown: function(e) {
    if (e.which === 32)
      return false;
  },
  change: function() {
    this.value = this.value.replace(/\s/g, "");
  }
});

