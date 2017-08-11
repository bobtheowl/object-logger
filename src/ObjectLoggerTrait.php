<?php
namespace ObjectLogger;

use ObjectLogger\ObjectLoggerConstants as Consts;
use ObjectLogger\ObjectLoggerException as ObjectLoggerException;

trait ObjectLoggerTrait
{
    // DEV NOTES ABOUT TEMPLATE FORMATTING
    // '{id} {name}|strtoupper() "week"|->getData|json_encode()'
    // '(ClassName [id: 784239] [name: IN UPPERCASE] [week: {week: 1, data: stuff}])'
    /*
     {property}                 property               output [id: 859340]
     function()                 std function call      output function() return
     ->method                   method call            output $this->method() return
     {property}->{subProperty}  property sub-property  output $this->property->subProperty
     {property}->method         property method call   output $this->property->method() return
     Class::staticMeth          static method call     output Class::staticMeth() return
     |                          pipe                   take the value on the left and pass it to the right
     "string"                   string                 output exactly as-is
                                section separator      output as a space between sections
    */

    /**
     * Gets the short class name for the current class.
     * @return string
     */
    protected function olGetClass()
    {
        $reflection = new \ReflectionClass(self::class);
        return $reflection->getShortName();
    }//end olGetClass()

    /**
     * Returns the section type of the given section.
     * @param string $section
     * @return int
     * @see \ObjectLogger\ObjectLoggerConstants
     */
    protected function olGetSectionType($section)
    {
        switch (true) {
            case (stripos($section, '|') !== false):
                return Consts::SECTION_TYPE_PIPED;
            case (
                stripos($section, '{') !== false &&
                stripos($section, '}') !== false &&
                stripos($section, '->') === false
            ):
                return Consts::SECTION_TYPE_PROPERTY;
            case (
                stripos($section, '{') === false &&
                stripos($section, '}') === false &&
                stripos($section, '->') !== false
            ):
                return Consts::SECTION_TYPE_METHOD;
            case (
                stripos($section, '{') !== false &&
                stripos($section, '}') !== false &&
                stripos($section, '->') !== false &&
                stripos($section, '()') === false
            ):
                return Consts::SECTION_TYPE_PROPERTY_PROPERTY;
            case (
                stripos($section, '{') !== false &&
                stripos($section, '}') !== false &&
                stripos($section, '->') !== false &&
                stripos($section, '()') !== false
            ):
                return Consts::SECTION_TYPE_PROPERTY_METHOD;
            case (stripos($section, '()') !== false):
                return Consts::SECTION_TYPE_GLOBAL_FUNCTION;
            case (stripos($section, '"') !== false):
                return Consts::SECTION_TYPE_STRING_LITERAL;
            case (stripos($section, '::') !== false):
                return Consts::SECTION_TYPE_STATIC_METHOD;
        }
        return Consts::SECTION_TYPE_UNKNOWN;
    }

    protected function olGetLabelForTemplateSection($section)
    {
        switch ($this->olGetSectionType($section)) {
            case Consts::SECTION_TYPE_PROPERTY:
                return str_replace(['{', '}'], ['', ''], $section);
            case Consts::SECTION_TYPE_METHOD:
                return str_replace('->', '', $section);
            case Consts::SECTION_TYPE_PROPERTY_PROPERTY:
                return str_replace(['{', '}'], ['', ''], $section);
            case Consts::SECTION_TYPE_PROPERTY_METHOD:
                list($property, $method) = explode('->', $section);
                return str_replace(['{', '}'], ['', ''], $property) . '::' . $method;
            case Consts::SECTION_TYPE_GLOBAL_FUNCTION:
                return $section;
            case Consts::SECTION_TYPE_STRING_LITERAL:
                return '';
            case Consts::SECTION_TYPE_STATIC_METHOD:
                list($class, $method) = explode('::', $section);
                // str_replace because '()' is technically optional
                return str_replace('()', '', $method) . '()';
        }//end switch
        throw new ObjectLoggerException(Consts::ERROR_UNKNOWN_SECTION_TYPE . $section);
    }//end olGetLabelForTemplateSection()

    protected function olParseTemplateSection($section, $arg = null)
    {
        switch ($this->olGetSectionType($section)) {
            case Consts::SECTION_TYPE_PROPERTY:
                return $this->{str_replace(['{', '}'], ['', ''], $section)};
            case Consts::SECTION_TYPE_METHOD:
                return call_user_func([$this, str_replace('->', '', $section)], $arg);
            case Consts::SECTION_TYPE_PROPERTY_PROPERTY:
                list($property, $subProperty) = explode('->', $section);
                $property = str_replace(['{', '}'], ['', ''], $property);
                $subProperty = str_replace(['{', '}'], ['', ''], $subProperty);
                return $this->{$property}->{$subProperty};
            case Consts::SECTION_TYPE_PROPERTY_METHOD:
                list($property, $method) = explode('->', $section);
                $property = str_replace(['{', '}'], ['', ''], $property);
                return call_user_func([$this->{$property}, $method], $arg);
            case Consts::SECTION_TYPE_GLOBAL_FUNCTION:
                $function = str_replace('()', '', $section);
                return ($arg === null)
                    ? call_user_func($function)
                    : call_user_func($function, $arg);
            case Consts::SECTION_TYPE_STRING_LITERAL:
                return str_replace('"', '', $section);
            case Consts::SECTION_TYPE_STATIC_METHOD:
                return call_user_func(str_replace('()', '', $section), $arg);
        }//end switch
        throw new ObjectLoggerException(Consts::ERROR_UNKNOWN_SECTION_TYPE . $section);
    }//end olParseTemplateSection()

    protected function olHandlePipedSection($section)
    {
        $value = null;
        $sectionArray = explode(Consts::PIPE, $section);

        foreach (explode(Consts::PIPE, $section) as $index => $subSection) {
            $value = $this->olParseTemplateSection($subSection, $value);
        }//end foreach

        $label = $this->olGetLabelForTemplateSection($sectionArray[0]);

        return ($label === '')
            ? '[' . $value . ']'
            : '[' . $label . ': ' . $value . ']';
    }//end olHandlePipedSection()

    /**
     * Generates a log message based on the class template.
     * @return string
     */
    public function olGenerateMessage()
    {
        $message = '(' . $this->olGetClass() . ' ';

        $sectionArray = [];
        foreach (preg_split(Consts::SECTION_DIVIDER_REGEX, $this->logTemplate) as $section) {
            $isPiped = (stripos($section, Consts::PIPE) !== false);
            $value = ($isPiped)
                ? $this->olHandlePipedSection($section)
                : $this->olParseTemplateSection($section);
            $label = ($isPiped) ? '' : $this->olGetLabelForTemplateSection($section);
            if ($isPiped) {
                $sectionArray[] = $value;
            } else {
                $sectionArray[] = ($label === '')
                    ? '[' . $value . ']'
                    : '[' . $label . ': ' . $value . ']';
            }//end if/else
        }//end foreach
        $message .= implode(' ', $sectionArray);

        $message .= ')';

        return $message;
    }//end olGenerateMessage()
}//end trait ObjectLoggerTrait
