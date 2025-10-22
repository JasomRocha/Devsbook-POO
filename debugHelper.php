<?php
/**
 * Inspeciona uma ou mais variáveis e, opcionalmente, para a execução do script.
 *
 * @param mixed ...$vars  As variáveis para inspecionar.
 * @param bool  $exit     Define se o script deve encerrar após o dump (padrão: true).
 */
function dd(...$vars, bool $exit = true)
{
    echo '<pre>'; // Formatação legível no navegador

    // Loop para inspecionar todas as variáveis passadas
    foreach ($vars as $var) {
        var_dump($var);
    }

    echo '</pre>';

    // Interrompe a execução apenas se $exit for verdadeiro
    if ($exit) {
        die();
    }
}
