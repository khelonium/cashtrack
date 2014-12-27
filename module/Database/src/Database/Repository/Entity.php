<?php

namespace Database\Repository;

class Entity extends \ArrayObject
{

    /**
     * Key and Value pairs , where the key is the database field
     * and the value is the model field
     * @var array
     */
    protected $map = array();

    /**
     * (non-PHPdoc)
     * @see ArrayObject::exchangeArray()
     */
    public function exchangeArray($data)
    {
        $transformed_data = $this->doMapping($data);

        return parent::exchangeArray($transformed_data);
    }


    public function getDatabaseCopy()
    {
        $reversed_map = array_flip($this->map);
        $data         = $this->getArrayCopy();
        $out          = array();

        foreach ($data as $key => $val) {
            $mapped_key       = isset($reversed_map[$key])?$reversed_map[$key]:$key;
            $out[$mapped_key] = $val;
        }


        return $out;
    }

    /**
     * Returns a function that can be used to obtain db copy
     * @return callable
     */
    public function getDatabaseMapper()
    {
        $reversed_map =  array_flip($this->map);
        return function ($key) use ($reversed_map) {
            return isset($reversed_map[$key])?$reversed_map[$key]:$key;
        };
    }


    /**
     * @param mixed $name
     * @return mixed
     */
    public function __get( $name )
    {
        return $this[$name];
    }

    /**
     * @param mixed $name
     * @param mixed $value
     */
    public function __set( $name, $value )
    {
        $this[$name] = $value;
    }

    /**
     * @param mixed $name
     *
     * @return bool
     */
    public function __isset( $name )
    {
        return isset( $this[$name] );
    }

    /**
     * @param mixed $name
     */
    public function __unset( $name )
    {
        unset( $this[$name] );
    }

    /**
     *      * End of stolen code *
     */

    /**
     * @param array $data
     * @return array
     */
    protected function doMapping($data)
    {
        if (count($this->map) == 0) {

            return $data;
        }

        $out = array();

        foreach ($data as $key=> $val) {
            if (isset($this->map[$key])) {
                $out[$this->map[$key]] = $val;
            } else {
                $out[$key] = $val;
            }
        }

        return $out;
    }

}