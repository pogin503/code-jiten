<!doctype html>
<html>
<?php
require_once 'models/Template.php';
require_once 'models/Language.php';
require_once 'src/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['template'])) {
        $template = (new Template());
        foreach ($_POST['template'] as $row) {
            $template->updateTemplate(intval($row['language_id']), $row['template']);
        }

    }
}
$loader = new Twig_Loader_Filesystem('views');
$twig = new Twig_Environment($loader, array(
    //'cache' => './compilation_cache',
    'debug' => true,
));

$twig->addExtension(new Twig_Extension_Debug());
echo $twig->load('header.html.twig')->render();

$languages = (new Language())->all();
$templates = (new Template())->getWithLanguage();
$template_array = [
    'templates' => $templates,
    'languages' => $languages,
];

$json = json_encode($template_array);

?>
  <?php echo $twig->load('navbar.html.twig')->render(); ?>
  <body>
    <main>
      <div class="container p-3">
        <div class="row px-3">
          <form name="save-form" action="register_template.php" method="post">
          <section id="template" v-cloak>
              <table class="table table-sm">
                <thead>
                  <thead>
                    <tr>
                      <th v-for="key in gridColumns">
                        {{ key }}
                      </th>
                    </tr>
                  </thead>
                </thead>
              <tbody>
                <tr v-for="(template, index) in templates" :key="template.language_id">
                  <td>
                    {{ template.language }}
                    <input :name="'template[' + index + '][language_id]'" type="hidden" :value="template.language_id"/>
                  </td>
                  <td>
                    <autosize-textarea class="form-control"
                                         :name="'template[' + index + '][template]'"
                                         v-model="template.template"></autosize-textarea>
                  </td>
                  </tr>
              </tbody>
            </table>
            <button class="btn btn-info" type="button" v-on:click="add" disabled>追加</button>
            <button class="btn btn-primary" type="submit">保存</button>
          </section>
        </form>
        <script id="disp-vue" data-json="<?php echo h($json); ?>"></script>
        <script src="assets/autosize/autosize.js"></script>
        <script>
         const AutosizeTextarea = {
              props: [ 'value' ],
              template: '<textarea rows="3" cols="60">{{ value }}</textarea>',
              mounted: function () {
                autosize(this.$el);
              }
            };
            const json = JSON.parse(document.getElementById('disp-vue').dataset.json);
            var d = Object.assign({},
              json,
              {
                gridColumns: ['言語', 'テンプレート']
              }
            );
            var v = new Vue({
              components: {
                'autosize-textarea': AutosizeTextarea
                    },
              el: '#template',
              data: d,
              methods: {
                add: function (event) {
                  v.$data.items.push(
                    Object.assign(
                      {}, {
                        template: '',
                        insert_flag: true,
                      }));
                  return false;
                }
              },
            });
          </script>
        </div>
      </div>
    </main>
  </body>
</html>
