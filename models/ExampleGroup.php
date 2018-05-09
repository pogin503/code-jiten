<?php
/**
 * Example Group Class
 *
 * @category Class
 * @package  MyPackage
 * @author   pogin503 <pogin503@gmail.com>
 *
 */
require 'ValueObject.php';

class ExampleGroup extends ValueObject
{
    protected $group_cd;
    protected $group_name;
    protected $desc;
    protected $disp_flag;
}
