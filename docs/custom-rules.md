Custom Rules
==================

Let's create a rule `round`:

```php
use rock\sanitize\rules\Rule

class Round extends Rule
{
    protected $precision = 0;
    
    public function __construct($precision = 0)
    {
        $this->precision= $precision;
    }

    /**
     * @inheritdoc
     */
    public function sanitize($input)
    {
        return round($input, $this->precision);
    }    
}
```

Profit:

```php
$config = [
    'rules' => [
        'round' => \namespace\to\Round::className()
    ]
];
$s = new Sanitize($config);

$s->round()->sanitize(7.4); // output: 7.0
```