<?php

class Admin_MastergridController extends Zend_Controller_Action {

    public $succes = "";

    public function init() {
        /* Initialize action controller here */
        $this->_options = $this->getInvokeArg('bootstrap')->getOptions();
    }

    public function popupimportAction() {
        $this->getHelper('layout')->disableLayout();
    }

    /* Author: Gurjyot Kaur
     * Description : importmgpAction() imports the data in different tables products,unit value,master grid.
     * Created-date: 2014-07-7
     * Modified-date: 2014-07-7
     */

    public function importdataAction() { // /var/www/vhosts/dev.mygreenpayback.com/public..dev
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();

        require_once $_SERVER['DOCUMENT_ROOT'] . '/common/reader.php';
        //$request = $this->getRequest();
        $adapter = new Zend_File_Transfer_Adapter_Http();

        $adapter->setDestination($_SERVER['DOCUMENT_ROOT'] . '/import_files/');

        if (!$adapter->receive()) {
            $messages = $adapter->getMessages();
            echo implode("\n", $messages);
        } //echo 'eq6-'; 
        $upload = new Zend_File_Transfer();
        $files = $upload->getFileInfo();


        foreach ($files as $file => $info) {
            // file uploaded ?
            if (!$upload->isUploaded($file)) {
                print "Why havn't you uploaded the file ?";
                continue;
            }
        }

        $upload = new Zend_File_Transfer();
        $upload->receive();

        // Returns the file names from all files
        //echo"<pre>";
        $names = $upload->getFileName(); //print_R($names);exit;
        //print_r($names);
        $actual_name = $upload->getFileInfo();
        //print_r($actual_name['filename']['name']);
        //exit;
        $path_parts = pathinfo($names);
        $filename = $path_parts['filename'];
        $data = new Spreadsheet_Excel_Reader();
        // Set output Encoding.
        $data->setOutputEncoding('CP1251'); //echo $filename;exit;
        //$ans =  @file_get_contents('import_files/'.$filename);print_R($ans);exit;
        $data->read($_SERVER['DOCUMENT_ROOT'] . '/import_files/' . $filename . '.xls');

        error_reporting(E_ALL ^ E_NOTICE);
        //  $colname=array('id','name');
        //    for($sheetsnum = 0; $sheetsnum < sizeof($data->sheets); $sheetsnum++){
        for ($i = 1; $i <= $data->sheets[0]['numRows']; $i++) {
            for ($j = 1; $j <= $data->sheets[0]['numCols']; $j++) {
                $product[$i - 1][$j - 1] = $data->sheets[0]['cells'][$i][$j];
                //  $product[$i-1][$colname[$j-1]]=$data->sheets[$sheetsnum]['cells'][$i][$j];
            }
        }
        $numberofcolumns = $data->sheets[0]['numCols'];
        $mastergridModel = new Admin_Model_Mastergrid ();
        //echo '<pre>';print_r($product);exit;
        // $userAuthNamespace->product = $product;
        if (count($product) > 1) {
            for ($i = 2; $i < count($product); $i++) { //echo "<pre>"; print_R($product);
                // echo '<pre>';  
                //insert << 
                if ($product[$i][1] != '' && $product[$i][2] != '') {
                    // $product_id = $mastergridModel->checkproductexists($product[$i][0],$product[$i][1]); 
                    $product_id = $mastergridModel->checkproductexists($product[$i][1]);
                 
                    //if ($product[$i][3] != '' && $product[$i][3] != 0) {echo "a"; die;
                    if (!empty($product[$i][3])) {
                         $tier1id = $mastergridModel->gettier1idbyname($product[$i][3]);
                    }
                    if ($product[$i][6] != '' && $product[$i][6] != 0) {
                        $tier2id = $mastergridModel->gettier2idbyname($product[$i][6]);
                    } else {
                        $tier2id = '';
                    }

                    $tag1 = $mastergridModel->gettag1idbyname($product[$i][9]);
                    if ($tag1 != '') {
                        $tag1array = array();
                        foreach ($tag1 as $key1 => $val2) {
                            array_push($tag1array, $val2['tag1_id']);
                        }
                        $tag1 = implode(',', $tag1array);
                    } else {
                        $tag1 = '';
                    }
                    $tag2 = $mastergridModel->gettag2idbyname($product[$i][10]);
                    if ($tag2 != '') {
                        $tag2array = array();
                        foreach ($tag2 as $key1 => $val2) {
                            array_push($tag2array, $val2['tag2_id']);
                        }
                        $tag2 = implode(',', $tag2array);
                    } else {
                        $tag2 = '';
                    }
           
                    if ($product_id == 0) {
                        if ($tier1id != '') {
                            $productArray = array(
                                'product_name' => $product[$i][2],
                                'product_img' => $product[$i][8],
                                'product_num' => $product[$i][1],
                                'product_brand' => $product[$i][5],
                                'purchase_price' => $product[$i][7],
                                'tier1_id' => $tier1id,
                                'tier2_id' => $tier2id,
                                'tag1_id' => $tag1,
                                'tag2_id' => $tag2,
                                'created_date' => Zend_Date::now()->toString('yyyy:MM:dd HH:mm:ss')
                            );
                            $product_id = $mastergridModel->insertimportdata($productArray, 'product');
                        }
                    } else { 
                        if ($tier1id != '') {  
                            $p1 = $product[$i][2]; 
                            $p2 = $product[$i][8];
                            $p3 = $product[$i][1];
                            $p4 = $product[$i][5];
                            $p5 = $product[$i][7];

                            $productArray = " 
                                product_name = '$p1',
                                product_img = '$p2',
                                product_num = '$p3',
                                product_brand = '$p4',
                                purchase_price = '$p5',
                                tier1_id = '$tier1id',
                                tier2_id = '$tier2id',
                                tag1_id = '$tag1',
                                tag2_id = '$tag2'";

                            $where = array(
                                'product_id=?' => $product_id
                            );
                            $product1_id = $mastergridModel->updateimportdata('product', $productArray, $product_id);
                        }
                    }

                    if ($product_id != 0) {
                        //  exit;        
                        $objAtt = new Admin_Model_Attribute();
                        //  $subAttList = $objAtt->fetchSubAttList(); 
                        $attList = $objAtt->fetchAttList();
                        $subattrarrResult = array();
                        $valuesResult = array();
                        for ($a = 0; $a < count($attList); $a++) {
                            $arrResult = $objAtt->fetchSubattByAttId($attList[$a]['attribute_id']); //print_R($arrResult);
                            for ($sub = 0; $sub < count($arrResult); $sub++) {
                                // if(in_array($arrResult[$sub]['subatt_name'], $product[$i][2])){
                                array_push($subattrarrResult, $arrResult[$sub]['subatt_id']);
                                //  }
                            }
                        }
                        //echo "<pre>bb"; print_r($subattrarrResult); die;
                        $countproductconf = count($subattrarrResult) + 11;
                        for ($cols = 11; $cols < $countproductconf; $cols++) {
                            array_push($valuesResult, $product[$i][$cols]);
                        }
                        $baselinecol = $countproductconf ;
                        $calcol = $countproductconf + 1;
                        $usedaycol = $countproductconf + 2;
                        $useweekcol = $countproductconf + 3;
                        $hrsdaycol = $countproductconf + 4;
                        $hrsweekcol = $countproductconf + 5; 
                        if ($product[$i][$baselinecol] != '') { 
                            $baseline_id = $mastergridModel->getbaselineid($product[$i][$baselinecol]);
                        } else {
                            $baseline_id = 0;
                        }
                     
                        $p1 = $product[$i][$useweekcol]; if(!empty($p1)) { $uses_wk_flag = 'y';}else{ $uses_wk_flag = 'n';}
                        $p2 = $product[$i][$usedaycol];  if(!empty($p2)) { $uses_day_flag = 'y';}else{ $uses_day_flag = 'n';}
                        $p3 = $product[$i][$hrsdaycol];  if(!empty($p3)) { $hrs_day_flag = 'y';}else{ $hrs_day_flag = 'n';}
                        $p4 = $product[$i][$hrsweekcol]; if(!empty($p4)) { $hrs_wk_flag = 'y';}else{ $hrs_wk_flag = 'n';}
                        $productArray = " 
                            uses_wk_value = '$p1',
                            uses_wk_flag = '$uses_wk_flag',
                            uses_day_value = '$p2',
                            uses_day_flag = '$uses_day_flag',
                            hrs_day_value = '$p3',
                            hrs_day_flag = '$hrs_day_flag',
                            hrs_wk_value = '$p4',
                            hrs_wk_flag = '$hrs_wk_flag'";
                        $where = array(
                            'product_id = ?' => $product_id
                        );
                        $product1_id = $mastergridModel->updateimportdata('product', $productArray, $product_id);    
                        /*  if ($product[$i][2] == '' && $product[$i][1] != '') {
                          $product_id = $mastergridModel->checkbaseline($product[$i][1]);
                          $baseline_id = 0;
                          if ($baseline_id == 0) {
                          $productArray = array(
                          'product_name' => $product[$i][1],
                          'baseline' => 'y',
                          'created_date' => Zend_Date::now()->toString('yyyy:MM:dd HH:mm:ss')
                          );
                          $baseline_id = $mastergridModel->insertimportdata($productArray, 'product');
                          } else {
                          $baseline_id = '';
                          }
                          } */

                        $calc_values = array();
                        $arrcalcids = array(); 
                        if ($product[$i][$calcol] != '') {
                            $calc_values = explode(',', $product[$i][$calcol]);
                            for ($j = 0; $j < count($calc_values); $j++) {
                                $baselineyn = $mastergridModel->checkcalculator($calc_values[$j]);
                                /* if ($baselineyn == 0) {
                                  $productArray = array(
                                  'name' => $calc_values[$j],
                                  'active' => 'y',
                                  'created_date' => Zend_Date::now()->toString('yyyy:MM:dd HH:mm:ss')
                                  );
                                  $calc_id = $mastergridModel->insertimportdata($productArray, 'calc_mod');
                                  array_push($arrcalcids, $calc_id);
                                  } else { */
                                array_push($arrcalcids, $baselineyn); 
                                //}
                            } 
                            $strcalcids = implode(',', $arrcalcids);
                        } else {
                            $strcalcids = '';
                        }
                        //insert >>




                        for ($c = 0; $c < ( count($subattrarrResult) - 1 ); $c++) {
                            for ($d = 0; $d < count($subattrarrResult) - $c - 1; $d++) {
                                if ($subattrarrResult[$d] > $subattrarrResult[$d + 1]) {
                                    $swap = $subattrarrResult[$d];
                                    $subattrarrResult[$d] = $subattrarrResult[$d + 1];
                                    $subattrarrResult[$d + 1] = $swap;

                                    $swapVal = $valuesResult[$d];
                                    $valuesResult[$d] = $valuesResult[$d + 1];
                                    $valuesResult[$d + 1] = $swapVal;
                                }
                            }
                        }
                        $finalsub = array();
                        $finalval = array();
                        for ($j = 0; $j < count($subattrarrResult); $j++) {
                            if ($valuesResult[$j] != '') {
                                array_push($finalsub, $subattrarrResult[$j]);
                                array_push($finalval, $valuesResult[$j]);
                            }
                        }
                        // echo'<pre>'; print_R($finalsub);print_R($finalval);exit;
                        //  echo $product_id;
                        $strattributes = implode(',', $finalsub);
                        $strattributesvalues = implode(',', $finalval);
                        // find if the product_id exist in unit value table
                        $chkunitresult = $mastergridModel->checkunitvalue($product_id); //echo $chkunitresult;exit;

                        if ($chkunitresult == 0) {

                            $productArray = array(
                                'product_id' => $product_id,
                                'subatt_id' => $strattributes,
                                'value' => $strattributesvalues
                            ); //echo '<pre>';print_R($productArray);
                            $uv_id = $mastergridModel->insertimportdata($productArray, 'unit_value');
                            $chkunitresult = $uv_id;
                        } else {

                            $productArray = " 
                                    subatt_id = '$strattributes',
                                    value = '$strattributesvalues'";

                            $where = array(
                                'product_id = ?' => $product_id
                            );
                            $uv_id = $mastergridModel->updateimportdata('unit_value', $productArray, $product_id);
                        }
                        //echo $uv_id;echo '<br>';
                        $chkmasterresult = $mastergridModel->checkmastergrid($product_id); //echo $baseline_id;exit;

                        if ($chkmasterresult == 0) {
                            $productArray = array(
                                'product_id' => $product_id,
                                'uv_id' => $chkunitresult,
                                'calcmod_id' => $strcalcids,
                                'baseline_id' => $baseline_id,
                                'created_date' => Zend_Date::now()->toString('yyyy:MM:dd HH:mm:ss')
                            ); //echo'<pre>';print_r($productArray);
                            $master_id = $mastergridModel->insertimportdata($productArray, 'mastergrid');
                        } else {
                            $productArray = "
                                    uv_id = '$chkunitresult',
                                    calcmod_id = '$strcalcids',
                                    baseline_id = '$baseline_id'
                                "; //echo'<pre>';print_r($productArray);
                            $where = array(
                                'product_id = ?' => $product_id,
                            );
                            $master_id = $mastergridModel->updateimportdata('mastergrid', $productArray, $product_id);
                        }
                    }
                }
            }
        }
        $this->_redirect('/admin/mastergrid');
    }

