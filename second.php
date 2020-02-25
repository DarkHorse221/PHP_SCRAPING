<?php
    session_start(); // DO CALL ON TOP OF BOTH PAGES
    // echo '<pre>';
    // print_r($_SESSION['result']) ; // GIVES SAME $array FOR BOTH PAGES

    $result1 = array(array("Author Name", "Total Blogs", "Total Reach"));
        
    foreach($_SESSION['result'] as $key => $value){
        $exist = false; $index = 0;
        if($key != 0){
            foreach($result1 as $k => $val){
                if(in_array($value[0], $val)){
                    $exist = true;
                    $index = $k;
                    break;
                }
            }
            if($exist){
                $result1[$index][1] += 1;
                $result1[$index][2] += $value[5];
            }
            else{
                $result1[] = [$value[0], 1, $value[5]];
            }
        }
    }

    // echo '<pre>';
    // print_r($result1) ;


    header("Content-Disposition: attachment; filename=\"report2.xls\"");
    header("Content-Type: application/vnd.ms-excel;");
    header("Pragma: no-cache");
    header("Expires: 0");
    $out = fopen("php://output", 'w');
    foreach ($result1 as $data)
    {
    fputcsv($out, $data,"\t");
    }
    fclose($out);
?>