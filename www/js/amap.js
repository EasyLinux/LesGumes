$().ready(function () {
  loadContent('Main');

  // Lire le cookie de l'avertissement RGPD
  if( getCookie('cookieOk') != 'true' ){
    // Pas présent affiche le bandeau 
    setTimeout(function () {
      $("#cookieConsent").fadeIn(200);
      }, 500);
    // Acceptation par l'utilisateur, on place un cookie
    $("#closeCookieConsent, .cookieConsentOK").click(function() {
      $("#cookieConsent").fadeOut(200);
      setCookie('cookieOk','true',30);
    }); 
   } else {
     // Déjà présent, on le prolonge
     setCookie('cookieOk','true',30);
  }
}); 

function setCookie(cname, cvalue, exdays) {
  var d = new Date();
  d.setTime(d.getTime() + (exdays*24*60*60*1000));
  var expires = "expires="+ d.toUTCString();
  document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

function getCookie(cname) {
  var name = cname + "=";
  var decodedCookie = decodeURIComponent(document.cookie);
  var ca = decodedCookie.split(';');
  for(var i = 0; i <ca.length; i++) {
    var c = ca[i];
    while (c.charAt(0) == ' ') {
      c = c.substring(1);
    }
    if (c.indexOf(name) == 0) {
      return c.substring(name.length, c.length);
    }
  }
  return "";
}



// Amap special code 
$(function () {
  $.loadScript = function (url, callback) {
    jQuery.ajax({
      url: url,
      dataType: 'script',
      success: callback,
      async: true
    });
  }
});

/**
 * loadContent
 * 
 * Charge un élément et agit en fonction
 *   si   .php  -> renvoi sur l'ancien fonctionnement
 *   si   .pdf  -> ouvre une nouvelle page avec le pdf
 *   enfin ouvre le contenu dans l'élément désigné par 'id=content'
 * 
 * @param {string} content 
 */
function loadContent(content) {
  // si # on ignore
  if (content == '#') {
    return false;
  }

  if (content.indexOf(".") == -1) {
    // Pas de . dans la chaine -> appel Ajax
    data = {
      Action: "Content",
      Content: content
    };
    $.post("/ajax/index.php", data, function (data) {
      $("#content").html(data);
    });
    return true;
  }
  if (content.indexOf(".php") != -1) {
//    $("#content").load(content);
    console.log("Ouvrir: "+content);
    location.replace(content);
    return true;
  }
  if (content.indexOf(".pdf") != -1) {
    window.open(content, "_blank");
    return true;
  }
  if (content.indexOf(".js") > 0) {
    // Chargement d'une fonction Javascript, 
    eval(content.replace(".js", "") + "()");
    return true;
  }

}



/**
 * authenticate
 * 
 * Authentification d'un utilisateur par appel Ajax
 * 
 * @param {void}
 */
function authenticate() {
alert("Dans authenticate de amap.js");


  var login = $("#login").val();
  var passw = $("#password").val();
  if (login == "" || passw == "") {
    alertBox('danger', 'Vous devez saisir un login et un mot de passe !', 'ERREUR');
    return false;
  }
  data = {
    Action: "Login",
    Login: login,
    Passwd: passw
  };
  $.post("/ajax/index.php", data,
    function (resp) {
      if (resp.Errno == -1) {
        alertBox("danger",resp.ErrMsg,'ERREUR');
      }
      else {
        $("#btn-primary").removeClass("btn-primary");
        $("#btn-primary").addClass("btn-success");
        $("#btn-primary").html(" <span class='glyphicon glyphicon-user'></span> " + resp.User.Prenom + " " + resp.User.Nom);
        document.getElementById("btn-primary").dataset.connected="1";
        // // TODO charger en fonction des droits
        // Si membre du groupe admin, il faut charger tools.js ...
        //$.loadScript("/js/tools.js");

        // recharge la page d'accueil
        loadContent("Main");
        // recharger le menu
        loadMenu();
      }
    });
}

/**
 * passwordLost
 * 
 * fonction appelée en cas d'appui sur mot de passe oublié
 *
 */
function passwordLost() {
  alert("Dans passwordLost de Amap.js");
  var login = $("#login").val();
  if (login == "") {
    alertBox('danger', 'Vous devez saisir un login ou un eMail', 'ERREUR');
    return false;
  }
  data = {
    Action: 'doUser',
    Want: 'resetPassword',
    Login: $("#login").val(),
    Passwd: ''
  }
  $.post('/ajax/index.php', data, function (resp) {
    if (resp.Errno != 0) {
      Msg = resp.ErrMsg + " " + $("#login").val();
      alertBox('danger', Msg, "ERREUR");
    } else {
      Msg = 'Un message a été envoyé sur votre boîte mail <b>' + $("#login").val() + '</b>';
      Msg += "<b>NB</b>  Voir l'administrateur TODO  ";
      alertBox('success', Msg, 'INFO');
    }
  });
}

function afficherPopupInformation(message) {
  // crée la division qui sera convertie en popup
  $("#popupinformation").html(message);
  // transforme la division en popup
  var popup = $("#popupinformation").dialog({
    autoOpen: true,
    width: 400,
    dialogClass: 'dialogstyleperso',
    modal: true,
    buttons: [
      {
        text: "OK",
        "class": 'ui-state-information',
        click: function () {
          $(this).dialog("close");
          $('#popupinformation').hide();
        }
      }
    ]
  });

  // ajouter le style à la barre de titre
  // note : on n'utilise pas .dialogClass dans la définition de la boîte de dialogue car mettrait tout le fond en couleur
  $("#popupinformation").prev().addClass('ui-state-information');
  return popup;

}

/**
 * loadMenu
 * 
 * Recharge le menu à gauche pour prendre en compte les nouveaux droits 
 * (après connexion)
 */
function loadMenu() {
  data = {
    Action: 'doMenu',
    Want: 'loadMenu'
  }
  $.post('/ajax/index.php', data, function (resp) {
    $("#leftmenu").html(resp.Html);
  });

}


/**
 * alertBox
 * 
 * Display an alert div 
 * @param {string} sType        type de message <danger|info|success> 
 * @param {string} sMessage     Message à afficher
 * @param {string} sTitre       Titre de la Box
 * @param {int} iDuration       Durée de l'affichage (optionnel 3000)
 */
function alertBox2(sType, sMessage, sTitre, iDuration) {
  if (typeof (iDuration) == 'undefined') {
    iDuration = 3000;
  }
  $("#ErrTitle").text(sTitre);
  $("#ErrMsg").text(sMessage);
  $("#ErrBox").removeClass('alert-success');
  $("#ErrBox").removeClass('alert-danger');
  $("#ErrBox").removeClass('alert-info');
  $("#ErrBox").addClass('alert-' + sType);
  $("#ErrBox").show();
  setTimeout(function () {
    $("#ErrBox").fadeOut(1000);
  }, iDuration);
}