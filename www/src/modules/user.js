import * as modal from './modal.js';

var inputToField = [  // @var tableau des champs de la table
  "id","sEmail","sNom","sPrenom","sAdresse","sCodePostal",
  "sVille","sTelephone","sTelMobile","sLogin","dateIns"];

/**
 * Aiguillage des demandes 
 * 
 * @param {string} action 
 * @param {string} params 
 */
export function user(action,params)
{
  if( action == undefined ){
    // appel sans paramètre, depuis le menu
    editUsers();
    return false;
  }
  switch(action)
  {
    case 'login':
      loadContent('Login');
      break; 
    
    case 'logout':
      $('#content').load('/templates/logout.smarty')
      break;

    case 'quit':
      document.getElementById('cnx-info').dataset.connected='0';
      $.ajax({
        type: 'GET',
        url: 'ajax/logout.php',
        success: function (msg) {
          setTimeout(function () {
            window.location.href = '/index.php';
          }, 500);    
        }
      });
      break;

    case 'changePass':
      changePass();
      break;  

    case 'auth':
      // Connexion au site
      authenticate();
      break;

    case 'add':
      user('edit',0);
      break;

    case 'save':
      saveUser(); 
      break;

    case 'del':
      delUser(params);
      break;

    case 'uniqueEmail':
      uniqueEmail(params);
      break;

    case 'edit':
      editUser(params);
      break;

    case 'pwd':
      pwdUser(params);
      break;

    case 'Password':
      initPassword();
      break;

    default:
      alert("Non implémenté "+action+" "+params);
  }
}

/**
 * editUsers
 * 
 * Fonction appelée par le menu d'édition des utilisateurs
 */
function editUsers() {
  modal.createModal("content", "user-manage", "Gestion des utilisateurs","modal-xl");
  $("#user-manage-content").addClass('myModal-body');
  var Btns=[{
    Label:    "Quitter",
    Func:     "",
    Action:   "",
    Glyph:    "log-out",
    type:     "secondary"
  }];
  modal.addButtons("user-manage",Btns);
  refreshUser();
  $('#user-manage').modal('show');
}

/**
 * Edition d'un utilisateur
 * 
 * @param {int} id 
 */
function editUser(id)
{
  var Btns=[{
    Label:    "Enregistrer",
    Func:     "user",
    Action:   "save",
    Glyph:    "ok",
    type:     "primary"
  },{  
    Label:    "Quitter",
    Func:     "",
    Action:   "",
    Glyph:    "log-out",
    type:     "secondary"
  }];
  modal.createModal("user-manage", "user-edit", "Edition utilisateur","modal-lg");
  modal.addButtons("user-edit",Btns);
  $('#user-edit-content').load("/tools/templates/userEdit.smarty", function () {
    loadUser(id);
    $('#user-edit').modal('show');
  });
}

function refreshUser()
{
  data = {
    Action: "User",
    Want: "listUsers",
    Vars: ""
  };
  $.post('/ajax/index.php', data, function (resp) {
    $("#user-manage-content").html(resp.html);
  });
}

/**
 * authenticate
 * 
 * Authentification d'un utilisateur par appel Ajax
 * 
 * @param {void}
 */
function authenticate() {
  var login = $("#login").val();
  var passw = $("#password").val();
  if (login == "" || passw == "") {
    modal.alertBox('danger', 'Vous devez saisir un login et un mot de passe !', 'ERREUR');
    return false;
  }
  var sVars='{"login":"'+login+'","passw":"'+passw+'"}';
  data = {
    Action:   "User",
    Want:     "Login",
    Vars:     sVars
  };
  $.post("/ajax/index.php", data,function (resp) {
      if (resp.Errno == -1) {
        modal.alertBox("danger",resp.ErrMsg,'ERREUR');
        return false;
      }
      console.log("Connexion OK");
      var cnx = document.getElementById('cnx-info');
      cnx.innerHTML = "<span class='glyphicon glyphicon-user'></span> " + resp.User.Prenom + " " + resp.User.Nom;
      cnx.dataset.connected=1;
      cnx.onclick = function() {
        user('logout','');
      };
      // A VOIR Si membre du groupe admin, il faut charger tools.js ...

      // recharge la page d'accueil
      loadContent("Main");
      // recharger le menu
      loadMenu();
    });
}

