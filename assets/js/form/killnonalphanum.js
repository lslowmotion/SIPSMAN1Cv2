$("#alpha").on('keyup keypress change',function(){
    var pattern = /^[a-zA-Z.,' ]+$/;
    var txtval = $("#alpha").val();
    if(!pattern.test(txtval)){
       $(this).val($(this).val().replace(/[^a-zA-Z.,' ]+/g,''))
    }
});

$("#alphanum").on('keyup keypress change',function(){
    var pattern = /^[a-zA-Z0-9]+$/;
    var txtval = $("#alphanum").val();
    if(!pattern.test(txtval)){
       $(this).val($(this).val().replace(/[^a-zA-Z0-9]+/g,''))
    }
});

$("#num").on('keyup keypress change',function(){
    var pattern = /^[0-9]+$/;
    var txtval = $("#num").val();
    if(!pattern.test(txtval)){
       $(this).val($(this).val().replace(/[^0-9]+/g,''))
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

