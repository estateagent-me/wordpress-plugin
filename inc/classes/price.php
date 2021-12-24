<?php

class Price
{
    public static function display($currency, $price, $decimals = 2)
    {
        $currencies = self::getCurrencies();

        $sign = (isset($currencies[$currency])) ? $currencies[$currency] : '$';
        return $sign . self::format($price, $decimals);
    }
    public static function displayPriceOnly($price)
    {
        return self::format($price);
    }

    public static function getSign($currency)
    {
        $currencies = self::getCurrencies();

        $sign = (isset($currencies[$currency])) ? $currencies[$currency] : '$';
        return $sign;
    }

    public static function format($price, $decimals = 2)
    {
      $price = intval(str_replace(',', '', $price));
        return number_format($price, $decimals, '.', ',');
    }

    public static function commission()
    {
        $values = range(70, 0, 5);

        $commission = array();
        foreach ($values as $val) {
            $commission[$val] = $val . "%";
        }

        return $commission;
    }

    /**
     * Available Currencies
     *
     * Return an array of available currencies
     *
     * @access public
     * @return array
     */

    public static function getCurrencies()
    {
        $currencies = array('USD' => '$', 'GBP' => '£');
        return $currencies;
    }
}
