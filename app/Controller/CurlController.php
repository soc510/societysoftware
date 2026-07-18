<?php

App::uses('AppController', 'Controller');

class CurlController extends AppController {

    public function index() {

        $this->autoRender = false;

        $url = 'https://api.easylogicstechnology.com/api/send-email';

        $filePath = WWW_ROOT . 'files/test-document.pdf';

        if (!file_exists($filePath)) {
            echo 'File not found: ' . $filePath;
            return;
        }

        $htmlBody = <<<HTML
<!DOCTYPE html>
<html>
<body>
    <p><strong>Society Name :</strong> Prem-Bhavna_Co-Op_Housing_Society_Ltd</p>
    <p>Dear Sagar</p>
    <p>Your maintenance bill is attached below.</p>
</body>
</html>
HTML;

        $payload = [
            'recipients' => ['deepbase.co@gmail.com'],
            'subject'    => 'Maintenance Bill - Flat 90 Member HASINA_MIRZA_GULAM_HUSAIN',
            'htmlBody'   => $htmlBody,
            'attachments'=> [[
                'name' => 'test-document.pdf',
                'type' => 'application/pdf',
                'data' => base64_encode(file_get_contents($filePath))
            ]]
        ];

        $ch = curl_init($url);

        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_HTTPHEADER     => [
                'Content-Type: application/json',
                'Accept: application/json'
            ],
            CURLOPT_POSTFIELDS     => json_encode($payload),
            CURLOPT_TIMEOUT        => 30
        ]);

        $response = curl_exec($ch);

        if ($response === false) {
            echo 'cURL ERROR (' . curl_errno($ch) . '): ' . curl_error($ch);
            curl_close($ch);
            return;
        }

        echo "HTTP CODE: " . curl_getinfo($ch, CURLINFO_HTTP_CODE) . "<br><br>";
        echo "RESPONSE:<br>";
        echo htmlspecialchars($response);

        curl_close($ch);
    }
}

