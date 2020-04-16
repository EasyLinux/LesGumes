/**
 * authenticate
 * 
 * Authentification d'un utilisateur par appel Ajax
 * 
 * @param {void}
 */
export function authenticate() {
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
      console.log(resp);
      if (resp.Errno == -1) {
        alertBox("danger",resp.ErrMsg,'ERREUR');
      }
      else {
        $("#btn-info").removeClass("btn-primary");
        $("#btn-info").addClass("btn-success");
        $("#btn-info").html(" <span class='glyphicon glyphicon-user'></span> " + resp.User.Prenom + " " + resp.User.Nom);
        $("#btn-info").on("click", function () {
          loadContent('Logout.js');
          //logout(); 
          return false;
        });
        // TODO charger en fonction des droits
        // Si membre du groupe admin, il faut charger tools.js ...

        // recharge la page d'accueil
        loadContent("Main");
        // recharger le menu
        loadMenu();
      }
    });
}
