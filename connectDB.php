<?php
// mysqli_report(MYSQLI_REPORT_ALL);
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

class connectDB
{
    private const SERVERNAME = "localhost";
    private const USERNAME = "root";
    private const PASS = "";
    private const DB = "slmax-testovoe-zadanie";

    function connection()
    {
        try {
            $conn = new mysqli(self::SERVERNAME, self::USERNAME, self::PASS, self::DB);

            // print("Connected Successfully" . "<br>");

            return $conn;
        } catch (Exception $e) {
            echo 'Exception error: ',  $e->getMessage(), "\n";
        }
    }

    function closeConnect($conn)
    {
        $conn->close();
        print("Connected Close" . "<br>");
    }
}
