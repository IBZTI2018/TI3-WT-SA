<?php

namespace WTSA1;

use WTSA1\Engines\Database;
use WTSA1\Engines\Session;
use WTSA1\Engines\Route;
use WTSA1\Pages\HomePage;
use WTSA1\Pages\LoginPage;
use WTSA1\Pages\LogoutPage;
use WTSA1\Pages\RegisterPage;
use WTSA1\Pages\CreatePage;
use WTSA1\Pages\ImagePage;

class Diary
{

	public function initRoutes() {
		// Diary routes
		Route::add('/', function() {(new HomePage())->render(); }, 'get');
		Route::add('/create', function() {(new CreatePage())->render(); },  array('get', 'post'));

		// Authorization routes
		Route::add('/register', function() {(new RegisterPage())->render(); },  array('get', 'post'));
		Route::add('/login', function() {(new LoginPage())->render(); }, array('get', 'post'));
		Route::add('/logout', function() {(new LogoutPage())->render(); }, array('post'));
		Route::add('/image', function () {(new ImagePage())->render(); }, array('get'));

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
