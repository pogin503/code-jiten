<?php
require_once("const.php");

class Example {
    public $example;
    public function __construct() {
        // $this->cat1 = $this->fetchItems("select * from t_category where category_type = 0");
        // $this->cat2 = $this->fetchItems("select * from t_category where category_type = 1");
        $this->example = $this->fetchItems("select * from v_example_desc;");
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
    private function toArray() {
        
    }
}
