var id_card_selected;
var etat;
$(document).ready(function(){
  affichageMain();
  affichageMainsJoueurs();
  affichageTerrains();
  affichageEtat();
  affichageScores();

  $('.defausse_manche').click(function(){
    $('.overlay_defausse').show();
    affichageDefausse();
  });

  $('.defausse').click(function(){
    affichageDefausse($(this).attr('id'));
  });

  $('#pioche').click(function(){
    pioche();
  });

  $('#jouer').click(function(){
    if(id_card_selected == null){
      alert('Vous n\'avez pas sélectionné de carte');
    }else{
      switch(id_card_selected){
        case "1": choixJoueur().done(function(data){
                $('.overlay_defausse').show();
                $('.overlay_defausse').empty();
                $('.overlay_defausse').append('<h6>Choisis un joueur</h6>');
                $('.overlay_defausse').append(data);
                $('.overlay_defausse .choix_joueur').click(function(){
                  var idJoueur=$(this).attr('id');
                  $('.overlay_defausse').hide();
                  effetGarde().done(function(data){
                    $('.overlay_defausse').show();
                    $('.overlay_defausse').empty();
                    $('.overlay_defausse').append('<h6>Choisis une carte</h6>');
                    $('.overlay_defausse').append(data);
                    $('.overlay_defausse .carte').click(function(){
                      var idCarte=$(this).attr('id');
                      $('.overlay_defausse').hide();
                      completeEffetGarde(idJoueur,idCarte);
                      jouer(id_card_selected);
                    });
                  });
                });
        });
                break;
        case "2":choixJoueur().done(function(data){
                $('.overlay_defausse').show();
                $('.overlay_defausse').empty();
                $('.overlay_defausse').append('<h6>Choisis un joueur</h6>');
                $('.overlay_defausse').append(data);
                $('.overlay_defausse .choix_joueur').click(function(){
                  var idJoueur=$(this).attr('id');
                  $('.overlay_defausse').hide();
                  effetPretre(idJoueur);
                  jouer(id_card_selected);
                });
        });
                break;
        case "3":choixJoueur().done(function(data){
                $('.overlay_defausse').show();
                $('.overlay_defausse').empty();
                $('.overlay_defausse').append('<h6>Choisis un joueur</h6>');
                $('.overlay_defausse').append(data);
                $('.overlay_defausse .choix_joueur').click(function(){
                  var idJoueur=$(this).attr('id');
                  $('.overlay_defausse').hide();
                  effetBaron(idJoueur).done(function(data){
                    $('.overlay_defausse').show();
                    $('.overlay_defausse').empty();
                    $('.overlay_defausse').append('<h6>Choisis une carte</h6>');
                    $('.overlay_defausse').append(data);
                    if($('.overlay_defausse .carte').length==0){
                      jouer(id_card_selected);
                      $('.overlay_defausse').hide();
                    }
                    $('.overlay_defausse .carte').click(function(){
                      var idCarte=$(this).attr('id');
                      $('.overlay_defausse').hide();
                      completeEffetBaron(idJoueur,idCarte);
                      jouer(id_card_selected);
                    });
                });
              });
        });
                break;
        case "4": jouer(id_card_selected);
                break;
        case "5": choixJoueur().done(function(data){
                $('.overlay_defausse').show();
                $('.overlay_defausse').empty();
                $('.overlay_defausse').append('<h6>Choisis un joueur</h6>');
                $('.overlay_defausse').append(data);
                $('.overlay_defausse').append('<button id="'+$('.terrain_user').attr('id')+'" class="btn waves-effect waves-light grey darken-1 choix_joueur" type="button" name="action" action="javascript:void(0)">Vous</button>');
                $('.overlay_defausse .choix_joueur').click(function(){
                  var idJoueur=$(this).attr('id');
                  $('.overlay_defausse').hide();
                  jouer(id_card_selected).done(function(){
                    effetPrince(idJoueur);
                  });
              });
        });
                break;
        case "6":choixJoueur().done(function(data){
                $('.overlay_defausse').show();
                $('.overlay_defausse').empty();
                $('.overlay_defausse').append('<h6>Choisis un joueur</h6>');
                $('.overlay_defausse').append(data);
                $('.overlay_defausse .choix_joueur').click(function(){
                  var idJoueur=$(this).attr('id');
                  $('.overlay_defausse').hide();
                  jouer(id_card_selected).done(function(){
                    effetRoi(idJoueur);
                  });
              });
        });
                break;
        default: jouer(id_card_selected);
                break;
      }
    }
  });

  var refresh = setInterval(function(){
    affichageEtat();
  }, 2000);
});

