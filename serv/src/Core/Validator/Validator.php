<?php

namespace App\Core\Validator;

use Respect\Validation\Validator as Respect;

class Validator{
    private $fields = [
        [
            "key" => "student_surname",
            "type" => "string"
        ],
        [
            "key" => "student_name",
            "type" => "string"
        ],
        [
            "key" => "student_ss",
            "type" => "string"
        ],
        [
            "key" => "student_unice_number",
            "type" => "intVal"
        ],
        [
            "key" => "student_email",
            "type" => "email"
        ],
        [
            "key" => "student_phone",
            "type" => "phone"
        ],
        [
            "key" => "student_insurance",
            "type" => "string"
        ],
        [
            "key" => "student_policy",
            "type" => "intVal"
        ],
        [
            "key" => "promotion",
            "type" => "string"
        ],
        [
            "key" => "student_gender",
            "type" => "string"
        ],
        [
            "key" => "student_address",
            "type" => "string"
        ],
         [
            "key" => "ent_name",
            "type" => "string"
        ],
        [
            "key" => "ent_website",
            "type" => "url"
        ],
        [
            "key" => "ent_director_surname",
            "type" => "string"
        ],
        [
            "key" => "ent_director_name",
            "type" => "string"
        ],
        [
            "key" => "ent_director_email",
            "type" => "email"
        ],
        [
            "key" => "ent_director_phone",
            "type" => "phone"
        ],
        [
            "key" => "ent_director_quality",
            "type" => "string"
        ],
        [
            "key" => "ent_director_gender",
            "type" => "string"
        ],
        [
            "key" => "ent_address",
            "type" => "string"
        ],
        [
            "key" => "ent_stage_address",
            "type" => "string"
        ],
        [
            "key" => "internship_dos",
            "type" => "date"
        ],
        [
            "key" => "internship_doe",
            "type" => "date"
        ],
        [
            "key" => "internship_week_hours",
            "type" => "intVal"
        ],
        [
            "key" => "internship_remuneration",
            "type" => "intVal"
        ],
        [
            "key" => "internship_title",
            "type" => "string"
        ],
        [
            "key" => "internship_hours_text",
            "type" => "string"
        ],
        [
            "key" => "internship_extra_text",
            "type" => "string"
        ],
        [
            "key" => "internship_advantages",
            "type" => "string"
        ],
        [
            "key" => "internship_description",
            "type" => "string"
        ],
        [
            "key" => "internship_remuneration_way",
            "type" => "string"
        ],
        [
            "key" => "ent_tutor_name",
            "type" => "string"
        ],
        [
            "key" => "ent_tutor_surname",
            "type" => "string"
        ],
        [
            "key" => "ent_tutor_email",
            "type" => "email"
        ],
        [
            "key" => "ent_tutor_phone",
            "type" => "phone"
        ],
        [
            "key" => "ent_tutor_quality",
            "type" => "string"
        ],
        [
            "key" => "unice_tutor_name",
            "type" => "string"
        ],
        [
            "key" => "unice_tutor_surname",
            "type" => "string"
        ],
        [
            "key" => "unice_tutor_email",
            "type" => "email"
        ],
        [
            "key" => "unice_tutor_phone",
            "type" => "phone"
        ],
        [
            "key" => "unice_tutor_quality",
            "type" => "string"
        ],
        [
            "key" => "ent_tutor_gender",
            "type" => "string"
        ],
        [
            "key" => "unice_tutor_gender",
            "type" => "string"
        ],
        [
            "key" => "convention_extras",
            "type" => "string"
        ],
    ];
    private $errors;

    public function __construct(array $fields = []){
        //$this->fields = $fields;
        $this->errors = [];
    }

    public function validateParams($params){
        if($params == null){
            $this->errors[0] = [
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
            if($input['id'] == $field['key']){
                switch ($field['type']){
                    case 'string' :
                        if(!Respect::stringType()->validate($input['value'])){
                            $error = true;
                        }
                        break;
                    case 'intVal' :
                        if(!Respect::intVal()->validate($input['value'])){
                            $error = true;
                        }
                        break;
                    case 'email' :
                        if(!Respect::email()->validate($input['value'])) {
                            $error = true;
                        }
                        break;
                    case 'date' :
                        if(!Respect::date()->validate($input['value'])){
                            $error = true;
                        }
                        break;
                    case 'phone' :
                        if(!Respect::phone()->validate($input['value'])){
                            $error = true;
                        }
                        break;
                    case 'url' :
                        if(!Respect::url()->validate($input['value'])){
                            $error = true;
                        }
                        break;
                }

            }
        }

        if($error){
            $value = $input['value'];
            $this->registerError("invalid value : $value", $input['id']);
        }
    }

    private function registerError($message, $key){
        array_push($this->errors, [$key => $message]);
    }
}