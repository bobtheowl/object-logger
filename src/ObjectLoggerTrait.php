<?php
namespace ObjectLogger;

use ObjectLoggerConstants as Consts;

trait ObjectLoggerTrait {
    protected $logTemplate = '';

    // DEV NOTES ABOUT TEMPLATE FORMATTING
    // '{id} {name}|strtoupper() "week"|->getData|json_encode()'
    // '(ClassName [id: 784239] [name: IN UPPERCASE] [week: {week: 1, data: stuff}])'
    /*
     {property}           property            output [id: 859340]
     function()           std function call   output function() return
     ->method             method call         output $this->method() return
     {property}->method
     Class::staticMeth  
     |                    pipe                take the value on the left and pass it to the right
     "string"             string              output exactly as-is
                          section separator   output as a space between sections
    */

    /**
     * Gets the short class name for the current class.
     * @return string
     */
    protected function olGetClass() {
        $reflection = new \ReflectionClass(self::class);
        return $reflection->getShortName();
    }//end olGetClass()

    /**
     * Returns the section type of the given section.
     * @param string $section
     * @return int
     * @see \ObjectLogger\ObjectLoggerConstants
     */
    protected function olGetSectionType($section) {
        switch (true) {
            case (stripos('|', $section) !== false):
                return Consts::SECTION_TYPE_PIPED;
            case (
                stripos('{', $section) !== false &&
                stripos('}', $section) !== false &&
                stripos('->', $section) === false
            ):
                return Consts::SECTION_TYPE_PROPERTY;
            case (
                stripos('{', $section) === false &&
                stripos('}', $section) === false &&
                stripos('->', $section) !== false
            ):
                return Consts::SECTION_TYPE_METHOD;
            case (
                stripos('{', $section) !== false &&
                stripos('}', $section) !== false &&
                stripos('->', $section) !== false
            ):
                return Consts::SECTION_TYPE_PROPERTY_METHOD;
            case (stripos('()', $section) !== false):
                return Consts::SECTION_TYPE_GLOBAL_FUNCTION;
            case (stripos('"', $section) !== false):
                return Consts::SECTION_TYPE_STRING_LITERAL;
            case (stripos('::', $section) !== false):
                return Consts::SECTION_TYPE_STATIC_METHOD;
        }
        return Consts::SECTION_TYPE_UNKNOWN;
    }

    protected function olGetLabelForTemplateSection($section) {
        //TODO
    }//end olGetLabelForTemplateSection()

    protected function olParseTemplateSection($sectionType, $section, $arg = null) {
        switch ($sectionType) {
            case Consts::SECTION_TYPE_PROPERTY:
                break;//TODO
            case Consts::SECTION_TYPE_METHOD:
                break;//TODO
            case Consts::SECTION_TYPE_PROPERTY_METHOD:
                break;//TODO
            case Consts::SECTION_TYPE_GLOBAL_FUNCTION:
                $function = str_replace('()', '', $section);
                return ($value === null)
                    ? call_user_func($function)
                    : call_user_func($function, $arg);
            case Consts::SECTION_TYPE_STRING_LITERAL:
                return str_replace('"', '', $section);
            case Consts::SECTION_TYPE_STATIC_METHOD:
                break;//TODO
        }
        throw new \Exception(Consts::ERROR_UNKNOWN_SECTION_TYPE . $section);
    }//end olParseTemplateSection()

    protected function olHandlePipedSection($section) {
        $string = '';

        //TODO

        return $string;
    }//end olHandlePipedSection()

    /**
     * Generates a log message based on the class template.
     * @return string
     */
    protected function olGenerateMessage() {
        $message = '(' . $this->getClassForLog() . ' ';

        $sectionArray = [];
        foreach (explode($this->logTemplate) as $section) {
            //TODO: Set the label and the surround
            $sectionArray[] = (stripos($section, '|') === false)
                ? $this->olParseTemplateSection($section)
                : $this->olHandlePipedSection($section);
        }
        $message .= implode(' ', $sectionArray);

        $message .= ')';

        return $message;
    }//end olGenerateMessage()
}