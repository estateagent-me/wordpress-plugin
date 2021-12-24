<?php

class Property extends Base
{
    public function __construct($attributes = array())
    {
        $this->fill($attributes);
    }

    public function getAddrStreet()
    {
        return $this->getPropertyNumber() . ' ' . $this->getStreetName();
    }

    public function price( $strike = false, $only = false )
    {
        if ($this->SaleOrRent() == 's')
        {
            if ($strike) return '<s>' . \Price::display('GBP', $this->getPriceSale(), 0) . '</s> <small>' . $this->price_desc . '</small>';
            if ($only) return $this->getPriceSale();
            return \Price::display('GBP', $this->getPriceSale(), 0) . ' <small>' . $this->price_desc . '</small>';
        }
        elseif ($this->SaleOrRent() == 'r')
        {
            if ($strike) return '<s>' . \Price::display('GBP', $this->getPriceRent(), 0) . '</s> <small>' . $this->price_desc . '</small>';
            if ($only) return $this->getPriceRent();
            return \Price::display('GBP', $this->getPriceRent(), 0) . ' <small>' . $this->price_desc . '</small>';
        }
        elseif ($this->SaleOrRent() == 'b')
        {
            // TODO if ($strike) return '<s>' . \Price::display('GBP', $this->getPriceRent(), 0) . '</s> <small>' . $this->price_desc . '</small>';
            // TODO if ($only) return $this->getPriceRent();
            $sale = \Price::display('GBP', $this->getPriceSale(), 0) . ' <small>' . $this->price_desc . '</small>';
            $rent = \Price::display('GBP', $this->getPriceRent(), 0) . ' <small>' . $this->price_desc . '</small>';
            return "{$sale} / {$rent}";
        }
    }

    public function SaleOrRent( $word = false )
    {
        if ($word) {
            return $this->getSaleOrRent();
        }

        if (strtolower($this->getSaleOrRent()) == 'sale') return 's';
        if (strtolower($this->getSaleOrRent()) == 'rent') return 'r';
        if (strtolower($this->getSaleOrRent()) == 'both') return 'b';
    }

    public function detailURL()
    {
        return strtolower("/properties/for-" . $this->SaleOrRent(true) . "/in-" . sanitize_title($this->town) . "/{$this->property_id}");
    }

    /** ---------------------------------------------------------------------------------------------------- **/

    public function getPriceRent()
    {
        return $this->price;
    }

    public function getPriceSale()
    {
        return $this->price;
    }

    public function getSaleOrRent()
    {
        return $this->sale_or_rent;
    }

    public function getPropertyNumber()
    {
        return $this->property_number;
    }

    public function getStreetName()
    {
        return $this->street_name;
    }

    public function getTown()
    {
        return $this->town;
    }

    public function getCounty()
    {
        return $this->county;
    }

    public function getPostcode()
    {
        return $this->postcode;
    }

    public function getPostcodePt1()
    {
        return $this->postcode_pt1;
    }

    public function getPostcodePt2()
    {
        return $this->postcode_pt2;
    }

}
