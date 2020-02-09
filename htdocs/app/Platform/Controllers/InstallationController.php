<?php namespace Platform\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Core;
use Illuminate\Encryption\Encrypter;
use Illuminate\Support\Str;

class InstallationController extends \App\Http\Controllers\Controller {

	/*
	|--------------------------------------------------------------------------
	| Installation Controller
	|--------------------------------------------------------------------------
	|
	*/

	/**
	 * Check for installation with api call
	 */
	public function postPing() {
    $referrer = request('referrer', null);

    if ($referrer == 'install') {
      if ($this->isInstalled()) {
        $response = ['redirect' => 'home'];
      } else {
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
          $distro = 'Windows';
        } else {
          //declare Linux distros(extensible list).
          $distros = array(
            "Arch" => "arch-release",
            "Debian" => "debian_version",
            "Fedora" => "fedora-release",
            "Ubuntu" => "lsb-release",
            'Redhat' => 'redhat-release',
            'CentOS' => 'centos-release'
          );
          //Get everything from /etc directory.
          $etcList = scandir('/etc');

          //Loop through /etc results...
          $distro = '';
          foreach ($etcList as $entry) {
            //Loop through list of distros..
            foreach ($distros as $distroReleaseFile) {
              //Match was found.
              if ($distroReleaseFile === $entry) {
                //Find distros array key(i.e. Distro name) by value(i.e. distro release file)
                $distro = array_search($distroReleaseFile, $distros);
                break 2;//Break inner and outer loop.
              }
            }
          }
        }

        $binaries_executable = true;
        $dir = base_path('resources/wkhtmltopdf/');
        $files = \File::files($dir);
        foreach($files as $f) {
          if (! is_executable($f)) $binaries_executable = false;
        }

        $response = [
          'binaries_executable' => $binaries_executable,
          'php' => version_compare (PHP_VERSION, '7.2.0') >= 0,
          'pdo_mysql' => extension_loaded ('pdo_mysql'),
          'distro' => $distro,
          'servers' => [
            'windows-64' => 'Windows 64-bit',
            'amd64-debian10' => 'Debian 10 64-bit',
            'amd64-debian9' => 'Debian 9 64-bit',
            'amd64-ubuntu1804' => 'Ubuntu 18.04 64-bit',
            'amd64-ubuntu1604' => 'Ubuntu 16.04 64-bit',
            'x86_64-centos8' => 'CentOS 8 64-bit',
            'x86_64-centos7' => 'CentOS 7 64-bit',
            'x86_64-centos6' => 'CentOS 6 64-bit',
          ],
          'bcmath' => extension_loaded ('bcmath'),
          'ctype' => extension_loaded ('ctype'),
          'json' => extension_loaded ('json'),
          'mbstring' => extension_loaded ('mbstring'),
          'openssl' => extension_loaded ('openssl'),
          'PDO' => extension_loaded ('PDO'),
          'tokenizer' => extension_loaded ('tokenizer'),
          'xml' => extension_loaded ('xml'),
          'gd' => extension_loaded ('gd')
        ];
      }
    } else {
      if (!$this->isInstalled()) {
        $response = ['redirect' => 'install'];
      } else {
        $response = ['ok'];
      }
    }
    return response()->json($response, 200);
	}
  
	/**
	 * Check for installation
	 */
	public static function isInstalled () {
    return (\File::exists(base_path('.env'))) ? true : false;
	}

	/**
	 * Post installation
	 */
  public function postInstall(Request $request) {
		# https://stackoverflow.com/a/28898174
		if(!defined('STDIN'))  define('STDIN',  fopen('php://stdin',  'rb'));
		if(!defined('STDOUT')) define('STDOUT', fopen('php://stdout', 'wb'));
		if(!defined('STDERR')) define('STDERR', fopen('php://stderr', 'wb'));

    set_time_limit(500);

    $response = $request->input('install');

    $name = $response['adminName'];
    $email = $response['adminEmail'];
    $pass = $response['adminPassword'];

    $APP_KEY = 'base64:'.base64_encode(
      Encrypter::generateKey(config('app.cipher'))
    );

    // Get .env.example file as blueprint
    $env = \File::get(base_path('.env.example'));

    // Filter
    $all = [];
    $filter = ['loading', 'adminName', 'adminEmail', 'adminPassword', 'hasError', 'errors', 'success'];
    foreach ($response as $key => $value) {
      if (!in_array($key, $filter)) {
        $all[$key] = $value;
      }
    }

    //$all['APP_KEY'] = $APP_KEY;
    $all['APP_DEBUG'] = 'false';
    $all['APP_ENV'] = 'production';

    // Loop through .env.example and set config
    $new_env = '';

    foreach(preg_split("/((\r?\n)|(\r\n?))/", $env) as $line) {
      $cfg_found = false;

      foreach ($all as $key => $value) {
        if (Str::startsWith($line, $key . '=')) {
          $cfg_found = true;
          if ($value == 'true' || $value == 'false' || is_numeric($value)) {
            $new_env .= $key . '=' . $value . '' . PHP_EOL;
          } else { 
            $new_env .= $key . '="' . $value . '"' . PHP_EOL;
          }
        }
      }

      if (! $cfg_found) {
        $new_env .= $line . PHP_EOL;
      }
    }

    \File::put(base_path('.env'), $new_env);

    \Artisan::call('install');

    $user = \App\User::find(1);
    $user->name = $name;
    $user->email = $email;
    $user->password = bcrypt($pass);
    $user->save();

    return response()->json(['status' => 'success'], 200);
	}
}