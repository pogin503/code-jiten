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

    <script id="disp-group-vue" data-json="<?= ($disp_group == '') ? '{&quot;items&quot;: null, &quot;seen&quot;: false}' : h($disp_group) ?>"></script>
    <main>
      <div class="container">
        <div class="row">
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
                <td><a v-bind:href="'register.php?group_cd=' + item.group_cd">{{ item.group_name }}</a></td>
              </tr>
            </table>
          </section>
        </div>
      </div>
    </main>
    <script src="assets/js/dispGroup.vue"></script>
    <?php echo $twig->load('footer.html')->render(); ?>
  </body>
</html>
