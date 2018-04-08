<!DOCTYPE html>
<html>
  <head>
<?php
require_once './vendor/autoload.php';
require_once './models/Example.php';
require_once './models/ExampleMapper.php';
require_once './models/ExampleGroup.php';
require_once './models/ExampleGroupMapper.php';
require_once './models/Language.php';
require_once './src/functions.php';
require_once './config/database.php';

$loader = new Twig_Loader_Filesystem('views');
$twig = new Twig_Environment($loader, array(
    //'cache' => './compilation_cache',
    'debug' => true,
));

$twig->addExtension(new Twig_Extension_Debug());
echo $twig->load('header.html.twig')->render();

$group_cd = isset($_GET['group_cd']) ? $_GET['group_cd'] : null;
$parent_id = isset($_GET['parent_id']) ? $_GET['parent_id'] : null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_GET['group_cd'])) {
        $mapper = new ExampleMapper();
        if (!empty($_POST['items'])) {
            foreach ($_POST['items'] as $row) {
                if ($row['insert_flag'] == "true") {
                    $mapper->insertExample(
                        intval($row['example']['language_id']),
                        $row['example']['example'],
                        intval($row['group_cd'])
                    );
                } else {
                    $mapper->updateExample(
                        $row['example']['example'],
                        intval($row['example']['example_id'])
                    );
                }
            }
        }
        if (!empty($_POST['delete_target'])) {
            foreach ($_POST['delete_target'] as $example_id) {
                $mapper->deleteExample(intval($example_id));
            }
        }
    }
}
$_POST = array();
$json = '';
$examples = '';
$disp_group = '';
$group_name = null;
$group_data_json = '';

if (isset($group_cd)) {

    $db = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWD);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // example data
    $examples_stmt = $db->prepare(
        "SELECT example_id, example, language_id, language, group_cd, group_name
         FROM v_example_desc WHERE group_cd = :group_cd;");
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

    $languages = Language::getWithTemplate()->toArray();

    if (empty($examples)) {
        $json = json_encode([
            'items' => [],
            'languages' => $languages
        ]);
    } else {

        $items = array_map(function($i, $idx) {
            return [
                'example' => $i->toArray(),
                'insert_flag' => false,
                'update_flag' => false,
                'row_num' => $idx
            ];
        }, $examples, range(1, count($examples)));

        $json = json_encode([
            'items' => $items,
            'group_cd' => $group_cd,
            'languages' => $languages]);
    }

    // group data
    $group_data = array_map(function ($i) {
        return new ExampleGroup($i);
    }, ExampleGroupMapper::fetchParents($group_cd));

    if (empty($group_data)) {
        $group_data_json = json_encode(['group_names' => []]);
    } else {
        $group_data_json = json_encode(
            ['group_names' =>
             array_map(function ($i) {
                 return [
                     'group_cd' => $i->group_cd,
                     'group_name' => $i->group_name
                 ];
             }, $group_data),
        ]);
    }

} else {
    if (empty($parent_id)) {
        $disp_group = json_encode([
            'items' => ExampleGroupMapper::fetchLeaf()->toArray(),
            'seen' => true
        ]);
    } else {
        $disp_group = json_encode([
            'items' => ExampleGroupMapper::fetchChilds(intval($parent_id)),
            'seen' => true
        ]);
    }
}

    ?>
  </head>
  <body>
    <?php echo $twig->load('navbar.html.twig')->render(); ?>
    <main>
      <div class="container p-3">
        <div class="row px-3">
          <script id="json-vue" data-json="<?= h($json) ?>"></script>
          <script id="group-vue" data-json="<?= ($group_cd == '') ? '' : h("{ \"group_cd\": ${group_cd}, \"group_name\": \"${group_name}\" }") ?>"></script>
          <script id="disp-group-vue" data-json="<?= ($disp_group == '') ? '{&quot;items&quot;: null, &quot;seen&quot;: false}' : h($disp_group) ?>"></script>
          <script id="group-names-vue" data-json="<?= h($group_data_json) ?>"></script>
          <section id="disp-group" v-cloak>
            <table v-show="seen" class="table table-sm table-bordered">
              <thead>
                <tr>
                  <th v-for="key in gridColumns">
                    {{ key }}
                  </th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="item in items">
                  <td><a :href="'register.php?parent_id=' + item.parent_id">{{ item.parent_name }}</a> / <a :href="'register.php?group_cd=' + item.group_cd">{{ item.group_name }}</a></td>
                </tr>
              </tbody>
            </table>
          </section>
          <form name="save-form" action="register.php?group_cd=<?= $group_cd; ?>" method="post">
            <section id="app" v-cloak>
              <h2><a href="register.php?group_cd=<?= $group_cd; ?>">{{ group_name }}</a></h2>
              <span v-for="item in group_names">
                <span><a :href="'register.php?parent_id=' + item.group_cd">[{{ item.group_name }}]</a></span>
              </span>
              <table class="table table-sm table-bordered">
                <thead>
                  <tr>
                    <th v-for="key in gridColumns">
                      {{ key }}
                    </th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(item, index) in items"
                      :key="item.row_num">
                    <td>
                      <span v-if="item.insert_flag">
                        <select class="form-control" :name="'items[' + index + '][example][language_id]'"
                                v-on:change="addTemplate(item, item.example.language_id)"
                                v-model="item.example.language_id"
                                required>
                          <option v-for="language in languages" :value="language.language_id">
                            {{ language.language }}
                          </option>
                        </select>
                      </span>
                      <span v-else>
                        {{ item.example.language }}
                        <input :name="'items[' + index + '][example][language]'" type="hidden" v-model="item.example.language"/>
                        <input :name="'items[' + index + '][example][language_id]'" type="hidden" v-model="item.example.language_id"/>
                      </span>
                    </td>
                    <td>
                      <autosize-textarea class="form-control" :name="'items[' + index + '][example][example]'" v-model="item.example.example" required></autosize-textarea>
                    </td>
                    <td>
                      <input class="form-control" :name="'items[' + index + '][example][example_id]'" type="hidden" v-model.number="item.example.example_id"/>
                      <input :name="'items[' + index + '][group_cd]'" type="hidden" v-model.number="item.group_cd"/>
                      <input :name="'items[' + index + '][insert_flag]'" type="hidden" v-model="item.insert_flag"/>
                      <button class="btn btn-danger" type="button" v-on:click="remove(index, item.example.example_id)">削除</button>
                    </td>
                  </tr>
                </tbody>
              </table>
              <div style="display:none;">
                <span v-for="item in delete_target">
                  <input :name="'delete_target[]'" :key="item.example_id" type="number" v-model.number="item.example_id"/>
                </span>
              </div>
              <button class="btn btn-info" type="button" v-on:click="add">追加</button>
              <button class="btn btn-primary" type="submit">保存</button>
            </section>
          </form>
          <script src="assets/autosize/autosize.js"></script>
          <script src="assets/js/dispGroup.vue"></script>
          <script src="assets/js/register.vue"></script>
        </div>
      </div>
    </main>
    <?php echo $twig->load('footer.html')->render(); ?>
  </body>
</html>
