<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\I18n\Time;

class Daftaruser extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_user'          => [
                'type'           => 'INT',
                'constraint'     => 15,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'nama_lengkap_user'       => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'username_user' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
            ],
            'mail_user' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
            ],
            'password_user' =>
            [
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
            'jenisKelamin' => [
                'type' => 'VARCHAR',
                'constraint' => '25',
            ],
            'umur_user' => [
                'type' => 'INT',
                'constraint' => '3',
                'default' => '0',
            ],
            'role_user' => [
                'type' => 'text',
                'default' => '2', // 1 = admin, 2 = user
            ],
            'user_aktif' => [
                'type' => 'text',
                'default' => '0', // 0 = nonaktif, 1 = aktif
            ],
            'user_login' => [
                'type' => 'Boolean',
                'default' => false,
            ],
            'akun_dibuat' => [
                'type' => 'varchar',
                'constraint' => '255',
                'default' =>  Time::now('Asia/Jakarta',  'id_ID'),
            ],
            'terakhir_diedit' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'default' => Time::now('Asia/Jakarta',  'id_ID'),
            ],
        ]);
        $this->forge->addKey('id_user', true);
        $this->forge->addUniqueKey('id_user');
        $this->forge->addUniqueKey('username_user');
        $this->forge->createTable('daftarUser', true);
    }

    public function down()
    {
        $this->forge->dropTable('daftarUser');
    }
}
