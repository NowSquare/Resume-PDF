<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('pdf', function() {
  $experience = [];

  $experience[] = [
    'type' => 'education',
    'when' => 'Jul 1998 - Feb 2001',
    'where' => 'University of Awesomeness',
    'location' => 'Amsterdam',
    'what' => 'Web development. International, large, scalable SaaS apps.'
  ];
  
  $experience[] = [
    'type' => 'work',
    'when' => 'Feb 2001 - Mar 2007',
    'where' => 'Company Name',
    'location' => 'Nuenen, Eindhoven Area',
    'what' => 'Owner. Software and business development.'
  ];
  
  $experience[] = [
    'type' => 'work',
    'when' => 'Feb 2001 - Mar 2007',
    'where' => 'Company Name',
    'location' => 'Nuenen, Eindhoven Area',
    'what' => 'Owner. Software and business development.'
  ];
  
  $projects = [];

  $projects[] = [
    'when' => 'Jul 2011',
    'title' => 'Project title',
    'img' => url('img/link-pages.jpg'),
    'description' => 'Proximity marketing app that triggers geo-fence and Bluetooth beacon scenarios with local push notifications.',
    'tags' => ['PHP', 'MySQL', 'JavaScript', 'CSS', 'Design']
  ];
  
  $projects[] = [
    'when' => 'Jul 2011',
    'title' => 'Project title',
    'img' => url('img/link-pages.jpg'),
    'description' => 'Proximity marketing app that triggers geo-fence and Bluetooth beacon scenarios with local push notifications.',
    'tags' => ['PHP', 'MySQL', 'JavaScript', 'CSS', 'Design']
  ];
  
  $projects[] = [
    'when' => 'Jul 2011',
    'title' => 'Project title',
    'img' => url('img/link-pages.jpg'),
    'description' => 'Proximity marketing app that triggers geo-fence and Bluetooth beacon scenarios with local push notifications.',
    'tags' => ['PHP', 'MySQL', 'JavaScript', 'CSS', 'Design']
  ];

  $pdf = storage_path('app/html.pdf');

  $view = 'pdf.resume';

  //if (\File::exists($pdf)) \File::delete($pdf);

  $footer = view('pdf.resume-footer', compact('experience'))->render();

  return \PDF::loadView($view, compact('experience', 'projects'))
    ->setOrientation('portrait')
    ->setOption('page-size', 'A4')
    ->setOption('margin-top', 18)
    ->setOption('margin-right', 18)
    ->setOption('margin-left', 18)
    ->setOption('margin-bottom', 18)
    ->setOption('footer-html', $footer)
    /*->setOption('footer-left', '[isodate] | https://nowsquare.com')
    ->setOption('footer-right', 'Page [page] of [toPage]')*/
    ->setOption('footer-spacing', 5)
    ->setOption('footer-font-name', 'Roboto')
    ->setOption('footer-font-size', 8)
    ->setOption('disable-local-file-access', true)
    ->inline();
  //\PDF::loadView($view, $data)->setPaper('a4')->setOrientation('portrait')->setOption('margin-bottom', 0)->save($pdf);
  //\PDF::loadFile($url)->setPaper('a4')->setOrientation('portrait')->setOption('margin-bottom', 0)->save($pdf);
});

Route::get('footer', function() {
  return 'dsfdsfsdf';
});

Route::get('/{any?}', '\Platform\Controllers\App\AppController@index')->where('any', '^(?!api|c\/)[\/\w\.-]*');
Route::get('/c/{uuid}', '\Platform\Controllers\Pages\PageController@getClick');