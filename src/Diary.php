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

		// Run Route
		Route::run('/');
	}

	public function startApp()
	{
	    // Get Git Version
        $GLOBALS['_VERSION_'] = null;
        $version_file = @fopen("VERSION.txt", "r") or null;
        if ($version_file) {
            $version_content = fread($version_file, filesize("VERSION.txt"));
            if (strlen($version_content) > 0) {
                $GLOBALS['_VERSION_'] = $version_content;
            }
            fclose($version_file);
        }


		// Start Database Singleton
		Database::getInstance();

		// Start Session Singleton
		Session::getInstance();

		$this->initRoutes();
	}
}
