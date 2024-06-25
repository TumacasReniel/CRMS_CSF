<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\psto;

class PSTOSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sub_units = [
            // Region I
            [
                'region_id' => '1',
                'psto_name' => 'Ilocos Norte',
                'slug' => 'ilocos-norte',
            ],
            [
                'region_id' => '1',
                'psto_name' => 'Ilocos Sur',
                'slug' => 'ilocos-sur',
            ],
            [
                'region_id' => '1',
                'psto_name' => 'La Union',
                'slug' => 'la-union',
            ],
            [
                'region_id' => '1',
                'psto_name' => 'Pangasinan',
                'slug' => 'pangasinan',
            ],  

            // Region II
            [
                'region_id' => '2',
                'psto_name' => 'Batanes',
                'slug' => 'batanes',
            ],
            [
                'region_id' => '2',
                'psto_name' => 'Cagayan',
                'slug' => 'cagayan',
            ],
            [
                'region_id' => '2',
                'psto_name' => 'Isabela',
                'slug' => 'isabela',
            ],
            [
                'region_id' => '2',
                'psto_name' => 'Nueva Biscaya',
                'slug' => 'nueva-biscaya',
            ],  
            [
                'region_id' => '2',
                'psto_name' => 'Quirino',
                'slug' => 'quirino',
            ],  

            // Region III
            [
                'region_id' => '3',
                'psto_name' => 'Aurora',
                'slug' => 'aurora',
            ],
            [
                'region_id' => '3',
                'psto_name' => 'Bulacan',
                'slug' => 'bulacan',
            ],
            [
                'region_id' => '3',
                'psto_name' => 'Bataan',
                'slug' => 'bataan',
            ],
            [
                'region_id' => '3',
                'psto_name' => 'Nueva Ecija',
                'slug' => 'nueva-ecija',
            ],  
            [
                'region_id' => '3',
                'psto_name' => 'Pampanga',
                'slug' => 'pampanga',
            ],
            [
                'region_id' => '3',
                'psto_name' => 'Tarlac',
                'slug' => 'tarlac',
            ],  
            [
                'region_id' => '3',
                'psto_name' => 'Zambales',
                'slug' => 'zambales',
            ],  

            // Region IVA
            [
                'region_id' => '4',
                'psto_name' => 'Cavite',
                'slug' => 'cavite',
            ],
            [
                'region_id' => '4',
                'psto_name' => 'Laguna',
                'slug' => 'laguna',
            ],
            [
                'region_id' => '4',
                'psto_name' => 'Batangas',
                'slug' => 'batangas',
            ],
            [
                'region_id' => '4',
                'psto_name' => 'Rizal',
                'slug' => 'rizal',
            ],  

            [
                'region_id' => '4',
                'psto_name' => 'Quezon',
                'slug' => 'quezon',
            ], 

             // Region IVB
             [
                'region_id' => '5',
                'psto_name' => 'Mindoro Occidental',
                'slug' => 'mindoro-occidental',
            ],
            [
                'region_id' => '5',
                'psto_name' => 'Mindoro Orriental',
                'slug' => 'mindoro-oriental',
            ],
            [
                'region_id' => '5',
                'psto_name' => 'Marinduque',
                'slug' => 'marinduque',
            ],
            [
                'region_id' => '5',
                'psto_name' => 'Romblon',
                'slug' => 'romblon',
            ],  

            [
                'region_id' => '5',
                'psto_name' => 'Palawan',
                'slug' => 'palawan',
            ], 
            
             // Region V
             [
                'region_id' => '6',
                'psto_name' => 'Albay',
                'slug' => 'albay',
            ],
            [
                'region_id' => '6',
                'psto_name' => 'Camarines Norte',
                'slug' => 'camarines-norte',
            ],
            [
                'region_id' => '6',
                'psto_name' => 'Camarines Sur',
                'slug' => 'camarines-sur',
            ],
            [
                'region_id' => '6',
                'psto_name' => 'Sorsogon',
                'slug' => 'sorsogon',
            ],  
            [
                'region_id' => '6',
                'psto_name' => 'Catanduanes',
                'slug' => 'catanduanes',
            ],  
            [
                'region_id' => '6',
                'psto_name' => 'Masbate',
                'slug' => 'masbate',
            ],

            // Region VI
            [
                'region_id' => '7',
                'psto_name' => 'Aklan',
                'slug' => 'aklan',
            ],
            [
                'region_id' => '7',
                'psto_name' => 'Antique',
                'slug' => 'antique',
            ],
            [
                'region_id' => '7',
                'psto_name' => 'Negros Occidental ',
                'slug' => 'negros-occidental',
            ],
            [
                'region_id' => '7',
                'psto_name' => 'Capiz ',
                'slug' => 'capiz',
            ],
            [
                'region_id' => '7',
                'psto_name' => 'Guimaras ',
                'slug' => 'guimaras',
            ],

            // Region VII
            [
                'region_id' => '8',
                'psto_name' => 'Cebu',
                'slug' => 'cebu',
            ],
            [
                'region_id' => '8',
                'psto_name' => 'Bohol',
                'slug' => 'bohol',
            ],
            [
                'region_id' => '8',
                'psto_name' => 'Negros Orriental ',
                'slug' => 'negros-orriental',
            ],
            [
                'region_id' => '8',
                'psto_name' => 'Siquijor ',
                'slug' => 'siquijor',
            ],

              // Region VIII
              [
                'region_id' => '9',
                'psto_name' => 'Leyte',
                'slug' => 'leyte',
            ],
            [
                'region_id' => '9',
                'psto_name' => 'Southern Leyte',
                'slug' => 'southern-leyte',
            ],
            [
                'region_id' => '9',
                'psto_name' => 'Northern Samar ',
                'slug' => 'northern-samar',
            ],
            [
                'region_id' => '9',
                'psto_name' => 'Biliran ',
                'slug' => 'biliran',
            ],
            [
                'region_id' => '9',
                'psto_name' => 'Samar ',
                'slug' => 'samar',
            ],
            [
                'region_id' => '9',
                'psto_name' => 'Eastern Samar ',
                'slug' => 'eastern-samar',
            ],
            // Region IX
            [
                'region_id' => '10',
                'psto_name' => 'Zamboanga Sibugay',
                'slug' => 'zamboanga-sibugay',
            ],
            [
                'region_id' => '10',
                'psto_name' => 'Zamboanga del Sur',
                'slug' => 'zamboanga-del-sur',
            ],
            [
                'region_id' => '10',
                'psto_name' => 'Zamboanga del Norte',
                'slug' => 'zamboanga-del-norte',
            ],
            [
                'region_id' => '10',
                'psto_name' => 'Zamboanga City',
                'slug' => 'zamboanga-city',
            ],  

            // Region X
            [
                'region_id' => '11',
                'psto_name' => 'Misamis Oriental',
                'slug' => 'misamis-oriental',
            ],
            [
                'region_id' => '11',
                'psto_name' => 'Camiguin',
                'slug' => 'camiguin',
            ],
            [
                'region_id' => '11',
                'psto_name' => 'Misamis Occidental',
                'slug' => 'misamis-occidental',
            ],
            [
                'region_id' => '11',
                'psto_name' => 'Bukidnon',
                'slug' => 'bukidnon',
            ],  
            [
                'region_id' => '11',
                'psto_name' => 'Lanao Del Norte',
                'slug' => 'lanao-del-norte',
            ],  

            
            // Region XI
            [
                'region_id' => '12',
                'psto_name' => 'Davao Del Sur',
                'slug' => 'davao-del-sur',
            ],
            [
                'region_id' => '12',
                'psto_name' => 'Davao Del Norte',
                'slug' => 'davao-del-norte',
            ],
            [
                'region_id' => '12',
                'psto_name' => 'Davao Occidental',
                'slug' => 'davao-occidental',
            ],
            [
                'region_id' => '12',
                'psto_name' => 'Davao Oriental',
                'slug' => 'davao-oriental',
            ],  
            [
                'region_id' => '12',
                'psto_name' => 'Davao De Oro',
                'slug' => 'davao-de-oro',
            ],  
            [
                'region_id' => '12',
                'psto_name' => 'Davao City',
                'slug' => 'davao-city',
            ],  

             // Region XII
             [
                'region_id' => '13',
                'psto_name' => 'Sargen',
                'slug' => 'Sargen',
            ],
            [
                'region_id' => '13',
                'psto_name' => 'North Cotabato',
                'slug' => 'north-cotabato',
            ],
            [
                'region_id' => '13',
                'psto_name' => 'South Cotabato',
                'slug' => 'south-cotabato',
            ],
            [
                'region_id' => '13',
                'psto_name' => 'Sultan Kudarat',
                'slug' => 'sultan-kudarat',
            ],

            //Region CARAGA
            [
                'region_id' => '14',
                'psto_name' => 'Surigao del Sur',
                'slug' => 'surigao-del-sur',
            ],
            [
                'region_id' => '14',
                'psto_name' => 'Surigao Del Norte',
                'slug' => 'surigao-del-norte',
            ],

            [
                'region_id' => '14',
                'psto_name' => 'Agusan del Sur',
                'slug' => 'agusan-del-sur',
            ],

            [
                'region_id' => '14',
                'psto_name' => 'Dinagat',
                'slug' => 'dinagat',
            ],
            [
                'region_id' => '14',
                'psto_name' => 'Agusan Del Norte',
                'slug' => 'agusan-del-norte',
            ],

            //Region CAR
            [
                'region_id' => '15',
                'psto_name' => 'Abra',
                'slug' => 'abra',
            ],
            [
                'region_id' => '15',
                'psto_name' => 'Apayao',
                'slug' => 'apayao',
            ],
            [
                'region_id' => '15',
                'psto_name' => 'Benguet',
                'slug' => 'benguet',
            ],
            [
                'region_id' => '15',
                'psto_name' => 'Ifugao',
                'slug' => 'ifugao',
            ],
            [
                'region_id' => '15',
                'psto_name' => 'Kalinga',
                'slug' => 'kalinga',
            ],
            [
                'region_id' => '15',
                'psto_name' => 'Mountain Province',
                'slug' => 'mountain-province',
            ],

            // Region NCR
            [
                'region_id' => '16',
                'psto_name' => 'National Capital Region',
                'slug' => 'national-capital-region',
            ],
            // Region ARMM
            [
                'region_id' => '16',
                'psto_name' => 'Maguindanao',
                'slug' => 'maguindanao',
            ],
            [
                'region_id' => '16',
                'psto_name' => 'Lanao Del Sur',
                'slug' => 'lanao-del-sur',
            ],
            [
                'region_id' => '16',
                'psto_name' => 'Basilan',
                'slug' => 'basilan',
            ],
            [
                'region_id' => '16',
                'psto_name' => 'Sulu',
                'slug' => 'sulu',
            ],
            [
                'region_id' => '16',
                'psto_name' => 'Tawi-Tawi',
                'slug' => 'tawi-tawi',
            ],
 
        ];

        psto::insert($sub_units);
    }
}
