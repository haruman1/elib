<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UserImage extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_image'          => [
                'type'           => 'INT',
                'constraint'     => 15,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'user_id'       => [
                'type'       => 'INT',
                'constraint' => 15,
                'unsigned' => true,
            ],
            'avatarURL' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
        ]);
        $this->forge->addKey('id_image', true);
        $this->forge->addUniqueKey('user_id');
        $this->forge->addUniqueKey('avatarURL');
        $this->forge->addForeignKey('user_id', 'daftarUser', 'id_user');
        $this->forge->createTable('userImage');
    }

    public function down()
    {
        $this->forge->dropTable('userImage');
    }
}
