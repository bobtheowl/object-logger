<?php

require dirname(__FILE__) . '/../src/ObjectLoggerConstants.php';
require dirname(__FILE__) . '/../src/ObjectLoggerException.php';
require dirname(__FILE__) . '/../src/ObjectLoggerTrait.php';

use ObjectLogger\ObjectLoggerTrait;

class TestObject
{
    use ObjectLoggerTrait;

    protected $logTemplate = '{id} {name}|strtoupper() "week"|->getData|json_encode()';

    public $id = 23;

    public $name = 'Test Object Name';

    public function getData($range)
    {
        return [
            'range' => $range,
            'data' => [
                125,
                231,
                315,
                153,
                321
            ]
        ];
    }
}

$obj = new TestObject;
var_export($obj->olGenerateMessage());
