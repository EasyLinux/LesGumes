$().ready(function () {
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
function loadContent(content) {
    if (content.indexOf(".") == -1) {
        // Pas de . dans la chaine -> appel Ajax
        // Case where Ajax must be called
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
        window.location.reload(content);
        return true;
    }
    if (content.indexOf(".pdf") != -1) {
        window.open(content, "_blank");
        return true;
    }
    if (content.indexOf(".tmpl") != -1) {
        $("#content").load(content);
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
    var login = $("#login").val();
    var passw = $("#password").val();
    if (login == "" || passw == "") {
        $("#ErrMsg").text('Vous devez saisir un login et un mot de passe !');
        $("#ErrBox").addClass('alert-danger');
        $('#ErrTitle').text('Erreur');
        $("#ErrMsg").removeClass('alert-success');
        $("#ErrBox").show();
        setTimeout(function () {
            $("#ErrBox").fadeOut(1000);
        }, 3000);
        return false;
    }
    data = {
        Action:  "Login",
        Login:   login,
        Passwd:  passw
    };
    $.post("/ajax/index.php", data,
        function (resp) {
            console.log(resp);
            if (resp.Errno == -1) {
                $("#ErrMsg").text(resp.ErrMsg);
                $("#ErrBox").show();
                setTimeout(function () {
                    $("#ErrBox").fadeOut(1000);
                }, 3000);
            }
            else {
                $("#btn-info").removeClass("btn-primary");
                $("#btn-info").addClass("btn-success");
                $("#btn-info").html(" <span class='glyphicon glyphicon-user'></span> " + resp.User.Prenom + " " + resp.User.Nom);
                $("#btn-info").on("click", function(){
                    loadContent('Logout.js');
                    //logout(); 
                    return false;
                });
                // recharge la page d'accueil
                loadContent("Main");
                // recharger le menu
                loadMenu();
            }
        });
    //alert("Authentification en cours");

}

/**
 * passwordLost
 * 
 * fonction appelée en cas d'appui sur mot de passe oublié
 *
 */
function passwordLost() {
    var login = $("#login").val();
    if (login == "") {
        $("#ErrMsg").text('Vous devez saisir un login');
        $("#ErrBox").show();
        setTimeout(function () {
            $("#ErrBox").fadeOut(1000);
        }, 3000);
        return false;
    }
    data = {
        Action:   'doUser',
        Want:     'resetPassword',
        Login:    $("#login").val(),
        Passwd:   ''
    }
    $.post('/ajax/index.php',data, function(resp){
        console.log(resp);
        if( resp.Errno != 0){
            $("#ErrBox").addClass('alert-danger');
            $('#ErrTitle').text('Erreur');
            $("#ErrMsg").removeClass('alert-success');
            $("#ErrMsg").text(resp.ErrMsg + " "+$("#login").val());
        } else {
            $("#ErrBox").addClass('alert-success');
            $('#ErrTitle').text('INFO');
            $("#ErrMsg").removeClass('alert-danger');
            Msg  = 'Un message a été envoyé sur votre boîte mail <b>'+$("#login").val()+'</b>';
            Msg += "<b>NB</b>  Voir l'administrateur TODO  ";
            $("#ErrMsg").html(Msg);
        }
        
        $("#ErrBox").show();
        setTimeout(function () {
            $("#ErrBox").fadeOut(1000);
        }, 3000);

    });
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

/**
 * loadMenu
 * 
 * Recharge le menu à gauche pour prendre en compte les nouveaux droits 
 * (après connexion)
 */
function loadMenu()
{
    console.log("chargement du menu");
    data = {
        Action: 'doMenu',
        Want:   'loadMenu'
    }
    $.post('/ajax/index.php',data,function(resp){
        $("#leftmenu").html(resp.Html);
    });
    
}


function Logout()
{
    $.ajax({
        type: 'GET',
        url: 'ajax/logout.php',
        success: function(msg) {
            //alert('Logout');
            setTimeout(function(){
                window.location.href = '/index.php';
            },500);
            
        }
    });
}

/* =====================================================================================
   =                       Gestion des mots de passe                                   =
   =====================================================================================*/

var pwdOk=false;   

/** Validate
 * 
 * fonction appelée lors de l'appui sur une touche dans la saisie des mots de passe
 * permet de guider l'utilisateur pour obtenir un mot de passe adéquoit
 */
function Validate()
{
    // Le mot de passe doit :  Majuscule, minuscule, Chiffre, 8 char, concorder ff0000 à 00ff00
    var valid = 0;
    curPass1 = $('#pass1').val();
    curPass2 = $('#pass2').val();
    if(curPass1.match( /[0-9]/g)){
        valid += 20;
        $('#num').removeClass("red");
        $('#num').addClass("green");
    } else {
        $('#num').removeClass("green");
        $('#num').addClass("red");
    }
    if(curPass1.match( /[a-z]/g)){
        valid += 20;
        $('#min').removeClass("red");
        $('#min').addClass("green");
    } else {
        $('#min').removeClass("green");
        $('#min').addClass("red");
    }
    if(curPass1.match( /[A-Z]/g)){
        valid += 20;
        $('#maj').removeClass("red");
        $('#maj').addClass("green");
    } else {
        $('#maj').removeClass("green");
        $('#maj').addClass("red");
    }
    if(curPass1.match( /[!@#$%^&*()_+\-=\[\]{};:\|,.<>\/?]/g)){
        valid += 20;
        $('#spec').removeClass("red");
        $('#spec').addClass("green");
    } else {
        $('#spec').removeClass("green");
        $('#spec').addClass("red");
    }
    if(curPass1.match( /['\"']/g)){
        valid = 0;
        $('#spec').removeClass("green");
        $('#spec').addClass("red");
        $("#ErrTitle").text("ERREUR");
        $("#ErrMsg").text("Votre mot de passe contient des caractères interdits (' ou \")");
        $("#ErrBox").removeClass('alert-success');
        $("#ErrBox").addClass('alert-danger');
        $("#ErrBox").show();
        setTimeout(function () {
            $("#ErrBox").fadeOut(1000);
        }, 3000);
    }
    if(curPass1.length > 7){
        valid += 20;
        $('#taille').removeClass("red");
        $('#taille').addClass("green");
    } else {
        $('#taille').removeClass("green");
        $('#taille').addClass("red");
    }
    // raz de l'indicateur de mot de passe valide
    pwdOk = false;  
    if( curPass1 == curPass2 ){
        $("#pass2").removeClass("password");
        $("#pass2").addClass("password-Ok");
        if( valid == 100){
            // les mots de passe coincident et satisfont les règles de complexité.
            pwdOk = true;
        }
    } else {
        if( $("#pass2").hasClass("password-Ok") ){
            $("#pass2").removeClass("password-Ok");
            $("#pass2").addClass("password");
        }
    }

    document.getElementById("myBar").style.width = valid+"%";
    if( valid == 100){
        $("#pass1").removeClass("password");
        $("#pass1").addClass("password-Ok");
    }

    return valid;
}

function pwdFocus()
{   
    // indicateur de qualité de mot de passe visible
    $('.pwd-strengh').css("opacity",1);
    $('.pwd-strengh').removeClass("w-100");
    console.log('Change bar');
}

/**
 * ChgPwd
 * 
 * Appelée pour un changement de mot de passe
 */
function ChgPwd()
{
    $('#content').load("/templates/logout.smarty");
}

/**
 * ChgPwd
 * 
 * Appelée pour un changement de mot de passe
 */
function changePass()
{
    Msg  = "Votre mot de passe est incorrect.\n\n";
    Msg += "Il doit : \n";
    Msg += "   - contenir au moins une minuscule\n";
    Msg += "   - contenir au moins une majuscule\n";
    Msg += "   - contenir au moins un chiffre\n";
    Msg += "   - contenir au moins un caractère spécial !@#$%^&*()_+-=[]{};:\\|,.<>\/?\n";
    Msg += "   - avoir une taille minimale de 8 caractères\n";
    Msg += "\nLes deux mots de passe doivent coincider\n";
    Msg += "Ex: Pa$$w0Ord - est un mot de passe compatible, ne l'utilisez pas, il est mondialement connu !";
    if( !pwdOk ){
        alert(Msg);
        return false;
    }
    data={
        Action: 'doUser',
        Want:   'changePass',
        Login:  '',
        Passwd: $("#pass1").val()
    }
    $.post("/ajax/index.php",data,function(resp){
        console.log(resp.Errno);
        if( resp.Errno != 0){
            $("#ErrTitle").text("ERREUR");
            $("#ErrMsg").text(resp.ErrMsg);
            $("#ErrBox").removeClass('alert-success');
            $("#ErrBox").addClass('alert-danger');
        } else {
            $("#ErrTitle").text("INFO");
            $("#ErrMsg").text(resp.ErrMsg);
            $("#ErrBox").addClass('alert-success');
            $("#ErrBox").removeClass('alert-danger');
        }
        $("#ErrBox").show();
        setTimeout(function () {
            $("#ErrBox").fadeOut(1000);
        }, 50000);

    });
}
