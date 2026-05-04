<?php $_SERVER["REQUEST_URI"] = "/course-create" . (isset($_SERVER["QUERY_STRING"]) && $_SERVER["QUERY_STRING"] !== "" ? "?" . $_SERVER["QUERY_STRING"] : ""); require __DIR__ . "/index.php";
