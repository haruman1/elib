<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Jenisbuku extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_jenis_buku'          => [
                'type'           => 'INT',
                'constraint'     => 15,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'kodeBuku' => [
                'type' => 'VARCHAR',
                'constraint' => '255',

            ],
            'nama_jenis_buku' => [
                'type' => 'VARCHAR',
                'constraint' => '255',

            ],
            'id_buku' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
        ]);
        $this->forge->addKey('id_jenis_buku', true);
        $this->forge->addUniqueKey('id_jenis_buku');
        $this->forge->addUniqueKey('id_buku');
        $this->forge->addForeignKey('id_buku', 'bukutersedia', 'id_buku');

        $this->forge->createTable('jenisBuku', true);
    }

    public function down()
    {
        $this->forge->dropTable('jenisBuku');
    }
}
