<?php

namespace App\Models;

class Address{
    public $street;
    public $city;
    public $state;
    public $country;
    public $postal_code;

    public function __construct(string $street, string $city, string $state, string $country, string $postal_code){
        $this->street = $street;
        $this->city = $city;
        $this->state = $state;
        $this->country = $country;
        $this->postal_code = $postal_code;
    }
}