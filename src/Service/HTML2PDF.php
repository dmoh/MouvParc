<?php
/**
 * Created by PhpStorm.
 * User: UTILISATEUR
 * Date: 28/05/2018
 * Time: 8:55
 */

namespace App\Service;


class HTML2PDF
{
    private $pdf;


    public function create($orientation = null, $format = null, $lang = null, $unicode = null, $encoding = null, $margin = null, $template = null, $name =null)
    {
        $this->pdf = new \Spipu\Html2Pdf\Html2Pdf(
            $orientation  ? $orientation : $this->orientation,
            $format  ? $format : $this->format,
            $lang  ? $lang : $this->lang,
            $unicode  ? $unicode : $this->unicode,
            $encoding  ? $encoding: $this->encoding,
            $margin ? $margin : $this->margin
        );
        $this->pdf->writeHTML($template);
        return $this->pdf->output('C:\wamp64\www\MouvParc\\public\\uploads\\'.$name.'.pdf', 'FI');
    }

    public function generatePdf($template, $name)
    {

    }
}