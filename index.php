<html>
<head>
<?php
ini_set('display_startup_errors',1);
ini_set('display_errors',1);
error_reporting(-1);
error_reporting(E_ALL);
?>
<?php
require_once './vendor/autoload.php';
require_once './models/Example.php';
require_once './src/functions.php';
require_once './config/database.php';
$loader = new Twig_Loader_Filesystem('views');
$twig = new Twig_Environment($loader, array(
    //'cache' => './compilation_cache',
    'debug' => true,
));
$twig->addExtension(new Twig_Extension_Debug());
$template = $twig->load('header.html.twig');
echo $template->render();

$db = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWD);
$language = $db->query("select language from t_language;")->fetchAll(PDO::FETCH_ASSOC);
$group_cd = 5;
$examples = $db->query("select * from v_example_desc where group_cd = ${group_cd};")->fetchALL(PDO::FETCH_ASSOC);
$group_name = $db->query("select distinct group_name from v_example_desc where group_cd = ${group_cd};")->fetchALL(PDO::FETCH_ASSOC);

$json = json_encode(['items' => $examples]);
// echo h($json)."<br>";
use Ramsey\Pygments\Pygments;
?>
</head>
<body>
<?php echo $twig->load('navbar.html.twig')->render(); ?>

  <section id="disp-group" v-cloak>
    <table v-show="seen">
      <thead>
        <tr>
          <th v-for="key in gridColumns">
            {{ key }}
          </th>
        </tr>
      </thead>
      <tr v-for="item in items">
        <td><a v-bind:href="'register?group_cd=' + item.group_cd">{{ item.group_name }}</a></td>
      </tr>
    </table>
  </section>

    <section id="app1" v-cloak>
      <!-- <div id="editor">some text</div> -->
          <script>
          // var editor = ace.edit("editor");
          // var JavaScriptMode = ace.require("ace/mode/javascript").Mode;
          // editor.session.setMode(new JavaScriptMode());
          </script>
          <table class="container">
            <tr>
              <td>
              <?php
              /* echo $group_name[0]['group_name'];*/
              ?>
              </td>
              <?php

                // $styles = $pygments->getStyles();
                // foreach ($examples as $example) {
                //     echo "<td>";
                //     echo $example['language'] . '<br>';
                //     echo $example['example'] . '<br>';
                //     $lang = lang2pygmentsLexer($example['language']);
                //     echo $example['example'];
                //     echo '<br>';
                //     echo $pygments->highlight($example['example'], $lang);

                //     echo "</td>";
                // }
              ?>
            </tr>

            <!-- <tr v-for="(item, index) in items">
                 <input :name="'items[' + index + '][example_id]'" :value="item.example_id"/>
                 <input :name="'items[' + index + '][example_id]'" :value="item.example_id"/>
                 </tr> -->
          </table>
    </section>
    <script src="assets/js/script.js"></script>
  </body>
</html>
