<!DOCTYPE html>
<html>
  <head>
    <?php
    require_once './vendor/autoload.php';
    require_once './src/functions.php';
    require_once './models/ExampleGroup.php';
    require_once './models/ExampleGroupMapper.php';

    $loader = new Twig_Loader_Filesystem('views');
    $twig = new Twig_Environment($loader, array(
      //'cache' => './compilation_cache',
      'debug' => true,
    ));

    $twig->addExtension(new Twig_Extension_Debug());
    $template = $twig->load('header.html.twig');
    echo $template->render();
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $mapper = new ExampleGroupMapper();
      foreach($_POST['items'] as $row) {
        $mapper->updateGroup(
          $row['group_cd'],
          $row['group_name'],
          $row['desc'],
          $row['disp_flag'],
          (isset($row['parent_id'])) ? $row['parent_id'] : 0
        );
      }
      if (!empty($_POST['insert_target'])) {
        foreach($_POST['insert_target'] as $row) {
          if (isset($row['group_name'])
            ||  isset($row['desc'])
            ||  isset($row['disp_flag'])
            ||  !empty($row['parent_id']))
          {
            $mapper->insertGroup(
              $row['group_name'],
              $row['desc'],
              $row['disp_flag'],
              (isset($row['parent_id'])) ? $row['parent_id'] : 0
            );
          }
        }
      }

      if (!empty($_POST['delete_target'])) {
        $mapper->deleteGroup($_POST['delete_target']);
      }
    }

    $disp_group_record = ExampleGroupMapper::orderBy('parent_id', 'asc')->get();

    $disp_group = json_encode([
      'items' => array_map(function($record) {
        return [
          'group' => $record->toArray(),
            'insert_flag' => false,
            'parent_id' => -1,
        ];
      }, array_map(function($record) {
        return new ExampleGroup($record);
      }, $disp_group_record->toArray())),
    ]);
    ?>
  </head>
  <body>
    <?php echo $twig->load('navbar.html.twig')->render(); ?>

    <main>
      <div class="container p-3">
        <div class="row px-3">
          <script id="disp-group-vue" data-json="<?= ($disp_group == '') ? '{&quot;items&quot;: null}' : h($disp_group) ?>"></script>
          <form name="save-form" action="group_register.php" method="post">
            <section id="disp-group" v-cloak>
              <table  class="table table-sm table-bordered">
                <thead>
                  <tr>
                    <th v-for="key in gridColumns">
                      {{ key }}
                    </th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(item, index) in items">
                    <td>
                      <input class="form-control" :name="'items[' + index + '][group_name]'" type="text" v-model="item.group.group_name" required/>
                      <input :name="'items[' + index + '][group_cd]'" type="hidden" v-model.number="item.group.group_cd" required/>
                      <input :name="'items[' + index + '][insert_flag]'" type="hidden" v-model="item.group.insert_flag"/>
                    </td>
                    <td>
                      <select class="form-control" :name="'items[' + index + '][parent_id]'" v-model="item.group.parent_id">
                        <option v-for="group in group_names" :value="group.group_cd">
                          {{ group.group_name }}
                        </option>
                      </select>
                    </td>
                    <td>
                      <input class="form-control" :name="'items[' + index + '][desc]'" type="text" v-model.number="item.group.desc"/>
                    </td>
                    <td>
                      <select class="form-control" :name="'items[' + index + '][disp_flag]'" v-model="item.group.disp_flag" required>
                        <option v-for="disp_flag in [{ value: 0, label: '非表示'}, { value: 1, label: '表示'}]" :value="disp_flag.value">
                          {{ disp_flag.label }}
                        </option>
                      </select>
                    </td>
                    <td>
                      <button class="btn btn-danger" type="button" v-on:click="remove(index, item.group.group_cd)">削除</button>
                    </td>
                  </tr>
                </tbody>
              </table>
              <div style="display:none">
                <span v-for="item in delete_target">
                  <input :name="'delete_target[]'" :key="item.group_cd" type="number" v-model.number="item.group_cd"/>
                </span>
              </div>
              <div v-show="show_flag">
                <hr style="border:1px solid #000000;" ></hr>
                <table>
                  <tbody>
                    <tr v-for="(item, index) in insert_target">
                      <td>
                        <input class="form-control" :name="'insert_target[' + index + '][group_name]'" type="text" v-model="item.group_name" required/>
                        <input :name="'insert_target[' + index + '][insert_flag]'" type="hidden" v-model="item.insert_flag"/>
                      </td>
                      <td>
                        <select class="form-control" :name="'insert_target[' + index + '][parent_id]'" v-model="item.parent_id">
                          <option v-for="group in group_names" :value="group.group_cd">
                            {{ group.group_name }}
                          </option>
                        </select>
                      </td>
                      <td>
                        <input class="form-control" :name="'insert_target[' + index + '][desc]'" type="text" v-model.number="item.desc"/>
                      </td>
                      <td>
                        <select class="form-control" :name="'insert_target[' + index + '][disp_flag]'" v-model="item.disp_flag" required>
                          <option v-for="disp_flag in options" :value="disp_flag.value" selected="{{ disp_flag.value == item.disp_flag }}">
                            {{ disp_flag.label }}
                          </option>
                        </select>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <button class="btn btn-info" type="button" v-on:click="add">追加</button>
              <button class="btn btn-primary" type="submit">保存</button>
            </section>
          </form>
          <script src="assets/js/group.vue"></script>
        </div>
      </div>
    </main>
    <?php echo $twig->load('footer.html')->render(); ?>
  </body>
</html>
