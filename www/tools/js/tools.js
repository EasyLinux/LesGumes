var toolbarType='complete';
var CkEditor;

function insertSQL()
{
    var description = $("#description").val();
    var version = $("#version").val();
    var sql = $("#sql").val();
    data = {
        Action: "SQL",
        Desc: description,
        Version: version,
        SQL: sql
    };
    $.post("/tools/index.php",data,
        function(data, status){
          console.log("Etat: "+ status);
        });
      
}

function getSQL()
{
    var version = $("#version").val();
    data = {
        Action: "getSQL",
        Version: version,
    };
    $.post("/tools/index.php",data,
        function(data, status){
          $("#sql").text(data);
          console.log("Etat: "+ status);
        });
      
}

function editContent()
{
  // TODO a changer
  $('#Popup').modal('show');
}

function getImage()
{
  return prompt("URL image : ");
}