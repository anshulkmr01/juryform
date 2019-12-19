
<?php
header('Content-disposition: inline');
header('Content-type: application/msword'); // not sure if this is the correct MIME type
readfile('http://localhost:8080/PHPmerge/uploads/MY_FUTURE_TIMELINE3.docx');
?>