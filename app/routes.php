<?php

$env = $_SERVER['SERVER_NAME'] === 'localhost' ? 'local' : 'live';

$redirects = [
	'blog' => [
		'local' => 'http://localhost:7001',
		'live' => 'http://blog.anchorcms.com'
	],

	'forum' => [
		'local' => 'http://localhost:7002',
		'live' => 'http://forum.anchorcms.com'
	],

	'docs' => [
		'local' => 'http://localhost:7003',
		'live' => 'http://docs.anchorcms.com'
	]
];

/*
	Home page
*/
Route::get(array('/', 'home'), function() {
	return Layout::create('home', array('homepage' => true));
});

Route::get('features', function() {
	return Layout::create('features', array(
		'title' => 'Dozens of reasons to use Anchor'
	));
});

/*
	Redirects
*/
foreach($redirects as $slug => $options) {
	Route::redirect($slug, $options[$env]);
}

/*
	Download
*/
Route::get('download', function() {
	Query::table('downloads')->insert(get_insert_stats());

	return Response::redirect('https://github.com/anchorcms/anchor-cms/archive/' . LATEST_VERSION . '.zip');
});

/*
	Resources
*/
Route::get('resources', function() {
	$vars['title'] = 'Resources';
	return Layout::create('resources', $vars);
});

/*
	Stats
*/
Route::get('stats', function() {
	$vars['title'] = 'Stats';
	$vars['downloads'] = Query::table('downloads')->count();
	$vars['active'] = Query::table('active')->count();

	return Layout::create('stats', $vars);
});

/*
	Latest Version
*/
Route::get('version', function() {
	Query::table('active')->insert(get_insert_stats());

	return Response::create(LATEST_VERSION, 200, array('content-type' => 'text/plain'));
});

/*
	Github push/pull
*/
Route::any('deploy', function() {
	$msg = gmdate('Y-m-d H:i:s') . ' --> Received post from ' . $_SERVER['REMOTE_ADDR'] . PHP_EOL;
	file_put_contents(APP . 'storage/logs/access.log', $msg, FILE_APPEND);

	exec('git pull ' . PATH, $output);

	foreach($output as $line) {
		$msg = gmdate('Y-m-d H:i:s') . ' --> ' . $line . PHP_EOL;
		file_put_contents(APP . 'storage/logs/term.log', $msg, FILE_APPEND);
	}

	return Response::json('{result: true}');
});

/*
	404 error
*/
Route::not_found(function() {
	$output = Layout::create('error/404', array(
		'title' => 'Page not found'
	))->exec();

	return Response::create($output, 404);
});