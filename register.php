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
    /* echo "<pre>";
     * var_dump($_POST);
     * echo "</pre>";*/
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $group_cd = isset($_GET['group_cd']) ? $_GET['group_cd'] : '';
      if(isset($_GET['group_cd'])) {
        foreach ($_POST['items'] as $row) {
          if ($row['insert_flag'] == "true") {
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt = $db->prepare("INSERT INTO t_example (\"language\", \"example\", \"group_cd\") VALUES
(:language, :example, :group_cd)");
            $stmt->bindParam(':language',$row['example']['language']);
            $stmt->bindParam(':example', $row['example']['example']);
            $stmt->bindParam(':group_cd',intval($row['example']['group_cd']));
            $stmt->execute();
          }
        }
        $example_records = $db->query("select * from v_example_desc where group_cd = ${group_cd};")
          ->fetchALL(PDO::FETCH_ASSOC);
        $examples = array_map(function ($record) {
          return new Example($record);
        }, $example_records);

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
          return new Example($record);
        }, $example_records);
      }
    }
    /* var_dump($examples);*/
    $json = json_encode(['items' =>
      array_map(function($i, $idx) {
        return [
          'example' => $i->toArray(),
            'insert_flag' => false,
            'update_flag' => false,
            'row_num' => $idx,
        ];
      }, $examples, range(1, count($examples))),
    ]);
    $languages = $db->query("select * from t_language order by language")->fetchAll(PDO::FETCH_ASSOC);
    $languages_json = json_encode($languages);

    var_dump($json);
    /* var_dump($languages_json)*/
    ?>
    <body>
      <?php echo $twig->load('navbar.html.twig')->render(); ?>
      <script id="json-vue" data-json="<?= h($json) ?>"></script>
      <script id="languages-vue" data-json="<?= h($languages_json) ?>"></script>
      <script id="group-cd-vue" data-json="<?= h("{\"example\": { \"group_cd\": ${group_cd}}}") ?>"></script>
      <style>[v-cloak] { display: none; }</style>
      <form name="save-form" action="register.php?group_cd=<?= $group_cd; ?>" method="post">
        <section id="app">
          <table>
            <tr v-for="(item, index) in items">
              <td>
                <span v-if="item.insert_flag">
                  <select :name="'items[' + index + '][example][language]'" v-model="item.example.language">
                    <option v-for="language in item.languages">
                      {{ language.language }}
                    </option>
                  </select>
                </span>
                <span v-else>
                  {{ item.example.language }}
                  <input :name="'items[' + index + '][example][language]'" type="hidden" v-model="item.example.language"/>
                </span>
              </td>
              <td>
                <autosize-textarea :name="'items[' + index + '][example][example]'" v-model="item.example.example">
                  {{ item.example.example }}
                </autosize-textarea>
              </td>
              <td><input :name="'items[' + index + '][example][group_cd]'" type="hidden" v-model="item.example.group_cd"/>{{ item.example.group_cd }}</td>
              <td><input :name="'items[' + index + '][insert_flag]'" type="hidden" v-model="item.insert_flag"/>{{ item.insert_flag }}</td>
              <td> {{ item.row_num }} </td>
            </tr>
          </table>
          <button class="btn" type="button" v-on:click="add">追加</button>
          <button class="btn" type="submit">保存</button>
        </section>
      </form>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/autosize.js/3.0.16/autosize.min.js"></script>
      <script>
       const json = JSON.parse(document.getElementById('json-vue').dataset.json);
       const languages = JSON.parse(document.getElementById('languages-vue').dataset.json);
       const group_cd = JSON.parse(document.getElementById('group-cd-vue').dataset.json);
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
           'languages': '',
         }),
         methods: {
           add: function (event) {
             v.$data.items.push(
               Object.assign({}, group_cd, {
               'insert_flag': true,
               'update_flag': false,
               'languages': languages,
               'row_num': v.$data.items.length + 1,
             }));
             return false;
           }
         }
       });
      </script>
    </body>
</html>
