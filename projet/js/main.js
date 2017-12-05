$(document).ready(function(){
  $('.button-collapse').sideNav();

  $('.profile_pictures').click(function(){
    inscription_change_img(this);
  });

  $('#inscription').click(function(){
    verification_inscription();
  });

  $('#connected').click(function(){
    if($('#action_box').hasClass('hide')){
      $('#action_box').removeClass('hide');
    }else{
      $('#action_box').addClass('hide');
    }
  });

  $('.connexion_menu').click(function(){
    if($('#action_box').hasClass('hide')){
      $('#action_box').removeClass('hide');
    }else{
      $('#action_box').addClass('hide');
    }
  });

  $('#connexion').click(function(){
    connexion();
  });

  $('#login').change(function(){
    var login = $('#login').val();
    if(login.length == 0 || login.length > 24){
      $('#login').removeClass('valid');
      $('#login').addClass('invalid');
    }else{
      $('#login').removeClass('invalid');
      $('#login').addClass('valid');
    }
  });

  $('#pwd, #pwd2').change(function(){
    var pwd1 = $('#pwd').val();
    var pwd2 = $('#pwd2').val();
    if(pwd1.length == 0 || pwd1.length > 24){
      $("#pwd").removeClass('valid');
      $("#pwd").addClass('invalid');
    }else{
      $("#pwd").removeClass('invalid');
      $("#pwd").addClass('valid');
      if(pwd1===pwd2){
        $("#pwd2").removeClass('invalid');
        $("#pwd2").addClass('valid');
      }else{
        $("#pwd2").removeClass('valid');
        $("#pwd2").addClass('invalid');
      }
    }
  });
});

function inscription_change_img(e){
  $('.profile_pictures').removeClass('selected');
  $('#img').attr('value',$(e).attr('id'));
  $(e).addClass('selected');
}

function verification_inscription(){
  var login = $('#login').val();
  var pwd1 = $('#pwd').val();
  var pwd2 = $('#pwd2').val();
  var img = $('#img').val();
  if(login.length != 0 || login.length <= 24 || pwd1.length != 0 || pwd1.length <= 24 || pwd1==pwd2){
    //requete AJAX
    $.ajax({ url: window.location.href,
             data: {username: login,
                    password: pwd1,
                    image:    img},
             type: 'post',
             success: function(data){
               document.body.innerHTML = "";
               document.write(data);
               $('#connected').click(function(){
                 if($('#action_box').hasClass('hide')){
                   $('#action_box').removeClass('hide');
                 }else{
                   $('#action_box').addClass('hide');
                 }
               });
             }
    });
  }
}

function connexion(){
  var login = $('#login_connexion').val();
  var pwd = $('#pwd_connexion').val();
  var url_connexion = 'http://'+window.location.host+'/loveletters/projet/connexion';
  //requete AJAX
  $.ajax({ url: url_connexion,
           data: {username: login,
                  password: pwd},
           type: 'post',
           success: function(data){
             document.body.innerHTML = "";
             document.write(data);
             $('#connected').click(function(){
               if($('#action_box').hasClass('hide')){
                 $('#action_box').removeClass('hide');
               }else{
                 $('#action_box').addClass('hide');
               }
             });
           }
  });
}
