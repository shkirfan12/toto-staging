<?php

class Default_PaybackAnalysisController extends Zend_Controller_Action {

    public function init() {
        /* Initialize action controller here */
        $this->_options = $this->getInvokeArg('bootstrap')->getOptions();
        $sessPasscode = new Zend_Session_Namespace('passcode');
         $sessUser = new Zend_Session_Namespace('user');
            if (!empty($sessUser->user_Id)) {
               $sessPasscode->validPasscode = true;
            }
            
          if(!isset($sessPasscode->validPasscode) ||  $sessPasscode->validPasscode == false){
              // $this->_redirect('/index/index');
               $this->_redirect('/');
            }
    }

    public function checklogin() {
        $sessUser = new Zend_Session_Namespace('user');
        if (empty($sessUser->user_Id)) {
            $this->_redirect('/login/account');
        }
    }

    public function indexAction() {
        error_reporting(E_ERROR);
        $this->checklogin();
        $request = $this->getRequest();
        $data = $request->getParams();
        
        $sessUser = new Zend_Session_Namespace('user');
        $objPayback = new Default_Model_PaybackAnalysis();
        $objSubAtt = new Admin_Model_Attribute();
        $objProduct = new Product_Model_Product();
        if (!empty($data['pid'])) { 
            $this->view->pid = $data['pid'];
            $projInfo = $objPayback->fetchProjectInfoById($data['pid']); //echo "projInfo<pre>"; print_R($projInfo); die;
          
            foreach ($projInfo[1] as $key => $value) {
                $productDetail = $objProduct->fetchProductInfoByIdWithDetail($projInfo[1][$key]['product_id']);
                    $projInfo[1][$key]['productDetail'] = $productDetail;
                }
//                echo "info<pre>"; print_R($projInfo); die;
             $this->view->fetchShortCodeList = $objSubAtt->fetchShortCodeList();
            if(!empty($projInfo)){
                $objProductConfig = new Default_Model_ProductConfig();
                $info = $objProductConfig->projInfoFetch($data['pid']); //echo "info<pre>"; print_R($info); 
                $product_id = substr($info['product_id'], 0, -1); 
                
                $qty_usesWK_Info = $objProductConfig->fetchQtyInfo($data['pid'],$product_id); //echo "qty_usesWK_Info<pre>"; print_r($qty_usesWK_Info); die;
                
                $base_updated_values = $objProductConfig->getBaseUpdatedPrice($data['pid'],$product_id);
                
                $SubValueResult = $objProductConfig->getSubattValues($product_id); //echo "SubValueResult<pre>"; print_r($SubValueResult); die;
                
                $humanplanetCount = $objProductConfig->getHumanPlanetCount();
               
                $prodSubValueResult = $SubValueResult[0]; // Products Sub attributes Values
                $baseSubValueResult = $SubValueResult[1];// Baseline  Sub attributes Values
                $arrBasePriceResult = $SubValueResult[2];  // BAseline Price
                
                
                
                $arrSubNames = array();  $arrSubValues = array();
                
                $arrBaseSubNames = array(); $arrBaseSubValues = array();
                
                $arrProdSpecValues = array(); $arrBaseSpecValues = array();
                
                // BASELINE UPDATED SUB-ATT VALUES ARRAY
                
                $arrBaseUpdatedNames = array();
                $arrBaseUpdatedSubValues = array();
                
                
                
                // ENERGY USAGE VALUES ARRAY()
                
                $arrEnergyUsage = array(); $arrBaseEnergyUsage = array(); $arrBaseUpdatedEnergyUsage = array();
                
                $arrTotalInputPower = array(); $arrBaseTotalInputPower = array(); $arrBaseUpdatedTotalInputPower = array();
                
                $arrEnergyCycle = array(); $arrBaseEnergyCycle = array();  $arrBaseUpdatedEnergyCycle = array();
                
                $arrAnnualEnergy = array(); $arrBaseAnnualEnergy = array(); $arrBaseUpdatedAnnualEnergy = array();
                
                // WATER ATTRIBUTE VALUES ARRAY()
                $arrDFHighFlow = array();
                $arrBaseDFHighFlow = array();
                $arrBaseUpdatedDFHighFlow = array();
                
                $arrDFLowFlow = array();
                $arrBaseDFLowFlow = array();
                $arrBaseUpdatedDFLowFlow = array();
                
                $arrFlowRate = array();
                $arrBaseFlowRate = array();
                $arrBaseUpdatedFlowRate = array();
                
                $arrFlushRate = array();
                $arrBaseFlushRate = array();
                $arrBaseUpdatedFlushRate = array();
                
                $arrWaterCycle = array();
                $arrBaseWaterCycle = array();
                $arrBaseUpdatedWaterCycle = array();
                
                
                // LIFE CYCLE VALUES ARRAY();
                $arrProdMonthlyMainCost = array();
                $arrBaseMonthlyMainCost = array();
                $arrBaseUpdatedMonthlyMainCost = array();
                
                $arrProdMonthlyBattery = array();
                $arrBaseMonthlyBattery = array();
                $arrBaseUpdatedMonthlyBattery = array();
                
                $arrBulbLife =  array();
                $arrBaseBulbLife = array();
                $arrBaseUpdatedBulbLife = array();
                
                
                
                // Converting the comma seperated values and subattributes names into an array
                            
                    for($i=0;$i< count($prodSubValueResult);$i++){
                        
                        $arrProdSN = explode(',', $prodSubValueResult[$i]['sub_name']);
                        $arrProdSV = explode(',', $prodSubValueResult[$i]['value']);
                                                
                        array_push($arrSubNames, $arrProdSN);
                        array_push($arrSubValues, $arrProdSV);
                  
                    }
                    
                    for($b=0;$b<count($baseSubValueResult);$b++){
                        for($p=0;$p<count($baseSubValueResult[$b]);$p++){
                           
                            $arrBaseSN = explode(',', $baseSubValueResult[$b][$p]['sub_name']);
                            $arrBaseSV = explode(',', $baseSubValueResult[$b][$p]['value']);
                                       
                            array_push($arrBaseSubNames, $arrBaseSN);
                            array_push($arrBaseSubValues, $arrBaseSV);
                         } 

                    }
                    
                    for($u=0;$u < count($base_updated_values);$u++){
                        
                        $BaseUpdatedSN = explode(',', $base_updated_values[$u]['sub_name']);
                        $BaseUpdatedSV = explode(',', $base_updated_values[$u]['value']);
                                                
                        array_push($arrBaseUpdatedNames, $BaseUpdatedSN);
                        array_push($arrBaseUpdatedSubValues, $BaseUpdatedSV);
                    }
                   //echo "<pre>PROD- "; print_r($prodSubValueResult);
//                   echo "<pre>SUBATT- "; print_r($arrBaseUpdatedNames);
//                   echo "<pre>SUBATT VAL- "; print_r($arrBaseUpdatedSubValues);die;
//                 
                    // LOOP through For Actual Product
                    for($j=0;$j<count($arrSubNames);$j++){
                        
                        for($k=0;$k<count($arrSubNames[$j]);$k++){
                            
                            // ENERGY : Standard Power usage (W)
                            if($arrSubNames[$j][$k] == "SPU"){
                                $spu = $arrSubValues[$j][$k];    
                                
                                $arrEnergyUsage[$j]['product_id'] = $prodSubValueResult[$j]['product_id'];
                                $arrEnergyUsage[$j]['SPU'] = $spu;
                            }
                            
                            // ENERGY : Total Input Power (W)
                            if($arrSubNames[$j][$k] == "TIP"){
                                $tip = $arrSubValues[$j][$k];    
                                
                                $arrTotalInputPower[$j]['product_id'] = $prodSubValueResult[$j]['product_id'];
                                $arrTotalInputPower[$j]['TIP'] = $tip;
                            }
                            
                            // ENERGY : Energy Cycle Usage
                            if($arrSubNames[$j][$k] == "EUPC"){
                                $ECU = $arrSubValues[$j][$k];    
                                
                                $arrEnergyCycle[$j]['product_id'] = $prodSubValueResult[$j]['product_id'];
                                $arrEnergyCycle[$j]['EUPC'] = $ECU;
                            }
                            
                            // ENERGY : Annual Energy Usage
                            if($arrSubNames[$j][$k] == "AEU"){
                                $AEU = $arrSubValues[$j][$k];
                                
                                $arrAnnualEnergy[$j]['product_id'] = $prodSubValueResult[$j]['product_id'];
                                $arrAnnualEnergy[$j]['AEU'] = $AEU;
                            }
                            
                            
                           // WATER : DUAL FLUSH HIGH FLOW SUBATT VALUE
                            if($arrSubNames[$j][$k] == "DF_RATE_HIGH"){
                                
                                $DFH = $arrSubValues[$j][$k];
                                
                                $arrDFHighFlow[$j]['product_id'] = $prodSubValueResult[$j]['product_id'];
                                $arrDFHighFlow[$j]['DF_RATE_HIGH'] = $DFH;
                            } 
                                                      
                           // WATER : DUAL FLUSH LOW FLOW SUBATT VALUE
                            
                            if($arrSubNames[$j][$k] == "DF_RATE_LOW"){
                                
                                $DFL = $arrSubValues[$j][$k];
                                
                                $arrDFLowFlow[$j]['product_id'] = $prodSubValueResult[$j]['product_id'];
                                $arrDFLowFlow[$j]['DF_RATE_LOW'] = $DFL;
                            }
                            
                           // WATER: FLOW RATE
                           
                            if($arrSubNames[$j][$k] == "FLOW_RATE"){
                                
                                $FL= $arrSubValues[$j][$k];
                                
                                $arrFlowRate[$j]['product_id'] = $prodSubValueResult[$j]['product_id'];
                                $arrFlowRate[$j]['FLOW_RATE'] = $FL;
                            }
                           // WATER : FLUSH RATE
                           
                            if($arrSubNames[$j][$k] == "FLUSH_RATE"){
                                
                                $FR= $arrSubValues[$j][$k];
                                
                                $arrFlushRate[$j]['product_id'] = $prodSubValueResult[$j]['product_id'];
                                $arrFlushRate[$j]['FLUSH_RATE'] = $FR;
                            }
                            
                           // WATER : CYCLE USAGE
                            
                            if($arrSubNames[$j][$k] == "WUPC"){
                                
                                $WC= $arrSubValues[$j][$k];
                                                                
                                $arrWaterCycle[$j]['product_id'] = $prodSubValueResult[$j]['product_id'];
                                $arrWaterCycle[$j]['WUPC'] = $WC;
                            }
                            
                            
                           // LIFE CYCLE : MONTHLY Maintenance COST 
                            if($arrSubNames[$j][$k] == "MMC"){ 
                                
                                $arrProdMonthlyMainCost[$j]['product_id'] = $prodSubValueResult[$j]['product_id'];
                                $arrProdMonthlyMainCost[$j]['MMC'] = $arrSubValues[$j][$k];
                            }
                            
                           // LIFE CYCLE : MONTHLY Maintenance COST  battery_cost
                            if($arrSubNames[$j][$k] == "BAT_REP_SAV"){ 
                                                                
                                $arrProdMonthlyBattery[$j]['product_id'] = $prodSubValueResult[$j]['product_id'];
                                $arrProdMonthlyBattery[$j]['BAT_REP_SAV'] = $arrSubValues[$j][$k];
                            }
                            
                            if($arrSubNames[$j][$k] == "BULB_LIFE"){ 
                                                                
                                $arrBulbLife[$j]['product_id'] = $prodSubValueResult[$j]['product_id'];
                                $arrBulbLife[$j]['BULB_LIFE'] = $arrSubValues[$j][$k];
                            }
                            
                            
                           // HUMAN HEALTH SUBATT and VALUES
                                if($arrSubNames[$j][$k] == "UNI_HGT"){

                                    $humanHealth[] = $arrSubValues[$j][$k];
                                }
                                if($arrSubNames[$j][$k] == "ADA"){

                                    $humanHealth[] = $arrSubValues[$j][$k];
                                }
                           
                           // PLANET HEALTH SUBATT and VALUES
                            
                                if($arrSubNames[$j][$k] == "LCA_AVAIL"){

                                    $planetHealth[] = $arrSubValues[$j][$k];
                                }
                                
                                if($arrSubNames[$j][$k] == "SM_LINK"){

                                    $planetHealth[] = $arrSubValues[$j][$k];

                                }
                            
                        }
                    }
                    
                    
                    // LOOP through for BASELINE Product
                    for($j=0;$j<count($arrBaseSubNames);$j++){
                        
                        for($k=0;$k<count($arrBaseSubNames[$j]);$k++){
                            // ENERGY : Standard Power usage (W)
                            if($arrBaseSubNames[$j][$k] == "SPU"){
                                
                                $spu = $arrBaseSubValues[$j][$k];    
                                
                                $arrBaseEnergyUsage[$j]['product_id'] = $prodSubValueResult[$j]['product_id'];
                                $arrBaseEnergyUsage[$j]['SPU'] = $spu;
                            }
                            
                            // ENERGY : Total Input Power (W)
                            if($arrBaseSubNames[$j][$k] == "TIP"){
                                
                                $tip = $arrBaseSubValues[$j][$k];    
                                
                                $arrBaseTotalInputPower[$j]['product_id'] = $prodSubValueResult[$j]['product_id'];
                                $arrBaseTotalInputPower[$j]['TIP'] = $tip;
                            }
                            
                            // ENERGY : Energy Cycle Usage
                            if($arrBaseSubNames[$j][$k] == "EUPC"){
                                
                                $ECU = $arrBaseSubValues[$j][$k];    
                                
                                $arrBaseEnergyCycle[$j]['product_id'] = $prodSubValueResult[$j]['product_id'];
                                $arrBaseEnergyCycle[$j]['EUPC'] = $ECU;
                            }
                          
                            // ENERGY : Annual Energy Usage
                            if($arrBaseSubNames[$j][$k] == "AEU"){
                                
                                $AEU = $arrBaseSubValues[$j][$k];
                                
                                $arrBaseAnnualEnergy[$j]['product_id'] = $prodSubValueResult[$j]['product_id'];
                                $arrBaseAnnualEnergy[$j]['AEU'] = $AEU;
                            }
                            
                            
                           // WATER : DUAL FLUSH HIGH FLOW SUBATT VALUE
                            if($arrBaseSubNames[$j][$k] == "DF_RATE_HIGH"){
                                
                                $DFH = $arrBaseSubValues[$j][$k];
                                
                                $arrBaseDFHighFlow[$j]['product_id'] = $prodSubValueResult[$j]['product_id'];
                                $arrBaseDFHighFlow[$j]['DF_RATE_HIGH'] = $DFH;
                            }
                                                      
                           // WATER : DUAL FLUSH LOW FLOW SUBATT VALUE
                            
                            if($arrBaseSubNames[$j][$k] == "DF_RATE_LOW"){
                                
                                $DFL = $arrBaseSubValues[$j][$k];
                                
                                $arrBaseDFLowFlow[$j]['product_id'] = $prodSubValueResult[$j]['product_id'];
                                $arrBaseDFLowFlow[$j]['DF_RATE_LOW'] = $DFL;
                            }
                            
                           // WATER: FLOW RATE
                           
                            if($arrBaseSubNames[$j][$k] == "FLOW_RATE"){
                                
                                $FR= $arrBaseSubValues[$j][$k];
                                                                
                                $arrBaseFlowRate[$j]['product_id'] = $prodSubValueResult[$j]['product_id'];
                                $arrBaseFlowRate[$j]['FLOW_RATE'] = $FR;
                            }
                            
                           // WATER : FLUSH RATE
                           
                            if($arrBaseSubNames[$j][$k] == "FLUSH_RATE"){
                                
                                $FR= $arrBaseSubValues[$j][$k];
                                
                                $arrBaseFlushRate[$j]['product_id'] = $prodSubValueResult[$j]['product_id'];
                                $arrBaseFlushRate[$j]['FLUSH_RATE'] = $FR;
                            }
                            
                           // WATER : CYCLE USAGE
                            
                            if($arrBaseSubNames[$j][$k] == "WUPC"){
                                
                                $WC= $arrBaseSubValues[$j][$k];
                                                               
                                $arrBaseWaterCycle[$j]['product_id'] = $prodSubValueResult[$j]['product_id'];
                                $arrBaseWaterCycle[$j]['WUPC'] = $WC;
                            }
                            
                           // LIFE CYCLE : MONTHLY Maintenance COST 
                            if($arrBaseSubNames[$j][$k] == "MMC"){ 
                                
                                $arrBaseMonthlyMainCost[$j]['product_id'] = $prodSubValueResult[$j]['product_id'];
                                $arrBaseMonthlyMainCost[$j]['MMC'] = $arrBaseSubValues[$j][$k];
                            }
                            
                           // LIFE CYCLE : MONTHLY Maintenance COST 
                            if($arrBaseSubNames[$j][$k] == "BAT_REP_SAV"){
                                
                                $arrBaseMonthlyBattery[$j]['product_id'] = $prodSubValueResult[$j]['product_id'];
                                $arrBaseMonthlyBattery[$j]['BAT_REP_SAV'] = $arrBaseSubValues[$j][$k];
                            }
                           
                           // LIFE CYCLE :  BULB  LIFE
                            if($arrBaseSubNames[$j][$k] == "BULB_LIFE"){ 
                                                                
                                $arrBaseBulbLife[$j]['product_id'] = $prodSubValueResult[$j]['product_id'];
                                $arrBaseBulbLife[$j]['BULB_LIFE'] = $arrBaseSubValues[$j][$k];
                            }
                            
                           
                        }
                    }
                    
                    //-----------------------------USER UPDATED BASELINE VALUES LOOP--------------
                    
                     for($j=0;$j<count($arrBaseUpdatedNames);$j++){
                        
                        for($k=0;$k<count($arrBaseUpdatedNames[$j]);$k++){
                            // ENERGY : Standard Power usage (W)
                            if($arrBaseUpdatedNames[$j][$k] == "SPU"){
                                
                                $spu = $arrBaseUpdatedSubValues[$j][$k];    
                                
                                $arrBaseUpdatedEnergyUsage[$j]['product_id'] = $prodSubValueResult[$j]['product_id'];
                                $arrBaseUpdatedEnergyUsage[$j]['SPU'] = $spu;
                            }
                            
                            // ENERGY : Total Input Power (W)
                            if($arrBaseUpdatedNames[$j][$k] == "TIP"){
                                
                                $tip = $arrBaseUpdatedSubValues[$j][$k];    
                                
                                $arrBaseUpdatedTotalInputPower[$j]['product_id'] = $prodSubValueResult[$j]['product_id'];
                                $arrBaseUpdatedTotalInputPower[$j]['TIP'] = $tip;
                            }
                            
                            // ENERGY : Energy Cycle Usage
                            if($arrBaseUpdatedNames[$j][$k] == "EUPC"){
                                
                                $ECU = $arrBaseUpdatedSubValues[$j][$k];    
                                
                                $arrBaseUpdatedEnergyCycle[$j]['product_id'] = $prodSubValueResult[$j]['product_id'];
                                $arrBaseUpdatedEnergyCycle[$j]['EUPC'] = $ECU;
                            }
                          
                            // ENERGY : Annual Energy Usage
                            if($arrBaseUpdatedNames[$j][$k] == "AEU"){
                                
                                $AEU = $arrBaseUpdatedSubValues[$j][$k];
                                
                                $arrBaseUpdatedAnnualEnergy[$j]['product_id'] = $prodSubValueResult[$j]['product_id'];
                                $arrBaseUpdatedAnnualEnergy[$j]['AEU'] = $AEU;
                            }
                            
                            
                           // WATER : DUAL FLUSH HIGH FLOW SUBATT VALUE
                            if($arrBaseUpdatedNames[$j][$k] == "DF_RATE_HIGH"){
                                
                                $DFH = $arrBaseUpdatedSubValues[$j][$k];
                                
                                $arrBaseUpdatedDFHighFlow[$j]['product_id'] = $prodSubValueResult[$j]['product_id'];
                                $arrBaseUpdatedDFHighFlow[$j]['DF_RATE_HIGH'] = $DFH;
                            }
                                                      
                           // WATER : DUAL FLUSH LOW FLOW SUBATT VALUE
                            
                            if($arrBaseUpdatedNames[$j][$k] == "DF_RATE_LOW"){
                                
                                $DFL = $arrBaseUpdatedSubValues[$j][$k];
                                
                                $arrBaseUpdatedDFLowFlow[$j]['product_id'] = $prodSubValueResult[$j]['product_id'];
                                $arrBaseUpdatedDFLowFlow[$j]['DF_RATE_LOW'] = $DFL;
                            }
                            
                           // WATER: FLOW RATE
                           
                            if($arrBaseUpdatedNames[$j][$k] == "FLOW_RATE"){
                                
                                $FR= $arrBaseUpdatedSubValues[$j][$k];
                                                                
                                $arrBaseUpdatedFlowRate[$j]['product_id'] = $prodSubValueResult[$j]['product_id'];
                                $arrBaseUpdatedFlowRate[$j]['FLOW_RATE'] = $FR;
                            }
                            
                           // WATER : FLUSH RATE
                           
                            if($arrBaseUpdatedNames[$j][$k] == "FLUSH_RATE"){
                                
                                $FR= $arrBaseUpdatedSubValues[$j][$k];
                                
                                $arrBaseUpdatedFlushRate[$j]['product_id'] = $prodSubValueResult[$j]['product_id'];
                                $arrBaseUpdatedFlushRate[$j]['FLUSH_RATE'] = $FR;
                            }
                            
                           // WATER : CYCLE USAGE
                            
                            if($arrBaseUpdatedNames[$j][$k] == "WUPC"){
                                
                                $WC= $arrBaseUpdatedSubValues[$j][$k];
                                                               
                                $arrBaseUpdatedWaterCycle[$j]['product_id'] = $prodSubValueResult[$j]['product_id'];
                                $arrBaseUpdatedWaterCycle[$j]['WUPC'] = $WC;
                            }
                            
                           // LIFE CYCLE : MONTHLY Maintenance COST 
                            if($arrBaseUpdatedNames[$j][$k] == "MMC"){ 
                                
                                $arrBaseUpdatedMonthlyMainCost[$j]['product_id'] = $prodSubValueResult[$j]['product_id'];
                                $arrBaseUpdatedMonthlyMainCost[$j]['MMC'] = $arrBaseUpdatedSubValues[$j][$k];
                            }
                            
                           // LIFE CYCLE : MONTHLY Maintenance COST 
                            if($arrBaseUpdatedNames[$j][$k] == "BAT_REP_SAV"){
                                
                                $arrBaseUpdatedMonthlyBattery[$j]['product_id'] = $prodSubValueResult[$j]['product_id'];
                                $arrBaseUpdatedMonthlyBattery[$j]['BAT_REP_SAV'] = $arrBaseUpdatedSubValues[$j][$k];
                            }
                           
                           // LIFE CYCLE :  BULB  LIFE
                            if($arrBaseUpdatedNames[$j][$k] == "BULB_LIFE"){ 
                                                                
                                $arrBaseUpdateBulbLife[$j]['product_id'] = $prodSubValueResult[$j]['product_id'];
                                $arrBaseUpdateBulbLife[$j]['BULB_LIFE'] = $arrBaseUpdatedSubValues[$j][$k];
                            }
                            
                           
                        }
                    }
                    
                 //echo "<pre>HUMAN - ";  print_r($arrBaseFlowRate);
                   //echo "<pre>HUMAN - "; print_r($arrFlushRate);die;
                   
                    $totalHumanHealth  = count($projInfo[1]); 
                    $totalPlanetHealth = count($projInfo[1]);
                    $yesCountHuman = 0;
                    $noCountHuman = 0;
                    $yesCountPlanet = 0;
                    $noCountPlanet = 0;
                    
                        for($z=0;$z<count($humanHealth);$z++){
                           
                           if(trim($humanHealth[$z]) == 'Yes'){
                               ++$yesCountHuman;
                           }
                           else{
                               //echo "No-working";
                               ++$noCountHuman; 
                                
                           }
                        }
                        
                        for($h=0;$h<count($planetHealth);$h++){
                           
                           if(trim($planetHealth[$h]) == 'Yes'){
                               
                               ++$yesCountPlanet;
                           }
                           else{
                               ++$noCountPlanet;
                           }
                        }
                        
                        if($totalHumanHealth > 0){
                            $totalHumanYes = ($yesCountHuman / $totalHumanHealth) * 100;
                            $totalHumanNo = 100 - $totalHumanYes;
                        }
                        else{
                            $totalHumanYes = 0;
                            $totalHumanNo = 0 ;
                        }
                        
                        if($totalPlanetHealth > 0){
                            $totalPlanetYes = ($yesCountPlanet / $totalPlanetHealth) * 100;
                            $totalPlanetNo = 100 - $totalPlanetYes;
                        }
                        else{
                            $totalPlanetYes = 0;
                            $totalPlanetNo = 0;
                        }
                        
                        
                    
               // SUBSTRACT and ADD for PAYBACK VALUES
               for($j=0;$j<count($arrBaseSpecValues);$j++){
                   $MonthlyCostPayback[] =  $arrBaseSpecValues[$j] - $arrProdSpecValues[$j];
               }
              
               // WATER SUBATT VALUES IN ARRAY()
               
                $this->view->DFH = $arrDFHighFlow;
                $this->view->DFL = $arrDFLowFlow;
                $this->view->FlowRate = $arrFlowRate;
                $this->view->FlushRate = $arrFlushRate;
                $this->view->WaterCycle = $arrWaterCycle;
                
                $this->view->BaseDFH = $arrBaseDFHighFlow;
                $this->view->BaseDFL = $arrBaseDFLowFlow;
                $this->view->BaseFlowRate = $arrBaseFlowRate;
                $this->view->BaseFlushRate = $arrBaseFlushRate;
                $this->view->BaseWaterCycle = $arrBaseWaterCycle;
                
                $this->view->BaseUpdatedDFH = $arrBaseUpdatedDFHighFlow;
                $this->view->BaseUpdatedDFL = $arrBaseUpdatedDFLowFlow;
                $this->view->BaseUpdatedFlowRate = $arrBaseUpdatedFlowRate;
                $this->view->BaseUpdatedFlushRate = $arrBaseUpdatedFlushRate;
                $this->view->BaseUpdatedWaterCycle = $arrBaseUpdatedWaterCycle;
                
                
                // ENERGY SUBATT VALUES IN ARRAY()
                
                $this->view->EnergyUsage = $arrEnergyUsage;
                $this->view->TotalInputPower = $arrTotalInputPower;
                $this->view->EnergyCycle = $arrEnergyCycle;
                $this->view->AnnualEnergy =  $arrAnnualEnergy;
               
                $this->view->BaseEnergyUsage = $arrBaseEnergyUsage;
                $this->view->BaseTotalInputPower = $arrBaseTotalInputPower;
                $this->view->BaseEnergyCycle = $arrBaseEnergyCycle;
                $this->view->BaseAnnualEnergy =  $arrBaseAnnualEnergy;
                
                $this->view->BaseUpdatedEnergyUsage = $arrBaseUpdatedEnergyUsage;
                $this->view->BaseUpdatedTotalInputPower = $arrBaseUpdatedTotalInputPower;
                $this->view->BaseUpdatedEnergyCycle = $arrBaseUpdatedEnergyCycle;
                $this->view->BaseUpdatedAnnualEnergy =  $arrBaseUpdatedAnnualEnergy;
               
                
                // LIFE CYCLE SUBATT VALUES
                $this->view->prodMonthlyMaint = $arrProdMonthlyMainCost;
                $this->view->baseMonthlyMaint = $arrBaseMonthlyMainCost;
                $this->view->baseUpdatedMonthlyMaint = $arrBaseUpdatedMonthlyMainCost;
                
                $this->view->prodMonthlyBattery = $arrProdMonthlyBattery;
                $this->view->baseMonthlyBattery = $arrBaseMonthlyBattery;
                $this->view->baseUpdatedMonthlyBattery = $arrBaseUpdatedMonthlyBattery;
                
                $this->view->BulbLife =  $arrBulbLife;
                $this->view->BaseBulbLife = $arrBaseBulbLife;
                $this->view->BaseUpdatedBulbLife = $arrBaseUpdatedBulbLife;
                
                // PLANET HEALTH SUBATT VALUES
                
                $this->view->totalHumanYes = $totalHumanYes;
                $this->view->totalHumanNo =  $totalHumanNo;
                $this->view->totalPlanetYes = $totalPlanetYes;
                $this->view->totalPlanetNo =  $totalPlanetNo;
                
                // BASE PRODUCT PRICE AND QTY ARRAY
                $this->view->BasePrices = $arrBasePriceResult;
                $this->view->updated_base_price = $base_updated_values;
                $this->view->qty_usesWK_Info = $qty_usesWK_Info;
                
            }
            // human health and planet health result set
           // $hpResult = $objPayback->getHumanPlanet($data['pid']);
            
            $this->view->projInfo = $projInfo;
            
            /*Different calculations for commercial and residentials products as indicated by project type[comm/resi]*/
            // if (products under projects are of type Commercial)
            //  THEN calculate all products values based on their calculator maodules selected and sum them for PB analysis
        }
    }
    
