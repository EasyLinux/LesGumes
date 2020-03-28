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