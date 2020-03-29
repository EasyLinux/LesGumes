// Amap special code 


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
    console.log(content);
    if( content.indexOf(".php") != -1 ) {
        window.location.reload(content);
    } else {
        if( content.indexOf(".pdf") != -1) {
            window.open(content); 
        }
        else {
            $('#content').load(content +".tmpl");
        }
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
        $("#ErrMsg").text('Vous devez saisir un login et un mot de passe');
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
                alert('OK');
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