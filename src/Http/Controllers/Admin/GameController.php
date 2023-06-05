<?php

namespace Vesaka\Games\Http\Controllers\Admin;

use Vesaka\Core\Http\Controllers\Admin\ModelController;

/**
 * Description of GameController
 *
 * @author vesak
 */
class GameController extends ModelController {
    protected string $type = 'game';

    //    public function index() {
    //        dd('fsdfsd');
    //        $view = "admin::crud.$this->type.list";
    //        if (view()->exists($view)) {
    //            return view($view);
    //        }
    //        return view("admin::crud.model.list");
    //    }
}
