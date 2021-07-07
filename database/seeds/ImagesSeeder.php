<?php

use Illuminate\Database\Seeder;
use App\Models\Store\Category;
use App\Models\Common\Image;

class ImagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Save categories images
        $this->categoriesImages();

        // Save banners
        $this->banners();
    }

    /**
     * Save categories images
     *
     * @return void
     */
    public function categoriesImages()
    {
        // Get default categories
        $data = $this->getCategoriesImages();

        // Create
        foreach( $data as $cat )
        {
            echo $cat['slug'], '... ';

            // Create Image
            $image = Image::create( $cat['image'] );

            if( ! $image )
            {
                echo 'ERROR CREATING IMAGE!';
                echo PHP_EOL;
                continue;
            }

            // Load Category
            $node = Category::where('slug', '=', $cat['slug'])->first();

            // Save image
            $node->cover_id = $image->id;
            $node->save();

            echo $image->url, PHP_EOL;
        }
    }

    /**
     * Save banners
     *
     * @return void
     */
    public function banners()
    {
        // Get data
        $data = $this->getBanners();

        // Create
        foreach( $data as $ban )
        {
            echo $ban['name'], '... ';

            // Create Image
            $image = Image::create( $ban );

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
     * Returns the array with Categories images
     *
     * @return array
     */
    protected function getCategoriesImages()
    {
        $categories = [
            [
                'slug' => slug('Lifestyle'),
                'image' => [
                    'user_id'            => 1,
                    'original_name'      => 'lifestyle.png',
                    'original_extension' => 'png',
                    'name'               => 'lifestyle.png',
                    'path'               => 'D:\apache2\htdocs\keewe\public\media\categories',
                    'url'                => 'media/categories/lifestyle.png',
                    'status'             => 'active'
                ]
            ],
            [
                'slug' => slug('Kids'),
                'image' => [
                    'user_id'            => 1,
                    'original_name'      => 'kids.png',
                    'original_extension' => 'png',
                    'name'               => 'kids.png',
                    'path'               => 'D:\apache2\htdocs\keewe\public\media\categories',
                    'url'                => 'media/categories/kids.png',
                    'status'             => 'active'
                ]
            ],
            [
                'slug' => slug('Casa'),
                'image' => [
                    'user_id'            => 1,
                    'original_name'      => 'casa.png',
                    'original_extension' => 'png',
                    'name'               => 'casa.png',
                    'path'               => 'D:\apache2\htdocs\keewe\public\media\categories',
                    'url'                => 'media/categories/casa.png',
                    'status'             => 'active'
                ]
            ],
            [
                'slug' => slug('Pets'),
                'image' => [
                    'user_id'            => 1,
                    'original_name'      => 'pets.png',
                    'original_extension' => 'png',
                    'name'               => 'pets.png',
                    'path'               => 'D:\apache2\htdocs\keewe\public\media\categories',
                    'url'                => 'media/categories/pets.png',
                    'status'             => 'active'
                ]
            ],
            [
                'slug' => slug('Homens'),
                'image' => [
                    'user_id'            => 1,
                    'original_name'      => 'homem.png',
                    'original_extension' => 'png',
                    'name'               => 'homem.png',
                    'path'               => 'D:\apache2\htdocs\keewe\public\media\categories',
                    'url'                => 'media/categories/homem.png',
                    'status'             => 'active'
                ]
            ],
            [
                'slug' => slug('Mulheres'),
                'image' => [
                    'user_id'            => 1,
                    'original_name'      => 'mulher.png',
                    'original_extension' => 'png',
                    'name'               => 'mulher.png',
                    'path'               => 'D:\apache2\htdocs\keewe\public\media\categories',
                    'url'                => 'media/categories/mulher.png',
                    'status'             => 'active'
                ]
            ],
            [
                'slug' => slug('Genderless'),
                'image' => [
                    'user_id'            => 1,
                    'original_name'      => 'genderless.png',
                    'original_extension' => 'png',
                    'name'               => 'genderless.png',
                    'path'               => 'D:\apache2\htdocs\keewe\public\media\categories',
                    'url'                => 'media/categories/genderless.png',
                    'status'             => 'active'
                ]
            ],
            [
                'slug' => slug('On the go'),
                'image' => [
                    'user_id'            => 1,
                    'original_name'      => 'viagem.png',
                    'original_extension' => 'png',
                    'name'               => 'viagem.png',
                    'path'               => 'D:\apache2\htdocs\keewe\public\media\categories',
                    'url'                => 'media/categories/viagem.png',
                    'status'             => 'active'
                ]
            ],
            [
                'slug' => slug('Office'),
                'image' => [
                    'user_id'            => 1,
                    'original_name'      => 'office.png',
                    'original_extension' => 'png',
                    'name'               => 'office.png',
                    'path'               => 'D:\apache2\htdocs\keewe\public\media\categories',
                    'url'                => 'media/categories/office.png',
                    'status'             => 'active'
                ]
            ],
        ];

        return $categories;
    }

    /**
     * Returns the array with Banners
     *
     * @return array
     */
    protected function getBanners()
    {
        $banners = [
            [
                'user_id'            => 1,
                'original_name'      => 'home-joia.png',
                'original_extension' => 'png',
                'name'               => 'home-joia.png',
                'path'               => 'D:\apache2\htdocs\keewe\public\media\banners',
                'url'                => 'media/banners/home-joia.png',
                'status'             => 'active'
            ],
            [
                'user_id'            => 1,
                'original_name'      => 'banner-home.jpg',
                'original_extension' => 'png',
                'name'               => 'banner-home.jpg',
                'path'               => 'D:\apache2\htdocs\keewe\public\media\banners',
                'url'                => 'media/banners/banner-home.jpg',
                'status'             => 'active'
            ]
        ];

        return $banners;
    }
}
