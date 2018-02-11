<?php
function h($str)
{
    return htmlspecialchars($str, ENT_QUOTES);
}

function pp($obj) {
    echo '<pre>';
    var_dump($obj);
    echo '</pre>';
}

function lang2pygmentsLexer($lang) {
    switch ($lang) {
        case 'EmacsLisp':
            return 'Lisp';
            break;
        default:
            return $lang;
            break;
    }
}
