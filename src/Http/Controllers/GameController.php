<?php
namespace Vesaka\Games\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


/**
 * Description of GameController
 *
 * @author vesak
 */
class GameController extends Controller {
    //put your code here
    const APP_FILE = 'packages/vesaka/games/resources/js/game/%s/app.js';
    const DEMO_FILE = 'packages/vesaka/games/resources/js/play/%s.js';
    public function app(Request $request) {
        return view('game::app', [
            'name' => $request->name,
            'appJs' => $this->getAppJsPath($request->name),
            'title' => Str::camel($request->name)
        ]);
    }
    
    public function play(Request $request) {
        return view('game::play', [
            'name' => $request->name,
            'appJs' => $this->getDemoJsPath($request->name),
            'title' => Str::camel($request->name)
        ]);
    }
    
    private function getAppJsPath(string $name): string {
        return sprintf(self::APP_FILE, $name);
    }
    
    private function getDemoJsPath(string $name): string {
        return sprintf(self::DEMO_FILE, $name);
    }
}
