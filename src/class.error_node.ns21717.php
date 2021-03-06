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
require_once dirname(__FILE__).'/utils/class.msg_bus.ns1438.php';

class error_node__ns21717 extends node__ns21085 {
    protected $_error_node__message_html;
    protected $_error_node__buttons_html;
    
    protected function _base_node__on_init() {
        parent::_base_node__on_init();
        
        $msg_token = $this->get_arg('msg_token');
        $args = recv_msg__ns1438($msg_token, 'error_node__ns21717::args');
        
        if($args && array_key_exists('message', $args)) {
            $message = $args['message'];
        } else {
            $message = '(Неопределённая Ошибка)';
        }
        
        if($args && array_key_exists('next', $args)) {
            $next = $args['next'];
            
            $button = sprintf(
                '<a href="%s">ОК</a>',
                htmlspecialchars($next)
            );
        } else {
            $button = '<a href="?">Начало</a>';
        }
        
        $this->_error_node__message_html = $this->html_from_txt($message);
        
        $this->_error_node__buttons_html = $button;
    }
    
    protected function _node__get_title() {
        $parent_title = parent::_node__get_title();
        
        return 'Ошибка - '.$parent_title;
    }
    
    protected function _node__get_head() {
        $parent_head = parent::_node__get_head();
        
        $html = '';
        
        $html .=
            $parent_head.
            '<link rel="stylesheet" href="/media/error_node/css/style.css" />';
        
        return $html;
    }
    
    protected function _node__get_aside() {
        $button_html = '';
        
        $html = '';
        
        $html .=
            '<div class="SmallFrame">'.
                '<div class="ErrorColor TextAlignCenter">'.
                    $this->_error_node__message_html.
                '</div>'.
                '<div class="ErrorColor TextAlignCenter">'.
                    '<p>'.$this->_error_node__buttons_html.'</p>'.
                '</div>'.
            '</div>';
        
        return $html;
    }
}

