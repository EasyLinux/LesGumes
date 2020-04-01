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

function editRights()
{
  var data = "{}";
  $('#holder').load('/tools/templates/popup.tmpl',data,function (){
    $('#pop-title').text('Edition des droits');
    $('#pop-all').modal('show');
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