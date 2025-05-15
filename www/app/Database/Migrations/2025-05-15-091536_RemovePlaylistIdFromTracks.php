<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class RemovePlaylistIdFromTracks extends Migration
{
    public function up()
    {
        $this->forge->dropForeignKey('tracks', 'tracks_playlist_id_foreign');
        $this->forge->dropColumn('tracks', 'playlist_id');
    }

    public function down()
    {
        $this->forge->addColumn('tracks', [
            'playlist_id' => [
                'type'     => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null'     => true,
            ],
        ]);

        $this->forge->addForeignKey('playlist_id', 'playlists', 'id', 'CASCADE', 'CASCADE');
    }
}
