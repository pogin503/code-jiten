<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title></title>
    <meta charset="utf-8">
    <meta name="description" content="description">
    <meta name="author" content="SitePoint">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="">
    <!--[if lt IE 9]>
      <script src="//cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.js"></script>
      <script src="//cdnjs.cloudflare.com/ajax/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <link rel="shortcut icon" href="">
    <link rel="stylesheet" href="assets/css/styles.css?v=1.0">
    <link rel="stylesheet" href="assets/css/normalize.css">
    <link rel="stylesheet" href="assets/bootstrap-4.0.0-beta.3-dist/css/bootstrap.min.css">
    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.3/css/bootstrap.min.css" integrity="sha384-Zug+QiDoJOrZ5t4lssLdxGhVrurbmBWopoEl+M6BdEfwnCJZtKxi1KgxUyJq13dy" crossorigin="anonymous"> -->
    <!-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.3/js/bootstrap.min.js" integrity="sha384-a5N7Y/aK3qNeh15eJKGWxsqtnX/wWdSZSKp+81YjTmS15nvnvxKHuzaWwXHDli+4" crossorigin="anonymous"></script> -->
    <script src="assets/bootstrap-4.0.0-beta.3-dist/js/bootstrap.min.js" integrity="sha384-a5N7Y/aK3qNeh15eJKGWxsqtnX/wWdSZSKp+81YjTmS15nvnvxKHuzaWwXHDli+4" crossorigin="anonymous"></script>
  </head>
  <body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
      <a class="navbar-brand" href="#">Navbar</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
        <div class="navbar-nav">
          <a class="nav-item nav-link active" href="index.php">Home <span class="sr-only">(current)</span></a>
          <a class="nav-item nav-link" href="#">Features</a>
          <a class="nav-item nav-link" href="#">Pricing</a>
        </div>
      </div>
    </nav>
    <div id="main-container">
      <form action="post">
        <table>
          <tr>
            <th>カテゴリ</th>
            <th>Haskell</th>
            <th>Ruby</th>
            <th>EmacsLisp</th>
          </tr>
          <tr>
            <td>string length</td>
            <td>main = print $ length "123"</td>
            <td>puts "123".length</td>
            <td>(length "123") ;=> 3</td>
          </tr>
          <tr>
            <td>array length</td>
            <td></td>
            <td>[1,2,3].length</td>
            <td>(setq vec [1 2 3 4])<br>
              (length vec)</td>
          </tr>
        </table>
        <input name="save" type="submit" value="保存"/>
      </form>
      <!-- <script src="js/scripts.js"></script> -->
      <style>
        /* div {
           display: table;
           } */
        table {
          border-style: solid;
          /* width: 1; */
        }
        .container {
          display: flex;
        }
        .item {
          /* border-style: solid; */
          box-sizing: border-box;
        }
        .item-grow {
          flex-grow: 1;
        }
      </style>
      <h2 id="usage-title" style="display:inline;">
        String Length
      </h2>
      <span>&nbsp;<a href="#">edit</a></span>
      <div class="item-wrapper">
        <div>
          <span class="item-lang">
            Haskell
          </span>
        </div>
        <div class="item-content">
          <pre>main = print $ length "123"</pre>
        </div>
        <div class="item-output">
          3
        </div>
      </div> <!-- .item-wrapper end -->
      <div class="item-wrapper">
        <div>
          <span class="item-lang">
            Ruby
          </span>
        </div>
        <div class="item-content">
          <pre>puts "123".length</pre>
        </div>
        <div class="item-output">
          3
        </div>
      </div> <!-- .item-wrapper end -->
      <div style="display: flex">
        <ul class="">
          <li class="item">1</li>
          <li class="item">Haskell</li>
          <li class="item">Ruby</li>
          <li class="item">EmacsLisp</li>
        </ul>
        <ul class="">
          <li class="item">strnig length</li>
          <li class="item">main = print $ length "123"</li>
          <li class="item">puts "123".length</li>
          <li class="item">(length "123") ;=> 3</li>
        </ul>
      </div>
      <select id="category" name="">
        <option value="1">1. データ構造</option>
        <option value="2">2. 配列</option>
        <option value="3">3. 配列の長さ</option>
        <option value="4">2. 文字列</option>
        <option value="5">3. 文字列の長さ</option>
      </select>
      <br/>
      <select id="category" name="cat1">
        <option value="1">1. データ構造</option>
      </select>
      <select id="category" name="cat2">
        <option value="2">2. 配列</option>
        <option value="4">2. 文字列</option>
      </select>
      <select id="category" name="cat3">
        <option value="3">3. 配列の長さ</option>
        <option value="5">3. 文字列の長さ</option>
      </select>
    </div>
    <footer>
     Copyright pogin 2017
    </footer>
  </body>
</html>
