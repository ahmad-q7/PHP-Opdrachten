<?php
class Calculator {
    public $precision = 2;

    public function calculate($input) {
        $input = str_replace('sqrt', 'sqrt_', $input); // tijdelijke vervanging
        $input = preg_replace_callback('/sqrt_\\(([^)]+)\\)/', function($match) {
            return sqrt((float) $match[1]);
        }, $input);

        $input = str_replace('^', '**', $input);
        $input = preg_replace('/[^0-9\.\+\-\*\/\%\(\)]/', '', $input);

        $result = @eval("return {$input};");
        return $result === false ? "Fout" : round($result, $this->precision);
    }
}
?>
