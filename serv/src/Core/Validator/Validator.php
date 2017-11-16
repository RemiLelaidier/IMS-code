<?php

namespace App\Core\Validator;

use Respect\Validation\Validator as Respect;

class Validator{
    private $fields = [
        0 => [
            "key" => "student_surname",
            "type" => "string"
        ],
        1 => [
            "key" => "student_name",
            "type" => "string"
        ],
        2 => [
            "key" => "student_ss",
            "type" => "string"
        ],
        3 => [
            "key" => "student_unice_number",
            "type" => "integer"
        ],
        4 => [
            "key" => "student_email",
            "type" => "email"
        ],
        5 => [
            "key" => "student_dob",
            "type" => "date"
        ],
        6 => [
            "key" => "student_phone",
            "type" => "phone"
        ],
        7 => [
            "key" => "student_insurance",
            "type" => "string"
        ],
        8 => [
            "key" => "student_policy",
            "type" => "integer"
        ],
        9 => [
            "key" => "promotion",
            "type" => "string"
        ],
        10 => [
            "key" => "student_gender",
            "type" => "string"
        ],
        11 => [
            "key" => "student_address",
            "type" => "string"
        ],
        12 => [
            "key" => "ent_name",
            "type" => "string"
        ],
        13 => [
            "key" => "ent_website",
            "type" => "url"
        ],
        14 => [
            "key" => "ent_director_surname",
            "type" => "string"
        ],
        15 => [
            "key" => "ent_director_name",
            "type" => "string"
        ],
        16 => [
            "key" => "ent_director_email",
            "type" => "email"
        ],
        17 => [
            "key" => "ent_director_phone",
            "type" => "phone"
        ],
        18 => [
            "key" => "ent_director_quality",
            "type" => "string"
        ],
        19 => [
            "key" => "ent_director_gender",
            "type" => "string"
        ],
        20 => [
            "key" => "ent_address",
            "type" => "string"
        ],
        21 => [
            "key" => "ent_stage_address",
            "type" => "string"
        ],
        22 => [
            "key" => "internship_dos",
            "type" => "date"
        ],
        23 => [
            "key" => "internship_doe",
            "type" => "date"
        ],
        24 => [
            "key" => "internship_week_hours",
            "type" => "integer"
        ],
        25 => [
            "key" => "internship_remuneration",
            "type" => "integer"
        ],
        26 => [
            "key" => "internship_title",
            "type" => "string"
        ],
        27 => [
            "key" => "internship_hours_text",
            "type" => "string"
        ],
        28 => [
            "key" => "internship_extra_text",
            "type" => "string"
        ],
        29 => [
            "key" => "internship_advantages",
            "type" => "string"
        ],
        30 => [
            "key" => "internship_description",
            "type" => "string"
        ],
        31 => [
            "key" => "internship_remuneration_way",
            "type" => "string"
        ],
        32 => [
            "key" => "ent_tutor_name",
            "type" => "string"
        ],
        33 => [
            "key" => "ent_tutor_surname",
            "type" => "string"
        ],
        34 => [
            "key" => "ent_tutor_email",
            "type" => "email"
        ],
        35 => [
            "key" => "ent_tutor_phone",
            "type" => "phone"
        ],
        36 => [
            "key" => "ent_tutor_quality",
            "type" => "string"
        ],
        37 => [
            "key" => "unice_tutor_name",
            "type" => "string"
        ],
        38 => [
            "key" => "unice_tutor_surname",
            "type" => "string"
        ],
        39 => [
            "key" => "unice_tutor_email",
            "type" => "email"
        ],
        40 => [
            "key" => "unice_tutor_phone",
            "type" => "phone"
        ],
        41 => [
            "key" => "unice_tutor_quality",
            "type" => "string"
        ],
        42 => [
            "key" => "ent_tutor_gender",
            "type" => "string"
        ],
        43 => [
            "key" => "unice_tutor_gender",
            "type" => "string"
        ],
        44 => [
            "key" => "convention_extras",
            "type" => "string"
        ],
    ];
    private $errors;

    public function __construct(array $fields = []){
        //$this->fields = $fields;
        $this->errors = null;
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
            $inputs = $section['inputs'];
            $addresses = $section['addresses'];
            $dropdowns = $section['dropdowns'];
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
        foreach ($this->fields as $field){
            if($input['id'] == $field['key']){
                switch ($field['type']){
                    case 'string' :
                        if(!Respect::stringType()->validate($input['value'])){
                            $this->registerError("invalid", $input['id']);
                        }
                        break;
                    case 'integer' :
                        if(!Respect::intVal()->validate($input['value'])){
                            $this->registerError("invalid", $input['id']);
                        }
                        break;
                    case 'email' :
                        if(!Respect::email()->validate($input['value'])) {
                            $this->registerError("invalid", $input['id']);
                        }
                        break;
                    case 'date' :
                        if(!Respect::date()->validate($input['value'])){
                            $this->registerError("invalid", $input['id']);
                        }
                        break;
                    case 'phone' :
                        if(!Respect::phone()->validate($input['value'])){
                            $this->registerError("invalid", $input['id']);
                        }
                        break;
                    case 'url' :
                        if(!Respect::url()->validate($input['value'])){
                            $this->registerError("invalid", $input['id']);
                        }
                        break;
                }
            }
        }
    }

    private function registerError($message, $key){
        array_push($this->errors, [$key => $message]);
    }
}