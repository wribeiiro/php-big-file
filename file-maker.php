<?php

$biggerFile = fopen('bigger-file.csv', 'w');

for ($i = 1; $i < 10_000_000; $i++){
    fputcsv($biggerFile, ["Deu Beyblade $i", rand(1, 100)]);
}

fclose($biggerFile);