    public function paybackerrorAction(){
        
        $this->checklogin();
        $request = $this->getRequest();
        $data = $request->getParams();
        $projInfo = '';
        $sessUser = new Zend_Session_Namespace('user');
        $objPayback = new Default_Model_PaybackAnalysis();

        if (!empty($data['pid'])) { 
            $this->view->pid = $data['pid'];
            $projInfo = $objPayback->fetchProjectInfoById($data['pid']); //echo "projInfo<pre>"; print_R($projInfo); 
        }
        
        $this->view->projInfo = $projInfo; 
    }
     public function savepaybackAction() {
       
        $this->checklogin();
        
        $this->_helper->viewRenderer->setNoRender(true);
       
        $request = $this->getRequest();
        $data = $request->getParams();
       
        $pid = $data['pid'];
        $savings = $data['savings'];
        $energy = $data['energy'];
        $water = $data['water'];
        
        $sessUser = new Zend_Session_Namespace('user');
        $objPayback = new Default_Model_PaybackAnalysis();
        
        $pResult = $objPayback->savePaybackDetails($sessUser->user_Id, $pid, $savings, $energy, $water);
          $this->_redirect('/payback-analysis/index/pid/'.$data['pid'].'');
     }
      public function saveimageAction() {
          $this->getHelper('layout')->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $sessUser = new Zend_Session_Namespace('user');
           $request = $this->getRequest();
        $data = $request->getParams();
        $pid = $data['pid'];
        $responseImage = $data['responseImage'];
        copy('http://export.highcharts.com/'.$responseImage, 'paybackcharts/'.$sessUser->user_Id.'_'.$pid.'.jpeg');
     }

}

