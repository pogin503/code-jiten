<?php
abstract class ValueObject
{
    public function __construct(array $params)
    {
        foreach($params as $propertyName => $value) {
            $this->$propertyName = $value;
        }
    }

    public function __get($propertyName)
    {
        if (!property_exists(get_class($this), $propertyName)) {
            throw new Exception(sprintf('undefined property %s', $propertyName));
        }

        return $this->$propertyName;
    }

    public function toArray() {
        $class_vars = get_object_vars($this);
        $rtn = [];
        foreach($class_vars as $propertyName => $value) {
            $rtn["$propertyName"] = $value;
        }
        return $rtn;
    }
}
