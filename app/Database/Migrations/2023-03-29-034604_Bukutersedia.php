<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Bukutersedia extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_buku_tersedia'          => [
                'type'           => 'INT',
                'constraint'     => 15,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'id_buku' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
            'judulbuku'       => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'stok_buku' => [
                'type' => 'int',
                'constraint' => '50',
            ],
            'totalBuku_dipinjam' => [
                'type' => 'INT',
                'constraint' => '255',
                'unsigned'       => true,
            ],
            'yang_nambahin_buku' => [
                'type' => 'int',
                'constraint' => '50',
            ],
        ]);
        $this->forge->addKey('id_buku_tersedia', true);
        $this->forge->addUniqueKey('id_buku');
        $this->forge->createTable('bukutersedia', true);
    }

    public function down()
    {
        $this->forge->dropTable('bukutersedia');
    }
}
