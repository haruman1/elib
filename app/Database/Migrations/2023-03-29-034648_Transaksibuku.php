<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Transaksibuku extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 10,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'id_buku' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
            'id_user' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
            'judulbuku' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
            'tanggal_peminjaman' => [
                'type' => 'datetime',
            ],
            'tanggal_pengembalian' => [
                'type' => 'datetime',
            ],
            'file_buku' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('id');
        $this->forge->addUniqueKey('id_buku');
        $this->forge->addUniqueKey('judulbuku');
        $this->forge->addForeignKey('id_buku', 'bukutersedia', 'id_buku');
        $this->forge->createTable('transaksibuku', true);
    }

    public function down()
    {
        $this->forge->dropTable('transaksibuku');
    }
}
