<html>
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title></title>
    <meta charset="utf-8">
    <meta name="description" content="description">
    <meta name="author" content="SitePoint">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="">
    <!--[if lt IE 9]>
        <script src="//cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <link rel="shortcut icon" href="">
    <link rel="stylesheet" href="assets/css/styles.css?v=1.0">
    <link rel="stylesheet" href="assets/css/normalize.css">
    <link rel="stylesheet" href="assets/bootstrap-4.0.0-beta.3-dist/css/bootstrap.min.css">
    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.3/css/bootstrap.min.css" integrity="sha384-Zug+QiDoJOrZ5t4lssLdxGhVrurbmBWopoEl+M6BdEfwnCJZtKxi1KgxUyJq13dy" crossorigin="anonymous"> -->
    <!-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.3/js/bootstrap.min.js" integrity="sha384-a5N7Y/aK3qNeh15eJKGWxsqtnX/wWdSZSKp+81YjTmS15nvnvxKHuzaWwXHDli+4" crossorigin="anonymous"></script> -->
    <script src="assets/bootstrap-4.0.0-beta.3-dist/js/bootstrap.min.js" integrity="sha384-a5N7Y/aK3qNeh15eJKGWxsqtnX/wWdSZSKp+81YjTmS15nvnvxKHuzaWwXHDli+4" crossorigin="anonymous"></script>
    <!-- <script src="https://cdn.jsdelivr.net/npm/vue"></script> -->
    <script src="assets/js/vue.js"></script>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">Navbar</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
            <div class="navbar-nav">
                <a class="nav-item nav-link active" href="index.php">Home <span class="sr-only">(current)</span></a>
                <a class="nav-item nav-link" href="#">Features</a>
                <a class="nav-item nav-link" href="#">Pricing</a>
                <a class="nav-item nav-link" href="mock.php">Mock</a>
            </div>
        </div>
    </nav>
<?php
ini_set('display_startup_errors',1);
ini_set('display_errors',1);
error_reporting(-1);
error_reporting(E_ALL);

require_once './vendor/autoload.php';
require_once './models/Example.php';
require_once './src/functions.php';
/* $example = (new Example())->example;*/
$db = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWD);
$language = $db->query("select language from t_language;")->fetchAll(PDO::FETCH_ASSOC);
$group_cd = 5;
$examples = $db->query("select * from v_example_desc where group_cd = ${group_cd};")->fetchALL(PDO::FETCH_ASSOC);
$group_name = $db->query("select distinct group_name from v_example_desc where group_cd = ${group_cd};")->fetchALL(PDO::FETCH_ASSOC);

// $twig->render('index.twig', array(
//     'name' => $name,
// ));
// {% set rows = example->cat3 %}
// {% for row in rows %}

foreach ($examples as $row) {
  var_dump($row);
  echo "<br>";
  /* echo $row['example_id'];
   * echo $row['language'] . "\t";
   * echo $row['example'] . "<br>";*/
}
echo "hello world";

$json = json_encode(['items' => $examples]);
// echo h($json)."<br>";
?>
    <!-- <script id="json-vue" data-json="<?= h($json); ?>"></script> -->
    <section id="app">
      <form action="post">
          <!-- {{ message1 }} -->
          <table class="container">
            <tr>
              <td>

                <?php
      echo $group_name[0]['group_name'];
      ?>
              </td>
              <?php
                var_dump($examples);
                foreach ($examples as $example) {
                    echo "<td>";
                    echo $example['language'] . '<br>';
                    echo $example['example'] . '<br>';
                    // foreach ($example as $row) {
                    // var_dump($row);
                    // echo "2<br>";
                    // }
                    echo "</td>";
                }
              ?>
            </tr>

            <!-- <tr v-for="(item, index) in items">
                 <input :name="'items[' + index + '][example_id]'" :value="item.example_id"/>
                 <input :name="'items[' + index + '][example_id]'" :value="item.example_id"/>
                 </tr> -->
          </table>
        <button type="button" v-on:click="add">追加</button>
        <button type="submit">保存</button>
      </form>
    </section>
    <div id="app-2">
        <span v-bind:title="message2">
            Hover your mouse over me for a few seconds
            to see my dynamically bound title!
        </span>
    </div>
    <div id="app-3">
        <span v-if="seen">Now you see me</span>
    </div>
    <div id="app-4">
        <ol>
            <li v-for="todo in todos">
                {{ todo.text }}
            </li>
        </ol>
    </div>
    <div id="app-5">
        <p>{{ message5 }}</p>
        <button v-on:click="reverseMessage">Reverse Message</button>
    </div>
    <div id="app-6">
        <p>{{ message6 }}</p>
        <input v-model="message6">
    </div>
    <script src="assets/js/script.js"></script>
</body>
</html>
