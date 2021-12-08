<!DOCTYPE html>
<html>
<form method="post">
    <p>
        <input type="file" name="PDFfile">
        <input type="submit" value="загрузить" name="upload">
    </p>
</form>
</html>

<?php
require 'vendor/autoload.php';

use Aws\S3\S3Client;
use Aws\Exception\AwsException;

$redirect = 'http://' . $_SERVER['HTTP_HOST'];
$bucket = 'pdf-backet';

$client = new S3Client([
    'version' => 'latest',
    'region' => 'eu-west-2',
    'endpoint' => 'http://hb.bizmrg.com',
    'credentials' => [
        'key' => 'hWJTXTdkdtc6PL7HpEFE25',
        'secret' => 'cQZ4AYBhLKFFLh1LJsvs2Ci9ENEnkKS7S1fr2zzzf4Ta',
    ],
]);

if ($_POST) {
    //Загружаем
    if (isset($_POST['upload']) && isset($_FILES['PDFfile'])){
        if ($_FILES['PDFfile']['error'][0] == 0){
            try {
                $upload = new $client->putObject([
                    'Bucket' => $bucket,
                    'Key' => $_FILES9['PDFfile'],
                    'acl' => 'public-read'
                ]);
                echo "uploaded successful";
            } catch (Aws\S3\Exception\S3Exception $e) {
                echo "There was an error uploading the file.\n";
                echo $e->getMessage();
            }
        }
    }
}

// access key ID
// hWJTXTdkdtc6PL7HpEFE25

// Secret Key
// cQZ4AYBhLKFFLh1LJsvs2Ci9ENEnkKS7S1fr2zzzf4Ta