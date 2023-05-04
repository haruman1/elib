<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class DetailBuku extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'idDetailBuku'          => [
                'type'           => 'INT',
                'constraint'     => 15,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'judulBuku'          => [
                'type'           => 'TEXT',
                'constraint'     => 255,

            ],
            'idDetailBuku'          => [
                'type'           => 'INT',
                'constraint'     => 15,
                'unsigned'       => true,
                'auto_increment' => true,
            ],


        ]);
    }

    public function down()
    {
        //
    }
}