/**
 * loadUser 
 * 
 * charge les données du formulaire avec les données de l'utilisateur en base
 * @param {int}   id    identifiant de l'utilisateur
 */
function loadUser(id)
{
  if( id == 0 ){
    return true;
  }
  data = {
    Action:   "User",
    Want:     "loadUserId",
    Vars:     id
  }
  $.post("/ajax/index.php", data, function (resp) {
    if (resp.Errno != 0) {
      alert(resp.ErrMsg);
      return false;
    }
    // Remplacer les données du formulaire avec celles de la table
    inputToField.forEach(function (item) {
      document.getElementById(item).value = resp.User[item];
    });
    // gestion des droits
    resp.Rights.forEach(function (right) {
      var Div = document.createElement('div');
      var input = document.createElement('input');
      var label = document.createElement('label');
      input.value = right.idRight;
      input.type = 'checkbox';
      input.name = 'rights';
      input.checked = right.gotRight;
      Div.appendChild(input);
      label.setAttribute("for", right.idRight);
      label.innerText = right.sLabel;
      Div.appendChild(label);
      document.getElementById('checkRights').appendChild(Div);
    });
  });
}

function saveUser()
{
  // Test des champs obligatoires
  var mail = document.getElementById('sEmail').value;
  if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(mail)) {
    // OK return (true)
  }else{
    $('sEmail').addClass('required');
    alert("Le mail est incorrect !")
    return false;
  }
  if( mail == "" ){
    $('sEmail').addClass('required');
    alert("Le mail est obligatoire !")
    return false;
  }
  $('sEmail').removeClass('required');
  var nom = document.getElementById('sNom').value;
  if( nom == ""){
    $('sNom').addClass('required');
    alert("Le Nom est obligatoire !")
    return false;
  }
  $('sNom').removeClass('required');
  // {"User":{"id":1,"sNom":"Noel"},"Rights":{"1","3","7"}}
  var data = '{"User":{';
  // Remplacer les données du formulaire avec celles de la table
  inputToField.forEach(function (item) {
    if( item != "dateIns"){
      data += '"'+ item +'":"' + $('#' + item).val() + '",';
    }
  });
  // La dernière virgule est en trop
  data = data.substring(0, data.length - 1);
  data += '},"Rights":[';
  // Récupérer les droits
  var exist = false;
  $('#checkRights input:checked').each(function () {
    data += '"' + $(this).val() + '",';
    exist = true;
  });
  if (exist) {
    data = data.substring(0, data.length - 1);
  }
  data += "]}";
  // Envoi au backend
  var send = {
    Action:  "User",
    Want:    "save",
    Vars:    data
  };
  $.post('/ajax/index.php',send,function(resp){
    if( resp.Errno != 0 ){
      alert(resp.ErrMsg);
    }
    refreshUser();
    $('#user-edit').modal('hide');
  });
}

/**
 * uniqueEmail
 * 
 * Validation de l'unicité d'un email dans la base
 * @param {string} email 
 */
function uniqueEmail(email)
{
  // Vérifions que c'est un email
  if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(email)) {
    // OK return (true)
  } else {
    alert("Ce n'est pas un email valide !");
    document.getElementById('sEmail').value="";
    $('#sEmail').focus();
    return false;
  }
  // Vérification que l'email est unique, appelé lors 
  // d'un changement du champs Email
  data = {
    Action:   "User",
    Want:     "testEmail",
    Vars:     email
  };
  $.post("/ajax/index.php", data, function (resp) {
    if (resp.Errno != 0) {
      if (confirm(resp.ErrMsg)) {
        loadUser(resp.id);
      } else {
        $('#sEmail').val('');
        $('#sEmail').focus();
      }
      return false;
    }
    if ($('#sLogin').val() == "") {
      $('#sLogin').val($('#sEmail').val());
    }
  });
  return true;
}

function delUser(id)
{
  var el = document.getElementById('user-'+id);
  var Msg = "Effacer "+el.cells[0].innerText+" "+el.cells[1].innerText+" ?";
  if (confirm(Msg))
    // Effacer
    data = {
      Action:  "User",
      Want:    "delUser",
      Vars:    id
    };
  console.log(data);
  $.post('/ajax/index.php', data, function(resp){
    if( resp.Errno != 0){
      alert(resp.ErrMsg);
    }
    refreshUser();
  });
}

