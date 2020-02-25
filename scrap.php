<?php
    $start = $_POST["Start_Date"];
    $end = $_POST["End_Date"];

    include("simple_html_dom.php");

    $data = array();

    // scrap blog data start

    $pageb = 0;
    while (file_get_html('https://enterprisedb.com/blog?page='.$pageb."'")->find('div[class="blog-date"]', 0) != null){
        $html[$pageb] = file_get_html('https://enterprisedb.com/blog?page='.$pageb."'");
        $pageb++;     
    }

    for ($j = 0; $j < $pageb; $j++){
        for ($i = 0; $i < 10 ; $i ++) {
               $temp = date('Y-m-d', strtotime(strip_tags((string)($html[$j]->find('div[class="blog-date"]', $i)))));
               if ($start < $temp && $end > $temp){
                   array_push($data, array(
                        (string)strip_tags($html[$j]->find('div[class="blog-post-author"]', $i )),
                        date('Y-m-d', strtotime(strip_tags((string)($html[$j]->find('div[class="blog-date"]', $i))))),
                        "Blog",
                        strip_tags((string)$html[$j]->find('div[class="blog-post-title"]', $i)),
                        "www.enterprisedb.com" . ($html[$j]->find('div[class="blog-post-title"] a', $i)->href))
                   );
               }
        }
    }

    // scrap blog data end

    
    // scrap tutorial data start

    $page = 0;
    while (file_get_html('https://enterprisedb.com/postgres-tutorials?page='.$page."'")->find('div[class="views-field views-field-created"] span', 0) != null){
        $htmlt[$page] = file_get_html('https://enterprisedb.com/postgres-tutorials?page='.$page."'");
        $page++;     
    }

    for ($k = 0; $k < count($htmlt); $k++){
        for ($i = 0; $i < 50 ; $i++) {
            $temp = date('Y-m-d', strtotime(strip_tags((string)($htmlt[$k]->find('div[class="views-field views-field-created"] span', $i)))));
            if ($temp!=null && $start<$temp && $end>$temp){
                array_push($data, array(
                        substr((str_replace(array(' |'), '', (string)strip_tags($htmlt[$k]->find('div[class="views-field views-field-field-authored-by-ref"]', $i)))), 7),
                        date('Y-m-d', strtotime(strip_tags((string)($htmlt[$k]->find('div[class="views-field views-field-created"] span', $i))))),
                        "Tutorial",
                        substr((strip_tags((string)$htmlt[$k]->find('div[class="views-field views-field-title"]', $i))), 7),
                        "www.enterprisedb.com" . ($htmlt[$k]->find('div[class="views-field views-field-title"] span a', $i)->href))
                );
            }
    
        }
        
    }

    // scrap tutorial data end

   //excel import module start

   $result = array(array("Author Name", "Date", "Type", "Blog Title", "Link/page", "Pageviews", "Unique Pageviews", "Avg. Time on Page", "Entrances", "Bounce Rate", "% Exit", "Page Value"));

   $dir_path_blog = "blog";
   if(is_dir($dir_path_blog)){
       $files_blog = opendir($dir_path_blog);
       if($files_blog){
           while (($file_name_blog = readdir($files_blog)) !== FALSE){
               if($file_name_blog!= '.' && $file_name_blog!='..'){
                   require_once "Classes/PHPExcel.php";
                   $file_path_blog = 'blog/'.$file_name_blog;
                   $blogexcel = PHPExcel_IOFactory::load($file_path_blog);
                   $blogexcel->setActiveSheetIndex(0);
                   $blog = array();
                   $i = 2;
                   while($blogexcel->getActiveSheet()->getCell('A'.$i)->getValue() != ""){
                       $page =$blogexcel->getActiveSheet()->getCell('A'.$i)->getValue();
                       $pageview = $blogexcel->getActiveSheet()->getCell('B'.$i)->getValue();
                       $unique = $blogexcel->getActiveSheet()->getCell('C'.$i)->getValue();
                       $avg = $blogexcel->getActiveSheet()->getCell('D'.$i)->getValue();
                       $entrance = $blogexcel->getActiveSheet()->getCell('E'.$i)->getValue();
                       $bounce = $blogexcel->getActiveSheet()->getCell('F'.$i)->getValue();
                       $exit = $blogexcel->getActiveSheet()->getCell('G'.$i)->getValue();
                       $pagevalue = $blogexcel->getActiveSheet()->getCell('H'.$i)->getValue();
                       array_push($blog, array(
                           $page,
                           $pageview,
                           $unique,
                           $avg,
                           $entrance,
                           $bounce,
                           $exit,
                           $pagevalue
                       ));
                       $i++;

                   }
                   
                   for($i = 0; $i < count($data); $i++){
                       for($j = 0; $j < count($blog); $j++ ){
                           if($data[$i][4] == $blog[$j][0]){
                               array_push($result, array(
                                   $data[$i][0],
                                   $data[$i][1],
                                   $data[$i][2],
                                   $data[$i][3],
                                   $blog[$j][0],
                                   $blog[$j][1],
                                   $blog[$j][2],
                                   $blog[$j][3],
                                   $blog[$j][4],
                                   $blog[$j][5],
                                   $blog[$j][6],
                                   $blog[$j][7]
                               ));
                           }
                       }
                   }

               }

           }
       }
   }


   $dir_path_tutorial = "tutorial";
   if(is_dir($dir_path_tutorial)){
       $files_tutorial = opendir($dir_path_tutorial);
       if($files_tutorial){
           while (($file_name_tutorial = readdir($files_tutorial)) !== FALSE){
               if($file_name_tutorial!= '.' && $file_name_tutorial!='..'){
                   require_once "Classes/PHPExcel.php";
                   $file_path_tutorial = 'tutorial/'.$file_name_tutorial;
                   $tutorialexcel = PHPExcel_IOFactory::load($file_path_tutorial);
                   $tutorialexcel->setActiveSheetIndex(0);
                   $tutorial = array();
                   $i = 2;
                   while($tutorialexcel->getActiveSheet()->getCell('A'.$i)->getValue() != ""){
                       $page =$tutorialexcel->getActiveSheet()->getCell('A'.$i)->getValue();
                       $pageview = $tutorialexcel->getActiveSheet()->getCell('B'.$i)->getValue();
                       $unique = $tutorialexcel->getActiveSheet()->getCell('C'.$i)->getValue();
                       $avg = $tutorialexcel->getActiveSheet()->getCell('D'.$i)->getValue();
                       $entrance = $tutorialexcel->getActiveSheet()->getCell('E'.$i)->getValue();
                       $bounce = $tutorialexcel->getActiveSheet()->getCell('F'.$i)->getValue();
                       $exit = $tutorialexcel->getActiveSheet()->getCell('G'.$i)->getValue();
                       $pagevalue = $tutorialexcel->getActiveSheet()->getCell('H'.$i)->getValue();
                       array_push($tutorial, array(
                           $page,
                           $pageview,
                           $unique,
                           $avg,
                           $entrance,
                           $bounce,
                           $exit,
                           $pagevalue
                       ));
                       $i++;
                   }
                   for($i = 0; $i < count($data); $i++){
                       for($j = 0; $j < count($tutorial); $j++ ){
                           if($data[$i][4] == $tutorial[$j][0]){
                               array_push($result, array(
                                   $data[$i][0],
                                   $data[$i][1],
                                   $data[$i][2],
                                   $data[$i][3],
                                   $tutorial[$j][0],
                                   $tutorial[$j][1],
                                   $tutorial[$j][2],
                                   $tutorial[$j][3],
                                   $tutorial[$j][4],
                                   $tutorial[$j][5],
                                   $tutorial[$j][6],
                                   $tutorial[$j][7]
                               ));
                           }
                       }
                   }

               }

           }
       }
   }

    //excel import module end
   
    session_start(); // DO CALL ON TOP OF BOTH PAGES
    $_SESSION['result'] = $result;

    header("Content-Disposition: attachment; filename=\"report1.xls\"");
    header("Content-Type: application/vnd.ms-excel;");
    header("Pragma: no-cache");
    header("Expires: 0");
    $out1 = fopen("php://output", 'w');
    foreach ($result as $data1)
    {
    fputcsv($out1, $data1,"\t");
    }
    fclose($out1);
    

?>