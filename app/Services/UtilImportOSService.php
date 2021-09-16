<?php
namespace App\Services;

use Illuminate\Http\Request;

class UtilImportOSService
{
    
    public static function readReferencia($line){

        $line_info = explode(":", $line);
        return trim($line_info[1]);
    }

    public static function readCliente($line){
        $line_info = explode(":", $line);
        return trim($line_info[1]);
    }

    public static function readCidade($line){
        $line_info = explode(":", $line);
        $line_info = explode(" / ", $line_info[1]);
        return trim($line_info[0]);
    }

    public static function readUf($line){
        $line_info = explode(":", $line);
        $line_info = explode(" / ", $line_info[1]);
        return trim($line_info[1]);
    }

    public static function readValor($line){
        $line_info_tmp = explode(":", $line);
        $line_info = explode(" ", $line_info_tmp[1]);
        $line_info = array_filter($line_info);
        $line_info = array_values($line_info);

        return self::convertValueToUTC(trim($line_info[1]));
    }

    public static function readDeslocalmento($line){
        $line_info_tmp = explode(":", $line);
        $line_info = explode(" ", $line_info_tmp[2]);
        $line_info = array_filter($line_info);
        $line_info = array_values($line_info);

        return self::convertValueToUTC(trim($line_info[1]));
    }

    public static function readDataEmissao($line){
        $line_info_tmp = explode(":", $line);
        $line_info = explode(" ", $line_info_tmp[1]);
        $line_info = array_filter($line_info);
        $line_info = array_values($line_info);

        return self::convertDataToUTC(trim($line_info[0]));
    }

    public static function readPrazo($line){
        $line_info_tmp = explode(":", $line);
        $line_info = explode(" ", $line_info_tmp[1]);
        $line_info = array_filter($line_info);
        $line_info = array_values($line_info);

        return trim($line_info[0]);
    }

    public static function readServico($line){
        $line_info_tmp = explode(":", $line);
        $line_info = explode(" - ", $line_info_tmp[1]);
        return trim($line_info[0]);
    }

    public static function currency($value, $showSymbol = true) {

      // Remove a virgula do valor
      $value = str_replace(',', '', $value);

      // Formata usando o numero de casas decimais desejado
      $formattedValue = $showSymbol ? settings('currency_code').' ' : '';

      $formattedValue .= number_format($value, 2, ',', '.');

      return $formattedValue;
      
    }

    public static function convertValueToUTC($value) {

        if(empty($value)) return 0;

        $source = array('.', ',');
        $replace = array('', '.');
        $valor = str_replace($source, $replace, $value); //remove os pontos e substitui a virgula pelo ponto

        return $valor;
    }

    public static function convertDataToUTC($value){
        $data_tmp = explode("/", $value);

        return $data_tmp[2].'-'.$data_tmp[1].'-'.$data_tmp[0];
    }

}
