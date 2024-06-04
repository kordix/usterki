<?php

session_start();

echo $_SERVER['DOCUMENT_ROOT'];


if(!isset($_SESSION['zalogowany'])){
  //  return;
}



// echo $baseDir;

echo 'fasdfdsa';

function generujLosowaNazwe()
{
    $alfabet = 'abcdfghijklmnopxstwz';
    $dlugosc = 10;
    $nazwa = '';

    // Generowanie losowej nazwy
    for ($i = 0; $i < $dlugosc; $i++) {
        $losowyIndeks = mt_rand(0, strlen($alfabet) - 1);
        $nazwa .= $alfabet[$losowyIndeks];
    }

    return $nazwa;
}


// Get the uploaded file
@$file = $_FILES['file']['tmp_name'];

# @$folder = $_POST['folder'];

$folder = 'upload';


if ($file) {
    echo 'COŚ WIDZ';
    $upload_dir = "../uploads/" . $folder . '/';
    @$filename = $_FILES['file']['name'];
    $uploadOk = 1;

    $check = getimagesize($_FILES["file"]["tmp_name"]);
    if ($check !== false) {
        echo "Jest obrazek - " . $check["mime"] . ".";
        $uploadOk = 1;

        $uploadedFileExtension = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);

    } else {
        echo "Plik nie jest obrazkiem. Mam nadzieję że nie chcesz przesłać jakiegoś syfu";

        $uploadedFileExtension = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);

        $allowedExtensions = array('txt', 'doc', 'docx','pdf');

        if (in_array($uploadedFileExtension, $allowedExtensions)) {
            echo 'plik nie jest obrazkiem ale jest bezpieczny';
        } else {
            echo 'plik nie jest bezpeiczny';

            $uploadOk = 0;

        }

    }
    // Set the target path for the uploaded file
    @$target_path = $upload_dir . $filename;






    if($uploadOk) {
        sleep(2);
        // echo dirname(__FILE__).'..'.DIRECTORY_SEPARATOR.'uploads'.DIRECTORY_SEPARATOR.$filename;
        // echo realpath("C:\Users\BOBKOR.bertrand\Desktop\_kordi\PROJEKTY\bertrandusterki\\");

        // echo realpath(dirname(__FILE__).'..'.DIRECTORY_SEPARATOR.'uploads'.DIRECTORY_SEPARATOR.$filename);
       


        $filename = generujLosowaNazwe().'.'.$uploadedFileExtension;
        // Move the uploaded file to the target path
        
        $baseDir = $_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR.'uploads'.DIRECTORY_SEPARATOR.$filename;

        if (move_uploaded_file($file, $baseDir)) {
            // File was successfully uploaded
            echo "File uploaded successfully: " . $target_path.$filename;

            $dane = new stdClass();
            $dane->description = $_POST['description'];
            $dane->filename = $filename;
            
            $dane->usterka_id = $_POST['usterka_id'];



            require_once($_SERVER["DOCUMENT_ROOT"].'/api/addfile.php');




        } else {
            // Error uploading file
            echo "Error uploading file". $_FILES['file']['error'];
        }



    }
}
