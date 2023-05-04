<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class MyBook extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_bukuSaya' => [
                'type' => 'INT',
                'constraint' => 10,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'id_buku' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
            'judulbuku' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
            ],
            'file_buku' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
            'id_user'       => [
                'type'       => 'INT',
                'constraint' => '15',
                'unsigned'       => true,

            ],

        ]);

        $this->forge->addKey('id_bukuSaya', true);

        $this->forge->addUniqueKey('id_user');
        $this->forge->addUniqueKey('id_buku');
        $this->forge->addUniqueKey('id_bukuSaya');
        $this->forge->addForeignKey('id_user', 'daftarUser', 'id_user');
        $this->forge->addForeignKey('id_buku', 'bukutersedia', 'id_buku');

        $this->forge->createTable('mybook', true);
    }

    public function down()
    {
        $this->forge->dropTable('mybook');
    }
}
