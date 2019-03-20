<?php

namespace varnautov\numbers\test;

use varnautov\numbers\classes\Component;
use PHPUnit\Framework\TestCase;

class ComponentTest extends TestCase
{
    public function testHasAttribute()
    {
        $attribute = 'active';
        $obj = new Component();
        $this->assertFalse($obj->hasAttribute($attribute));
        $obj->{$attribute} = true;
        $this->assertTrue($obj->hasAttribute($attribute));
    }

    public function testSetAttributes()
    {
        $attributes = ['active' => true];
        $obj = new Component();
        foreach ($attributes as $attribute => $value) {
            $obj->{$attribute} = null;
        }
        $obj->setAttributes($attributes);
        foreach ($attributes as $attribute => $value) {
            $this->assertSame($obj->{$attribute}, $value);
        }
    }

    public function testConstructor()
    {
        $attributes = ['active' => true];
        $obj = new ActiveComponent($attributes);
        foreach ($attributes as $attribute => $value) {
            $this->assertSame($obj->{$attribute}, $value);
        }
    }
}