    /* Author: Gurjyot Kaur
     * Description : exportmgpAction() exports the data from different tables products,unit value,master grid,attributes,subattributes,calc_mod,product_baseline.
     * Created-date: 2014-07-2
     * Modified-date: 2014-07-4
     */

    public function exportmastergridAction() {
        error_reporting(0);
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        require_once $_SERVER['DOCUMENT_ROOT'] . '/common/Classes/PHPExcel.php';


        $objPHPExcel = new PHPExcel();

// Set properties
        $objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
                ->setLastModifiedBy("Maarten Balliauw")
                ->setTitle("Office 2007 XLSX Test Document")
                ->setSubject("Office 2007 XLSX Test Document")
                ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
                ->setKeywords("office 2007 openxml php")
                ->setCategory("Test result file");

        $styleArray = array(
            'font' => array(
                'bold' => true,
                'color' => array('argb' => '000000'),
            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
            ),
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
                'rotation' => 90,
                'startcolor' => array(
                    'argb' => 'FFA0A0A0',
                ),
                'endcolor' => array(
                    'argb' => 'FFFFFFFF',
                ),
            ),
        );
        $styleArray2 = array(
            'font' => array(
                'bold' => false,
                'color' => array('rgb' => 'FFFFFF'),
            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
            ),
            'borders' => array(
                'bottom' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array('rgb' => '191919'),
                ),
                'right' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array('rgb' => '191919'),
                ),
                'left' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array('rgb' => '191919'),
                ),
                'top' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array('rgb' => '191919'),
                ),
            ),
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'rotation' => 90,
                'startcolor' => array(
                    'argb' => 'FF87CEFA',
                ),
                'endcolor' => array(
                    'argb' => 'FF87CEFA',
                ),
            ),
        );
        $styleArray3 = array(
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_PATTERN_LIGHTGRAY,
                'rotation' => 90,
                'startcolor' => array(
                    'argb' => 'FF8D8000',
                ),
                'endcolor' => array(
                    'argb' => 'FF8D8000',
                ),
            ),
        );
        $objProduct = new Admin_Model_Product();

        $objAtt = new Admin_Model_Attribute();
        //  $subAttList = $objAtt->fetchSubAttList(); 
        $attList = $objAtt->fetchAttList();

        // Sets array for each attbutes and its respective sub attributes

        $prodData = $objProduct->productList(); //echo '<pre>';print_R($prodData);exit;
        $objProdSpec = new Admin_Model_Mastergrid();
        // Retrieves Units and Values from UnitValues and Mastergird table
        $rUnitsValues = $objProdSpec->getUnitsValues();
        for ($j = 0; $j <= 100; $j++) {
            $objPHPExcel->getActiveSheet(0)->getColumnDimensionByColumn($j)->setAutoSize(true);
        }
        $objPHPExcel->getActiveSheet(0)->mergeCells("B1:K1");
        $objPHPExcel->getActiveSheet()->getStyle("B1")
                ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('B1')->applyFromArray(
                array(
                    'font' => array(
                        'bold' => false,
                        'color' => array('rgb' => 'FFFFFF'),
                    ),
                    'fill' => array(
                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array('rgb' => '3399FF')
                    )
                )
        );
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValueByColumnAndRow('1', '1', 'Product Essentials');
        //$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow('1',  '1')->applyFromArray($styleArray2);
        $watercolor = array(
            'font' => array(
                'bold' => false,
                'color' => array('rgb' => 'FFFFFF'),
            ),
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => '007FFF')
            )
        );
        $energycolor = array(
            'font' => array(
                'bold' => false,
                'color' => array('rgb' => 'FFFFFF'),
            ),
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => 'FF6700')
            )
        );
        $lifecyclecolor = array(
            'font' => array(
                'bold' => false,
                'color' => array('rgb' => 'FFFFFF'),
            ),
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => '008080')
            )
        );
        $humancolor = array(
            'font' => array(
                'bold' => false,
                'color' => array('rgb' => 'FFFFFF'),
            ),
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => '800000')
            )
        );
        $planetcolor = array(
            'font' => array(
                'bold' => false,
                'color' => array('rgb' => 'FFFFFF'),
            ),
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => '734F96')
            )
        );
        // Sets array for each attbutes and its respective sub attributes
        $colorbg = array();
        $arrAttSub = array();  
        $columnnumber = '11';
        // $subattnumber = '11';
        $endcolumnnumber = '';
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValueByColumnAndRow('1', '2', 'Product Number*');
          
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValueByColumnAndRow('2', '2', 'Product Name*');

        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValueByColumnAndRow('3', '2', 'Product Category*');

        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValueByColumnAndRow('4', '2', 'Product Subcategory*');

        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValueByColumnAndRow('5', '2', 'Product Brand*');

        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValueByColumnAndRow('6', '2', 'Product Collection');
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValueByColumnAndRow('7', '2', 'Product Price*');

        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValueByColumnAndRow('8', '2', 'Product Image*');
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValueByColumnAndRow('9', '2', 'Certification');

        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValueByColumnAndRow('10', '2', 'Technology');
        for ($a = 0; $a < count($attList); $a++) {
            //  $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($columnnumber,  '1')->applyFromArray($styleArray2);
            $arrResult = $objAtt->fetchSubattByAttId($attList[$a]['attribute_id']); //print_R($arrResult);
            array_push($arrAttSub, $arrResult);
            $endcolumnnumber = $columnnumber + count($arrResult) - 1;
            if (preg_match('/Water/', $attList[$a]['attribute_name'])) {
                $colorbg = $watercolor;
            } else if (preg_match('/Energy/', $attList[$a]['attribute_name'])) {
                $colorbg = $energycolor;
            } else if (preg_match('/Lifecycle/', $attList[$a]['attribute_name'])) {
                $colorbg = $lifecyclecolor;
            } else if (preg_match('/Human/', $attList[$a]['attribute_name'])) {
                $colorbg = $humancolor;
            } else if (preg_match('/Planet/', $attList[$a]['attribute_name'])) {
                $colorbg = $planetcolor;
            }
            $objPHPExcel->getActiveSheet(0)->mergeCellsByColumnAndRow($columnnumber, '1', $endcolumnnumber, '1');
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($columnnumber, '1')->applyFromArray($colorbg);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($columnnumber, '1')
                    ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);


            $subattcolumnnumber = $columnnumber;
            for ($b = 0; $b < count($arrResult); $b++) {

                $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValueByColumnAndRow($subattcolumnnumber, '2', $arrResult[$b]['subatt_name']);
                $subattcolumnnumber = $subattcolumnnumber + 1;
            }


            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValueByColumnAndRow($columnnumber, '1', $attList[$a]['attribute_name']);


            $columnnumber = $endcolumnnumber + 1;
        }
        $prodData = $objProduct->productList(); //echo '<pre>';print_R($prodData);exit;
        $objProdSpec = new Admin_Model_Mastergrid();
        // Retrieves Units and Values from UnitValues and Mastergird table
        $rUnitsValues = $objProdSpec->getUnitsValues();
        $title_col = 3;

        foreach ($prodData as $key1 => $val1) { 
            $num1 = $title_col + $key1;
            $allsubattributes = $objProdSpec->getallunitvalues($val1['product_id']); //echo "<pre>"; print_r($allsubattributes); 
            $subattdata = array();
            $subattdata = explode(',', $allsubattributes['subatt_id']);//echo "<pre>"; print_r($subattdata); exit;
            $valdata = array();
            $valdata = explode(',', $allsubattributes['value']); // echo "<pre>"; print_R($subattdata); print_R($valdata); 
            $type = PHPExcel_Cell_DataType::TYPE_STRING;
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValueExplicit('B' . $num1, $val1['product_num']);
            
            $string = str_replace(' ', ' ', $val1['product_name']); // Replaces all spaces with hyphens.
            $string = preg_replace('/[^a-zA-Z0-9_ %\[\]\.\,\(\)\/%&-]/s', '', $string);
           
            //   $string = preg_replace('/[^A-Za-z0-9\-]/', ' ', $string); // Removes special chars.
            
            $objPHPExcel->setActiveSheetIndex(0)
                   // ->setCellValueExplicit('C' . $num1, $val1['product_name']);
                    ->setCellValueExplicit('C' . $num1, $string);

            $tier1 = $objProdSpec->gettier1namebyid($val1['tier1_id']);
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValueExplicit('D' . $num1, $tier1);

            $objPHPExcel->setActiveSheetIndex(0)
                   // ->setCellValueExplicit('E' . $num1, $val1['product_name']);
                    ->setCellValueExplicit('E' . $num1, $string);
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValueExplicit('F' . $num1, $val1['product_brand']);

            $tier2 = $objProdSpec->gettier2namebyid($val1['tier2_id']);
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValueExplicit('G' . $num1, $tier2);
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValueExplicit('H' . $num1, $val1['purchase_price']);
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValueExplicit('I' . $num1, $val1['product_img']);
            $tag1array = array();
            $tag2array = array();
            // $tag1array = explode(',', $val1['tag1_id']);
            
            if(!empty($val1['tag1_id'])){
                  $tag1 = $objProdSpec->gettag1namebyid($val1['tag1_id']); 
                  foreach ($tag1 as $key1 => $val2) {
                    array_push($tag1array, $val2['tag1_name']);
                  }
            }
          
            if(!empty($tag1array)){
                 $tag1 = implode(',', $tag1array);
            }else{
                 $tag1 ='';
            }
            
             //echo "sas11"; die; //.............................................................................

            if (!empty($val1['tag2_id'])) {
                $tag2 = $objProdSpec->gettag2namebyid($val1['tag2_id']);
                array_push($tag2array, $val2['tag2_name']);
            }//echo "sas11"; die;
        
            if(!empty($tag2array)){
                 $tag2 = implode(',', $tag2array);
            }else{
                 $tag2 = '';
            }

            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValueExplicit('J' . $num1, $tag1);
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValueExplicit('K' . $num1, $tag2);
            $columnnumber = '11';
            $endcolumnnumber = '';
            $subattcolumnnumber = '11';
            // $val1['product_name'] = 'asaa&,\"';
           // echo "<pre>"; print_r($attList); 
            for ($a = 0; $a < count($attList); $a++) {
                $arrResult = $objAtt->fetchSubattByAttId($attList[$a]['attribute_id']); 
                array_push($arrAttSub, $arrResult); //echo "<pre>"; print_R($arrResult);die;
                $endcolumnnumber = $columnnumber + count($arrResult) - 1;
                for ($b = 0; $b < count($arrResult); $b++) { 
                   
                    for ($c = 0; $c < count($subattdata); $c++) { 
                        if ($subattdata[$c] == $arrResult[$b]['subatt_id']) {//echo $arrResult[$b]['subatt_name'].'--'.$subattcolumnnumber.'--'.$valdata[$c].'<br>'; 
                            $objPHPExcel->setActiveSheetIndex(0)
                                    ->setCellValueByColumnAndRow($subattcolumnnumber, $num1, $valdata[$c]);// $subattcolumnnumber = $subattcolumnnumber + 1;
                        }
                        
                    }
                   $subattcolumnnumber = $subattcolumnnumber + 1;
                }
                $columnnumber = $endcolumnnumber + 1;
            }//die;
            $colorbg = array(
                'font' => array(
                    'bold' => false,
                    'color' => array('rgb' => 'FFFFFF'),
                ),
                'fill' => array(
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array('rgb' => '000000')
                )
            );
            $endcolumnnumber = $columnnumber + 5;
            $objPHPExcel->getActiveSheet(0)->mergeCellsByColumnAndRow($columnnumber, '1', $endcolumnnumber, '1');
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($columnnumber, '1')->applyFromArray($colorbg);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($columnnumber, '1')
                    ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValueByColumnAndRow($columnnumber, '1', 'Product Configuration');
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValueByColumnAndRow($columnnumber, '2', 'Assigned Baseline*');

            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValueByColumnAndRow($columnnumber + 1, '2', 'Assigned Calculator(s)*');

            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValueByColumnAndRow($columnnumber + 2, '2', 'Uses/day');

            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValueByColumnAndRow($columnnumber + 3, '2', 'Uses/wk');

            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValueByColumnAndRow($columnnumber + 4, '2', 'Hrs/day');

            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValueByColumnAndRow($columnnumber + 5, '2', 'Hrs/wk');
            // enter configuration details---------------
            $getbaselinename = $objProdSpec->getbaselinename($val1['product_id']);
            $getcalcmod = $objProdSpec->getcalcmod($val1['product_id']);
            $calculatorname = $objProdSpec->calculatorname($getcalcmod);
            $calculatornamearray = array();
            foreach ($calculatorname as $key1 => $val2) {
                array_push($calculatornamearray, $val2['name']);
            }
            $calculatornamearray = implode(',', $calculatornamearray);
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValueByColumnAndRow($columnnumber, $num1, $getbaselinename);

            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValueByColumnAndRow($columnnumber + 1, $num1, $calculatornamearray);

            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValueByColumnAndRow($columnnumber + 2, $num1, $val1['uses_day_value']);

            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValueByColumnAndRow($columnnumber + 3, $num1, $val1['uses_wk_value']);

            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValueByColumnAndRow($columnnumber + 4, $num1, $val1['hrs_day_value']);

            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValueByColumnAndRow($columnnumber + 5, $num1, $val1['hrs_wk_value']);
        }
        //echo 'eq1ss-';  die;
        $objPHPExcel->getActiveSheet()->setTitle('Master Grid Report');
// Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->getActiveSheet()->setShowGridlines(true);
        $objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
        $objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
        $objPHPExcel->getActiveSheet()->getPageMargins()->setRight(0.25);
        $objPHPExcel->getActiveSheet()->getPageMargins()->setLeft(0.25);
        $objPHPExcel->getActiveSheet()->getPageSetup()->setFitToWidth(1);
        $objPHPExcel->getActiveSheet()->getPageSetup()->setFitToHeight(0);

        $objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddHeader('&C&H Master Grid Report');
        $objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddFooter('&L&B &D &RPage &P of &N');

        $objPHPExcel->setActiveSheetIndex(0);

// Redirect output to a clientÃ¢â‚¬â„¢s web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="Master Grid Report.xls"');
        header('Cache-Control: max-age=0');

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
    }

    public function checklogin() {
        $loginSession = new Zend_Session_Namespace('login');
        if (empty($loginSession->userId)) {
            $this->_redirect('/admin');
        }
    }

    /* Author: Irfan Shaikh
     * Description : indexAction() populates data on master grid from different sections 
     *              like categories, products, attributes/sub attributes etc.
     * Created-date: 2014-04-12
     * Modified-date: 2014-04-12
     */

    public function indexAction() {

        $this->checklogin();
        $this->_helper->layout->disableLayout();

        // Retrieves data from tiers and tags tables
        $objProduct = new Admin_Model_Product();

        $tag1List = $objProduct->fetchTag1List();
        $this->view->tag1List = $tag1List;
        $tag2List = $objProduct->fetchTag2List();
        $this->view->tag2List = $tag2List;
        $tier1List = $objProduct->fetchTier1List();
        $this->view->tier1List = $tier1List;
        $tier2List = $objProduct->fetchTier2List();
        $this->view->tier2List = $tier2List;

        // Retrieves data from Attributes and Sub Attributes tables
        $objAtt = new Admin_Model_Attribute();

        $attList = $objAtt->fetchAttList();
        $subAttList = $objAtt->fetchSubAttList();
        $this->view->attList = $attList;
        $this->view->subAttList = $subAttList;

        // Sets array for each attbutes and its respective sub attributes
        $arrAttSub = array();
        for ($a = 0; $a < count($attList); $a++) {
            $arrResult = $objAtt->fetchSubattByAttId($attList[$a]['attribute_id']);
            array_push($arrAttSub, $arrResult);
        }
        $this->view->arrAttSub = $arrAttSub;

        // Retirieves Product data without baseline products

        $prodData = $objProduct->productList();

        $this->view->arrProducts = $prodData;


        $objProdSpec = new Admin_Model_Mastergrid();
        // Retrieves Units and Values from UnitValues and Mastergird table
        $rUnitsValues = $objProdSpec->getUnitsValues();

        echo"<pre>"; //print_R($rUnitsValues);
        //print_r($rUnitsValues);
        $this->view->arrUnitsValues = $rUnitsValues;
    }

    /* Author: Irfan Shaikh
     * Description : prodspecAction() populates data under product specs popups from calculator module
      as well as baseline peoducts from products table. It also passes units, values and
      subattributes ids from master grid textboxes to manageprodspecAction() in order to insert/update the records.

     * Created-date: 2014-04-13
     * Modified-date: 2014-04-13
     */

    public function prodspecAction() {
        $this->_helper->layout->disableLayout();

        $request = $this->getRequest();
        $data = $request->getParams();
        //print_r($data['prodId']); die;
        $this->view->data = $data;
        $objProd = new Admin_Model_Product();
        $pResult = $objProd->productList();
        $this->view->arrProducts = $pResult;

        $objCal = new Admin_Model_Mastergrid();
        $cResult = $objCal->getCalc();
        $this->view->arrCalc = $cResult;

        //Gets all baseline adn calc from mastergrid
        $cbResult = $objCal->getSelectedCalcBase($data['prodId']);
        $this->view->arrCalcAndBase = $cbResult;

        //print_r($cbResult);
    }

    public function manageprodspecAction() {

        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $mgData = $_POST;

        //Retrieves No.of Calculators selected for the products for calculation
        $calcIds = array();
        foreach ($mgData as $key => $value) {
            if (preg_match('/^calc_/', $key)) {
                array_push($calcIds, $key);
            }
        }

        //Retrieves No.of Baseline selected for the products for comparison
        $baselineIds = array();
        foreach ($mgData as $key => $value) {
            if (preg_match('/^base_/', $key)) {
                array_push($baselineIds, $key);
            }
        }

        // As the data was sent through Jquery it was in STRING format. Below line of code converts it into array
        // --Starts-- 

        $strUnitValues = str_replace("=>", "", $mgData['hdnSUV']);
        //print_r($strUnitValues);
        preg_match_all('#\[([^\]]+)\]\s*([^\]\[]*[^\]\[\s])#msi', $strUnitValues, $matches);
        $keys = $matches[1];
        $values = $matches[2];
        $values = str_replace(")", "", $values);

        $arrUnitValues = array_combine($keys, $values);

        // --Ends--
        //echo "<pre>"; print_r($arrUnitValues); die;
        $objProdSpec = new Admin_Model_Mastergrid();

        $rOutput = $objProdSpec->addupdateMasterGrid($arrUnitValues, $calcIds, $baselineIds);
        print_r($rOutput);
    }

}

