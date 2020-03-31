$(function(){
	// CKFinder.start();
});

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

function ActiveEditor()
{
  ClassicEditor
			.create( document.querySelector( '.editor' ), {
				// myFinder: {
				// 	uploadUrl: '/tools/myFinder.php?Action=Load&type=File&response=json',	
				// },
   
				extraPlugins: [ myCustomUploadAdapterPlugin ],
				toolbar: {
					items: [
            // 'myFinder',
						'heading',
						'|',
						'bold',
						'italic',
						'link',
						'bulletedList',
						'numberedList',
						'|',
						'indent',
						'outdent',
						'|',
						'imageUpload',
						'insertImage',
						'blockQuote',
						'insertTable',
						'mediaEmbed',
						'undo',
						'redo'
					]
				},
				language: 'fr',
				image: {
					toolbar: [
						'imageTextAlternative',
						'imageStyle:full',
						'imageStyle:side'
					]
				},
				table: {
					contentToolbar: [
						'tableColumn',
						'tableRow',
						'mergeTableCells',
						'tableCellProperties',
						'tableProperties'
					]
				},
				licenseKey: '',
				
			} )
			.then( editor => {
				window.editor = editor;

				
			} )
			.catch( error => {
				console.error( error );
      } );
      
}
 