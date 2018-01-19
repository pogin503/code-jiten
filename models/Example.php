<?php
class Example {
    private $language;
    private $example;
    private $group_cd;
    private $group_name;
    private $example_id;

    public function __construct(array $source) {
        $this->language = $source['language'];
        $this->example_id = $source['example_id'];
        $this->example = $source['example'];
        $this->group_cd = $source['group_cd'];
        $this->group_name = $source['group_name'];
    }

    public function __get($name)
    {
        return $this->$name;
    }

    public function toArray()
    {
        return [
            'language' => $this->language,
            'example_id' => $this->example_id,
            'example' => $this->example,
            'group_cd' => $this->group_cd,
            'group_name' => $this->group_name,
        ];
    }

    private function getConnection() {
        return new PDO(PDO_DSN, DB_USERNAME, DB_PASSWD);
    }

    private function fetchItem($sql) {
        try {
            $db = getConnection();
            // PDO::ATTR_ERRMODE
            // PDO::ERRMODE_EXCEPTION
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // insert
            // $db->exec("insert into users (name, score) values ('taguchi', 55);");
            // echo "Add complete!";
            $stmt = $db->query($sql);
            $ret = $stmt->fetch(PDO::FETCH_ASSOC);
            // print_r($ret1);
            // echo $ret1['category_name'];
            /* $st = $db->prepare("insert into t_score (user_id, score) values(?,?)");
               $st->execute(array(6, $_POST['score'])); */
            return $ret;

        } catch (PDOException $e) {
            echo "invalid<br>";
            echo $e->getMessage();
            exit;
        }
    }

    private function fetchItems($sql) {
        try {
            $db = $this->getConnection();
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "invalid<br>";
            echo $e->getMessage();
            exit;
        }
    }
}
