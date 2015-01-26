<?php

namespace rock\sanitize;


use rock\base\ObjectInterface;
use rock\base\ObjectTrait;
use rock\di\Container;
use rock\sanitize\rules\Abs;
use rock\sanitize\rules\BasicTags;
use rock\sanitize\rules\Boolean;
use rock\sanitize\rules\Call;
use rock\sanitize\rules\Decode;
use rock\sanitize\rules\DefaultRule;
use rock\sanitize\rules\Email;
use rock\sanitize\rules\Encode;
use rock\sanitize\rules\Float;
use rock\sanitize\rules\Int;
use rock\sanitize\rules\Lowercase;
use rock\sanitize\rules\LowerFirst;
use rock\sanitize\rules\LtrimWords;
use rock\sanitize\rules\Negative;
use rock\sanitize\rules\NoiseWords;
use rock\sanitize\rules\Numbers;
use rock\sanitize\rules\Positive;
use rock\sanitize\rules\Round;
use rock\sanitize\rules\SpecialChars;
use rock\sanitize\rules\ReplaceRandChars;
use rock\sanitize\rules\RtrimWords;
use rock\sanitize\rules\Rule;
use rock\sanitize\rules\RemoveScript;
use rock\sanitize\rules\RemoveTags;
use rock\sanitize\rules\String;
use rock\sanitize\rules\ToType;
use rock\sanitize\rules\Slug;
use rock\sanitize\rules\Trim;
use rock\sanitize\rules\Truncate;
use rock\sanitize\rules\TruncateWords;
use rock\sanitize\rules\Unserialize;
use rock\sanitize\rules\Uppercase;
use rock\sanitize\rules\UpperFirst;

/**
 * Class Sanitize
 * @method static Sanitize attributes(array $attributes)
 * @method static Sanitize allOf(Sanitize $sanitize)
 * @method static Sanitize nested(bool $nested = true)
 * @method static Sanitize rules(array $rules)
 *
 * @method static Sanitize abs()
 * @method static Sanitize basicTags(string $allowedTags = '')
 * @method static Sanitize bool()
 * @method static Sanitize call(mixed $call, array $args = null)
 * @method static Sanitize decode()
 * @method static Sanitize defaultValue(mixed $default = null)
 * @method static Sanitize email()
 * @method static Sanitize encode(bool $doubleEncode = true)
 * @method static Sanitize float()
 * @method static Sanitize int()
 * @method static Sanitize lowercase()
 * @method static Sanitize lowerFirst()
 * @method static Sanitize ltrimWords(array $words)
 * @method static Sanitize negative()
 * @method static Sanitize noiseWords(string $enNoiseWords = '')
 * @method static Sanitize numbers()
 * @method static Sanitize positive()
 * @method static Sanitize specialChars()
 * @method static Sanitize removeScript()
 * @method static Sanitize removeTags()
 * @method static Sanitize replaceRandChars(string $replaceTo = '*')
 * @method static Sanitize round(int $precision = 0)
 * @method static Sanitize rtrimWords(array $words)
 * @method static Sanitize string()
 * @method static Sanitize toType()
 * @method static Sanitize slug(string $replacement = '-', bool $lowercase = true)
 * @method static Sanitize trim()
 * @method static Sanitize truncate(int $length = 4, string $suffix = '...')
 * @method static Sanitize truncateWords(int $length = 100, string $suffix = '...')
 * @method static Sanitize unserialize()
 * @method static Sanitize uppercase()
 * @method static Sanitize upperFirst()
 *
 * @package rock\sanitize
 */
class Sanitize implements ObjectInterface
{
    use ObjectTrait {
        ObjectTrait::__construct as parentConstruct;
        ObjectTrait::__call as parentCall;
    }

    const REMAINDER = '_remainder';

    /**
     * Sanitize rules.
     * @var array
     */
    public $rules = [];
    public $nested = true;
    /** @var Rule[]  */
    public  $_rules = [];

    public function __construct($config = [])
    {
        $this->parentConstruct($config);
        $this->rules = array_merge($this->defaultRules(), $this->rules);
    }

