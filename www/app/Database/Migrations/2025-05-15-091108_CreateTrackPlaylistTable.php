<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTrackPlaylistTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'track_id' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'playlist_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey(['track_id', 'playlist_id'], true);
        $this->forge->addForeignKey('track_id', 'tracks', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('playlist_id', 'playlists', 'id', 'CASCADE', 'CASCADE');

        $this->forge->createTable('track_playlist');
    }

    public function down()
    {
        $this->forge->dropTable('track_playlist');
    }
}
