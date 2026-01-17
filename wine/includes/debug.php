<?php

function dg($data) {
    echo '<pre style="background-color:black; color:white;padding: 1rem;">';
    var_dump($data);
    echo '</pre>';
}

function dd($data) {
    echo '<pre style="background-color:black; color:white;padding: 1rem;">';
    var_dump($data);
    echo '</pre>';
    die();
}