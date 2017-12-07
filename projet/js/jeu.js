$(document).ready(function(){
  $('#creerGame').click(function(){
    creerSalon();
  });

  $('#nom').change(function(){
    var nom = $('#nom').val();
    if(nom.length == 0 || nom.length > 24){
      $('#nom').removeClass('valid');
      $('#nom').addClass('invalid');
    }else{
      $('#nom').removeClass('invalid');
      $('#nom').addClass('valid');
    }
  });

  $('#nbJoueurs').change(function(){
    var nbJoueurs = $('#nbJoueurs').val();
    if(nbJoueurs < 2 || nbJoueurs > 4){
      $('#nbJoueurs').removeClass('valid');
      $('#nbJoueurs').addClass('invalid');
    }else{
      $('#nbJoueurs').removeClass('invalid');
      $('#nbJoueurs').addClass('valid');
    }
  });

  $('#rejoindre').click(function(){
    var id = $('.selected').attr('id');
    rejoindre(id);
  });
});

$(window).on('load', function(){
  loadSalons();
});

function creerSalon(){
  var name = $('#nom').val();
  var nbPlayers = $('#nbJoueurs').val();
  var url_creerSalon = 'http://'+window.location.host+'/loveletters/projet/jouer/creerSalon';

  if(name.length > 0 && name.length < 24 && nbPlayers >= 2 && nbPlayers <=4){
    $.ajax({ url: url_creerSalon,
             dataType: "json",
             data: {nom: name,
                    nbJoueurs: nbPlayers},
             type: 'post',
             success: function(data){
               if(data != 'error'){
                 $('.game_list').animate({
                   width: '0px'
                 }).hide(100);
                 $('.create_game').animate({
                   marginLeft: '0px',
                   width: '100%'
                 });
                 $('.create_game').addClass('salon');
                 $('.salon').removeClass('create_game');
                 $('.salon').empty();
                 listParticipants(data);
               }
             }
    });
  }else{
    if(name.length == 0 || name.length > 24){
      $('#nom').removeClass('valid');
      $('#nom').addClass('invalid');
    }
    if(nbPlayers < 2 || nbPlayers > 4){
      $('#nbJoueurs').removeClass('valid');
      $('#nbJoueurs').addClass('invalid');
    }
  }
}

function listParticipants(data){
  var html='<div class="title center">'+
            '<h4>'+data.nom+' ('+Object.keys(data.participants).length+'/'+data.nbJoueurs+')</h4>'+
           '</div>';
  jQuery.each(data.participants, function(id, participant) {
    html+='<div class="participant">';
    if(participant.proprio){
      html+='<i class="material-icons">star</i>';
    }
    html+='<img class="headerProfilePictures" src="/loveletters/projet/img/profile_pictures/'+participant.idImg+'"/>'+
          participant.login+
        '</div>';
  });
  html+='<button id="retour" class="btn waves-effect waves-light grey darken-1" type="button" name="action" action="javascript:void(0)">Retour</button>';
  $('.salon').append(html);
  $('#retour').click(function(){
    $('.salon').empty();
    if($('.game_list')==null){
      $('.salon').addClass('game_list');
      $('.game_list').removeClass('salon');
    }
    if($('.create_game')==null){
      $('.salon').addClass('create_game');
      $('.create_game').removeClass('salon');
    }
  });
}

function loadSalons(){
  var url_loadSalons = 'http://'+window.location.host+'/loveletters/projet/jouer/loadSalons';
  $.ajax({ url: url_loadSalons,
           dataType: "json",
           data: {},
           type: 'post',
           success: function(data){
             listSalons(data);
           },
           complete: function(){
             $('.preloader-wrapper').hide();
             $('.salon_list').click(function(){
               $('.salon_list').removeClass('selected');
               $(this).addClass('selected');
             });
           }
  });
}

function listSalons(data){
  var html='';
  var b = true;
  jQuery.each(data.salons, function(id, values){
    if(b){
      html+='<div id="'+id+'" class="salon_list selected">'+
              values.nom+' - '+values.nbUsers+'/'+values.nbJoueurs+
            '</div>';
    }else{
      html+='<div id="'+id+'" class="salon_list">'+
              values.nom+' - '+values.nbUsers+'/'+values.nbJoueurs+
            '</div>';
    }
    b = false;
  });
  $('.games').append(html);
}

function rejoindre(id){
  var url_joinSalon = 'http://'+window.location.host+'/loveletters/projet/jouer/joinSalon';
  $.ajax({ url: url_joinSalon,
           dataType: "json",
           data: {idSalon: id},
           type: 'post',
           success: function(data){
             if(data != 'error'){
               $('.create_game').hide(100);
               $('.game_list').animate({
                 width: '100%'
               });
               $('.game_list').addClass('salon');
               $('.salon').removeClass('game_list');
               $('.salon').empty();
               listParticipants(data);
             }
           }
  });
}
