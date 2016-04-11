<?php

namespace Connections\ConnectionsBundle\Helper;


class FieldsValidator{


    /**
     * Mandatory fields for Registration Form
     */
    private $mandatoryRegistrationForm = array('Username','Password','Email','Name','Image');


    /**
     * Validate Registration Form Fields
     *
     * @param $data
     * @return string|null
     */
    public function validateRegistrationForm($data){
        return $this->validation($this->mandatoryRegistrationForm, $data);
    }

    /**
     * Validate  function
     *
     * @param $data
     * @param $mandatoryFields
     * @return string|null
     */
    private function validation($mandatoryFields, $data){
        $output = null;
        foreach($mandatoryFields as $field){
            if(!array_key_exists($field,$data) || $data[$field] == '' || $data[$field] == null){
                $output.= $field . ' must be set ';
                break;
            };
        };
        return $output;
    }
}