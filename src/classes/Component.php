<?php

namespace varnautov\numbers\classes;

class Component
{
    /**
     * FormBase constructor
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->setAttributes($attributes);
    }

    /**
     * @param array $attributes
     */
    public function setAttributes(array $attributes = [])
    {
        foreach ($attributes as $attribute => $value) {
            if ($this->hasAttribute($attribute)) {
                $this->{$attribute} = $value;
            }
        }
    }

    /**
     * @param string $attribute
     * @return bool
     */
    public function hasAttribute(string $attribute): bool
    {
        return property_exists($this, $attribute);
    }
}
