$(document).ready(function(){

});

function affichageMain(){
  var url_getMain = 'http://'+window.location.host+'/loveletters/projet/partie/main';
  $.ajax({ url: url_getMain,
           dataType: "json",
           data: {},
           type: 'post',
           success: function(data){

           }
  });
}
