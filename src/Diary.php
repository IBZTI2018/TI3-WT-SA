<?php

namespace WTSA1;

use WTSA1\Engines\Database;
use WTSA1\Engines\Session;
use WTSA1\Engines\Route;
use WTSA1\Models\User;
use WTSA1\Pages\HomePage;
use WTSA1\Pages\LoginPage;
use WTSA1\Pages\RegisterPage;

class Diary
{

	public function initRoutes()
	{
		// Setup routes
		Route::add('/', function() 
		{
			(new HomePage())->render();
		}, 
		'get');


		// Authorization routes
		Route::add('/login', function() {(new LoginPage())->render(); }, array('get', 'post'));
		Route::add('/register', function() {(new RegisterPage())->render(); },  array('get', 'post'));

		// Run Route
		Route::run('/');
	}

	public function startApp()
	{
		// Start Database Singleton
		Database::getInstance();

		// Start Session Singleton
		Session::getInstance();

		$this->initRoutes();
	}
}
