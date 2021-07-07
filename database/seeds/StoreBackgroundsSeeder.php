<?php

use Illuminate\Database\Seeder;
use App\Models\Common\Image;

class StoreBackgroundsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Save backgrounds
        $this->backgrounds();
    }

    /**
     * Save banners
     *
     * @return void
     */
    public function backgrounds()
    {
        // Get data
        $data = $this->getBackgrounds();

        // Create
        foreach( $data as $img )
        {
            echo $img['name'], '... ';

            // Create Image
            $image = Image::create( $img );

            if( ! $image )
            {
                echo 'ERROR CREATING IMAGE!';
                echo PHP_EOL;
                continue;
            }

            echo $image->url, PHP_EOL;
        }
    }

    /**
     * Returns the array with Banners
     *
     * @return array
     */
    protected function getBackgrounds()
    {
        $images = [
            [
                'user_id'            => 1,
                'original_name'      => 'blur-01.jpg',
                'original_extension' => 'jpg',
                'name'               => 'Blur 01',
                'path'               => 'D:\apache2\htdocs\keewe\public\media\backgrounds',
                'url'                => 'media/backgrounds/blur-01.jpg',
                'status'             => 'active'
            ],
            [
                'user_id'            => 1,
                'original_name'      => 'blur-02.jpg',
                'original_extension' => 'jpg',
                'name'               => 'Blur 02',
                'path'               => 'D:\apache2\htdocs\keewe\public\media\backgrounds',
                'url'                => 'media/backgrounds/blur-02.jpg',
                'status'             => 'active'
            ],
            [
                'user_id'            => 1,
                'original_name'      => 'circuit.webp',
                'original_extension' => 'webp',
                'name'               => 'Circuit',
                'path'               => 'D:\apache2\htdocs\keewe\public\media\backgrounds',
                'url'                => 'media/backgrounds/circuit.webp',
                'status'             => 'active'
            ],
            [
                'user_id'            => 1,
                'original_name'      => 'fabric.jpg',
                'original_extension' => 'jpg',
                'name'               => 'Fabric',
                'path'               => 'D:\apache2\htdocs\keewe\public\media\backgrounds',
                'url'                => 'media/backgrounds/fabric.jpg',
                'status'             => 'active'
            ],
            [
                'user_id'            => 1,
                'original_name'      => 'hand-drawn.jpg',
                'original_extension' => 'jpg',
                'name'               => 'Hand drawn',
                'path'               => 'D:\apache2\htdocs\keewe\public\media\backgrounds',
                'url'                => 'media/backgrounds/hand-drawn.jpg',
                'status'             => 'active'
            ],
            [
                'user_id'            => 1,
                'original_name'      => 'lite-01.jpg',
                'original_extension' => 'jpg',
                'name'               => 'Lite 01',
                'path'               => 'D:\apache2\htdocs\keewe\public\media\backgrounds',
                'url'                => 'media/backgrounds/lite-01.jpg',
                'status'             => 'active'
            ],
            [
                'user_id'            => 1,
                'original_name'      => 'lite-02.jpg',
                'original_extension' => 'jpg',
                'name'               => 'Lite 02',
                'path'               => 'D:\apache2\htdocs\keewe\public\media\backgrounds',
                'url'                => 'media/backgrounds/lite-01.jpg',
                'status'             => 'active'
            ],
            [
                'user_id'            => 1,
                'original_name'      => 'lite-03.jpg',
                'original_extension' => 'jpg',
                'name'               => 'Lite 03',
                'path'               => 'D:\apache2\htdocs\keewe\public\media\backgrounds',
                'url'                => 'media/backgrounds/lite-01.jpg',
                'status'             => 'active'
            ],
            [
                'user_id'            => 1,
                'original_name'      => 'pavement.jpg',
                'original_extension' => 'jpg',
                'name'               => 'Pavement',
                'path'               => 'D:\apache2\htdocs\keewe\public\media\backgrounds',
                'url'                => 'media/backgrounds/pavement.jpg',
                'status'             => 'active'
            ],
            [
                'user_id'            => 1,
                'original_name'      => 'waves-01.jpg',
                'original_extension' => 'jpg',
                'name'               => 'Waves 01',
                'path'               => 'D:\apache2\htdocs\keewe\public\media\backgrounds',
                'url'                => 'media/backgrounds/waves-01.jpg',
                'status'             => 'active'
            ],
            [
                'user_id'            => 1,
                'original_name'      => 'waves-02.jpg',
                'original_extension' => 'jpg',
                'name'               => 'Waves 02',
                'path'               => 'D:\apache2\htdocs\keewe\public\media\backgrounds',
                'url'                => 'media/backgrounds/waves-02.jpg',
                'status'             => 'active'
            ],
            [
                'user_id'            => 1,
                'original_name'      => 'wood-lite.jpg',
                'original_extension' => 'jpg',
                'name'               => 'Wood lite',
                'path'               => 'D:\apache2\htdocs\keewe\public\media\backgrounds',
                'url'                => 'media/backgrounds/wood-lite.jpg',
                'status'             => 'active'
            ],
            [
                'user_id'            => 1,
                'original_name'      => 'wood.jpg',
                'original_extension' => 'jpg',
                'name'               => 'Wood',
                'path'               => 'D:\apache2\htdocs\keewe\public\media\backgrounds',
                'url'                => 'media/backgrounds/wood.jpg',
                'status'             => 'active'
            ]
        ];

        return $images;
    }
}
