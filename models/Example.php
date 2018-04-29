<?php
class Example
{
    private $_example;
    private $_example_id;
    private $_language;
    private $_language_id;
    private $_group_cd;
    private $_group_name;

    public function __construct(array $source)
    {
        $this->_example_id = $source['example_id'];
        $this->_example = $source['example'];
        $this->_language_id = $source['language_id'];
        $this->_language = $source['language'];
        $this->_group_cd = $source['group_cd'];
        $this->_group_name = $source['group_name'];
    }

    public function __get($name)
    {
        return $this->$name;
    }

    public function toArray()
    {
        return [
            'example_id' => $this->_example_id,
            'example' => $this->_example,
            'language_id' => $this->_language_id,
            'language' => $this->_language,
            'group_cd' => $this->_group_cd,
            'group_name' => $this->_group_name,
        ];
    }
}
