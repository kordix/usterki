<?php

session_start();



if(!isset($_SESSION['zalogowany'])){
  echo 'NIEZALOGOWANY';
    return;
}





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
    $upload_dir = "../uploads/" . $folder . '/';
    @$filename = $_FILES['file']['name'];
    $uploadOk = 1;

    $check = getimagesize($_FILES["file"]["tmp_name"]);
    if ($check !== false) {
        // echo "Jest obrazek - " . $check["mime"] . ".";
        $uploadOk = 1;

        $uploadedFileExtension = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);

  

    } else {
        // echo "Plik nie jest obrazkiem. Mam nadzieję że nie chcesz przesłać jakiegoś syfu";

        $uploadedFileExtension = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);

        $allowedExtensions = array('txt', 'doc', 'docx','pdf','xls','xlsx');

           

        if (in_array($uploadedFileExtension, $allowedExtensions)) {

        } else {

            
            echo('{"message":"Niedopuszczalne rozszerzenie. Dopuszczalne tylko obrazki oraz txt, doc, docx,pdf,xls,xlsx"}');


            $uploadOk = 0;

        }

    }
    // Set the target path for the uploaded file
    @$target_path = $upload_dir . $filename;






    if($uploadOk) {
        sleep(2);
       


        $filename = generujLosowaNazwe().'.'.$uploadedFileExtension;
        // Move the uploaded file to the target path
        
        $baseDir = $_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR.'uploads'.DIRECTORY_SEPARATOR.$filename;

        if (move_uploaded_file($file, $baseDir)) {
            // File was successfully uploaded

            
            echo('{"message":"Pomyślnie dodano załącznik"}');


            $dane = new stdClass();
            $dane->description = $_POST['description'];
            $dane->filename = $filename;
            
            $dane->usterka_id = $_POST['usterka_id'];
            $dane->user_id = $_SESSION['id'];



            require_once($_SERVER["DOCUMENT_ROOT"].'/api/addfile.php');




        } else {
            // Error uploading file
            echo "Error uploading file". $_FILES['file']['error'];
        }



    }
}
