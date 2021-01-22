<?php

$targetFolder = '/public/storage';
$linkFolder = $_SERVER['DOCUMENT_ROOT'].'/storage';

symlink($targetFolder,$linkFolder);

echo 'DOCUMENT_ROOT:'.$_SERVER['DOCUMENT_ROOT'].'<br>';
echo '$targetFolder:'.$targetFolder.'<br>';
echo '$linkFolder:'.$linkFolder.'<br>';
echo 'Symlink process successfully completed';

?>