<?php

namespace Vesaka\Games\Catalogue;

use Illuminate\Http\Request;
/**
 * Description of Unblockme
 *
 * @author vesak
 */
class Unblockme extends BaseGame {
    //put your code here
    
    public function calculate(Request $request): int|float {
        return $request->result['score'];
        return parent::calculate($request);
    }
}
