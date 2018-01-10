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
$loader = new Twig_Loader_Filesystem('views');
$twig = new Twig_Environment($loader, array(
    //'cache' => './compilation_cache',
    'debug' => true,
));
$twig->addExtension(new Twig_Extension_Debug());
$template = $twig->load('index.html.twig');
echo $template->render();
?>
</head>
<body
<!-- load navbar -->
<?php echo $twig->load('navbar.html.twig')->render(); ?>

<?php
require_once './models/Example.php';
require_once './src/functions.php';
require_once './const.php';

$db = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWD);
$language = $db->query("select language from t_language;")->fetchAll(PDO::FETCH_ASSOC);
$group_cd = 5;
$examples = $db->query("select * from v_example_desc where group_cd = ${group_cd};")->fetchALL(PDO::FETCH_ASSOC);
$group_name = $db->query("select distinct group_name from v_example_desc where group_cd = ${group_cd};")->fetchALL(PDO::FETCH_ASSOC);

$json = json_encode(['items' => $examples]);
// echo h($json)."<br>";
use Ramsey\Pygments\Pygments;

$pygments = new Pygments('/Users/pogin/.pyenv/shims/pygmentize');

?>
    <!-- <script id="json-vue" data-json="<?= h($json); ?>"></script> -->
    <section id="app">
      <form action="post">
          <!-- {{ message1 }} -->
          <div id="editor">some text</div>
          <script>
          var editor = ace.edit("editor");
          var JavaScriptMode = ace.require("ace/mode/javascript").Mode;
          editor.session.setMode(new JavaScriptMode());
          </script>
          <table class="container">
            <tr>
              <td>
              <?php
              echo $group_name[0]['group_name'];
              ?>
              </td>
              <?php

                $styles = $pygments->getStyles();
                foreach ($examples as $example) {
                    echo "<td>";
                    echo $example['language'] . '<br>';
                    // echo $example['example'] . '<br>';
                    $lang = lang2pygmentsLexer($example['language']);
                    echo $example['example'];
                    echo '<br>';
                    echo $pygments->highlight($example['example'], $lang);

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
