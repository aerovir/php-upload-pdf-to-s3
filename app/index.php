<!-- <!DOCTYPE html>
<html>
<form method="post">
    <p>
        <input type="file" name="uploadFile">
        <input type="submit" value="загрузить">
    </p>
</form>
</html> -->

<?php
require 'vendor/autoload.php';

use Aws\S3\S3Client;
use Aws\Exception\AwsException;

$redirect = 'http://' . $_SERVER['HTTP_HOST'];
$bucket = 'pdf-backet';

$client = new S3Client([
    'version' => 'latest',
    'region' => ''
]);

access key ID
hWJTXTdkdtc6PL7HpEFE25

Secret Key
cQZ4AYBhLKFFLh1LJsvs2Ci9ENEnkKS7S1fr2zzzf4Ta