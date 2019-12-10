<?php

require_once  PAYTMMODEL;
require_once(LIB."/payTm/encdec_paytm.php");

class payTmController {
      
        function updatePayTmPaymentStatus() {
            
            try {
                
                file_put_contents('/home/experswl/public_html/candidates.seekruit.com/backend/log_'.date("j.n.Y").'.log', 'iam in', FILE_APPEND);
                
                    $PAYTMKEY = "PO!Gb7dFYOPAC82%";
                    $PAYTMID  = "Seekru68562999173353";
                
                    $model = new payTmModel();
                    $rowData = $model->getAllProcessOrderData();		
                    
                    if(count($rowData['data']) > 0) :
                        
                    foreach($rowData['data'] as $row) :
                        
                        $paytmParams = array();

                        $paytmParams["MID"] = $PAYTMID;

                        $paytmParams["ORDERID"] = $row['orderId'];

                        $checksum = getChecksumFromArray($paytmParams, $PAYTMKEY);

                        $paytmParams["CHECKSUMHASH"] = $checksum;

                        $post_data = json_encode($paytmParams, JSON_UNESCAPED_SLASHES);

                        /* for Staging */
                        $url = "https://securegw-stage.paytm.in/order/status";

                        /* for Production */
                        // $url = "https://securegw.paytm.in/order/status";

                        $ch = curl_init($url);
                        curl_setopt($ch, CURLOPT_POST, 1);
                        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
                        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));  
                        $response = curl_exec($ch); 
                       
                        $resDecode = json_decode($response);
                        
                       file_put_contents('/home/experswl/public_html/candidates.seekruit.com/backend/log_'.date("j.n.Y").'.log', $response, FILE_APPEND);
                        
                        $formData = (object) [
                            
                            "registrationId" => $row['rId'],
                            "orderId" => $row['orderId'],
                            "cusRegistrationId" => $resDecode->TXNID,                      
                        ];
                        

                        ($resDecode->STATUS=="TXN_SUCCESS") ? $model->updateOrderProcessTable($formData) : $model->updateOnlyStatus($formData);	
                                               
                        
                    endforeach;
                    
                    endif;

                    echo $this->encodeJson(['status' => 200, 'message' => 'success']); die;
                
            } catch (Exception $e) {

                $res = ['status' => 500, 'message' => $e->getMessage()];

                echo $this->encodeJson($res); die;
            }
            
	}
        
        
        

	public function encodeJson($responseData) {
            
		$jsonResponse = json_encode($responseData);
                
		return $jsonResponse;		
	}
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
}
