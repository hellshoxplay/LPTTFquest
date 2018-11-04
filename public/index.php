<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

    <title>Hello, world!</title>
</head>
<body>
<h1>Upload fichier multiple</h1>

<form action="" enctype="multipart/form-data" method="post">
    <div>
        <label for='files'>Add Attachments:</label>
        <input id='files' name="files[]" type="file" multiple="multiple" />
    </div>

    <p><input type="submit" name="submit" value="Upload"></p>

</form>

<?php
if(!empty($_FILES['files']['name'][0])) {

    $files = $_FILES['files'];
    $uploaded = [];
    $failed = [];

    //caractère autorisés
    $allowedExtension = ['jpg', 'png', 'gif','jpeg'];

    // boucle de traitement de chaque fichié envoyé
    foreach ($files['name'] as $position => $fileName) {
        $fileTMP = $files['tmp_name'][$position]; // récupération du nom du fichier
        $fileSize = $files['size'][$position]; // récupération de la taille du fichier
        $fileError = $files['error'][$position]; //récupération du code erreur si nécessaire
        // récupération de l'extension:
        $file_ext = explode('.', $fileName);
        $file_ext = strtolower(end($file_ext));

//vérification de l'extension
        if (in_array($file_ext, $allowedExtension)) {

            //Si pas d'erreur on continue le script
            if ($fileError === 0) {

                // taille max autorisée
                if ($fileSize <= 1048576) { //2MB, en byte ca donne 209...

                    //création d'un fichier "unique" sur le serveur avec la meme extension que celle du fichier uploadé
                    $fileNameNew = "image" . uniqid('', true) . '.' . $file_ext;

                    //dossier de destination du fichier uploadé
                    $dirUpload = '../src/gallerie_photo';
                    $file_Destination = $dirUpload . '/' . $fileNameNew;

                    //vérification du dossier temporaire
                    if (move_uploaded_file($fileTMP, $file_Destination)) {

                        //enregistrement du lien du fichier sur le serveur
                        $uploaded[$position] = $file_Destination;


                    } else {
                        $failed[$position] = "[{$fileName}] failed to upload";
                    }

                } else {
                    $failed[$position] = "[{$fileName}] is too large.";
                }

            } else {
                $failed[$position] = "[{$fileName}] errored with code {$fileError}.";
            }

        } else {
            // message d'erreur personnalisé si l'extension n'est pas autorisée
            $failed[$position] = "[$fileName] file extension '$file_ext' is not allowed";
        }

    }
} else {
    if (!empty($_POST)) {
        $failed[] = "aucun fichier sélectionné";
    }
}
//si erreur dans le tableau d'erreur "$failed", on la traite
if (empty($failed)) {
    if (!empty($_FILES)) {
        header ("location: upload.php");
    }
} else {
    echo "<div class=\"alert alert-danger\">";
    foreach ($failed as $error) {
        echo '<br>' . $error . '<br>';
    }
    echo "</div>";
}
?>


<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
</body>
</html>

