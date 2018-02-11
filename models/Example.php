<?php
class Example {
    private $language;
    private $example;
    private $group_cd;
    private $group_name;
    private $example_id;

    public function __construct(array $source) {
        $this->language = $source['language'];
        $this->example_id = $source['example_id'];
        $this->example = $source['example'];
        $this->group_cd = $source['group_cd'];
        $this->group_name = $source['group_name'];
    }

    public function __get($name)
    {
        return $this->$name;
    }

    public function toArray()
    {
        return [
            'language' => $this->language,
            'example_id' => $this->example_id,
            'example' => $this->example,
            'group_cd' => $this->group_cd,
            'group_name' => $this->group_name,
        ];
    }
}
