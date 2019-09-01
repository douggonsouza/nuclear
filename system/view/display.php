<?php

namespace vendor\douggs\nuclear\system\view;

use vendor\douggs\nuclear\system\view\mimes;
use vendor\douggs\nuclear\configs\cfg;

class display{
	
	/**
	 * Responde com o conte�do do arquivo
	 * @param string $local
	 */
    final function body($local, $variables = null)
    {
        if(file_exists($local)){
            if(isset($variables) && !empty($variables)){
                foreach($variables as $key => $vle){
                    $$key = $vle;
                }                        
            }
            return include($local);
        }
    return '';
	}
               
    /**
	 * Responde com o conte�do do arquivo
	 * @param string $local
	 */
    final function output($local)
    {
        if(file_exists($local))
            return file_get_contents($local);
        return '';
    }
    
    final function render($request)
    {
        if(!isset($request))
            throw new \Exception('Not found object request.');
        $local = $request->localFile;
		$local = str_replace(
			array('/','//','\\','\\\\'),
			'/',
			$local);
        if(!file_exists($local))
            header("HTTP/1.0 404 Not Found");
        $this->headered(
            $local,
            $request,
            cfg::getCfg('direct_request')['binary'],
            cfg::getCfg('direct_request')['download']);
        readfile($local);
        die();
    }

    final function headered($local, $objRequest, $binary = false, $download = false)
    {
        header('Last-Modified: '.gmdate('D, d M Y H:i:s', filemtime($local)).' GMT', true, 200);
        $this->contentHeader(
            $local,
            $objRequest->filename,
            $objRequest->extensao,
            $binary,
            $download
        );
    }

    final function contentHeader($local, $filename, $ext, $binary = false, $download = false)
    {
        $mimes = new mimes();
        if(!isset($local))
            throw new \Exception('Not found header content.');
        header('Content-Length: '.filesize($local));
        header('Content-type: '. $mimes->get($ext));
        if($binary){
            header('Content-Transfer-Encoding: binary');
            header('Content-Type: application/octet-stream');
        }
        if($download)
            header('Content-Disposition: attachment; filename="'.$filename.$ext.'"');
    }
}

?>