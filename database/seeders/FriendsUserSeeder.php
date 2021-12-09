<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FriendsUser;

class FriendsUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      FriendsUser::insert([
        'name' => "Александр",
        "uid" => "61b0c92b3ff3b",
        "avatar" => "https://sun9-77.userapi.com/impg/c855736/v855736108/1d0b3b/1ofHdwcrvfs.jpg?size=1056x1686&quality=96&sign=4a14d1151f644a59cb87a3241971e7cc&type=album",
        "direction" => "to",
      ]);
      FriendsUser::insert([
        'name' => "Александр",
        "uid" => "61b0c92b3ff3b",
        "avatar" => "https://sun9-77.userapi.com/impg/c855736/v855736108/1d0b3b/1ofHdwcrvfs.jpg?size=1056x1686&quality=96&sign=4a14d1151f644a59cb87a3241971e7cc&type=album",
        "direction" => "from",
      ]);
      FriendsUser::insert([
        'name' => "Александра",
        "uid" => "61b0cb675cf2c",
        "avatar" => "https://sun9-68.userapi.com/impf/c847219/v847219648/1927af/ogJJVxiFhrM.jpg?size=960x1200&quality=96&sign=8f6b1388015f217a68655923e744e0f5&type=album",
        "direction" => "to",
      ]);
      FriendsUser::insert([
        'name' => "Александра",
        "uid" => "61b0cb675cf2c",
        "avatar" => "https://sun9-68.userapi.com/impf/c847219/v847219648/1927af/ogJJVxiFhrM.jpg?size=960x1200&quality=96&sign=8f6b1388015f217a68655923e744e0f5&type=album",
        "direction" => "from",
      ]);
      FriendsUser::insert([
        'name' => "Дмитрий",
        "uid" => "61b0cbcc783a5",
        "avatar" => "https://sun9-39.userapi.com/impg/SnMYXfUZGYkbyXNyuOJBv-zfikE6U3FHhT4XRA/1X0sldCT5mM.jpg?size=640x1224&quality=96&sign=c4bfc288a2c944922437a3e873356943&type=album",
        "direction" => "to",
      ]);
      FriendsUser::insert([
        'name' => "Дмитрий",
        "uid" => "61b0cbcc783a5",
        "avatar" => "https://sun9-39.userapi.com/impg/SnMYXfUZGYkbyXNyuOJBv-zfikE6U3FHhT4XRA/1X0sldCT5mM.jpg?size=640x1224&quality=96&sign=c4bfc288a2c944922437a3e873356943&type=album",
        "direction" => "from",
      ]);
      FriendsUser::insert([
        'name' => "Игорь",
        "uid" => "61b0cbec63db3",
        "avatar" => "https://sun9-88.userapi.com/impg/l_n48AKgWj516Udwemcg9nPjwRMBPOT4sa9vTA/M3K-C8HKbxo.jpg?size=2560x1244&quality=96&sign=58a56bcc508b433343f3f8286f38663c&type=album",
        "direction" => "to",
      ]);
      FriendsUser::insert([
        'name' => "Игорь",
        "uid" => "61b0cbec63db3",
        "avatar" => "https://sun9-88.userapi.com/impg/l_n48AKgWj516Udwemcg9nPjwRMBPOT4sa9vTA/M3K-C8HKbxo.jpg?size=2560x1244&quality=96&sign=58a56bcc508b433343f3f8286f38663c&type=album",
        "direction" => "from",
      ]);
    }
}
