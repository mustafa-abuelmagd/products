<?php

require_once __DIR__ . '/validate.php';
require_once __DIR__ . '/../models/response.php';
require_once __DIR__ . '/../models/Book.php';
require_once __DIR__ . '/../models/Dvd.php';
require_once __DIR__ . '/../models/Furnature.php';

class Validator
{
    private $message = null;
    private $inputs = null;

    function __construct(string $table_name , array $inputs)
    {

        $this->inputs = $inputs;
        $this->validate_product(new Book($table_name, $inputs)) ;

    }

    public function validate_product(Validate $validate)
    {
        if (!$validate->validate_SKU())
            $this->message .= 'Invalid Product SKU <br>';
        if (!$validate->validate_Name())
            $this->message .= 'Invalid Product Name <br>';
        if (!$validate->validate_Price())
            $this->message .= 'Invalid Product Price <br>';
        if (!$validate->validate_type())
            $this->message .= 'Invalid Product Type <br>';
        if (!$validate->validate_properties())
            $this->message .= 'Invalid Product properties <br>';

        if ($this->message == null) {
            $validate->add_product($this->inputs);
            return sendResponse(array('stauts' => 'Success', 'message' => 'Product added successfully.'));
        } else {
            return sendResponse(array('status' => 'Fail', 'message' => $this->message));
        }

    }

}