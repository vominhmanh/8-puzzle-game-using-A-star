<?php
    // get data from AJAX
    $tmp = $_GET['startState'];
    $tmp = json_decode($tmp, true);
    foreach($tmp AS $key => $element) {
        $start[intval($key)] = intval($element);
    }
    unset($start[0]);

    $goal = [1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5, 6 => 6, 7 => 7, 8 => 8, 9 => 9];
 
    if ($start === $goal) {
        echo null;
        die();
    }
    $ninePosition = array_search(9, $start, true); 

    // Initialise the Queue Set
    $Q = [];
    // Add StartState into O, with its information
    array_push($Q, ['currentState' => $start, 
                    'ninePos' => $ninePosition,    // position of number 9 (9 is the blank puzzle piece)
                    'pathCost' => 0,       //g(x)        // pathCost: amount of moves from Start state to this state
                    'Mahattan' => MahattanDistance($start), //h(x)  // calculate Mahattan Distance of state
                    'parentID' => null,            // ID of parent Node in Queue,
                                                   // I dont store parent State because it takes more time to back track
                    'nodeStatus' => 'inOpenSet'] );  // There are 2 status of a node: 1. inOpenSet: when we add a node into Q, 
                                                                                //    2. hasBeenVisited: when we choose a node to expand, this node will have been visited

    $queueCount = 1;
    while ( $queueCount > 0) {

        $n = stateHasMinFx();  // choose a node that has min f(x) = g(x) + h(x)
        $queueCount--;
        
        if ($n['currentID'] > 5000) {
            echo null;
            break;
        }
        if ($n['currentState'] === $goal) { // if the state reaches the GoalState 
            track($n);
            break;
        }
        
        // expand to 4 directions
        go($n, 'up');
        go($n, 'down');
        go($n, 'left');
        go($n, 'right');
        
        
    }

    function go($n, $direction) {
        // lấy dữ liệu từ $n
        $ninePosition = $n['ninePos'];
        $parentID = $n['currentID'];
        $newState =   $n['currentState'];
 
        switch ($direction) {
            case 'up':  if ($ninePosition > 3) {
                //Swap ninePos Puzzle with clicking Puzzle
                $tmp = $newState[$ninePosition];
                $newState[$ninePosition] = $newState[$ninePosition - 3];
                $newState[$ninePosition - 3] = $tmp;
                
                //Calculation the new info
                $newNinePosition = $ninePosition - 3 ;
                global $Q;
                $newStatePathCost = $Q[$parentID]['pathCost'] + 1; 
                $newStateMahattan = MahattanDistance($newState);
                
                // check if new state exists
                $searchPos = array_search($newState, array_column($Q, 'currentState' ), true);
                if ( $searchPos === false ) {  //if not, add to Queue
                    array_push($Q, ['currentState' => $newState, 
                                    'ninePos' => $newNinePosition, 
                                    'Mahattan' => $newStateMahattan,
                                    'pathCost' => $newStatePathCost,
                                    'parentID' => $parentID,
                                    'nodeStatus' => 'inOpenSet'] );
                    global $queueCount; $queueCount++;  // increase queue count
                    
                    // if existes,  compare new g(x) with old g(x), if greater than, update into new one
                } elseif ( $newStateMahattan + $newStatePathCost < $Q[$searchPos]['Mahattan'] + $Q[$searchPos]['pathCost']  ){
                        $Q[$searchPos]['Mahattan'] = $newStateMahattan;
                        $Q[$searchPos]['pathCost'] = $newStatePathCost;
                        $Q[$searchPos]['parentID'] = $parentID;
                        $Q[$searchPos]['nodeStatus'] = 'inOpenSet';

                        global $queueCount;  $queueCount++; 
                }
            }
            break;
                // do the same with down, left, right.
            case 'down': if ($ninePosition < 7){
                $tmp = $newState[$ninePosition];
                $newState[$ninePosition] = $newState[$ninePosition + 3];
                $newState[$ninePosition + 3] = $tmp;
                $newNinePosition = $ninePosition + 3 ;
                global $Q; global $mahattanArr;

                $newStatePathCost = $Q[$parentID]['pathCost'] + 1;
                $newStateMahattan = MahattanDistance($newState);
                
  //            var_dump(array_search($newState, array_column($Q, 'currentState' ), true));

                $searchPos = array_search($newState, array_column($Q, 'currentState' ), true);

                if ( $searchPos === false ) {
                    array_push($Q, ['currentState' => $newState, 
                                    'ninePos' => $newNinePosition, 
                                    'Mahattan' => $newStateMahattan,
                                    'pathCost' => $newStatePathCost,
                                    'parentID' => $parentID,
                                    'nodeStatus' => 'inOpenSet'] );
                                    global $queueCount;
                                    $queueCount++;
                } else { 
                    if ( $newStateMahattan + $newStatePathCost < $Q[$searchPos]['Mahattan'] + $Q[$searchPos]['pathCost']  ){
                        global $Q;

                        $Q[$searchPos]['Mahattan'] = $newStateMahattan;
                        $Q[$searchPos]['pathCost'] = $newStatePathCost;
                        $Q[$searchPos]['parentID'] = $parentID;
                        $Q[$searchPos]['nodeStatus'] = 'inOpenSet';

                        global $queueCount;
                        $queueCount++;
                    }
                }
            }
            break;

            case 'left': if ($ninePosition % 3 != 1){
                $tmp = $newState[$ninePosition];
                $newState[$ninePosition] = $newState[$ninePosition - 1];
                $newState[$ninePosition - 1] = $tmp;
                
                $newNinePosition = $ninePosition - 1 ;
                global $Q; global $mahattanArr;
                
                $newStatePathCost = $Q[$parentID]['pathCost'] + 1;
                $newStateMahattan = MahattanDistance($newState);
                
   //             var_dump(array_search($newState, array_column($Q, 'currentState' ), true));
                $searchPos = array_search($newState, array_column($Q, 'currentState' ), true);

                if ( $searchPos === false ) {
                    array_push($Q, ['currentState' => $newState, 
                                    'ninePos' => $newNinePosition, 
                                    'Mahattan' => $newStateMahattan,
                                    'pathCost' => $newStatePathCost,
                                    'parentID' => $parentID,
                                    'nodeStatus' => 'inOpenSet'] );
                                    global $queueCount;
                    $queueCount++;
          
                } else { 
                    if ( $newStateMahattan + $newStatePathCost < $Q[$searchPos]['Mahattan'] + $Q[$searchPos]['pathCost']  ){
                        global $Q;
                        $Q[$searchPos]['Mahattan'] = $newStateMahattan;
                        $Q[$searchPos]['pathCost'] = $newStatePathCost;
                        $Q[$searchPos]['parentID'] = $parentID;

                        $Q[$searchPos]['nodeStatus'] = 'inOpenSet';
                        global $queueCount;
                    $queueCount++;
                    
                    }
                }
            }
            break;

            case 'right': if ($ninePosition % 3 != 0){
                $tmp = $newState[$ninePosition];
                $newState[$ninePosition] = $newState[$ninePosition + 1];
                $newState[$ninePosition + 1] = $tmp;
                
                $newNinePosition = $ninePosition +1 ;
                global $Q; global $mahattanArr;
                
                $newStatePathCost = $Q[$parentID]['pathCost'] + 1;
                $newStateMahattan = MahattanDistance($newState);
                $searchPos = array_search($newState, array_column($Q, 'currentState' ), true);

                if ( $searchPos === false ) {
                    array_push($Q, ['currentState' => $newState, 
                                    'ninePos' => $newNinePosition, 
                                    'Mahattan' => $newStateMahattan,
                                    'pathCost' => $newStatePathCost,
                                    'parentID' => $parentID,
                                    'nodeStatus' => 'inOpenSet'] );
                                    global $queueCount;
                    $queueCount++;
    
                } else { 
                    if ( $newStateMahattan + $newStatePathCost < $Q[$searchPos]['Mahattan'] + $Q[$searchPos]['pathCost']  ){
                        global $Q;
                        $Q[$searchPos]['Mahattan'] = $newStateMahattan;
                        $Q[$searchPos]['pathCost'] = $newStatePathCost;
                        $Q[$searchPos]['parentID'] = $parentID;
                        $Q[$searchPos]['nodeStatus'] = 'inOpenSet';
                        global $queueCount;
                    $queueCount++;
   
                    }
                }
            }
            break;
        }
        
    }

    //function caculating mahattan distance of a state 
    function MahattanDistance($state) {
        // initialise the puzzle co-ordinate
        $p = [[0,0], ['x' => 1, 'y' => 1], ['x' => 1, 'y' => 2], ['x' => 1, 'y' => 3], 
                     ['x' => 2, 'y' => 1], ['x' => 2, 'y' => 2], ['x' => 2, 'y' => 3],
                     ['x' => 3, 'y' => 1], ['x' => 3, 'y' => 2], ['x' => 3, 'y' => 3] ];

        $m_distance = 0;
        // calculate the mahattan distance
        foreach ($state AS $place => $number) {
           $thisMahattan =    abs( $p[$place]['x'] - $p[$number]['x'] ) 
                            + abs( $p[$place]['y'] - $p[$number]['y'] ) ;
           $m_distance += $thisMahattan;
        }
        return $m_distance;
    }

    // function choosing the state that has min f(x)
    function stateHasMinFx () {
        global $Q;
        $minDistance = 99;
        $minKey = 0;
        foreach( $Q as $key => $aNode ) {
            if ( $aNode['nodeStatus'] == 'inOpenSet' ) {
                if ($aNode['Mahattan'] + $aNode['pathCost'] < $minDistance) {
                    $minDistance = $aNode['Mahattan'] + $aNode['pathCost'];
                    $minKey = $key;
                }
            }
        }

        $Q[$minKey]['nodeStatus'] = 'hasBeenVisited' ; // đánh dấu xóa khỏi Queue
        $Q[$minKey]['currentID'] = $minKey;
        return $Q [$minKey];
    }

    //back-tracking the path
    function track ($n) {
        global $Q;
        $currentID = $n['currentID']; // lấy ID hiện tại
        $solution = [];
        $solution_PathCost = 0;
        do {
            array_push($solution, $Q[$currentID]['currentState']);
            $solution_PathCost ++;
            $currentID =  $Q[$currentID]['parentID'];

        } while ( $Q[$currentID]['currentID'] !== 0 );
        //use JSON to transfer the Path to client. See on "script.js"
        echo json_encode($solution);
    }
?>