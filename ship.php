<?php
session_cache_expire(1800);
session_start();
header("content-type:text/html; charset=utf-8");
putenv("NLS_LANG=American_America.UTF8");
error_reporting(E_ALL);
ini_set("display_errors", 1);
date_default_timezone_set("Asia/Seoul");


//$key = 'abcdefghijklmnopqrstuvwxyz123456';





// function APIEncrypt ($key, $value){
// 		$padSize = 16 - (strlen ($value) % 16) ;
// 		$value = $value . str_repeat (chr ($padSize), $padSize) ;
// 		$output = mcrypt_encrypt (MCRYPT_RIJNDAEL_128, $key, $value, MCRYPT_MODE_CBC, str_repeat(chr(0),16)) ;
// 		return base64_encode ($output) ;
// }


// function APIDecrypt ($key, $value){
//     $value = base64_decode ($value) ;
//     $output = mcrypt_decrypt (MCRYPT_RIJNDAEL_128, $key, $value, MCRYPT_MODE_CBC, str_repeat(chr(0),16)) ;

//     $valueLen = strlen ($output) ;
//     if ( $valueLen % 16 > 0 )
//         $output = "";

//     $padSize = ord ($output{$valueLen - 1}) ;
//     if ( ($padSize < 1) or ($padSize > 16) )
//         $output = "";                // Check padding.

//     for ($i = 0; $i < $padSize; $i++)
//     {
//         if ( ord ($output{$valueLen - $i - 1}) != $padSize )
//             $output = "";
//     }
//     $output = substr ($output, 0, $valueLen - $padSize) ;

//     return $output;
// }


$setUserID = "eresitrade";
$setAESsnKey = "LJt3r5vzkqBX8uIVROwvDoADLcwNWBqU";



function APIEncrypt($key, $value)
{
    //global $key;
    return base64_encode(openssl_encrypt($value, "aes-256-cbc", $key, true, str_repeat(chr(0), 16)));
}

function APIDecrypt($key, $value)
{
    //global $key;
    return openssl_decrypt(base64_decode($value), "aes-256-cbc", $key, true, str_repeat(chr(0), 16));
}



$setShipperAddr1 = APIEncrypt($setAESsnKey, '서울시 강서구 외발산동');
$setShipperAddr2 = APIEncrypt($setAESsnKey, '217-1 ACI 빌딩');
$setShipperTel = APIEncrypt($setAESsnKey, '010-1234-69510');

$setReceiverName = APIEncrypt($setAESsnKey, 'neodong jung');
$setReceiverAddr = APIEncrypt($setAESsnKey, '13126 S. BROADWAY. LOS ANGELES, CA 90061 U.S.A');
$setReceiverTel = APIEncrypt($setAESsnKey, '310-965-9009');

$resultArray = array();
$arrayMiddle = array(
    "Departure_Station" => 'SEL',
    "Arrival_Nation" => 'US',
    "Transfer_Company_Code" => '',
    "Order_Date" => '20220922',
    "Order_Number" => "20201117101799",
    "Hawb_No" => "",
    "Shipper_Name" => "ACI EXPRESSFF (U.K) LTD.",
    "Shipper_Country" => "KR",
    "Shipper_State" => "",
    "Shipper_City" => "SEL",
    "Shipper_Zip" => "07641",
    "Shipper_Address" => $setShipperAddr1,
    "Shipper_Address_Detail" => $setShipperAddr2,
    "Shipper_Tel" => $setShipperTel,
    "Shipper_Hp" => "",
    "Shipper_Email" => "",
    "Receiver_Country" => "US",
    "Receiver_State" => "New York",
    "Receiver_City" => "New York",
    "Receiver_District" => "",
    "Receiver_Zip" => "12401",
    "Receiver_Name" => "Kamlesh kumar",
    "Native_Receiver_Name" => $setReceiverName,
    "Native_Receiver_Address" => $setReceiverAddr,
    "Receiver_Address" => $setReceiverAddr,
    "Native_Receiver_Address_Detail" => "",
    "Receiver_Address_Detail" => "",
    "Receiver_Tel" => $setReceiverTel,
    "Receiver_Hp" => "",
    "Receiver_Email" => "",
    "Box_Count" => "1",
    "Actual_Weight" => "2.6",
    "Volume_Weight" => "",
    "Volume_Length" => "",
    "Volume_Width" => "",
    "Volume_Height" => "",
    "Custom_Clearance_ID" => "",
    "Buy_Site" => "https://kocart.com/",
    "Size_Unit" => "CM",
    "Weight_Unit" => "KG",
    "Get_Buy" => "1",
    "Mall_Type" => "A",
    "Warehouse_Msg" => "",
    "Delivery_Msg" => "Call M",
		"Exp_Licence_YN"=> "N",
		"Exp_Business_Num"=> "",
    "GoodsInfo" => array()
);


$arrGoods = array(
    'Customer_Item_Code' => 'ITEM2',
    'Hs_Code' => '',
    'Brand' => 'Handcraft Vietnam1',
    'Item_Detail' => '(Handcraft Vietnam) Straw Tote Bag',
    'Native_Item_Detail' => '75301-6-1 VOGACORTE 70',
    'Item_Cnt' => '1',
    'Unit_Value' => '20.5',
    'Make_Country' => 'GB',
    'Make_Company' => '',
    'Item_Div' => '',
    'Qty_Unit' => 'EA',
    'Item_Url' => 'TESTURL.COM',
    'Item_Img_Url' => '',
    'Trking_Company' => 'EPOST',
    'Trking_Number' => '6063412344789',
    'Trking_Date' => '20220921',
    'Chg_Currency' => 'USD',
    'Item_Material' => ''

);
array_push($arrayMiddle['GoodsInfo'], $arrGoods);
array_push($resultArray, $arrayMiddle);
$json_result = json_encode($resultArray);

$setSendDate = date("YmdHis"); //KST
$setTokenKey = APIEncrypt($setAESsnKey, $setSendDate."|".$setUserID);


$headers[] = 'Content-Type: application/json';
$headers[] = 'UserID: ' . $setUserID;
$headers[] = 'APIkey: ' . $setTokenKey;


$ch = curl_init();
$url = "https://wms.acieshop.com/api/orderNomalRegist";
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $json_result);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
curl_setopt($ch, CURLOPT_TIMEOUT, 20);
$response = curl_exec($ch);

$response = urldecode($response);

print_r($response);
curl_close($ch);
