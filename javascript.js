
let stream;
$('#file').on('change', function (e) {
    var ext = $(this).val().split(".").pop().toLowerCase(); //csv
    if (e.target.files != undefined) {
        var reader = new FileReader();
        reader.onload = function (e) {
            var csvval = e.target.result.split("\n");
            stream = e.target.result;
            successFunction(csvval);

        };
        reader.readAsText(e.target.files.item(0));
    }
    return false;
});

function successFunction(allRows) {
    //var allRows = data.split(/\r?\n|\r/); thead
    $('#exampleModal .thead').empty();
    $('#exampleModal .tbody').empty();
    var tr = '<tr>';
    for (var singleRow = 0; singleRow < 1; singleRow++) {
        var headers  = allRows[singleRow].split(',"');
        for(var i = 0; i <headers.length; i++){
            tr +=' <th scope="col"><div class="cols">'+headers[i]+'</div></th>'
        }
    }
    tr += '<tr>';
    $('#exampleModal .thead').append(tr);

    tr = '';

  
    for (var rows = 1; rows < 11; rows++) {
        tr += '<tr>';
        var contents  = allRows[rows].split(',"');
        for(var i = 0; i <contents.length; i++){
            tr +=' <td scope="col"><div class="cols">'+contents[i]+'</div></td>'
        }
        tr += '<tr>';
    }
   
    $('#exampleModal .tbody').append(tr);

    $('#exampleModal').modal('show');
  }

  function blobToFile(theBlob, fileName){
    var b = theBlob;
    b.lastModifiedDate = new Date();
    b.name = fileName;
    b.type = 'text/csv'
    //Cast to a File() type
    return theBlob;
  }


  $('#save').click(function(){
    var formData = new FormData();
    var file = $('#file')[0].files;
   
    if(file.length > 0 ){
      
       

        let fileName = new Date().getTime() + '.csv';
        
        var blob = new Blob([stream], {
            encoding: "UTF-8",
            type: "text/csv;charset=UTF-8"
        });

        var blobFile = blobToFile(blob, fileName);
        //console.log(blobFile);

        var reader = new FileReader();
        reader.addEventListener("loadend", function(e) {
         //console.log( e.target.result)
        });
        reader.readAsText(blobFile);
        //var myFile = new File([blob], fileName);

        formData.append('file', blobFile);

        $.ajax({
            url: 'sheet.php',
            type: 'POST',
            data: formData,
            cache: false,
            processData: false, 
            contentType: false,  
            success: function(response){
                console.log(response);
                if(response == 1){
                    alert('archivo  subido');
                }else{
                // alert('archivo no subido');
                }
            },
            error: function(error) {
                console.log(error);
            },
            complete: function() {
            }
        });
    }else{
       alert("Por favor seleccione un archivo.");
    }
    
  });


/*
    var jsonObj = [];
    var headers  = csvval[0].split(",");
    for(var i = 1; i <csvval.length; i++){
        var data = csvval[i].split(',');
        var obj = {};
        for(var j = 0; j < data.length; j++) {
            obj[headers[j].trim()] = data[j].trim();
        }
        jsonObj.push(obj);
        
    }
    var jsonData = JSON.stringify(jsonObj);
    addDataToTable(jsonData);

*/