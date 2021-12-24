<?php

class Base
{
    /**
     * The attributes that are accessible for mass assignment.
     *
     * @var array
     */
    public static $accessible;

    /**
     * Hydrate the model with an array of attributes.
     *
     * @param  array  $attributes
     * @param  bool   $raw
     * @return Model
     */
    public function fill(array $attributes, $raw = false)
    {
        foreach ($attributes as $key => $value)
        {
            // If the "raw" flag is set, it means that we'll just load every value from
            // the array directly into the attributes, without any accessibility or
            // mutators being accounted for. What you pass in is what you get.
            if ($raw)
            {
                $this->set_attribute($key, $value);

                continue;
            }

            // If the "accessible" property is an array, the developer is limiting the
            // attributes that may be mass assigned, and we need to verify that the
            // current attribute is included in that list of allowed attributes.
            if (is_array(static::$accessible))
            {
                if (in_array($key, static::$accessible))
                {
                    $this->$key = $value;
                }
            }

            // If the "accessible" property is not an array, no attributes have been
            // white-listed and we are free to set the value of the attribute to
            // the value that has been passed into the method without a check.
            else
            {
                $this->$key = $value;
            }
        }

        return $this;
    }

    /**
     * Set an attribute's value on the model.
     *
     * @param  string  $key
     * @param  mixed   $value
     * @return void
     */
    public function set_attribute($key, $value)
    {
        $this[$key] = $value;
    }
}