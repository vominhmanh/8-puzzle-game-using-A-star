<?php
    $O = [];
    $start = [1 => 0, 2 => 2, 3 => 3, 4 => 1, 5 => 4, 6 => 5, 7 => 7, 8 => 8, 9 => 6];
    $goal = [1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5, 6 => 6, 7 => 7, 8 => 8, 9 => 0];

    array_push($O, $start);
    $k = 0;
    while ($k < count($O)) {
        $n = $O[$k++];
        // echo '<pre>';
        // print_r($n);
        // echo '</pre>';

        if ($n === $goal) {
            echo '<pre>';
            print_r($n);
            echo '</pre>';
            break;
        }

        go($n, 'up');
        go($n, 'down');
        go($n, 'left');
        go($n, 'right');
    }

    function go($n, $direction) {
        $ninePosition = array_search(0, $n, true); 
        switch ($direction) {
            case 'up':  if ($ninePosition > 3){
                $tmp = $n[$ninePosition];
                $n[$ninePosition] = $n[$ninePosition - 3];
                $n[$ninePosition - 3] = $tmp;
                global $O;
                if (! array_search($n, $O, true) ) {
                    array_push($O, $n);
                }
                break;
                
            }
            case 'down': if ($ninePosition < 7){
                $tmp = $n[$ninePosition];
                $n[$ninePosition] = $n[$ninePosition + 3];
                $n[$ninePosition + 3] = $tmp;
                global $O;
                if (! array_search($n, $O, true) ) {
                    array_push($O, $n);
                }
                break;
            }
            case 'left': if ($ninePosition % 3 != 1){
                $tmp = $n[$ninePosition];
                $n[$ninePosition] = $n[$ninePosition - 1];
                $n[$ninePosition - 1] = $tmp;
                global $O;
                if (! array_search($n, $O, true) ) {
                    array_push($O, $n);
                }
                break;
            }
            case 'right': if ($ninePosition % 3 != 0){
                $tmp = $n[$ninePosition];
                $n[$ninePosition] = $n[$ninePosition + 1];
                $n[$ninePosition + 1] = $tmp;
                global $O;
                if (! array_search($n, $O, true) ) {
                    array_push($O, $n);
                }
                break;
            }
        }
       
    }
    
    // $result = json_encode($start);
    // echo $result;
?>