    /**
     * Sanitize value.
     *
     * @param mixed $input
     * @return mixed
     * @throws SanitizeException
     */
    public function sanitize($input)
    {
        foreach($this->_rules as $rule){

            if ($rule instanceof Attributes) {
                return $rule->sanitize($input);
            }
            if ($rule instanceof AllOf) {
                return $rule->sanitize($input);
            }
            if ((is_array($input) || is_object($input)) && $this->nested) {
                return (new AllOf(['sanitize' => $this]))->sanitize($input);
            }

            $input = $rule->sanitize($input);
            if ((is_array($input) || is_object($input)) && $this->nested) {
                $input = (new AllOf(['sanitize' => $this]))->sanitize($input);
            }
        }

        return $input;
    }

    /**
     * Exists rule.
     * @param string  $name name of rule.
     * @return bool
     */
    public function existsRule($name)
    {
        return isset($this->rules[$name]);
    }

    public function __call($name, $arguments)
    {
        if (method_exists($this, "{$name}Internal")) {
            return call_user_func_array([$this, "{$name}Internal"], $arguments);
        }

        if (!isset($this->rules[$name])) {
            throw new SanitizeException("Unknown rule: {$name}");
        }
        if (!class_exists($this->rules[$name])) {
            throw new SanitizeException(SanitizeException::UNKNOWN_CLASS, ['class' => $this->rules[$name]]);
        }
        /** @var Rule $rule */
        $reflect = new \ReflectionClass($this->rules[$name]);
        $rule = $reflect->newInstanceArgs($arguments);
        $this->_rules[] = $rule;
        return $this;
    }

    public static function __callStatic($name, $arguments)
    {
        return call_user_func_array([static::getInstance(static::className()), $name], $arguments);
    }

    protected function attributesInternal(array $attributes)
    {
        $this->_rules = [];
        $this->_rules[] = new Attributes(['attributes' => $attributes]);

        return $this;
    }

    protected function allOfInternal(Sanitize $sanitize)
    {
        $this->_rules = [];
        $this->_rules[] = new AllOf(['sanitize' => $sanitize]);

        return $this;
    }

    protected function nestedInternal($nested = true)
    {
        $this->nested = $nested;
        return $this;
    }

    protected function rulesInternal(array $rules)
    {
        foreach ($rules as $rule => $args) {
            if (is_int($rule)) {
                $rule = $args;
                $args = [];
            }
            if (is_array($rule)) {
                $args = (array)current($rule);
                $rule = key($rule);
            }
            call_user_func_array([$this, $rule], $args);
        }
        return $this;
    }

    /**
     * Get instance.
     *
     * If exists {@see \rock\di\Container} that uses it.
     *
     * @param string|array $config the configuration. It can be either a string representing the class name
     *                                     or an array representing the object configuration.
     * @return static
     * @throws \rock\di\ContainerException
     */
    protected static function getInstance($config)
    {
        if (class_exists('\rock\di\Container')) {
            return Container::load($config);
        }
        return new static();
    }

    protected function defaultRules()
    {
        return [
            'abs' => Abs::className(),
            'basicTags' => BasicTags::className(),
            'bool' => Boolean::className(),
            'call' => Call::className(),
            'decode' => Decode::className(),
            'defaultValue' => DefaultRule::className(),
            'email' => Email::className(),
            'encode' => Encode::className(),
            'float' => Float::className(),
            'int' => Int::className(),
            'lowercase' => Lowercase::className(),
            'lowerFirst' => LowerFirst::className(),
            'ltrimWords' => LtrimWords::className(),
            'negative' => Negative::className(),
            'noiseWords' => NoiseWords::className(),
            'numbers' => Numbers::className(),
            'positive' => Positive::className(),
            'specialChars' => SpecialChars::className(),
            'removeScript' => RemoveScript::className(),
            'removeTags' => RemoveTags::className(),
            'replaceRandChars' => ReplaceRandChars::className(),
            'round' => Round::className(),
            'rtrimWords' => RtrimWords::className(),
            'string' => String::className(),
            'toType' => ToType::className(),
            'slug' => Slug::className(),
            'trim' => Trim::className(),
            'truncate' => Truncate::className(),
            'truncateWords' => TruncateWords::className(),
            'unserialize'=> Unserialize::className(),
            'uppercase'=> Uppercase::className(),
            'upperFirst'=> UpperFirst::className(),
        ];
    }
}