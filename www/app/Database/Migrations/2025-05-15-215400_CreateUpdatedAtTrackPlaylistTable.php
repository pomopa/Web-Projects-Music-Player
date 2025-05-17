<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUpdatedAtTrackPlaylistTable extends Migration
{
    public function up()
    {
        $this->forge->addColumn('track_playlist', [
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('track_playlist', 'updated_at');
    }
}
