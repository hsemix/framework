<?php
/**
 * Bluz Framework Component
 *
 * @copyright Bluz PHP Team
 * @link https://github.com/bluzphp/framework
 */

/**
 * @namespace
 */
namespace Bluz\Validator\Rule;

use Countable;
use Bluz\Validator\Exception\ComponentException;

/**
 * Class Length
 * @package Bluz\Validator\Rule
 */
class Length extends AbstractRule
{
    /**
     * @var integer
     */
    protected $minValue;

    /**
     * @var integer
     */
    protected $maxValue;

    /**
     * @var bool
     */
    protected $inclusive;

    /**
     * @param integer|null $min
     * @param integer|null $max
     * @param bool $inclusive
     * @throws \Bluz\Validator\Exception\ComponentException
     */
    public function __construct($min = null, $max = null, $inclusive = true)
    {
        $this->minValue = $min;
        $this->maxValue = $max;
        $this->inclusive = $inclusive;

        if ($min && !is_numeric($min)) {
            throw new ComponentException(
                __('"%s" is not a valid numeric length', $min)
            );
        }

        if ($max && !is_numeric($max)) {
            throw new ComponentException(
                __('"%s" is not a valid numeric length', $max)
            );
        }

        if (!is_null($min) && !is_null($max) && $min > $max) {
            throw new ComponentException(
                __('"%s" cannot be less than "%s" for validation', $min, $max)
            );
        }
    }

    /**
     * @param string $input
     * @return bool
     */
    public function validate($input)
    {
        if (!$length = $this->extractLength($input)) {
            return false;
        }
        return $this->validateMin($length) && $this->validateMax($length);
    }

    /**
     * @param string $input
     * @return integer
     */
    protected function extractLength($input)
    {
        if (is_string($input)) {
            return mb_strlen($input, mb_detect_encoding($input));
        } elseif (is_array($input) || $input instanceof Countable) {
            return sizeof($input);
        } elseif (is_object($input)) {
            return count(get_object_vars($input));
        } else {
            return false;
        }
    }

    /**
     * @param integer $length
     * @return bool
     */
    protected function validateMin($length)
    {
        if (is_null($this->minValue)) {
            return true;
        }

        if ($this->inclusive) {
            return $length >= $this->minValue;
        }

        return $length > $this->minValue;
    }

    /**
     * @param integer $length
     * @return bool
     */
    protected function validateMax($length)
    {
        if (is_null($this->maxValue)) {
            return true;
        }

        if ($this->inclusive) {
            return $length <= $this->maxValue;
        }

        return $length < $this->maxValue;
    }

    /**
     * Get error template
     *
     * @return string
     */
    public function getTemplate()
    {
        if (!$this->minValue) {
            return __('"{{name}}" must have a length greater than %d', $this->minValue);
        } elseif (!$this->maxValue) {
            return __('"{{name}}" must have a length lower than %d', $this->maxValue);
        } else {
            return __('"{{name}}" must have a length between %d and %d', $this->minValue, $this->maxValue);
        }
    }
}
