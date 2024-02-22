<?php

namespace Database\Seeders;

use App\Models\Competence;
use Illuminate\Database\Seeder;

class CompetenceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {


        $data=[
            [
                'name_ar'=>'خوذة اللون السلفر',
                'name_en'=>'Silver color helmet',
                'type'=>'Permanent',
                'price'=>350,
                'competence'=>'HeadProtection',
                'damage'=>20,
            ],
            [
                'name_ar'=>'الخوذة الذهبية',
                'name_en'=>'Golden Helmet',
                'type'=>'Permanent',
                'price'=>500,
                'competence'=>'HeadProtection',
                'damage'=>30,
            ],
            [
                'name_ar'=>'واقي الرصاص',
                'name_en'=>'Bullet protection',
                'type'=>'Permanent',
                'price'=>1100,
                'competence'=>'ChestProtection',
                'damage'=>20,
            ],
            [
                'name_ar'=>'واقي ذهبي',
                'name_en'=>'Gold guard',
                'type'=>'Permanent',
                'price'=>1900,
                'competence'=>'ChestProtection',
                'damage'=>30,
            ],
            [
                'name_ar'=>'حذاء سلفر',
                'name_en'=>'Silver shoes',
                'type'=>'Permanent',
                'price'=>1500,
                'competence'=>'Speed',
                'damage'=>20,
            ],
            [
                'name_ar'=>'حذاء ذهبي',
                'name_en'=>'Golden shoes',
                'type'=>'Permanent',
                'price'=>2100,
                'competence'=>'Speed',
                'damage'=>30,
            ],


            //Temporary Competences
            [
                'name_ar'=>'ملوتوف',
                'name_en'=>'Molotov',
                'type'=>'Temporary',
                'price'=>0,
                'competence'=>'Molotov',
                'damage'=>20,
            ],
            [
                'name_ar'=>'الصمغ',
                'name_en'=>'gum',
                'type'=>'Temporary',
                'price'=>0,
                'competence'=>'Immobilize',
                'damage'=>10,
            ],
            [
                'name_ar'=>'الطلقات السلفر',
                'name_en'=>'Silver shots',
                'type'=>'Temporary',
                'price'=>0,
                'competence'=>'DamagePower',
                'damage'=>20,
            ],
            [
                'name_ar'=>'الطلقات الذهبية',
                'name_en'=>'Golden shots',
                'type'=>'Temporary',
                'price'=>0,
                'competence'=>'DamagePower',
                'damage'=>30,
            ],
            [
                'name_ar'=>'مشروب الطاقة',
                'name_en'=>'Energy drink',
                'type'=>'Temporary',
                'price'=>0,
                'competence'=>'DamagePower',
                'damage'=>20,
            ],
        ];


        foreach ($data as $item){
            $newsItem = new Competence();
            $newsItem->type = $item['type'];
            $newsItem->price = $item['price'];
            $newsItem->competence = $item['competence'];
            $newsItem->damage = $item['damage'];
            $newsItem->save();
            $newsItem->translateOrNew('en')->name = $item['name_en'];
            $newsItem->translateOrNew('ar')->name = $item['name_ar'];
            $newsItem->save();

        }
    }
}
