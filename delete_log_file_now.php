<?php
$file = "logfile.txt";
if (!unlink($file))
  {
  echo ("Error deleting $file");
  }
else
  {
  echo ("Deleted $file, Log is cleared!");
  }
?>