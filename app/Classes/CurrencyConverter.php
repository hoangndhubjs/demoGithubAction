<?php
namespace App\Classes;

class CurrencyConverter
{
    protected $code;
    protected $symbol;
    protected $position;
    protected $type;

    const CURRENCIES = [
        'VNĐ' => [
            'decimals' => 0,
            'dec_point' => ',',
            'thousand_sep' => '.'
        ]
    ];

    public function __construct($system_currency, $position = 'Suffix', $type = 'symbol') {
        list($code, $symbol) = explode(' - ', $system_currency);
        $this->code = $code;
        $this->symbol =$symbol;
        $this->position = $position;
        $this->type = $type;
    }

    public function isSymbol() {
        return $this->type === 'symbol';
    }

    public function isPrefix() {
        return $this->position === 'Prefix';
    }

    public function getUserFormat($money = 0) {
        $currencyInfo = self::CURRENCIES[$this->code];
        $formatted_number = number_format(floatval($money), $currencyInfo['decimals'], $currencyInfo['dec_point'], $currencyInfo['thousand_sep']);
        $currency_connect = $this->isSymbol() ? $this->symbol : $this->code;
        if ($this->isPrefix()) {
            return sprintf("%s %s", $currency_connect, $formatted_number);
        } else {
            return sprintf("%s %s", $formatted_number, $currency_connect);
        }
    }

    public function getNumberFormat($string) {
        //@TODO:Please improve it in future...
        return $string;
    }



}
