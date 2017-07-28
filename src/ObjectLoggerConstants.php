<?php
namespace ObjectLogger;

class ObjectLoggerConstants
{
    const SECTION_TYPE_UNKNOWN = 0;

    const SECTION_TYPE_PIPED = 1;

    const SECTION_TYPE_PROPERTY = 2;

    const SECTION_TYPE_METHOD = 4;

    const SECTION_TYPE_PROPERTY_METHOD = 6;

    const SECTION_TYPE_GLOBAL_FUNCTION = 8;

    const SECTION_TYPE_STRING_LITERAL = 16;

    const SECTION_TYPE_STATIC_METHOD = 32;

    const ERROR_UNKNOWN_SECTION_TYPE = 'There appears to be a problem with the section: ';
}//end class ObjectLoggerConstants
