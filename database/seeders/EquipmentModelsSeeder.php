<?php
namespace Database\Seeders;

use App\Models\EquipmentModel;
use App\Models\Make;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class EquipmentModelsSeeder extends Seeder
{
    // Real equipment models matching amsparts.com machine fitment data.
    // year_start / year_end are real columns; year_range is an accessor — never seed it.
    private array $modelsByMake = [

        'Caterpillar' => [
            // Excavators
            ['name' => '308E2 CR',       'year_start' => 2015, 'year_end' => null],
            ['name' => '312D2',          'year_start' => 2013, 'year_end' => 2018],
            ['name' => '315D L',         'year_start' => 2010, 'year_end' => 2016],
            ['name' => '318D2 L',        'year_start' => 2013, 'year_end' => 2019],
            ['name' => '320B',           'year_start' => 1995, 'year_end' => 2001],
            ['name' => '320C',           'year_start' => 2001, 'year_end' => 2007],
            ['name' => '320D',           'year_start' => 2007, 'year_end' => 2013],
            ['name' => '320D2',          'year_start' => 2013, 'year_end' => 2019],
            ['name' => '320D2 GC',       'year_start' => 2016, 'year_end' => 2019],
            ['name' => '320GC',          'year_start' => 2019, 'year_end' => null],
            ['name' => '323D L',         'year_start' => 2009, 'year_end' => 2015],
            ['name' => '325C',           'year_start' => 2002, 'year_end' => 2007],
            ['name' => '325D',           'year_start' => 2007, 'year_end' => 2012],
            ['name' => '329D2',          'year_start' => 2013, 'year_end' => 2019],
            ['name' => '330B L',         'year_start' => 1995, 'year_end' => 2001],
            ['name' => '330C',           'year_start' => 2001, 'year_end' => 2007],
            ['name' => '330D',           'year_start' => 2007, 'year_end' => 2013],
            ['name' => '330D2',          'year_start' => 2014, 'year_end' => 2019],
            ['name' => '336D',           'year_start' => 2008, 'year_end' => 2015],
            ['name' => '336D2',          'year_start' => 2014, 'year_end' => 2019],
            ['name' => '336GC',          'year_start' => 2019, 'year_end' => null],
            ['name' => '349D',           'year_start' => 2009, 'year_end' => 2015],
            ['name' => '349D2',          'year_start' => 2014, 'year_end' => 2019],
            ['name' => '374D L',         'year_start' => 2012, 'year_end' => 2019],
            ['name' => '390D',           'year_start' => 2011, 'year_end' => 2018],
            // Bulldozers
            ['name' => 'D3K2 LGP',      'year_start' => 2012, 'year_end' => null],
            ['name' => 'D4K2 XL',       'year_start' => 2012, 'year_end' => null],
            ['name' => 'D5K2 LGP',      'year_start' => 2012, 'year_end' => null],
            ['name' => 'D6K2',          'year_start' => 2012, 'year_end' => null],
            ['name' => 'D6R',           'year_start' => 1995, 'year_end' => 2008],
            ['name' => 'D6T',           'year_start' => 2008, 'year_end' => null],
            ['name' => 'D7E',           'year_start' => 2009, 'year_end' => null],
            ['name' => 'D8R',           'year_start' => 1996, 'year_end' => 2009],
            ['name' => 'D8T',           'year_start' => 2008, 'year_end' => null],
            ['name' => 'D9T',           'year_start' => 2008, 'year_end' => null],
            ['name' => 'D10T',          'year_start' => 2008, 'year_end' => null],
            ['name' => 'D11T',          'year_start' => 2008, 'year_end' => null],
            // Wheel Loaders
            ['name' => '930G',          'year_start' => 1997, 'year_end' => 2004],
            ['name' => '938G',          'year_start' => 1997, 'year_end' => 2004],
            ['name' => '950H',          'year_start' => 2004, 'year_end' => 2011],
            ['name' => '950M',          'year_start' => 2012, 'year_end' => null],
            ['name' => '962H',          'year_start' => 2004, 'year_end' => 2011],
            ['name' => '966H',          'year_start' => 2004, 'year_end' => 2011],
            ['name' => '966M',          'year_start' => 2012, 'year_end' => null],
            ['name' => '972H',          'year_start' => 2004, 'year_end' => 2011],
            ['name' => '980H',          'year_start' => 2004, 'year_end' => 2011],
            ['name' => '980M',          'year_start' => 2012, 'year_end' => null],
            // Motor Graders
            ['name' => '12M',           'year_start' => 2008, 'year_end' => 2016],
            ['name' => '140H',          'year_start' => 1996, 'year_end' => 2008],
            ['name' => '140M',          'year_start' => 2007, 'year_end' => 2016],
            ['name' => '160M',          'year_start' => 2008, 'year_end' => 2016],
            // Skid Steers
            ['name' => '226D',          'year_start' => 2015, 'year_end' => null],
            ['name' => '232D',          'year_start' => 2015, 'year_end' => null],
            ['name' => '242D',          'year_start' => 2015, 'year_end' => null],
            ['name' => '262D',          'year_start' => 2015, 'year_end' => null],
            ['name' => '272D2 XHP',     'year_start' => 2015, 'year_end' => null],
        ],

        'Komatsu' => [
            // Excavators
            ['name' => 'PC35MR-3',      'year_start' => 2010, 'year_end' => null],
            ['name' => 'PC55MR-5',      'year_start' => 2014, 'year_end' => null],
            ['name' => 'PC78US-8',      'year_start' => 2012, 'year_end' => null],
            ['name' => 'PC130-8',       'year_start' => 2009, 'year_end' => null],
            ['name' => 'PC160LC-8',     'year_start' => 2009, 'year_end' => null],
            ['name' => 'PC200-6',       'year_start' => 1994, 'year_end' => 2001],
            ['name' => 'PC200-7',       'year_start' => 2001, 'year_end' => 2007],
            ['name' => 'PC200-8',       'year_start' => 2007, 'year_end' => 2015],
            ['name' => 'PC210LC-8',     'year_start' => 2008, 'year_end' => 2016],
            ['name' => 'PC228USLC-10',  'year_start' => 2016, 'year_end' => null],
            ['name' => 'PC240LC-8',     'year_start' => 2008, 'year_end' => 2016],
            ['name' => 'PC290LC-8',     'year_start' => 2008, 'year_end' => 2016],
            ['name' => 'PC300-8',       'year_start' => 2007, 'year_end' => 2015],
            ['name' => 'PC350LC-8',     'year_start' => 2008, 'year_end' => 2016],
            ['name' => 'PC360LC-10',    'year_start' => 2015, 'year_end' => null],
            ['name' => 'PC400LC-8',     'year_start' => 2008, 'year_end' => 2016],
            ['name' => 'PC490LC-10',    'year_start' => 2015, 'year_end' => null],
            ['name' => 'PC600LC-8',     'year_start' => 2008, 'year_end' => null],
            ['name' => 'PC800LC-8',     'year_start' => 2008, 'year_end' => null],
            // Bulldozers
            ['name' => 'D37EX-23',      'year_start' => 2014, 'year_end' => null],
            ['name' => 'D51EX-22',      'year_start' => 2012, 'year_end' => null],
            ['name' => 'D61EX-23',      'year_start' => 2014, 'year_end' => null],
            ['name' => 'D65PX-18',      'year_start' => 2015, 'year_end' => null],
            ['name' => 'D85EX-15',      'year_start' => 2010, 'year_end' => null],
            ['name' => 'D155AX-8',      'year_start' => 2015, 'year_end' => null],
            // Wheel Loaders
            ['name' => 'WA200-7',       'year_start' => 2015, 'year_end' => null],
            ['name' => 'WA250-7',       'year_start' => 2015, 'year_end' => null],
            ['name' => 'WA320-7',       'year_start' => 2015, 'year_end' => null],
            ['name' => 'WA380-7',       'year_start' => 2015, 'year_end' => null],
            ['name' => 'WA430-7',       'year_start' => 2015, 'year_end' => null],
            ['name' => 'WA470-6',       'year_start' => 2009, 'year_end' => 2019],
            ['name' => 'WA500-7',       'year_start' => 2016, 'year_end' => null],
            ['name' => 'WA600-6',       'year_start' => 2010, 'year_end' => null],
            // Motor Graders
            ['name' => 'GD555-5',       'year_start' => 2012, 'year_end' => null],
            ['name' => 'GD655-5',       'year_start' => 2012, 'year_end' => null],
            ['name' => 'GD675-5',       'year_start' => 2012, 'year_end' => null],
        ],

        'John Deere' => [
            // Excavators
            ['name' => '27D',           'year_start' => 2009, 'year_end' => 2019],
            ['name' => '35G',           'year_start' => 2013, 'year_end' => null],
            ['name' => '50G',           'year_start' => 2013, 'year_end' => null],
            ['name' => '75G',           'year_start' => 2013, 'year_end' => null],
            ['name' => '85G',           'year_start' => 2013, 'year_end' => null],
            ['name' => '135G',          'year_start' => 2013, 'year_end' => null],
            ['name' => '160G LC',       'year_start' => 2013, 'year_end' => null],
            ['name' => '180G LC',       'year_start' => 2013, 'year_end' => null],
            ['name' => '200G LC',       'year_start' => 2013, 'year_end' => null],
            ['name' => '210G LC',       'year_start' => 2013, 'year_end' => null],
            ['name' => '245G LC',       'year_start' => 2015, 'year_end' => null],
            ['name' => '290G LC',       'year_start' => 2015, 'year_end' => null],
            ['name' => '330G LC',       'year_start' => 2015, 'year_end' => null],
            ['name' => '350G LC',       'year_start' => 2015, 'year_end' => null],
            ['name' => '380G LC',       'year_start' => 2016, 'year_end' => null],
            ['name' => '470G LC',       'year_start' => 2016, 'year_end' => null],
            ['name' => '670G LC',       'year_start' => 2016, 'year_end' => null],
            // Wheel Loaders
            ['name' => '444K',          'year_start' => 2011, 'year_end' => 2016],
            ['name' => '524K',          'year_start' => 2011, 'year_end' => 2016],
            ['name' => '544K',          'year_start' => 2011, 'year_end' => 2016],
            ['name' => '624K',          'year_start' => 2011, 'year_end' => 2016],
            ['name' => '644K',          'year_start' => 2012, 'year_end' => 2018],
            ['name' => '724K',          'year_start' => 2012, 'year_end' => 2018],
            ['name' => '824K',          'year_start' => 2013, 'year_end' => 2019],
            // Backhoes
            ['name' => '310SK',         'year_start' => 2012, 'year_end' => 2019],
            ['name' => '410K',          'year_start' => 2012, 'year_end' => 2019],
            ['name' => '710K',          'year_start' => 2012, 'year_end' => 2019],
            // Motor Graders
            ['name' => '670GP',         'year_start' => 2015, 'year_end' => null],
            ['name' => '770GP',         'year_start' => 2015, 'year_end' => null],
            ['name' => '872GP',         'year_start' => 2015, 'year_end' => null],
        ],

        'Volvo' => [
            // Excavators
            ['name' => 'ECR88D',        'year_start' => 2015, 'year_end' => null],
            ['name' => 'EC140D',        'year_start' => 2014, 'year_end' => null],
            ['name' => 'EC160D',        'year_start' => 2014, 'year_end' => null],
            ['name' => 'EC220D',        'year_start' => 2014, 'year_end' => null],
            ['name' => 'EC220E',        'year_start' => 2019, 'year_end' => null],
            ['name' => 'EC250D',        'year_start' => 2014, 'year_end' => null],
            ['name' => 'EC300D',        'year_start' => 2014, 'year_end' => null],
            ['name' => 'EC350D',        'year_start' => 2015, 'year_end' => null],
            ['name' => 'EC380D',        'year_start' => 2015, 'year_end' => null],
            ['name' => 'EC480D',        'year_start' => 2015, 'year_end' => null],
            ['name' => 'EC700C',        'year_start' => 2009, 'year_end' => 2015],
            ['name' => 'EC750D',        'year_start' => 2015, 'year_end' => null],
            // Wheel Loaders
            ['name' => 'L110H',         'year_start' => 2015, 'year_end' => null],
            ['name' => 'L120H',         'year_start' => 2015, 'year_end' => null],
            ['name' => 'L150H',         'year_start' => 2015, 'year_end' => null],
            ['name' => 'L180H',         'year_start' => 2015, 'year_end' => null],
            ['name' => 'L220H',         'year_start' => 2015, 'year_end' => null],
            ['name' => 'L250H',         'year_start' => 2015, 'year_end' => null],
            // Articulated Haulers
            ['name' => 'A30G',          'year_start' => 2013, 'year_end' => null],
            ['name' => 'A35G',          'year_start' => 2013, 'year_end' => null],
            ['name' => 'A40G',          'year_start' => 2013, 'year_end' => null],
            ['name' => 'A45G',          'year_start' => 2014, 'year_end' => null],
        ],

        'Hitachi' => [
            // Excavators — ZX-5G and ZX-6 series
            ['name' => 'ZX85USB-5N',    'year_start' => 2015, 'year_end' => null],
            ['name' => 'ZX130-5N',      'year_start' => 2014, 'year_end' => null],
            ['name' => 'ZX135US-5N',    'year_start' => 2014, 'year_end' => null],
            ['name' => 'ZX160LC-5N',    'year_start' => 2014, 'year_end' => null],
            ['name' => 'ZX200-5G',      'year_start' => 2015, 'year_end' => null],
            ['name' => 'ZX225USLC-5N',  'year_start' => 2015, 'year_end' => null],
            ['name' => 'ZX250LC-5G',    'year_start' => 2015, 'year_end' => null],
            ['name' => 'ZX300LC-5G',    'year_start' => 2015, 'year_end' => null],
            ['name' => 'ZX330LC-5G',    'year_start' => 2015, 'year_end' => null],
            ['name' => 'ZX350LC-5G',    'year_start' => 2015, 'year_end' => null],
            ['name' => 'ZX370MTH-5G',   'year_start' => 2015, 'year_end' => null],
            ['name' => 'ZX470LC-5G',    'year_start' => 2015, 'year_end' => null],
            ['name' => 'ZX670LC-5G',    'year_start' => 2016, 'year_end' => null],
            ['name' => 'ZX300-1',       'year_start' => 2001, 'year_end' => 2007],
            ['name' => 'ZX330-1',       'year_start' => 2001, 'year_end' => 2007],
            ['name' => 'ZX370-1',       'year_start' => 2001, 'year_end' => 2007],
            // Wheel Loaders
            ['name' => 'ZW150-5B',      'year_start' => 2016, 'year_end' => null],
            ['name' => 'ZW180-5B',      'year_start' => 2016, 'year_end' => null],
            ['name' => 'ZW220-5B',      'year_start' => 2016, 'year_end' => null],
            ['name' => 'ZW250-5B',      'year_start' => 2016, 'year_end' => null],
        ],

        'Case' => [
            // Excavators
            ['name' => 'CX50C',         'year_start' => 2015, 'year_end' => null],
            ['name' => 'CX80C',         'year_start' => 2015, 'year_end' => null],
            ['name' => 'CX130D',        'year_start' => 2014, 'year_end' => null],
            ['name' => 'CX145C SR',     'year_start' => 2015, 'year_end' => null],
            ['name' => 'CX160D',        'year_start' => 2014, 'year_end' => null],
            ['name' => 'CX180D',        'year_start' => 2014, 'year_end' => null],
            ['name' => 'CX210D',        'year_start' => 2014, 'year_end' => null],
            ['name' => 'CX250D',        'year_start' => 2014, 'year_end' => null],
            ['name' => 'CX300D',        'year_start' => 2014, 'year_end' => null],
            ['name' => 'CX350D',        'year_start' => 2014, 'year_end' => null],
            ['name' => 'CX370D',        'year_start' => 2014, 'year_end' => null],
            ['name' => 'CX490D',        'year_start' => 2015, 'year_end' => null],
            ['name' => 'CX750D',        'year_start' => 2015, 'year_end' => null],
            // Wheel Loaders
            ['name' => '621F',          'year_start' => 2012, 'year_end' => 2019],
            ['name' => '721F',          'year_start' => 2012, 'year_end' => 2019],
            ['name' => '821F',          'year_start' => 2013, 'year_end' => null],
            ['name' => '921F',          'year_start' => 2013, 'year_end' => null],
            // Backhoes
            ['name' => '580ST',         'year_start' => 2014, 'year_end' => null],
            ['name' => '590ST',         'year_start' => 2014, 'year_end' => null],
            ['name' => '695ST',         'year_start' => 2015, 'year_end' => null],
        ],

        'Bobcat' => [
            // Skid Steers
            ['name' => 'S450',          'year_start' => 2015, 'year_end' => null],
            ['name' => 'S550',          'year_start' => 2012, 'year_end' => null],
            ['name' => 'S590',          'year_start' => 2015, 'year_end' => null],
            ['name' => 'S630',          'year_start' => 2012, 'year_end' => null],
            ['name' => 'S650',          'year_start' => 2011, 'year_end' => null],
            ['name' => 'S740',          'year_start' => 2016, 'year_end' => null],
            ['name' => 'S750',          'year_start' => 2012, 'year_end' => null],
            ['name' => 'S770',          'year_start' => 2015, 'year_end' => null],
            ['name' => 'S850',          'year_start' => 2016, 'year_end' => null],
            // Compact Track Loaders
            ['name' => 'T450',          'year_start' => 2015, 'year_end' => null],
            ['name' => 'T550',          'year_start' => 2012, 'year_end' => null],
            ['name' => 'T590',          'year_start' => 2015, 'year_end' => null],
            ['name' => 'T630',          'year_start' => 2012, 'year_end' => null],
            ['name' => 'T650',          'year_start' => 2011, 'year_end' => null],
            ['name' => 'T750',          'year_start' => 2012, 'year_end' => null],
            ['name' => 'T770',          'year_start' => 2015, 'year_end' => null],
            // Mini Excavators
            ['name' => 'E26',           'year_start' => 2015, 'year_end' => null],
            ['name' => 'E35',           'year_start' => 2013, 'year_end' => null],
            ['name' => 'E42',           'year_start' => 2015, 'year_end' => null],
            ['name' => 'E50',           'year_start' => 2013, 'year_end' => null],
            ['name' => 'E55',           'year_start' => 2015, 'year_end' => null],
            ['name' => 'E85',           'year_start' => 2014, 'year_end' => null],
            ['name' => 'E145',          'year_start' => 2017, 'year_end' => null],
        ],

        'Doosan' => [
            // Excavators
            ['name' => 'DX85R-3',       'year_start' => 2014, 'year_end' => null],
            ['name' => 'DX140LC-5',     'year_start' => 2015, 'year_end' => null],
            ['name' => 'DX180LC-5',     'year_start' => 2015, 'year_end' => null],
            ['name' => 'DX235LCR-5',    'year_start' => 2016, 'year_end' => null],
            ['name' => 'DX255LC-5',     'year_start' => 2015, 'year_end' => null],
            ['name' => 'DX300LC-5',     'year_start' => 2015, 'year_end' => null],
            ['name' => 'DX350LC-5',     'year_start' => 2015, 'year_end' => null],
            ['name' => 'DX420LC-5',     'year_start' => 2015, 'year_end' => null],
            ['name' => 'DX490LC-5',     'year_start' => 2015, 'year_end' => null],
            ['name' => 'DX530LC-5',     'year_start' => 2015, 'year_end' => null],
            // Wheel Loaders
            ['name' => 'DL220-5',       'year_start' => 2015, 'year_end' => null],
            ['name' => 'DL250-5',       'year_start' => 2015, 'year_end' => null],
            ['name' => 'DL300-5',       'year_start' => 2015, 'year_end' => null],
            ['name' => 'DL420-5',       'year_start' => 2015, 'year_end' => null],
            ['name' => 'DL550-5',       'year_start' => 2015, 'year_end' => null],
        ],

        'Hyundai' => [
            ['name' => 'R55-9A',        'year_start' => 2014, 'year_end' => null],
            ['name' => 'R80-7A',        'year_start' => 2013, 'year_end' => null],
            ['name' => 'R140LC-9A',     'year_start' => 2014, 'year_end' => null],
            ['name' => 'R160LC-9A',     'year_start' => 2014, 'year_end' => null],
            ['name' => 'R180LC-9A',     'year_start' => 2014, 'year_end' => null],
            ['name' => 'R210LC-9A',     'year_start' => 2014, 'year_end' => null],
            ['name' => 'R250LC-9A',     'year_start' => 2014, 'year_end' => null],
            ['name' => 'R300LC-9A',     'year_start' => 2014, 'year_end' => null],
            ['name' => 'R360LC-9A',     'year_start' => 2014, 'year_end' => null],
            ['name' => 'R480LC-9A',     'year_start' => 2014, 'year_end' => null],
            ['name' => 'HX235L',        'year_start' => 2016, 'year_end' => null],
            ['name' => 'HX300L',        'year_start' => 2016, 'year_end' => null],
        ],

        'Kobelco' => [
            ['name' => 'SK75SR-3E',     'year_start' => 2013, 'year_end' => null],
            ['name' => 'SK130LC-11',    'year_start' => 2014, 'year_end' => null],
            ['name' => 'SK140SRLC-5',   'year_start' => 2015, 'year_end' => null],
            ['name' => 'SK200-8',       'year_start' => 2008, 'year_end' => 2016],
            ['name' => 'SK210LC-10',    'year_start' => 2015, 'year_end' => null],
            ['name' => 'SK250LC-10',    'year_start' => 2015, 'year_end' => null],
            ['name' => 'SK300LC-10',    'year_start' => 2015, 'year_end' => null],
            ['name' => 'SK330LC-10',    'year_start' => 2015, 'year_end' => null],
            ['name' => 'SK380XDLC-10',  'year_start' => 2015, 'year_end' => null],
            ['name' => 'SK500LC-10',    'year_start' => 2016, 'year_end' => null],
            ['name' => 'SK135SR-1E',    'year_start' => 2010, 'year_end' => 2016],
            ['name' => 'SK300-1',       'year_start' => 1998, 'year_end' => 2005],
            ['name' => 'SK330-1',       'year_start' => 1998, 'year_end' => 2005],
        ],

        'Daewoo' => [
            ['name' => 'Solar 130LC-V',     'year_start' => 2002, 'year_end' => 2010],
            ['name' => 'Solar 170LC-V',     'year_start' => 2002, 'year_end' => 2010],
            ['name' => 'Solar 220LC-V',     'year_start' => 2002, 'year_end' => 2010],
            ['name' => 'Solar 280LC-V',     'year_start' => 2003, 'year_end' => 2010],
            ['name' => 'Solar 340LC-V',     'year_start' => 2003, 'year_end' => 2010],
            ['name' => 'Solar 400LC-V',     'year_start' => 2003, 'year_end' => 2010],
        ],

        'Kubota' => [
            ['name' => 'KX018-4',       'year_start' => 2012, 'year_end' => null],
            ['name' => 'KX033-4',       'year_start' => 2013, 'year_end' => null],
            ['name' => 'KX057-4',       'year_start' => 2013, 'year_end' => null],
            ['name' => 'KX080-4',       'year_start' => 2012, 'year_end' => null],
            ['name' => 'U50-5',         'year_start' => 2014, 'year_end' => null],
            ['name' => 'U55-4',         'year_start' => 2013, 'year_end' => null],
            ['name' => 'SVL75-2',       'year_start' => 2015, 'year_end' => null],
            ['name' => 'SVL95-2S',      'year_start' => 2016, 'year_end' => null],
        ],

        'Link Belt' => [
            ['name' => '80 X2 Spin Ace',  'year_start' => 2013, 'year_end' => null],
            ['name' => '145 X3',          'year_start' => 2014, 'year_end' => null],
            ['name' => '210 X4',          'year_start' => 2016, 'year_end' => null],
            ['name' => '250 X4',          'year_start' => 2016, 'year_end' => null],
            ['name' => '300 X4',          'year_start' => 2016, 'year_end' => null],
            ['name' => '350 X4',          'year_start' => 2016, 'year_end' => null],
            ['name' => '460 LX',          'year_start' => 2009, 'year_end' => 2019],
        ],

        'New Holland' => [
            ['name' => 'E80C',          'year_start' => 2014, 'year_end' => null],
            ['name' => 'E145C',         'year_start' => 2014, 'year_end' => null],
            ['name' => 'E215C',         'year_start' => 2014, 'year_end' => null],
            ['name' => 'E265C',         'year_start' => 2014, 'year_end' => null],
            ['name' => 'E305C',         'year_start' => 2015, 'year_end' => null],
            ['name' => 'W170D',         'year_start' => 2014, 'year_end' => null],
            ['name' => 'W190D',         'year_start' => 2014, 'year_end' => null],
        ],

        'Samsung' => [
            ['name' => 'SE130LC-2',     'year_start' => 1998, 'year_end' => 2007],
            ['name' => 'SE210LC-2',     'year_start' => 1998, 'year_end' => 2007],
            ['name' => 'SE280LC-2',     'year_start' => 2000, 'year_end' => 2008],
        ],

        'Takeuchi' => [
            ['name' => 'TB175W',        'year_start' => 2015, 'year_end' => null],
            ['name' => 'TB260',         'year_start' => 2015, 'year_end' => null],
            ['name' => 'TB290',         'year_start' => 2015, 'year_end' => null],
            ['name' => 'TL12R2',        'year_start' => 2016, 'year_end' => null],
            ['name' => 'TL12V2',        'year_start' => 2016, 'year_end' => null],
        ],

        'Timberjack' => [
            ['name' => '608L',          'year_start' => 2000, 'year_end' => 2010],
            ['name' => '628',           'year_start' => 1998, 'year_end' => 2008],
            ['name' => '748G III',      'year_start' => 2002, 'year_end' => 2010],
            ['name' => '810C',          'year_start' => 1998, 'year_end' => 2007],
        ],

        'Yanmar' => [
            ['name' => 'ViO35-6',       'year_start' => 2014, 'year_end' => null],
            ['name' => 'ViO55-6',       'year_start' => 2014, 'year_end' => null],
            ['name' => 'ViO80-1A',      'year_start' => 2012, 'year_end' => null],
            ['name' => 'SV100-2',       'year_start' => 2015, 'year_end' => null],
        ],

        'JCB' => [
            ['name' => '3CX',           'year_start' => 2008, 'year_end' => null],
            ['name' => '4CX',           'year_start' => 2008, 'year_end' => null],
            ['name' => 'JS145LC',       'year_start' => 2010, 'year_end' => null],
            ['name' => 'JS200LC',       'year_start' => 2010, 'year_end' => null],
            ['name' => 'JS290LC',       'year_start' => 2013, 'year_end' => null],
            ['name' => 'JS370LC',       'year_start' => 2014, 'year_end' => null],
            ['name' => '407ZX',         'year_start' => 2012, 'year_end' => null],
        ],

        'Dresser' => [
            ['name' => '510B',          'year_start' => 1993, 'year_end' => 2000],
            ['name' => '540B',          'year_start' => 1993, 'year_end' => 2000],
            ['name' => '545',           'year_start' => 1996, 'year_end' => 2004],
            ['name' => '570B',          'year_start' => 1994, 'year_end' => 2001],
        ],
    ];

    public function run(): void
    {
        $this->command->info('Seeding Equipment Models...');
        $total = 0;

        foreach ($this->modelsByMake as $makeName => $models) {
            $make = Make::where('slug', Str::slug($makeName))->first();

            if (! $make) {
                $this->command->warn("  Make not found: {$makeName}");
                continue;
            }

            $this->command->line("  → {$makeName} (" . count($models) . " models)");

            foreach ($models as $modelData) {
                // year_range is an ACCESSOR — never include it here. Only year_start and year_end.
                EquipmentModel::updateOrCreate(
                    [
                        'make_id' => $make->id,
                        'slug'    => Str::slug($modelData['name']),
                    ],
                    [
                        'make_id'     => $make->id,
                        'name'        => $modelData['name'],
                        'slug'        => Str::slug($modelData['name']),
                        'year_start'  => $modelData['year_start'],
                        'year_end'    => $modelData['year_end'],
                        'description' => null,
                        'is_active'   => true,
                    ]
                );
                $total++;
            }
        }

        $this->command->info("  ✓ Equipment Models seeded: {$total}");
    }
}
