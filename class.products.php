<?php
class products
{
    protected $header;

    public function __construct()
    {
        //Uppkoppling och skapandet av token
        try {

            $userData = array("username" => "demo", "password" => "demo123");
            $ch = curl_init("/index.php/rest/V1/integration/admin/token");
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($userData));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "Content-Lenght: " . strlen(json_encode($userData))));

            $token = curl_exec($ch);
            $token = json_decode($token);

            if(!$token)
            {
                throw new Exception("Fel, token finns inte");
            }

            //skapa HTTP header som används för auth vid anrop
            $this->header = array("Authorization: Bearer " . $token, "Content-Type: application/json");
        } catch(Exception $e) {
            echo "Error: " . $e->getMessage() . "\n";
        }
    }

    public function loadProducts()
    {
        try {

            //Hämtar 5 produkter skapade efter det specificerade datumet
            $requestUrl = '/index.php/rest/V1/products/?searchCriteria[pageSize]=5&searchCriteria[filter_groups][0][filters][0][field]=created_at&searchCriteria[filter_groups][0][filters][0][value]=2017-01-25 00:00:00&searchCriteria[filter_groups][0][filters][0][condition_type]=gt';

            $ch = curl_init($requestUrl);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $this->header);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $jsonresult = curl_exec($ch);
            curl_close($ch);
            $result = json_decode($jsonresult);

            if(!$result)
            {
                throw new Exception("Något blev fel när produkterna hämtades");
            }

            return $result;

        } catch(Exception $e) {
            echo "Error: " . $e->getMessage() . "\n";
        }
    }

    public function loadProductBySku($skuid)
    {
        try {
            //Hämtar produkt specificerad med sku i url
            $requestUrl = "/index.php/rest/V1/products/$skuid";

            $ch = curl_init($requestUrl);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $this->header);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $jsonresult = curl_exec($ch);
            curl_close($ch);
            $result = json_decode($jsonresult);

            if(!$result)
            {
                throw new Exception("Något blev fel när produkten hämtades");
            }

            return $result;
        } catch(Exception $e) {
            echo "Error: " . $e->getMessage() . "\n";
        }
    }

    public function updateProduct($namn, $sku, $pris)
    {
        try {
            $productdata = json_encode(array(
                'product' => array(
                    'name' => $namn,
                    'price' => $pris,
                )
            ));
            //

            $requestUrl = "/index.php/rest/V1/products/$sku";
            $ch = curl_init($requestUrl);

            //PUT vid update
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $productdata);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $this->header);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response_json = curl_exec($ch);
            curl_close($ch);
            $response = json_decode($response_json, true);

            if(!$response)
            {
                throw new Exception("Något blev fel med uppdateringen av produkten");
            }

            return "<div class=\"alert alert-success\" role=\"alert\" style=\"margin-top: 20px;\">Produkten uppdateras</div>";

        } catch(Exception $e) {
            echo "Error: " . $e->getMessage() . "\n";
        }
    }

    public function createProduct($namn, $sku, $pris)
    {

        try {
            //ej säker på vad som är nödvändigt, att enbart specifcera nödvändiga fält enligt dokumentationen verkar inte fungera
            $productdata = json_encode(array(
                'product' => array(
                    'id' => '1221323',
                'sku' => $sku,
                'name' => $namn,
                'attribute_set_id' => 4,
                'price' => $pris,
                'status' => 1,
                'visibility' => 4,
                'type_id' => 'simple',

                    'custom_attributes' => array(

                        array(
                            'attribute_code' => 'description',
                            'value' => 'en text om produkten',
                        ),
                        array(
                            'attribute_code' => 'color',
                            'value' => '49',
                        ),
                        array(
                            'attribute_code' => 'options_container',
                            'value' => 'container2',
                        ),
                        array(
                            'attribute_code' => 'required_options',
                            'value' => '0',
                        ),
                        array(
                            'attribute_code' => 'has_options',
                            'value' => '0',
                        ),
                        array(
                            //url /test.html är redan upptagen
                            'attribute_code' => 'url_key',
                            'value' => 'testmikael',
                        ),
                        array(
                            'attribute_code' => 'tax_class_id',
                            'value' => '2',
                        ),
                    )
                )
            ));

            $requestUrl = '/index.php/rest/V1/products/';
            $ch = curl_init($requestUrl);

            //POST
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $productdata);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $this->header);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response_json = curl_exec($ch);
            curl_close($ch);
            $response = json_decode($response_json, true);

            if(!$response)
            {
                throw new Exception("Något blev fel när produkten försökte skapas");
            }

            return "<div class=\"alert alert-success\" role=\"alert\" style=\"margin-top: 20px;\">Produkten skapades</div>";

        } catch(Exception $e) {
            echo "Error: " . $e->getMessage() . "\n";
        }
    }

    public function deleteProduct($skuid)
    {
        try {

            $requestUrl = "/index.php/rest/V1/products/$skuid";
            $ch = curl_init($requestUrl);

            //Delete
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
            curl_setopt($ch, CURLOPT_HTTPHEADER, $this->header);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response_json = curl_exec($ch);
            curl_close($ch);
            $response=json_decode($response_json, true);

            if(!$response)
            {
                throw new Exception("Något blev fel när produkten försökte raderas");
            }

            return "<div class=\"alert alert-success\" role=\"alert\" style=\"margin-top: 20px;\">Produkten Raderades</div>";

        } catch(Exception $e) {
            echo "Error: " . $e->getMessage() . "\n";
        }
    }
}
?>