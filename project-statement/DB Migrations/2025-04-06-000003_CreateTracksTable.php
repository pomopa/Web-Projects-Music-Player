<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTracksTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'          => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'name'        => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'cover'       => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'artist_id'   => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'artist_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'album_id'    => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'album_name'  => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'duration'    => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'player_url'  => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'playlist_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'created_at'  => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at'  => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('playlist_id', 'playlists', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('tracks');
    }

    public function down()
    {
        $this->forge->dropTable('tracks');
    }
}
