<?php
//include_once '../models/abs_model.php';
include_once __DIR__ . '/../validation/validate.php';
include_once __DIR__ . '/../models/ProductModel.php';

class Dvd extends ProductModel implements Validate
{

    public $inputs;


    public function __construct(array $inputs)
    {
        parent::__construct();
        $this->inputs = $inputs;

        $this->SKU = $inputs['SKU'];
        $this->name = $inputs['name'];
        $this->price = $inputs['price'];
        $this->type = $inputs['type'];
        $this->properties = $inputs['properties'];

    }

    public function validate_properties()
    {
        if(is_numeric(json_decode(json_encode($this->inputs[':properties']),true )['size']) && floatval(json_decode(json_encode($this->inputs[':properties']),true )['size'] >= 0)){
            $this->properties = json_decode(json_encode($this->inputs[':properties']),true )['size'] .' MB';
            return true;
        }
        else {
            return false ;
        }
    }


}


?>