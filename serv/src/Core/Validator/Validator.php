<?php

namespace App\Core\Validator;

use Respect\Validation\Validator as Respect;

class Validator {

    private $fields;

    private $errors;

    public function __construct(array $fields = []){
        $this->fields = $fields;
        $this->errors = [];
    }

    public function validateParams($params){
        if($params == null){
            $this->errors[] = [
                "id" => "data",
                "message" => "no params given"
            ];
            return $this->errors;
        }

        foreach($params as $section){
            $inputs = [];
            $dropdowns = [];
            $textareas = [];
            $addresses = [];

            if(array_key_exists('inputs', $section))
                $inputs = $section['inputs'];

            if(array_key_exists('dropdowns', $section))
                $dropdowns = $section['dropdowns'];

            if(array_key_exists('addresses', $section))
                $addresses = $section['addresses'];

            if(array_key_exists('textareas', $section))
                $textareas = $section['textareas'];

            foreach($inputs as $input){
                $this->validate($input);
            }

            foreach($addresses as $address){
                $this->validate($address);
            }

            foreach($dropdowns as $dropdown){
                $this->validate($dropdown);
            }

            foreach($textareas as $textarea){
                $this->validate($textarea);
            }
        }

    }

    public function getErrors(){
        return $this->errors;
    }

    private function validate($input){
        $error = false;
        foreach ($this->fields as $field){
            $value = "";
            if(array_key_exists('value', $input))
                $value = $input['value'];

            if($input['id'] == $field['key']){
                switch ($field['type']){
                    case 'string' :
                        if(!Respect::stringType()->validate($value)){
                            $error = true;
                        }
                        break;
                    case 'intVal' :
                        if(!Respect::intVal()->validate($value)){
                            $error = true;
                        }
                        break;
                    case 'email' :
                        if(!Respect::email()->validate($value)) {
                            $error = true;
                        }
                        break;
                    case 'date' :
                        if(!Respect::date()->validate($value)){
                            $error = true;
                        }
                        break;
                    case 'phone' :
                        if(!Respect::phone()->validate($value)){
                            $error = true;
                        }
                        break;
                    case 'url' :
                        if(!Respect::url()->validate($value)){
                            $error = true;
                        }
                        break;
                }

            }
        }

        if($error){
            $value = "undefined";
            if(array_key_exists('value', $input))
                $value = $input['value'];
            
            $this->registerError("invalid value : $value", $input['id']);
        }
    }

    private function registerError($message, $key){
        array_push($this->errors, [$key => $message]);
    }
}