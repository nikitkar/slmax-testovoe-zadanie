<?php

set_include_path(implode(PATH_SEPARATOR, array(
    get_include_path(),
    __DIR__
)));

spl_autoload_register(function ($name) {
    $filename = $name . '.php';
    if (stream_resolve_include_path($filename) === false) return;

    require_once $filename;
});

if (class_exists('BDPeopleList')) {
    // не уверен, что правильно сделал ининциализацию класса, так как сомневаюсь с область видимости
    class PeopleList
    {
        private const PEOPLELIST = [];

        private const DB = null;
        private const CONN = null;

        private const BDPeopleListClass = null;

        function __construct($id, $sumbol = "=")
        {
            $this->BDPeopleListClass = new BDPeopleList();
            $this->DB = new connectDB();
            $this->CONN = $this->DB->connection();

            if ($sumbol == "=" || $sumbol == ">" || $sumbol == "<" || $sumbol == ">=" || $sumbol == "<=" || $sumbol == "!=") {
                $query = "SELECT * FROM People WHERE id " . $sumbol . " " . $id . "";
                $results = mysqli_query($this->CONN, $query);

                if ($results) {
                    while ($row = mysqli_fetch_row($results)) {
                        $this->PEOPLELIST[$row[0]] = $row;
                    }
                }
            } else die("Only special characters are allowed to be entered (>, <, >=, <=, !=)");
        }

        public function getArrayInstances()
        {
            // я не понял, что надо сделать
        }

        public function deletePeople()
        {
            if (is_null($this->PEOPLELIST)) die("array is empty");

            foreach ($this->PEOPLELIST as $value) {
                $this->BDPeopleListClass->daletePeople($value[0]);
            }
        }

        public function print()
        {
            print_r($this->PEOPLELIST);
        }
    }
} else {
    die("The BDPeopleList class is missing <br>");
}
