<?php
/**
 * Created by PhpStorm.
 * User: UTILISATEUR
 * Date: 03/08/2018
 * Time: 14:25
 */

namespace App\Service;


class EnvoiSmartSheet
{
    public function mouvParcToSmartsheet($sheet_id, $col_Immat, $immat, $col_NomDecl, $userCurrent, $col_natureP, $naturePanne, $col_NumSerie, $numSerie, $col_DateDec, $dateDeclaration, $col_Garantie, $col_Kmactuel, $km_actuel)
{
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.smartsheet.com/2.0/sheets/".$sheet_id."/rows",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => "[{\"toTop\":true,\"cells\": [{\"columnId\": ".$col_Immat.", \"value\": \"".$immat."\"},{\"columnId\":".$col_NomDecl.", \"value\": \"".$userCurrent."\"},{\"columnId\":".$col_natureP.", \"value\": \"".$naturePanne."\"},{\"columnId\":".$col_NumSerie.", \"value\": \"".$numSerie."\"},{\"columnId\":".$col_DateDec.", \"value\": \"".$dateDeclaration."\"},{\"columnId\":".$col_Garantie.", \"value\": true},{\"columnId\":".$col_Kmactuel.", \"value\": ".$km_actuel."}]}];\r\n",
        CURLOPT_HTTPHEADER => array(
            "Authorization: Bearer 7ezq7gdmi2p966dk2wu1yht9vz",
            "Cache-Control: no-cache",
            "Content-Type: application/json",
        ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        echo "Erreur cUrl:" . $err;
    }

    return $response;
}

    public function portailApstoSmartSheetIncidient($sheet_id, $col_Immat, $immat, $col_NomDecl, $userCurrent, $col_natureP, $naturePanne, $col_NumSerie, $numSerie, $col_DateDec, $dateDeclaration, $col_Garantie, $col_Kmactuel, $km_actuel)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.smartsheet.com/2.0/sheets/".$sheet_id."/rows",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "[{\"toTop\":true,\"cells\": [{\"columnId\": ".$col_Immat.", \"value\": \"".$immat."\"},{\"columnId\":".$col_NomDecl.", \"value\": \"".$userCurrent."\"},{\"columnId\":".$col_natureP.", \"value\": \"".$naturePanne."\"},{\"columnId\":".$col_NumSerie.", \"value\": \"".$numSerie."\"},{\"columnId\":".$col_DateDec.", \"value\": \"".$dateDeclaration."\"},{\"columnId\":".$col_Garantie.", \"value\": true},{\"columnId\":".$col_Kmactuel.", \"value\": ".$km_actuel."}]}];\r\n",
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer 7ezq7gdmi2p966dk2wu1yht9vz",
                "Cache-Control: no-cache",
                "Content-Type: application/json",
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "Erreur cUrl:" . $err;
        }

        return $response;
    }
}