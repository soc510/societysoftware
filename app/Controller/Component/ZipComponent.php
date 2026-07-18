<?php

App::uses('Component', 'Controller');

class ZipComponent extends Component {

    public function initialize(Controller $controller) {
        $this->controller = $controller;
    }

    public function generateZip($files, $zipName) {
        # create new zip opbject
        $zip = new ZipArchive();
        $DelFilePath = $zipName . ".zip";

        if(file_exists(WWW_ROOT . $DelFilePath)) {
            unlink(WWW_ROOT . $DelFilePath);
        }

        if($zip->open(WWW_ROOT . $DelFilePath, ZIPARCHIVE::CREATE) != TRUE) {
            die("Could not open archive");
        }

        foreach ($files as $file) {
            $zip->addFile(basename($file));
        }

        $zip->close();
        header('Content-type: application/octet-stream');
        header('Content-Length: ' . filesize($DelFilePath));
        //This would be the one to rename the file
        header('Content-Disposition: attachment; filename=' . $DelFilePath . '');
        //clean all levels of output buffering
        while (ob_get_level()) {
            ob_end_clean();
        }

        readfile($DelFilePath);
        exit();
    }
}

?>