<!DOCTYPE html>
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
    require_once './const.php';

    $loader = new Twig_Loader_Filesystem('views');
    $twig = new Twig_Environment($loader, array(
      //'cache' => './compilation_cache',
      'debug' => true,
    ));

    $twig->addExtension(new Twig_Extension_Debug());
    $template = $twig->load('index.html.twig');
    echo $template->render();

    $db = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWD);
    $examples = '';

    /* var_dump($_POST);*/
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $group_cd = isset($_GET['group_cd']) ? $_GET['group_cd'] : '';
      if(isset($_GET['group_cd'])) {
        $example_records = $db->query("select * from v_example_desc where group_cd = ${group_cd};")
          ->fetchALL(PDO::FETCH_ASSOC);
        $examples = array_map(function ($record) {
          return new Example($record);
        }, $example_records);

        echo "2";
      }
      /* foreach ($_POST['items'] as $i) {
       *   if (!is_array($i) || array_values($i) === ['']) {
       *     continue;
       *   }
       *   $items[] = new Item($i);
       * }*/
    } else {
      $group_cd = isset($_GET['group_cd']) ? $_GET['group_cd'] : '';

      if(isset($_GET['group_cd'])) {
        $db = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWD);
        $example_records = $db->query("select * from v_example_desc where group_cd = ${group_cd};")
          ->fetchALL(PDO::FETCH_ASSOC);
        $examples = array_map(function ($record) {
          /* var_dump($record);
           * echo "<br>";*/
          /* return [
           *   'example' => new Example($record),
           *   'insert_flag' => false,
           * ];*/
          return new Example($record);
        }, $example_records);
        echo "1";
      }
    }
    /* var_dump($examples);*/
    $json = json_encode(['items' =>
      array_map(function($i) {
        return [
          'example' => $i->toArray(),
            'insert_flag' => false,
            'update_flag' => false,

        ];
      }, $examples),
    ]);
    $languages = $db->query("select * from t_language order by language")->fetchAll(PDO::FETCH_ASSOC);
    $languages_json = json_encode($languages);

    /* var_dump($json);*/
    /* var_dump($languages_json)*/
    ?>
    <body>
      <?php echo $twig->load('navbar.html.twig')->render(); ?>
      <script id="json-vue" data-json="<?= h($json) ?>"></script>
      <script id="languages-vue" data-json="<?= h($languages_json) ?>"></script>
      <form name="save-form" action="register.php" method="post">
        <section id="app">
          <table>
            <tr v-for="(item, index) in items">
              <td>
                <!-- <input :name="'items[' + index + '][example_id]'" :value="item.language"/> -->
                <!-- <select id="" :name="'items[' + index + '][example_id]'">
                     <option value="item.language">{{ item.language }} </option>
                     </select> -->
                <span v-if="item.insert_flag">
                  <select name="'items[' + index + '][example][language]'">
                    <option v-for="language in item.languages" :value="language">
                      {{ language.language }}
                    </option>
                  </select>
                </span>
                <span v-else>
                  {{ item.example.language }}
                </span>
              </td>
              <td>
                <!-- <textarea :name="'items[' + index + '][example][example]'" rows="10" cols="60">{{ item.example.example }}</textarea> -->
                <autosize-textarea :key="'items[' + index + '][example][example]'"  :value="item.example.example">
                  {{ item.example.example }}
                </autosize-textarea>
              </td>
              <td>{{ item.insert_flag }}</td>
            </tr>
          </table>
          <button type="button" v-on:click="add">追加</button>
          <button type="submit">保存</button>
        </section>
      </form>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/autosize.js/3.0.16/autosize.min.js"></script>
      <script>
       const json = JSON.parse(document.getElementById('json-vue').dataset.json);
       const languages = JSON.parse(document.getElementById('languages-vue').dataset.json);
       const AutosizeTextarea = {
         props: [ 'value' ],
         template: '<textarea rows="3" cols="60">{{ value }}</textarea>',
         mounted: function () {
           /* var num = this.value.split("\n").length;
            * if (num > 4) {
            *   autosize(this.$el);
            * }*/
           autosize(this.$el);
         }
       };

       var v = new Vue({
         components: {
           'autosize-textarea': AutosizeTextarea
         },
         el: '#app',
         data: Object.assign({}, json, {
           'insert_flag': false,
           'update_flag': false,
           'languages': ''
         }),
         methods: {
           add: function (event) {
             v.$data.items.push({
               'example': '',
               'insert_flag': true,
               'update_flag': false,
               'languages': languages
             });
             return false;
           }
         }
       });
      </script>
    </body>
</html>
