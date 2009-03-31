<?php
require_once ('Fynd/View/Download.php');
class DownloadModelMapView extends Fynd_View_Download
{
    /**
     * @see Fynd_View_Download::render()
     *
     * @return string
     */
    public function render()
    {
        $tempName = tempnam('','fyndmodelcreation');
        $zip = new ZipArchive();
        $fail = $zip->open($tempName,ZipArchive::CREATE | ZipArchive::OVERWRITE);
        if(false === $fail)
        {
            throw new Exception('Could not open temp file to write the zip file.');
        }
        $fileName = "";
        foreach($this->_data as $key=>$value)
        {
            if("DownloadFileName" == $key)
            {
                $fileName = $value;
            }
            else 
            {
                $zip->addFromString($key,$value);
            }    
        }
        
        $zip->close();
        
        $this->setFileName($fileName);
        $this->setMimeType("application/zip");
        readfile($tempName);
    }

}
?>