function pwdUser(id)
{
  var el = document.getElementById('user-'+id);
  var Msg = "Nouveau mot de passe pour "+el.cells[0].innerText+" "+el.cells[1].innerText+" ?";
  var passwd = prompt(Msg);
  var sVars = '{"id":"'+id+'","passwd":"'+passwd+'"}';
  if (passwd) {
    data = {
      Action:   'User',
      Want:     'changePassId',
      Vars:     sVars
    }
    $.post("/ajax/index.php", data);
  }
}

function initPassword()
{
  var login = $('#login').val();
  if( login == ""){
    alert("Vous devez donner votre email ou votre login !");
    return false;
  }
  data = {
    Action:   'User',
    Want:     'resetPass',
    Vars:     login
  }
  $.post("/ajax/index.php", data, function(resp){
    if(resp.Errno != 0){
      $('#ErrBox').removeClass('alert-info');
      $('#ErrBox').addClass('alert-danger');
      $('#ErrTitle').text('Erreur');
      $('#ErrMsg').html(resp.ErrMsg);
      return false;
      }
    $('#ErrBox').removeClass('alert-danger');
    $('#ErrBox').addClass('alert-info');
    $('#ErrTitle').text('Information');
    $('#ErrMsg').html("Votre nouveau mot de passe a été envoyé dans votre boîte aux lettres.");
  });
}

/**
 * ChangePass
 * 
 * Appelée pour un changement de mot de passe
 */
function changePass() {
  if( document.getElementById('chg-pwd').style.display == "none"){
    $('#btn-logout').hide();
    $('#btn-change').text("Changer le mot de passe");
    $("#chg-pwd").fadeIn();
    return false;
  }
  var Msg = "<p>Votre mot de passe est incorrect.</p>";
  Msg += "<p>Il doit : ";
  Msg += "<ul><li>contenir au moins une minuscule</li>";
  Msg += "<li>contenir au moins une majuscule</li>";
  Msg += "<li>contenir au moins un chiffre</li>";
  Msg += "<li>contenir éventuellement un caractère spécial !@#$%^&*()_+-=[]{};:\\|,.<>\/?</li>";
  Msg += "<li>avoir une taille minimale de 8 caractères</li></ul>";
  Msg += "</p><p>Les deux mots de passe doivent coincider</p>";
  Msg += "<p>Ex: <b>Pa$$w0Ord</b> - est un mot de passe compatible, mais, ne l'utilisez pas, il est mondialement connu !</p>";
  if( $('#pass1').val() != $('#pass2').val() ){
    Msg = "<p>Vos mots de passe sont différents !</p>"+Msg;
    modal.alertBox('danger', Msg, 'ERREUR', 20000);
    return false;
  }
  var passwd = $('#pass1').val();
  var score = scorePassword(passwd);
  console.log("Votre score "+score);
  if (score > 80) {
    Msg="<p>Mot de passe fort</p>";
    modal.alertBox('info', Msg, 'Information', 2000);
  }
  if (score < 30){
    Msg = "<p>Votre mot de passe n'est pas suffisement complexe</p>"+Msg;
    modal.alertBox('danger', Msg, 'ERREUR', 20000);
    return false;
  }

  data = {
    Action:   'User',
    Want:     'changePass',
    Vars:      passwd
  }
  $.post("/ajax/index.php", data, function (resp) {
    if (resp.Errno != 0) {
      modal.alertBox('danger', resp.ErrMsg, 'ERREUR');
    } else {
      modal.alertBox('success', resp.ErrMsg, 'INFO');
    }
  });
}

/**
 * donne un valeur en fonction de la robustesse du mot de passe
 * @param {string} pass 
 */
function scorePassword(pass) 
{
  var score = 0;
  if (!pass)
      return score;

  // award every unique letter until 5 repetitions
  var letters = new Object();
  for (var i=0; i<pass.length; i++) {
      letters[pass[i]] = (letters[pass[i]] || 0) + 1;
      score += 5.0 / letters[pass[i]];
  }

  // bonus points for mixing it up
  var variations = {
      digits: /\d/.test(pass),
      lower: /[a-z]/.test(pass),
      upper: /[A-Z]/.test(pass),
      nonWords: /\W/.test(pass),
  }

  var variationCount = 0;
  for (var check in variations) {
      variationCount += (variations[check] == true) ? 1 : 0;
  }
  score += (variationCount - 1) * 10;

  return parseInt(score);
}


