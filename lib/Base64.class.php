<?php

class Base64 {

    public function base64img($file) {
        try {
            if ($file != null) {
                $type = pathinfo($file, PATHINFO_EXTENSION);
                $data = @file_get_contents($file);
                $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                return $base64;
            } else {
                return "";
            }
        } catch (Exception $e) {
            return $file;
        }
    }

}
