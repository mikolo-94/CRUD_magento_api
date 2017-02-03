<?php
class validator {

    public static function validateName($namn)
    {
        if (!preg_match("/^[a-zA-Z0-9 ]*$/", $namn))
        {
            return $namnError = "Ange korrekt produktnamn, endast bokstäver, siffror och mellanslag är tillåtna";
        }

    }

    public static function validatePrice($pris)
    {
        if (!preg_match("/^[1-9][0-9]*$/", $pris))
        {
            return $prisError = "Ange korrekt pris, endast siffror är tillåtna";
        }
    }

    public static function validateSku($sku)
    {
        if (!preg_match("/^[a-z0-9]+$/i", $sku))
        {
            return $skuError = "Ange ett korrekt sku-värde. Endast siffror och bokstäver är tillåtna, inga mellanslag";
        }
    }



}
