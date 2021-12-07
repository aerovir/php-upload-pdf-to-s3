<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>PHP File Upload Example - Tutsmake.com</title>
</head>

<body>
    <form action="test.php" method="post" enctype="multipart/form-data">
        <h2>PHP Upload File</h2>
        <label for="file_name">Filename:</label>
        <input type="file" name="anyfile" id="anyfile">
        <input type="submit" name="submit" value="Upload">
        <p><strong>Note:</strong> Only .jpg, .jpeg, .gif, .png formats allowed to a max size of 5 MB.</p>
    </form>
</body>

</html>

<?php
require 'vendor/autoload.php';

use Aws\S3\S3Client;
// Instantiate an Amazon S3 client.
$s3Client = new S3Client([
    'version' => 'latest',
    'region'  => 'eu-west-2',
    'endpoint' => 'http://hb.bizmrg.com',
    'credentials' => [
        'key'    => 'hWJTXTdkdtc6PL7HpEFE25',
        'secret' => 'cQZ4AYBhLKFFLh1LJsvs2Ci9ENEnkKS7S1fr2zzzf4Ta'
    ]
]);
// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if file was uploaded without errors
    if (isset($_FILES["anyfile"]) && $_FILES["anyfile"]["error"] == 0) {
        $allowed = array("jpg" => "image/jpg", "jpeg" => "image/jpeg", "gif" => "image/gif", "png" => "image/png");
        $filename = $_FILES["anyfile"]["name"];
        $filetype = $_FILES["anyfile"]["type"];
        $filesize = $_FILES["anyfile"]["size"];
        // Validate file extension
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        if (!array_key_exists($ext, $allowed)) die("Error: Please select a valid file format.");
        // Validate file size - 10MB maximum
        $maxsize = 10 * 1024 * 1024;
        if ($filesize > $maxsize) die("Error: File size is larger than the allowed limit.");
        // Validate type of the file
        if (in_array($filetype, $allowed)) {
            // Check whether file exists before uploading it
            if (file_exists("upload/" . $filename)) {
                echo $filename . " is already exists.";
            } else {
                if (move_uploaded_file($_FILES["anyfile"]["tmp_name"], "upload/" . $filename)) {
                    $bucket = 'pdf-backet';
                    $file_Path = __DIR__ . '/upload/' . $filename;
                    $key = basename($file_Path);
                    try {
                        $result = $s3Client->putObject([
                            'Bucket' => $bucket,
                            'Key'    => $key,
                            'Body'   => fopen($file_Path, 'r'),
                            'ACL'    => 'public-read', // make file 'public'
                        ]);
                        echo "Image uploaded successfully. Image path is: " . $result->get('ObjectURL');
                    } catch (Aws\S3\Exception\S3Exception $e) {
                        echo "There was an error uploading the file.\n";
                        echo $e->getMessage();
                    }
                    echo "Your file was uploaded successfully.";
                } else {
                    echo "File is not uploaded";
                }
            }
        } else {
            echo "Error: There was a problem uploading your file. Please try again.";
        }
    } else {
        echo "Error: " . $_FILES["anyfile"]["error"];
    }
}
