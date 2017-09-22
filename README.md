# object-logger

Add logging templates to objects.

## Installation

Coming soon.

## Basic Usage

To add an object logging formatter to a class, simply implement `\ObjectLogger\ObjectLoggerTrait` and
add the format template as a protected `$logTemplate` property.

    use ObjectLogger\ObjectLoggerTrait;

    class ClassNameHere
    {
        use ObjectLoggerTrait;

        /**
         * Format used for logging.
         * @var string
         */
        protected $logTemplate = '{id} {name}|strtoupper() "week"|->getData|json_encode()';

Then you can call the `olGenerateMessage()` method on the object to get a formatted string containing
data from the class instance.

    $obj = new ClassNameHere;
    $obj->olGenerateMessage();
    // (ClassNameHere [id: 23] [name: TEST OBJECT NAME] [{"range":"week","data":[125,231,315,153,321]}])

## Available Formatting Options

### Output a property

#### Template

    {id}

#### Output

    [id: 859340]

### Output the result of a method

#### Template

    ->methodName

#### Output

    // Assuming $obj->methodName() returns an integer
    [methodName: 23]

### Output properties/methods of an object property

#### Template

    {subObj}->{property}

    {subObj}->method

#### Output

Outputs similar to the standard property/method output, except with the property name as well.

    // Assuming the property and method both return integers
    [subObj::property: 95235]

    [subObj::method: 32940]

### Output the result of a global function

#### Template

    time()

#### Output

    [time: 1506105591]

### Output the result of a static method

#### Template

    Zookeeper::getClientId

#### Output

    [getClientId: 0x140cfa4ae54000c]

### Output a string

#### Template

    "some string"

#### Output

    [some string]

### Redirect a value to a function/method

Different types of templates can be connected together with pipes, similar to piping in Linux.

The result of the first section of the template will be sent to the template after the pipe.

Currently only functions/methods support piping. This is also limited to a single argument as well.

#### Template

    {name}|strtoupper()

#### Resulting Logic

    strtoupper($this->name)

#### Output

    [name: JOHN DOE]

Note that the label is taken from the first section of the template.

## Integration

This trait is meant to be combined with a service provider for Laravel or some similar type of feature.
This way you can do something similar to `\Log::info($obj);` and get a formatted string. This is also
one of the reasons for the method names. They both lower the risk of interfering with the normal class
methods and they aren't meant to be called directly anyway.

Integration libraries to be added later.
