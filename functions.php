<?php

    $class_id = 0;

    function logout(){
        if(isset($_POST['logout'])){
          unset($_SESSION['loggedIn']);
      
          header('location:index.php');
        }
        if(!isset($_SESSION['loggedIn'])){
          header('location:index.php');
        }
      }

      function str_replace_first($search, $replace, $subject){
        $search = '/'.preg_quote($search, '/').'/';
        return preg_replace($search, $replace, $subject, 1);
      }
  
  
      function cards(){
          require "connection.php";
        
          $school_id = $_SESSION['school_id'];
          $result = mysqli_query($conn, "select * from classes where school_id='$school_id'");
        
          $res = '';

          while($row = mysqli_fetch_assoc($result)){
            $rgb = array();
            foreach(array('r', 'g', 'b') as $color){
              $rgb[$color] = mt_rand(125, 175);
            }
            $res .= '<a href="classes.php?class_id='.$row['id'].'">
                      <div class="card shadow-sm" style="background-color:rgb('.$rgb["r"].','.$rgb["g"].','.$rgb["b"].');">
                        <strong>
                          <svg class="bd-placeholder-img card-img-top" width="100%" height="225" xmlns="http://www.w3.org/2000/svg"
                              role="img" aria-label="Placeholder: Thumbnail" preserveAspectRatio="xMidYMid slice" focusable="false">
                              <text x="50%" y="50%" fill="#eceeef"
                              dy=".3em">'.$row['name'].'</text>
                          </svg>
                        </strong>
                      </div>
                    </a>';
          }
          return $res;
      }
  
    function students(){
        require "connection.php";
  
        $result = mysqli_query($conn, 'select * from students where class_id='.$_GET["class_id"].'');
        
        $res = '';
        while($row = mysqli_fetch_assoc($result)){
              $rgb = array();
              foreach(array('r', 'g', 'b') as $color){
                $rgb[$color] = mt_rand(125, 175);
              }
              $res .= '<a href="students.php?id='.$row['id'].'&class_id='.$_GET['class_id'].'" class="text-decoration-none">
                        <div class="card shadow-sm px-2 py-5" style="background-color:rgb('.$rgb["r"].','.$rgb["g"].','.$rgb["b"].');">
                          <div class="col d-flex align-items-start mx-auto">
                            <div class="text-center">
                              <h3 class="fw-bold mb-0 fs-4 text-white">'.$row['name'].'</h3>
                              <h4 class="fw-bold mb-0 fs-4 text-white">'.$row['id'].' номер</h4>
                            </div>
                          </div>
                        </div>
                      </a>';
        }
        return $res;
    }

    function grades_and_skips(){
        require "connection.php";

        $result = mysqli_query($conn, 'select * from students where id='.$_GET["id"].' and class_id='.$_GET["class_id"].'');

        $columns = array("bel", "maths", "english");

        $cols_in_bg = array();
        $cols_in_bg['bel'] = "БЕЛ";
        $cols_in_bg['maths'] = "МАТЕМАТИКА";
        $cols_in_bg['english'] = "АНГЛИЙСКИ";

        $res = '';
        $row = mysqli_fetch_assoc($result);
        foreach($columns as $column){
          $grades = array();
          foreach(explode(" ", $row[$column]) as $grade){
            array_push($grades, intval($grade));
          }
          if($row[$column] != '' || $row[$column] != null){
            $final_grade = 0;
            foreach($grades as $grade){
              $final_grade += $grade;
            }
            $final_grade /= sizeof($grades);
              //oценки
            $res .= '<tr>
                      <td><em>'.$cols_in_bg[$column].'</em></td>
                      <td>'.str_replace(" ", ", ", $row[$column]).'</td>
                      <td>'.$row['classes_skipped_'.$column.''].'</td>
                      <td>'.round($final_grade, 3).'</td>
                      <td class="td-class d-flex flex-row align-middle justify-content-center">
                        <form action="" method="post" class="d-flex flex-row">
                            <input type="hidden" value="'.$column.'" name="subject">
                            <select class="form-select border-secondary" name="grades" id="grades">
                              <option value="2">2</option>
                              <option value="3">3</option>
                              <option value="4">4</option>
                              <option value="5">5</option>
                              <option value="6">6</option>
                            </select>
                            <a href="?id='.$_GET['id'].'&class_id='.$_GET['class_id'].'"><button class="btn btn-outline-secondary" type="submit" name="add_grade">+</button></a>
                        </form>
                      </td>
                      <td>
                        <form action="" method="post" class="d-flex flex-row align-middle justify-content-center">
                            <input type="hidden" value="'.$column.'" name="skips_subject">
                            <input type="hidden" value="'.$row['classes_skipped_'.$column.''].'" name="skips">
                            <a href="?id='.$_GET['id'].'&class_id='.$_GET['class_id'].'"><button class="btn btn-outline-secondary" type="submit" name="remove_skip">-</button></a>
                            <a href="?id='.$_GET['id'].'&class_id='.$_GET['class_id'].'"><button class="btn btn-outline-secondary" type="submit" name="add_skip">+</button></a>
                        </form>
                      </td>
                   </tr>';
          } else {
            $res .= '<tr>
                      <td><em>'.$cols_in_bg[$column].'</em></td>
                      <td></td>
                      <td>'.$row['classes_skipped_'.$column.''].'</td>
                      <td></td>
                      <td class="td-class d-flex flex-row align-middle justify-content-center">
                        <form action="" method="post" class="d-flex flex-row">
                            <input type="hidden" value="'.$column.'" name="subject">
                            <a href="?id='.$_GET['id'].'&class_id='.$_GET['class_id'].'"><button class="btn btn-outline-secondary" type="submit" name="remove_grade">-</button></a>
                            <select class="form-select" name="grades" id="grades">
                              <option value="2">2</option>
                              <option value="3">3</option>
                              <option value="4">4</option>
                              <option value="5">5</option>
                              <option value="6">6</option>
                            </select>
                            <a href="?id='.$_GET['id'].'&class_id='.$_GET['class_id'].'"><button class="btn btn-outline-secondary" type="submit" name="add_grade">+</button></a>
                        </form>
                      </td>
                      <td>
                        <form action="" method="post" class="d-flex flex-row align-middle justify-content-center">
                            <input type="hidden" value="'.$column.'" name="skips_subject">
                            <a href="?id='.$_GET['id'].'&class_id='.$_GET['class_id'].'"><button class="btn btn-outline-secondary" type="submit" name="remove_skip">-</button></a>
                            <a href="?id='.$_GET['id'].'&class_id='.$_GET['class_id'].'"><button class="btn btn-outline-secondary" type="submit" name="add_skip">+</button></a>
                        </form>
                      </td>
                   </tr>';
          }
      }
        
      return $res;

    }

    function leaderboard(){
      require "connection.php";

        if($_GET['class_id'] == 1 || $_GET['class_id'] == 2 || $_GET['class_id'] == 5){
          $school_id = 1;
        } else if($_GET['class_id'] == 3 || $_GET['class_id'] == 4 || $_GET['class_id'] == 6){
          $school_id = 2;
        } else if($_GET['class_id'] == 7 || $_GET['class_id'] == 8 || $_GET['class_id'] == 9){
          $school_id = 3;
        } else if($_GET['class_id'] == 10 || $_GET['class_id'] == 11 || $_GET['class_id'] == 12){
          $school_id = 4;
        } else if($_GET['class_id'] == 13 || $_GET['class_id'] == 14 || $_GET['class_id'] == 15){
          $school_id = 5;
        }

        $res = '';
        
        $classes = mysqli_query($conn, 'select * from classes where school_id='.$school_id.'');
        $select = 'select * from students where class_id='.$_GET['class_id'].'';

        while($row = mysqli_fetch_assoc($classes)){
          $select .= ' or class_id='.$row['id'].'';
        }

        $result = mysqli_query($conn, $select);

        $bel = array();
        $maths = array();
        $english = array();
        // $skips = array();
        // $final_grade = array();

        while($row = mysqli_fetch_assoc($result)){

          array_push($bel, $row);
          array_push($maths, $row);
          array_push($english, $row);
          // array_push($skips, $row);
          // array_push($final_grade, $row);

        }

        for($i = 0; $i < 30; $i++){

          bubbleSort($bel, 'bel');
          bubbleSort($maths, 'maths');
          bubbleSort($english, 'english');

          $b = 0;
          $counter = 0;
          foreach(explode(" ", $bel[$i]['bel']) as $grade){
            ++$counter;
            $b += intval($grade);
          }
          $b = round($b/$counter, 3);
          $m = 0;
          $counter = 0;
          foreach(explode(" ", $maths[$i]['maths']) as $grade){
            ++$counter;
            $m += intval($grade);
          }
          $m = round($m/$counter, 3);
          $e = 0;
          $counter = 0;
          foreach(explode(" ", $english[$i]['english']) as $grade){
            ++$counter;
            $e += intval($grade);
          }
          $e = round($e/$counter, 3);

          $res .= '<tr>
                    <td>#'.strval($i+1).'</td>
                    <td>'.$bel[$i]['name'].' - '. number_format($b, 2, '.', ',') .' | '.$bel[$i]['id'].' номер в '.mysqli_fetch_assoc(mysqli_query($conn, "select * from classes where id=".$bel[$i]['class_id'].""))['name'].'</td>
                    <td>'.$maths[$i]['name'].' - '. number_format($m, 2, '.', ',') .' | '.$maths[$i]['id'].' номер в '.mysqli_fetch_assoc(mysqli_query($conn, "select * from classes where id=".$maths[$i]['class_id'].""))['name'].'</td>
                    <td>'.$english[$i]['name'].' - '. number_format($e, 2, '.', ',') .' | '.$english[$i]['id'].' номер в '.mysqli_fetch_assoc(mysqli_query($conn, "select * from classes where id=".$english[$i]['class_id'].""))['name'].'</td>
                  </tr>';
        }

        echo $res;

    }

    function bubbleSort(&$students, $subject){
      $n = sizeof($students);
      for($i = 0; $i < $n; $i++){
        for ($j = 0; $j < $n - $i - 1; $j++){
          $grades1 = 0;
          $grades2 = 0;

          $count = 0;
          foreach(explode(" ", $students[$j][$subject]) as $grade){
            ++$count;
            $grades1 += intval($grade);
          }
          $grades1 /= $count;
          $count = 0;
          foreach(explode(" ", $students[$j+1][$subject]) as $grade){
            ++$count;
            $grades2 += intval($grade);
          }
          $grades2 /= $count;

          if ($grades1 < $grades2){
            $temp = $students[$j];
            $students[$j] = $students[$j+1];
            $students[$j+1] = $temp;
          }
        }
      }
    }

?>