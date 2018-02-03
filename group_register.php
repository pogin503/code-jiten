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
    require_once './src/functions.php';
    require_once './models/ExampleGroup.php';
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

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $update_stmt = $db->prepare('UPDATE t_example_group SET group_name = :group_name where group_cd = :group_cd;');

      foreach($_POST['items'] as $row) {
        if ($row['insert_flag'] == 'true') {

        } else {
          $update_stmt->bindParam(':group_name', $row['group_name']);
          $update_stmt->bindParam(':group_cd', $row['group_cd']);
          $update_stmt->execute();
        }
      }
    }

    $disp_group_record = $db->query("SELECT group_cd, group_name, group_level, \"desc\", disp_flag
FROM t_example_group;")->fetchAll(PDO::FETCH_ASSOC);

    $disp_group = json_encode([
      'items' => array_map(function($record) {
        return $record->toArray();
      }, array_map(function($record) {
        return new ExampleGroup($record);
      }, $disp_group_record)),
    ]);
    ?>
  </head>
  <body>
    <?php echo $twig->load('navbar.html.twig')->render(); ?>

    <script id="disp-group-vue" data-json="<?= ($disp_group == '') ? '{&quot;items&quot;: null}' : h($disp_group) ?>"></script>

    <form name="save-form" action="group_register.php" method="post">
      <section id="disp-group">
        <table>
          <tr v-for="(item, index) in items" v-cloak>
            <td>
              <input :name="'items[' + index + '][group_name]'" type="text" v-model="item.group_name"/>
              <input :name="'items[' + index + '][group_cd]'" type="hidden" v-model.number="item.group_cd"/>
              <input :name="'items[' + index + '][insert_flag]'" type="hidden" v-model.number="item.insert_flag"/>
            </td>
          </tr>
        </table>
        <button class="btn" type="button" v-on:click="add">追加</button>
        <button class="btn" type="submit">保存</button>
      </section>
    </form>
    <script src="assets/js/group.vue"></script>
  </body>
</html>
