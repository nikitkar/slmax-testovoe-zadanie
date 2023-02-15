<?php
require_once 'connectDB.php';

class BDPeopleList
{
    private const NAME = null;
    private const SURNAME = null;
    private const DATEBIRTH = null;
    private const FLOOR = null;
    private const CITYBIRTH = null;

    private const DB = null;
    private const CONN = null;

    private static function validateDate($date, $format = 'Y-d-m')
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }

    function __construct()
    {
        $this->DB = new connectDB();
        $this->CONN = $this->DB->connection();

        $arrayArgs = func_get_args();
        $countArgs = func_num_args();

        switch ($countArgs) {
            case 0: ; break;
            case 1: {
                    $query = ("SELECT name, surname, dateBirth, floor, cityBirth FROM People WHERE id = " . $arrayArgs[0] . "");
                    $results = mysqli_query($this->CONN, $query);

                    if ($results) {
                        $row_count = mysqli_num_rows($results);

                        if ($row_count == 1) {
                            while ($row = mysqli_fetch_row($results)) {
                                $this->NAME = $row[0];
                                $this->SURNAME = $row[1];
                                $this->DATEBIRTH = $row[2];
                                $this->FLOOR = $row[3];
                                $this->CITYBIRTH = $row[4];
                            }
                        } else die("No data about the person was found");
                    }
                };
                break;
            case 5: {
                    $name = $arrayArgs[0];
                    $surname = $arrayArgs[1];
                    $datebirth = $arrayArgs[2];
                    $floor = $arrayArgs[3];
                    $citybirth = $arrayArgs[4];

                    if (preg_match("/^[a-zA-Zа-яА-Я]{1,255}+$/iu", $name)) $this->NAME = $name;
                    else die("For a name - only letters are allowed to be entered");

                    if (preg_match("/^[a-zA-Zа-яА-Я]{1,255}+$/iu", $surname)) $this->SURNAME = $surname;
                    else die("For a surname - only letters are allowed to be entered");

                    if (self::validateDate($datebirth)) $this->DATEBIRTH = $datebirth;
                    else die("For dates - only numbers in the format YYYY-DD-MM");

                    switch ($floor) {
                        case "0":
                        case "1":
                            $this->FLOOR = $floor;
                            break;
                        default:
                            die("For gender - only digits 0 - female, 1 - male");
                    }

                    if (preg_match("/^[a-zA-Zа-яА-Я]{1,255}+$/iu", $citybirth)) $this->CITYBIRTH = $citybirth;
                    else die("For a citybirth - only letters are allowed to be entered");
                };
                break;
            default:
                "";
                break;
        }
    }

    public function saveInfo()
    {
        $this->CONN->query("INSERT INTO `People` (`name`, `surname`, `dateBirth`, `floor`, `cityBirth`) VALUES ('" . $this->NAME . "', '" . $this->SURNAME . "', '" . $this->DATEBIRTH . "', '" . $this->FLOOR . "', '" . $this->CITYBIRTH . "');");

        return print("Info added");
    }

    public function daletePeople($id)
    {
        $this->CONN->query("DELETE FROM `People` WHERE `People`.`id` = " . $id . "");

        return print("People delete <br>");
    }

    public function convertDateToBirth()
    {
        $birthday_timestamp = strtotime($this->DATEBIRTH);
        $age = date('Y') - date('Y', $birthday_timestamp);
        if (date('md', $birthday_timestamp) > date('md')) $age--;

        return $age;
    }

    public function determineGender()
    {
        return $this->FLOOR == 0 ? "женщина" : "мужчина";
    }

    public function formattingPerson($datebirth, $floor = self::FLOOR)
    {
        if (self::validateDate($datebirth)) $this->DATEBIRTH = $datebirth;
        else die("For dates - only numbers in the format YYYY-DD-MM");

        switch ($floor) {
            case "0":
            case "1":
                $this->FLOOR = $floor;
                break;
            default:
                die("For gender - only digits 0 - female, 1 - male");
        }
    }

    public function print()
    {
        return "name - " . $this->NAME .
            ", surname - " . $this->SURNAME .
            ", datebirth - " . $this->DATEBIRTH .
            ", floor - " . $this->FLOOR .
            ", citybirth - " . $this->CITYBIRTH;
    }
}
