<?php

require_once  EXCELMODEL;
require_once VALIDATION;

class excelController {

	
	function excelImport() {
            
            $model = new excelModel();

            $rawData = $model->importCategories();			

            $response = $this->encodeJson($rawData);

            echo $response;	
	}

	public function encodeJson($responseData) {
            
		$jsonResponse = json_encode($responseData);
                
		return $jsonResponse;		
	}
     
        
}
