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
    require_once './models/ExampleGroup.php';
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
    $group_cd = isset($_GET['group_cd']) ? $_GET['group_cd'] : null;

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      /* echo "<pre>";
       * var_dump($_POST);
       * echo "</pre>";*/
      if(isset($_GET['group_cd'])) {
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $insert_stmt = $db->prepare("INSERT INTO t_example (\"language\", \"example\", \"group_cd\") VALUES
(:language, :example, :group_cd)");
        $update_stmt = $db->prepare("UPDATE t_example SET language = :language, example = :example
WHERE example_id = :example_id;");
        if (!empty($_POST['items'])) {
          foreach ($_POST['items'] as $row) {
            if ($row['insert_flag'] == "true") {
              $insert_stmt->bindParam(':language',$row['example']['language']);
              $insert_stmt->bindParam(':example', $row['example']['example']);
              $insert_stmt->bindParam(':group_cd',intval($row['group_cd']));
              $insert_stmt->execute();
            } else {
              $update_stmt->bindParam(':language',$row['example']['language']);
              $update_stmt->bindParam(':example', $row['example']['example']);
              $update_stmt->bindParam(':example_id',intval($row['example']['example_id']));
              $update_stmt->execute();
            }
          }
        }
        if (!empty($_POST['delete_target'])) {
          $delete_stmt = $db->prepare('DELETE FROM t_example WHERE example_id = :example_id;');
          foreach($_POST['delete_target'] as $example_id){
            $delete_stmt->bindParam(':example_id', intval($example_id));
            $delete_stmt->execute();
          }
        }
        echo "2<br/>";

        $_POST = array();
      }
    }

    $json = '';
    $examples = '';
    $disp_group = '';
    $group_name = null;
    $group_data_json = '';

    if(isset($group_cd)) {

      // example data
      $examples_stmt = $db->prepare("SELECT example_id, language, example, group_cd, group_name FROM v_example_desc WHERE group_cd = :group_cd;");
      $examples_stmt->bindParam(':group_cd', intval($group_cd));
      $examples_stmt->execute();
      $example_records = $examples_stmt->fetchALL(PDO::FETCH_ASSOC);

      if (!empty($example_records)) {
        $examples = array_map(function ($record) {
          return new Example($record);
        }, $example_records);

        $group_name = $example_records[0]['group_name'];
      } else {
        $group_name_stmt = $db->prepare("SELECT group_name FROM t_example_group WHERE group_cd = :group_cd");
        $group_name_stmt->bindParam(':group_cd', $group_cd);
        $group_name_stmt->execute();
        $group_name_row = $group_name_stmt->fetch();
        $group_name = $group_name_row['group_name'];
      }

      if(empty($examples)) {
        $json = json_encode(['items' => []]);
      } else {
        $json = json_encode(['items' =>
          array_map(function($i, $idx) {
            return [
              'example' => $i->toArray(),
                'insert_flag' => false,
                'update_flag' => false,
                'row_num' => $idx
            ];
          }, $examples, range(1, count($examples))),
          'group_cd' => $group_cd]);
      }

      // group data
      $group_stmt = $db->prepare("SELECT group_cd, group_name, group_level, \"desc\", disp_flag FROM t_example_group WHERE group_cd IN
(SELCT group_ancestor FROM t_example_relation WHERE group_descendant = :group_cd1 AND group_ancestor <> :group_cd2);");
      $group_stmt->bindParam(':group_cd1', intval($group_cd));
      $group_stmt->bindParam(':group_cd2', intval($group_cd));
      $group_stmt->execute();
      $group_data = array_map(function($i) {
        return new ExampleGroup($i);
      }, $group_stmt->fetchAll(PDO::FETCH_ASSOC));

      if (empty($group_data)) {
        $group_data_json = json_encode(['group_names' => []]);
      } else {
        $group_data_json = json_encode(['group_names' =>
          array_map(function($i) {
            return [
              'group_name' => $i->group_name
            ];
          }, $group_data),
        ]);
      }

      echo 1;

    } else {
      $disp_group_record = $db->query("SELECT group_cd, group_name
FROM t_example_group WHERE disp_flag = 1;")->fetchAll(PDO::FETCH_ASSOC);
      $disp_group = json_encode(['items' => $disp_group_record]);

      echo 3;
    }

    $languages = $db->query("SELECT * FROM t_language order by language")->fetchAll(PDO::FETCH_ASSOC);
    $languages_json = json_encode($languages);

    ?>
    <body>
      <?php echo $twig->load('navbar.html.twig')->render(); ?>
      <script id="json-vue" data-json="<?= h($json) ?>"></script>
      <script id="languages-vue" data-json="<?= h($languages_json) ?>"></script>
      <script id="group-vue" data-json="<?= ($group_cd == '') ? '' : h("{ \"group_cd\": ${group_cd}, \"group_name\": \"${group_name}\" }") ?>"></script>
      <script id="disp-group-vue" data-json="<?= ($disp_group == '') ? '{&quot;items&quot;: null}' : h($disp_group) ?>"></script>
      <script id="group-names-vue" data-json="<?= h($group_data_json) ?>"></script>

      <style>[v-cloak] { display: none; }</style>

      <section id="disp-group">
        <table>
          <tr v-for="item in items" v-cloak>
            <td><a v-bind:href="'register?group_cd=' + item.group_cd">{{ item.group_name }}</a></td>
          </tr>
        </table>
      </section>
      <form name="save-form" action="register.php?group_cd=<?= $group_cd; ?>" method="post">
        <section id="app" v-cloak>
          <h2><a href="register.php?group_cd=<?= $group_cd; ?>">{{ group_name }}</a></h2>
          <span v-for="name in group_names">
            <span>[{{ name.group_name }}] </span>
          </span>
          <table>
            <tr v-for="(item, index) in items"
                :key="item.row_num">
              <td>
                <span v-if="item.insert_flag">
                  <select :name="'items[' + index + '][example][language]'" v-model="item.example.language">
                    <option v-for="language in item.languages" :value="language.language">
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
              <td>
                <input :name="'items[' + index + '][example][example_id]'" type="hidden" v-model.number="item.example.example_id"/>
                <input :name="'items[' + index + '][group_cd]'" type="hidden" v-model.number="item.group_cd"/>
                <input :name="'items[' + index + '][insert_flag]'" type="hidden" v-model="item.insert_flag"/>
                <button class="btn" type="button" v-on:click="remove(index, item.example.example_id)">削除</button>
              </td>
            </tr>
          </table>
          <input :name="'delete_target[]'" type="hidden" v-model="delete_target"/>
          <button class="btn" type="button" v-on:click="add">追加</button>
          <button class="btn" type="submit">保存</button>
        </section>
      </form>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/autosize.js/3.0.16/autosize.min.js"></script>
      <script src="assets/js/register.vue"></script>
    </body>
</html>
