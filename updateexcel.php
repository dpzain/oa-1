<?php
if (isset($_GET)) {
    //列单元
    $b1 = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
    $b2 = array();
    for ($i = 0; $i < count($b1); $i++) {
        for ($j = 0; $j < count($b1); $j++) {
            $b2[] = $b1[$i] . $b1[$j];
        }
    }
    $b = array_merge($b1, $b2);
    //交换数组的键和值
    $b = array_flip($b);

    $ltarr = explode('/', $_GET['lt']);
    $rowindex = $ltarr[1] - 1;
    $colindex = $b[$ltarr[0]];
}
?>
