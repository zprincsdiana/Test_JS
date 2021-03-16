<?php
header("Content-Type: text/html; charset=utf-8");


ini_set("default_charset","UTF-8");

$fileName = $_FILES['file']['name'];
$fileType = $_FILES['file']['type'];
$fileError = $_FILES['file']['error'];
$fileContent = file_get_contents($_FILES['file']['tmp_name']);

if($fileError == UPLOAD_ERR_OK){
   //Processes your file here
   //echo $fileType;
   if (($_FILES["file"]["type"] == "text/csv")) {
        
        //print_r($fileContent);
        $filename =uniqid().'.csv';
        $location = "upload/".$filename;
 
        $f = file_put_contents($location,iconv("UTF-8", "ISO-8859-1//TRANSLIT", $fileContent)); //, FILE_APPEND | LOCK_EX
    
        // if (move_uploaded_file(($_FILES['file']['tmp_name']),($location))) {
        //$moveResult = move_uploaded_file( $_FILES['file']['tmp_name'],$location);
   
        if (file_exists($location)) {
            //more code here...
            //print_r($filename);
            $spreet=new SpreetSheet($filename,$fileContent);
            $spreet->csv();

            echo 1;
        } else {
            echo 0;
        }
    } else {
        echo 0;
    }
}else{
   switch($fileError){
     case UPLOAD_ERR_INI_SIZE:   
          $message = 'Error al intentar subir un archivo que excede el tamaño permitido.';
          break;
     case UPLOAD_ERR_FORM_SIZE:  
          $message = 'Error al intentar subir un archivo que excede el tamaño permitido.';
          break;
     case UPLOAD_ERR_PARTIAL:    
          $message = 'Error: no terminó la acción de subir el archivo.';
          break;
     case UPLOAD_ERR_NO_FILE:    
          $message = 'Error: ningún archivo fue subido.';
          break;
     case UPLOAD_ERR_NO_TMP_DIR: 
          $message = 'Error: servidor no configurado para carga de archivos.';
          break;
     case UPLOAD_ERR_CANT_WRITE: 
          $message= 'Error: posible falla al grabar el archivo.';
          break;
     case  UPLOAD_ERR_EXTENSION: 
          $message = 'Error: carga de archivo no completada.';
          break;
     default: $message = 'Error: carga de archivo no completada.';
              break;
    }
      echo json_encode(array(
               'error' => true,
               'message' => $message
            ));
}

class SpreetSheet {
    public $fileName;
    public $fileContent;
    public function __construct(string $fileName, string $fileContent) {
        $this->fileName = $fileName;
        $this->fileContent = $fileContent;
    }

    function csv() {
        $inputFileName = $this->fileName;
        $inputFileType = 'Csv';
        $ruta = './upload/'.$inputFileName;
       
        $fileGetContent = str_getcsv($this->fileContent, "\n"); 

        if(count($fileGetContent)>0){
            $fileGetContentArray= array_values($fileGetContent);
            

            echo '<h3>Worksheet Information</h3>'. PHP_EOL;
            echo '<ol>'. PHP_EOL;
                echo '<li>', '', '<br />'. PHP_EOL;
                echo 'Rows: ', count($fileGetContentArray),
                    ' Columns: ', count(explode(",", $fileGetContentArray[0])), '<br />'. PHP_EOL;
             
                echo '</li>'. PHP_EOL;
            echo '</ol>'. PHP_EOL;



            //header
            print_r('<p>Cabecera Inicio</p>'. PHP_EOL);
            $headers=explode(",", $fileGetContentArray[0]);
            for ($i=0; $i < count($headers); $i++) { 
                print_r($headers[$i] . '<br>'. PHP_EOL);
            }
            print_r('<p>Cabecera fin</p>'. PHP_EOL);

            print_r('<p>Contenido inicio</p>'. PHP_EOL);//colas
            for ($i=1; $i < 5; $i++) { 
                $contents=explode(",", $fileGetContentArray[$i]);
                for ($j=0; $j < count($contents); $j++) { 
                    print_r($contents[$j]. '<br>'. PHP_EOL);
                }
            }
            print_r('<p>Contenido fin</p>'. PHP_EOL);

        }
     
    }
}

/*
php.ini
memory_limit=10G
max_execution_time=12000
post_max_size=10G
upload_max_filesize=10G
*/
