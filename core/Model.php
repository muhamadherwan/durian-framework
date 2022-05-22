<?php

namespace app\core;

// create as absract to avoid create an instant on this model everytime used.
abstract class Model
{
    // set validation rules constant.
    public const RULE_REQUIRED = 'required';
    public const RULE_EMAIL = 'email';
    public const RULE_MIN = 'min';
    public const RULE_MAX = 'max';
    public const RULE_MATCH = 'match';
    public const RULE_UNIQUE = 'unique';

    // load data from form
    public function loadData($data)
    {

        foreach ($data as $key => $value) {
            // check is submitted data exist in child class property,
            // For example, check if the firstname key in submited form data
            // is exist in RegisterModel class property.
            // If exist set the data values to the properties.
            if (property_exists($this, $key)) {
                $this->{$key} = $value;
            }

        }
    }

    //  set validation rules in child class
    abstract public function rules():array;

    public function labels(): array
    {
        return [];
    }

    public function getLabel($attribute)
    {
        return $this->labels()[$attribute] ?? $attribute;
    }

    public array $errors = [];

    // validate data from form
    public function validate(): bool
    {

        foreach ($this->rules() as $attribute => $rules) {

            // set the field input data based on form input name
            $value = $this->{$attribute};

            // set field validation rules
            foreach ($rules as $rule) {
                $ruleName = $rule;
                // if has many rules, set it as array
                if (!is_string($ruleName)) {
                    $ruleName = $rule[0];
                }

                // start the validation
                if ($ruleName === self::RULE_REQUIRED && !$value) {
                    $this->addError($attribute,self::RULE_REQUIRED);
                }

                if ($ruleName === self::RULE_EMAIL && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $this->addError($attribute,self::RULE_EMAIL);
                }

                if ($ruleName === self::RULE_MIN && strlen($value) < $rule['min']) {
                    $this->addError($attribute,self::RULE_MIN, $rule);
                }

                if ($ruleName === self::RULE_MAX && strlen($value) > $rule['max']) {
                    $this->addError($attribute,self::RULE_MAX, $rule);
                }

                if ($ruleName === self::RULE_MATCH && $value !== $this->{$rule['match']}) {
                    $rule['match'] = $this->getLabel($rule['match']);
                    $this->addError($attribute,self::RULE_MATCH, $rule);
                }

                if ($ruleName === self::RULE_UNIQUE) {
                    $className = $rule['class'];
                    $uniqueAttr = $rule['attribute'] ?? $attribute;
                    $tableName = $className::tableName();
                    $statement = Application::$app->db->prepare("SELECT * FROM $tableName WHERE $uniqueAttr = :attr");
                    $statement->bindValue(":attr", $value);
                    $statement->execute();
                    $record = $statement->fetchObject();
                    if ($record) {
                        $this->addError($attribute, self::RULE_UNIQUE, ['field' => $this->labels()[$attribute]]);
                    }
                }


            }
        }

        return empty($this->errors);

    }

    public function addError(string $attribute, string $rule, $params = [])
    {
        // get the error message based on the rule
        $message = $this->errorMessages()[$rule] ?? '';

        // get the params and replace is as the message
        foreach($params as $key => $value){
            $message = str_replace("{{$key}}", $value, $message);
        }

        $this->errors[$attribute][] = $message;

    }

    public function errorMessages(): array
    {
        return [
            self::RULE_REQUIRED => 'This field is required',
            self::RULE_EMAIL => 'This field must be a valid email address',
            self::RULE_MIN => 'This length of this field must be {min}',
            self::RULE_MAX => 'This length of this field must be {max}',
            self::RULE_MATCH => 'This field must be the same as {match}',
            self::RULE_UNIQUE => 'Record with this {field} already exists',
        ];
    }

    public function hasError($attribute)
    {
        return $this->errors[$attribute] ?? false;
    }

    // return the first error only
    public function getFirstError($attribute)
    {
        return $this->errors[$attribute][0] ?? false;
    }

}