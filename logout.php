<?php
/* This file will destroy session and redirect user to "index.php" */
session_start();
session_destroy();
echo "<script type='text/javascript'>";
echo "document.location.href='index.php'";
echo "</script>";