function affichageEtat(){
  var url_getEtat = 'http://'+window.location.host+'/loveletters/projet/partie/getEtat';
  $.ajax({ url: url_getEtat,
           data: {},
           type: 'post',
           success: function(data){
             var etat_tmp = data;
             switch(data){
               case 'jouer': $('#jouer').show();
                             $('#pioche').hide();
                            break;
               case 'fin':   $('#jouer').hide();
                             $('#pioche').hide();
                            break;
               case 'pioche':$('#jouer').hide();
                             $('#pioche').show();
                            break;
             }
             affichageScores();
             affichageTerrains();
             affichageMainsJoueurs();
             if(etat!=etat_tmp){
               affichageMain();
             }
             etat=etat_tmp;
           }
  });
}

function affichageMain(){
  id_card_selected = null;
  var url_getMain = 'http://'+window.location.host+'/loveletters/projet/partie/main';
  $.ajax({ url: url_getMain,
           data: {},
           type: 'post',
           success: function(data){
             $('.main_user').empty();
             $('.main_user').append(data);
             if($('.main_user > .carte').length==1){
               $('.main_user > .carte').hover(
                 function(){
                   $(this).css('margin-top','-200px');
                 },

                 function(){
                   $(this).css('margin-top','0px');
                 }
               );
             }
             $('.main_user > .carte').click(function(){
               $('.main_user > .carte').removeClass('selected');
               $(this).addClass('selected');
               id_card_selected = $(this).attr('id');
             });
           }
  });
}

function affichageMainsJoueurs(){
  var url_getMainsJoueurs = 'http://'+window.location.host+'/loveletters/projet/partie/getMainsJoueurs';
  $.ajax({ url: url_getMainsJoueurs,
           data: {},
           type: 'post',
           dataType: "json",
           success: function(data){
             jQuery.each(data, function(id, nbCartes){
               $('#'+id+'.main').empty();
               for(var i = 0; i<nbCartes;i++){
                 var url_image = "../img/dos.jpg";
                  $('#'+id+'.main').append('<div class="carte"></div>');
                  $('#'+id+'.main .carte').css('background-image', 'url(' + url_image + ')');
               }
             });
           }
  });
}

function affichageTerrains(){
  $('.terrain').each(function(){
    var id = $(this).attr('id');
    var url_terrain = 'http://'+window.location.host+'/loveletters/projet/partie/terrain/'+$(this).attr('id');
    $.ajax({ url: url_terrain,
             data: {
               id: id
             },
             type: 'post',
             success: function(data){
               $('#'+id+'.terrain').empty();
               $('#'+id+'.terrain').append(data);
             }
    });
  });

}

function affichageDefausse(idJoueur = null){
  if(idJoueur!=null){
    var url_defausse = 'http://'+window.location.host+'/loveletters/projet/partie/affichageDefausse/'+idJoueur;
    $.ajax({ url: url_defausse,
             data: {},
             type: 'post',
             success: function(data){
               $('.overlay_defausse').show();
               $('.overlay_defausse').empty();
               $('.overlay_defausse').append('<button id="close_overlay" class="btn waves-effect waves-light grey darken-1" type="button" name="action" action="javascript:void(0)">Fermer</button>');
               $('.overlay_defausse').append(data);
               $('#close_overlay').click(function(){
                $('.overlay_defausse').hide();
               });
             }
    });
  }else{
    var url_defausse = 'http://'+window.location.host+'/loveletters/projet/partie/affichageDefausse';
    $.ajax({ url: url_defausse,
             data: {},
             type: 'post',
             success: function(data){
               $('.overlay_defausse').show();
               $('.overlay_defausse').empty();
               $('.overlay_defausse').append('<button id="close_overlay" class="btn waves-effect waves-light grey darken-1" type="button" name="action" action="javascript:void(0)">Fermer</button>');
               $('.overlay_defausse').append(data);
               $('#close_overlay').click(function(){
                $('.overlay_defausse').hide();
               });
             }
    });
  }
}

