$().ready(function(){
    loadContent('Main');

});

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
function loadContent(content)
{
    if( content.indexOf(".") == -1 ){
        // Pas de . dans la chaine -> appel Ajax
        // Case where Ajax must be called
        data = {
            Action: "Content",
            Content: content
        };
        $.post("/ajax/index.php",data, function(data){
            $("#content").html(data);
        });
        return true;
    }
    if( content.indexOf(".php") != -1 ) {
        window.location.reload(content);
        return true;
    }
    if( content.indexOf(".pdf") != -1) {
        window.open(content); 
        return true;
    }
    if( content.indexOf(".tmpl") != -1) {
        $("#content").load(content); 
        return true;
    }

    if( content.indexOf(".js") != -1) {
        // Chargement d'une fonction Javascript, 
        eval(content.replace(".js","") + "()");      
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
function authenticate()
{
    var login = $("#login").val();
    var passw = $("#password").val();
    if( login == "" || passw == "" ){
        $("#ErrMsg").text('Vous devez saisir un login et un mot de passe !');
        $("#ErrBox").addClass('alert-danger');
        $('#ErrTitle').text('Erreur');
        $("#ErrMsg").removeClass('alert-success');
        $("#ErrBox").show();
        setTimeout(function(){
            $("#ErrBox").fadeOut(1000);
        },3000);  
        return false;  
    }
    data = {
        Action: "Login",
        login: login,
        passw: passw
    };
    $.post("/ajax/index.php",data,
        function(data, status){
            if( data.Errno == -1)
            {
                $("#ErrMsg").text(data.ErrMsg);
                $("#ErrBox").show();
                setTimeout(function(){
                    $("#ErrBox").fadeOut(1000);
                },3000);  
            }
            else {
                $("#btn-info").removeClass("btn-primary");
                $("#btn-info").addClass("btn-success");
                $("#btn-info").text("Connecté en tant que " + data.User.Prenom + " " + data.User.Nom);
                $("#btn-info").attr("onclick","loadContent('ChgPwd'); return false;");
                // recharge la page d'accueil
                loadContent("Main");
            }
        });
    //alert("Authentification en cours");

}

function passwordLost()
{
    var login = $("#login").val();
    if( login == ""){
        $("#ErrMsg").text('Vous devez saisir un login');
        $("#ErrBox").show();
        setTimeout(function(){
            $("#ErrBox").fadeOut(1000);
        },3000);  
        return false;  
    }
    //alert("mot de passe oublié");
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