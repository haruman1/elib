<?php

namespace App\Models;

use CodeIgniter\CLI\Console;
use CodeIgniter\I18n\Time;
use CodeIgniter\Model;

class AuthModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'daftaruser';
    protected $primaryKey       = 'id_user';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['nama_lengkap_user', 'username_user',  'mail_user', 'password_user', 'user_login', 'jenisKelamin', 'umur_user', 'akun_dibuat'];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    // protected function logInsert(array $data)
    // {

    //     $db = \Config\Database::connect();
    //     $builder = $db->table('log_user');
    //     $check = $builder->find()
    //     $pesan =  $builder->insert([
    //         'id_user' => $data['id'],
    //         'keterangan' => "data dengan user id " . $data['id_user'] . " telah ditambahkan",
    //         'waktu' => Time::now('Asia/Jakarta', 'id_ID'),
    //     ]);
    //     return $pesan;
    // }
    // protected function logDelete(array $data)
    // {
    //     $db = \Config\Database::connect();
    //     $builder = $db->table('log_user');
    //     $builder->insert([
    //         'id_user' => $data['id'],
    //         'keterangan' => "data dengan user id " . $data['id'][0] . " telah dihapus",
    //         'waktu' => Time::now('Asia/Jakarta', 'id_ID'),
    //     ]);
    //     return $data;
    // }
    public function getUserByUsername($username)
    {
        return $this->where('username_user', $username)->first();
    }
    public function getUserByEmail($email)
    {
        return $this->where('mail_user', $email)->first();
    }
}
