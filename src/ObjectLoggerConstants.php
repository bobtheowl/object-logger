<?php
namespace ObjectLogger;

class ObjectLoggerConstants
{
    const SECTION_DIVIDER_REGEX = '/\G(?:"[^"]*"|\'[^\']*\'|[^"\'\s]+)*\K\s+/';

    const PIPE = '|';

    const SECTION_TYPE_UNKNOWN = 0;

    const SECTION_TYPE_PIPED = 1;

    const SECTION_TYPE_PROPERTY = 2;

    const SECTION_TYPE_METHOD = 3;

    const SECTION_TYPE_PROPERTY_PROPERTY = 4;

    const SECTION_TYPE_PROPERTY_METHOD = 5;

    const SECTION_TYPE_GLOBAL_FUNCTION = 6;

    const SECTION_TYPE_STRING_LITERAL = 7;

    const SECTION_TYPE_STATIC_METHOD = 8;

    const ERROR_UNKNOWN_SECTION_TYPE = 'There appears to be a problem with the section: ';
}//end class ObjectLoggerConstants