function pioche(){
  var url_pioche = 'http://'+window.location.host+'/loveletters/projet/partie/pioche';
  $.ajax({ url: url_pioche,
           data: {},
           type: 'post',
           success: function(data){
             affichageMain();
             affichageEtat();
           }
  });
}

function jouer(id){
  var url_jouer = 'http://'+window.location.host+'/loveletters/projet/partie/jouer/'+id;
  return $.ajax({ url: url_jouer,
           data: {},
           type: 'post',
           success: function(data){
             affichageMain();
             affichageTerrains();
             affichageMainsJoueurs();
             affichageEtat();
           }
  });
}

function affichageScores(){
  var url_getScores = 'http://'+window.location.host+'/loveletters/projet/partie/getScores';
  $.ajax({ url: url_getScores,
           data: {},
           dataType: "json",
           type: 'post',
           success: function(data){
             var b = false;
             jQuery.each(data, function(id, score){
               var previous_score=$('#'+id+'.score').text();
               $('#'+id+'.score').empty();
               $('#'+id+'.score').append(score);
               if(previous_score!=score){
                 b = true;
               }
             });
             if(b){
               affichageMain();
             }
           }
  });
}

function choixJoueur(){
  var url_choixJoueur = 'http://'+window.location.host+'/loveletters/projet/partie/choixJoueur';
  return $.ajax({ url: url_choixJoueur,
           data: {},
           type: 'post'
  });
}

function effetGarde(){
  var url_effetGarde = 'http://'+window.location.host+'/loveletters/projet/partie/effetGarde';
  return $.ajax({ url: url_effetGarde,
           data: {},
           type: 'post'
  });
}

function completeEffetGarde(idJoueur, idCarte){
  var url_verifCarteJoueur = 'http://'+window.location.host+'/loveletters/projet/partie/verifCarteJoueur/'+idJoueur+'/'+idCarte;
  $.ajax({ url: url_verifCarteJoueur,
           data: {},
           type: 'post'
  });
}

function effetPretre(idJoueur){
  var idCarte;
  var url_effetPretre = 'http://'+window.location.host+'/loveletters/projet/partie/effetPretre/'+idJoueur;
  $.ajax({ url: url_effetPretre,
           data: {},
           type: 'post',
           success: function(data){
             $('.overlay_defausse').show();
             $('.overlay_defausse').empty();
             $('.overlay_defausse').append('<button id="close_overlay" class="btn waves-effect waves-light grey darken-1" type="button" name="action" action="javascript:void(0)">Fermer</button>');
             $('.overlay_defausse').append(data);
             $('#close_overlay').click(function(){
              $('.overlay_defausse').hide();
             });
           }
  });
}

function effetBaron(idJoueur){
  var url_effetBaron = 'http://'+window.location.host+'/loveletters/projet/partie/effetBaron/'+idJoueur;
  return $.ajax({ url: url_effetBaron,
           data: {},
           type: 'post'
  });
}

function effetPrince(idJoueur){
  var url_effetPrince = 'http://'+window.location.host+'/loveletters/projet/partie/effetPrince/'+idJoueur;
  $.ajax({ url: url_effetPrince,
           data: {},
           type: 'post'
  });
}

function effetRoi(idJoueur){
  var url_effetRoi = 'http://'+window.location.host+'/loveletters/projet/partie/effetRoi/'+idJoueur;
  $.ajax({ url: url_effetRoi,
           data: {},
           type: 'post'
  });
}

function completeEffetBaron(idJoueur, idCarte){
  var url_completeEffetBaron = 'http://'+window.location.host+'/loveletters/projet/partie/completeEffetBaron/'+idJoueur+'/'+idCarte;
  return $.ajax({ url: url_completeEffetBaron,
           data: {},
           type: 'post'
  });
}
