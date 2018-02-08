<?php
require 'ValueObject.php';

class ExampleGroup extends ValueObject {
    protected $group_cd;
    protected $group_name;
    protected $desc;
    protected $disp_flag;
}
