<html>
  <head>
<?php
require_once './vendor/autoload.php';
require_once './src/functions.php';
require_once './models/ExampleGroupMapper.php';

$loader = new Twig_Loader_Filesystem('views');
$twig = new Twig_Environment($loader, array(
    //'cache' => './compilation_cache',
    'debug' => true,
));
$twig->addExtension(new Twig_Extension_Debug());
$template = $twig->load('header.html.twig');
echo $template->render();

$disp_group = json_encode([
    'items' => ExampleGroupMapper::fetchLeaf()->toArray(),
    'seen' => true
]);
?>
  </head>
  <body>
    <?php echo $twig->load('navbar.html.twig')->render(); ?>

    <main>
      <script id="disp-group-vue" data-json="<?= ($disp_group == '') ? '{&quot;items&quot;: null, &quot;seen&quot;: false}' : h($disp_group) ?>"></script>
      <div class="container p-3">
        <div class="row px-3">
          <section id="disp-group" v-cloak>
            <table v-show="seen" class="table table-sm table-bordered">
              <thead>
                <tr>
                  <th v-for="key in gridColumns">
                    {{ key }}
                  </th>
                </tr>
              </thead>
              <tr v-for="item in items">
                <td><a v-bind:href="'register.php?group_cd=' + item.group_cd">{{ item.group_name }}</a></td>
              </tr>
            </table>
          </section>
        </div>
      </div>
      <script src="assets/js/dispGroup.vue"></script>
    </main>
    <?php echo $twig->load('footer.html')->render(); ?>
  </body>
</html>
