<?php

namespace App\Controllers;

use App\Models\AuthModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\RESTful\ResourceController;

class Email extends ResourceController
{
    public function __construct()
    {
        $db = \Config\Database::connect();
        $this->authModel = new AuthModel();
        $this->builder = $db->table('daftaruser');
    }
    use ResponseTrait;
    public function sendEmail($email_penerima)
    {
        $decodedHash = base64_decode($email_penerima); // mengubah hash dari format Base64 menjadi string
        // menghitung hash MD5 dari string
        $check_email = $this->builder->getWhere(['mail_user' => $decodedHash])->getRowArray();
        if ($check_email) {
            $email = \Config\Services::email();
            $email->setTo($decodedHash);
            $email->setSubject('Verifikasi Akun');
            $email->setMessage('Klik link ini untuk verifikasi email anda website ' . $_ENV['app.name'] . '   <a href="' . $_ENV['app.baseURL'] . 'verifikasi?email=' . $email_penerima . '">Klik disini </a>');
            if (!$email->send()) {
                return $this->respond(['status' => false, 'message' => 'Email gagal terkirim, Silahkan Chat Admin']);
            } else {
                return $this->respond(['status' => true, 'message' => 'Email berhasil terkirim']);
            }
        } else {
            return $this->respond(['status' => false, 'message' => 'Email tidak ditemukan oleh server']);
        }
    }
}
