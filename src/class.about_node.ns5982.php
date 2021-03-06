<?php

/*
    This file is part of List58.

    List58 is free software: you can redistribute it and/or modify
    it under the terms of the GNU Affero General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    List58 is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU Affero General Public License for more details.

    You should have received a copy of the GNU Affero General Public License
    along with List58.  If not, see <http://www.gnu.org/licenses/>.

*/

require_once dirname(__FILE__).'/class.base_node.ns8054.php';
require_once dirname(__FILE__).'/class.node.ns21085.php';

class about_node__ns5982 extends node__ns21085 {
    protected function _node__get_title() {
        $parent_title = parent::_node__get_title();
        
        return 'О Системе - '.$parent_title;
    }
    
    protected function _node__get_head() {
        $parent_head = parent::_node__get_head();
        
        $html = '';
        
        $html .=
            $parent_head.
            '<link rel="stylesheet" href="/media/about_node/css/style.css" />';
        
        return $html;
    }
    
    protected function _node__get_aside() {
        $html = '';
        
        $html .=
            '<div class="SmallFrame">'.
                '<p style="color: rgb(255,0,0)">(здесь в будущем будет информация о системе)</p>'.
                '<p style="color: rgb(128,128,0)">(здесь в будущем будет информация о системе)</p>'.
                '<p style="color: rgb(0,255,0)">(здесь в будущем будет информация о системе)</p>'.
                '<p style="color: rgb(0,128,128)">(здесь в будущем будет информация о системе)</p>'.
                '<p style="color: rgb(0,0,255)">(здесь в будущем будет информация о системе)</p>'.
                '<p style="color: rgb(128,0,128)">(здесь в будущем будет информация о системе)</p>'.
            '</div>';
        
        return $html;
    }
}

