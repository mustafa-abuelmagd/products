<?php
include_once __DIR__ . '/../validation/validate.php';
include_once __DIR__ . '/../models/ProductModel.php';

class Furnature extends ProductModel implements Validate
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
        if((is_numeric($this->inputs['height']) && floatval($this->inputs['height'] >=0)) &&
            (is_numeric($this->inputs['length']) && floatval($this->inputs['length'] >=0))&&
            (is_numeric($this->inputs['width']) && floatval($this->inputs['width'] >=0))){
            $this->properties = $this->inputs['height'].'x' . $this->inputs['width']. 'x' .$this->inputs['length'];
            return true;
        }
        else {
            return false ;
        }
    }


}


?>