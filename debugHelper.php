<?php

/**
 * Inspeciona uma ou mais variáveis e para a execução do script.
 * @param mixed ...$vars As variáveis para inspecionar.
 */
function dd(...$vars)
{
    echo '<pre>'; // Prepara a formatação para ficar legível

    // Loop para inspecionar todas as variáveis passadas para a função
    foreach ($vars as $var) {
        var_dump($var);
    }

    echo '</pre>';
    die(); // Para a execução
}

