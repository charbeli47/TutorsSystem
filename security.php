<?php

define ('HMAC_SHA256', 'sha256');
define ('SECRET_KEY', '469a6613330e4ec18595ed07e84b6a1b66635b0dcea9490eab2d77a337116c99c6b75b9ea8644692acafad3126c414c1e7bcf606b02e4f28aa092cc2eed58fe9d0507eaf7896436eb30ca65b3c07bb7a81735ab4e43543ed9878b43a90f252ea90ffd7cedce142de858d5b4c860eccf181820e3a7a7d457f907430ce1b35c806');

function sign ($params) {
  return signData(buildDataToSign($params), SECRET_KEY);
}

function signData($data, $secretKey) {
    return base64_encode(hash_hmac('sha256', $data, $secretKey, true));
}

function buildDataToSign($params) {
        $signedFieldNames = explode(",",$params["signed_field_names"]);
        foreach ($signedFieldNames as &$field) {
           $dataToSign[] = $field . "=" . $params[$field];
        }
        return commaSeparate($dataToSign);
}

function commaSeparate ($dataToSign) {
    return implode(",",$dataToSign);
}

?>
