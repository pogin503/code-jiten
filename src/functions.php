<?php

/**
 * Escape function
 *
 * @param string $str value
 *
 * @return string
 */
function h($str)
{
    return htmlspecialchars($str, ENT_QUOTES);
}

/**
 * Pretty print
 *
 * @param mixed $obj any object
 *
 * @return void
 */
function pp($obj)
{
    echo '<pre>';
    var_dump($obj);
    echo '</pre>';
}

function lang2pygmentsLexer($lang)
{
    switch ($lang) {
        case 'EmacsLisp':
            return 'Lisp';
            break;
        default:
            return $lang;
            break;
    }
}
