var refresh;

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

  $('#refresh').click(function(){
    loadSalons();
  });

});

$(window).on('load', function(){
  loadSalons();
});

function refresh_participants(){
  var url_loadParticipants = 'http://'+window.location.host+'/loveletters/projet/jouer/loadParticipants';
  $.ajax({ url: url_loadParticipants,
           dataType: "json",
           type: 'post',
           success: function(data){
             listParticipants(data);
           }
  });
}

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
                 refresh = setInterval(refresh_participants, 4000);
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
  console.log(data);
  $('.salon').empty();
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
  if(data.start){
    html+='<button id="start" class="btn waves-effect waves-light grey darken-1" type="button" name="action" action="javascript:void(0)">Démarrer</button>';
  }
  $('.salon').append(html);


  if(data.start){
    $('#start').click(function(){
      clearInterval(refresh);
    });
  }

  $('#retour').click(function(){
    clearInterval(refresh);
    var url_leaveCurrentSalon = 'http://'+window.location.host+'/loveletters/projet/jouer/leaveCurrentSalon';
    $.ajax({ url: url_leaveCurrentSalon,
             type: 'post',
             complete: function(){
               $('.salon').empty();
               if($('.game_list').length==0){
                 $('.salon').addClass('game_list');
                 $('.game_list').removeClass('salon');
                 $('.game_list').append('<div class="title center">'+
                                           '<h4>Liste des parties</h4>'+
                                         '</div>'+
                                         '<button id="refresh" class="btn waves-effect waves-light grey darken-1" type="button" name="action" action="javascript:void(0)">'+
                                           '<i class="material-icons">refresh</i>'+
                                         '</button>'+
                                         '<div class="games">'+
                                           '<div class="center" style="margin-top: 30px">'+
                                             '<div class="preloader-wrapper big active">'+
                                               '<div class="spinner-layer spinner-white-only">'+
                                                 '<div class="circle-clipper left">'+
                                                   '<div class="circle"></div>'+
                                                 '</div><div class="gap-patch">'+
                                                   '<div class="circle"></div>'+
                                                 '</div><div class="circle-clipper right">'+
                                                   '<div class="circle"></div>'+
                                                 '</div>'+
                                               '</div>'+
                                             '</div>'+
                                           '</div>'+
                                         '</div>'+
                                         '<div class="center">'+
                                           '<button id="rejoindre" class="btn waves-effect waves-light grey darken-1" type="button" name="action" action="javascript:void(0)">Rejoindre</button>'+
                                        '</div>');
                 $('.game_list').animate({
                   width: '60%'
                 });
                 $('.create_game').animate({
                   marginLeft: '61%',
                   width: '39%'
                 }).show(100);
                 $('#rejoindre').click(function(){
                   var id = $('.selected').attr('id');
                   rejoindre(id);
                 });
               }
               if($('.create_game').length==0){
                 $('.salon').addClass('create_game');
                 $('.create_game').removeClass('salon');
                 $('.create_game').append('<div class="title center">'+
                                             '<h4>Création de partie</h4>'+
                                           '</div>'+
                                           '<form action="/" method="post">'+
                                            '<div class="input-field">'+
                                              '<input id="nom" type="text" name="nom" class="active">'+
                                              '<label for="nom">Nom de la partie</label>'+
                                            '</div>'+
                                            '<div class="input-field">'+
                                              '<input id="nbJoueurs" type="number" name="nbJoueurs" class="active">'+
                                              '<label for="nbJoueurs">Nombre de joueurs (max 4.)</label>'+
                                            '</div>'+
                                            '<div class="center">'+
                                              '<button id="creerGame" class="btn waves-effect waves-light grey darken-1" type="button" name="action" action="javascript:void(0)">Créer la partie</button>'+
                                            '</div>'+
                                           '</form>');
                 $('.game_list').animate({
                   width: '60%'
                 }).show(100);
                 $('.create_game').animate({
                   marginLeft: '61%',
                   width: '39%'
                 });
                 $('#creerGame').click(function(){
                   creerSalon();
                 });
               }
               loadSalons();
             }
    });
  });

}

function loadSalons(){
  $('.games').empty();
  $('.preloader-wrapper').show();
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
  $('.games').empty();
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
               refresh = setInterval(refresh_participants, 4000);
             }
           }
  });
}
