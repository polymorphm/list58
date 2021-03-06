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
require_once dirname(__FILE__).'/class.frame.ns26442.php';
require_once dirname(__FILE__).'/utils/class.mysql_tools.php';

class item_detail_frame__ns13033 extends frame__ns26442 {
    protected $_base_node__need_check_auth = TRUE;
    
    protected $_item_detail_frame__item_id = 0;
    protected $_item_detail_frame__item = NULL;
    protected $_item_detail_frame__item_sex = NULL;
    
    protected $_item_detail_frame__next = NULL;
    protected $_item_detail_frame__next_message = NULL;
    protected $_item_detail_frame__next_message_html = NULL;
    
    protected function _base_node__on_add_check_perms() {
        parent::_base_node__on_add_check_perms();
        
        $this->_base_node__add_check_perms(
            array(
                // требуется разрешение на просмотр Элементов Данных:
                'view_items' => TRUE,
            )
        );
    }
    
    protected function _base_node__on_init() {
        parent::_base_node__on_init();
        
        if(array_key_exists('item_id', $_GET)) {
            $this->_item_detail_frame__item_id = intval($this->get_arg('item_id'));
        } else {
            $this->_base_node__throw_site_error('Недостаточно аргументов');
        }
        
        $msg_token = $this->get_arg('msg_token');
        $args = recv_msg__ns1438($msg_token, 'item_detail_frame__ns13033::args');
        
        if($args && array_key_exists('next', $args)) {
            $this->_item_detail_frame__next = $args['next'];
        }
        if($args && array_key_exists('next_message', $args)) {
            $this->_item_detail_frame__next_message = $args['next_message'];
        }
        if($args && array_key_exists('next_message_html', $args)) {
            $this->_item_detail_frame__next_message_html = $args['next_message_html'];
        }
        
        $result = mysql_query_or_error(
            sprintf(
                'SELECT * FROM `items_base` WHERE NOT IFNULL(`item_deleted`, FALSE) AND `id` = \'%s\'',
                mysql_real_escape_string($this->_item_detail_frame__item_id)
            ),
            $this->_base_node__db_link
        );
        
        $row = mysql_fetch_assoc($result);
        mysql_free_result($result);
        
        if($row) {
            $this->_item_detail_frame__item = $row;
            if($this->_item_detail_frame__item['sex'] == 1) {
                $this->_item_detail_frame__item_sex = 'Мужской';
            } elseif($this->_item_detail_frame__item['sex'] == 2) {
                $this->_item_detail_frame__item_sex = 'Женский';
            }
        } else {
            $this->_base_node__throw_site_error('Данные отсутствуют');
        }
    }
    
    protected function _frame__get_head() {
        $parent_head = parent::_frame__get_head();
        
        $html =
                $parent_head.
                '<link rel="stylesheet" href="/media/item_detail_frame/css/style.css" />';
        
        return $html;
    }
    
    protected function _item_detail_frame__get_next_msg_args() {
        $msg_args = array();
        if($this->_item_detail_frame__next) {
            $msg_args['next'] = $this->_item_detail_frame__next;
        }
        if($this->_item_detail_frame__next_message) {
            $msg_args['next_message'] = $this->_item_detail_frame__next_message;
        }
        if($this->_item_detail_frame__next_message_html) {
            $msg_args['next_message_html'] = $this->_item_detail_frame__next_message_html;
        }
        
        return $msg_args;
    }
    
    protected function _item_detail_frame__get_mod_href() {
        $msg_args = $this->_item_detail_frame__get_next_msg_args();
        $msg_token = send_msg__ns1438('mod_item_node__ns16127::args', $msg_args);
        
        $href = '?'.http_build_query(array(
            'node' => 'mod_item',
            'item_id' => $this->_item_detail_frame__item_id,
            'msg_token' => $msg_token,
        ));
        
        return $href;
    }
    
    protected function _item_detail_frame__get_del_href() {
        $msg_args = $this->_item_detail_frame__get_next_msg_args();
        $msg_token = send_msg__ns1438('del_item_node__ns179::args', $msg_args);
        
        $href = '?'.http_build_query(array(
            'node' => 'del_item',
            'item_id' => $this->_item_detail_frame__item_id,
            'msg_token' => $msg_token,
        ));
        
        return $href;
    }
    
    protected function _item_detail_frame__get_actions_html() {
        $htmls = array();
        
        if($this->_base_node__is_permitted('mod_items')) {
            $htmls []=
                    '<a href="'.htmlspecialchars($this->_item_detail_frame__get_mod_href()).'">'.
                        '<img class="Ico" src="/media/share/img/item_edit_ico.png" alt="Изменить" title="Изменить" /> '.
                        'Изменить'.
                    '</a>';
            $htmls []=
                    '<a href="'.htmlspecialchars($this->_item_detail_frame__get_del_href()).'">'.
                        '<img class="Ico" src="/media/share/img/item_del_ico.png" alt="Удалить" title="Удалить" /> '.
                        'Удалить'.
                    '</a>';
        }
        
        $html = join(', ', $htmls);
        
        return $html;
    }
    
    protected function _frame__get_body() {
        $actions_html = $this->_item_detail_frame__get_actions_html();
        
        $html =
                '<table class="Width100Per Height100Per">'.
                    '<tr>'.
                        '<td class="Padding10Px MarginColor">'.
                            '<h1 class="TextAlignCenter">Полная информация о записи</h1>'.
                        '</td>'.
                    '</tr>'.
                    '<tr>'.
                        '<td class="Padding10Px">'.
                            '<div class="TwoColumns">'.
                                ($this->_item_detail_frame__item['given_name']?'<p><b>Имя:</b> '.
                                        htmlspecialchars($this->_item_detail_frame__item['given_name']).'</p>':'').
                                ($this->_item_detail_frame__item['family_name']?'<p><b>Фамилия:</b> '.
                                        htmlspecialchars($this->_item_detail_frame__item['family_name']).'</p>':'').
                                ($this->_item_detail_frame__item['patronymic_name']?'<p><b>Отчество:</b> '.
                                        htmlspecialchars($this->_item_detail_frame__item['patronymic_name']).'</p>':'').
                                ($this->_item_detail_frame__item['birth_year']?'<p><b>Дата Рождения:</b> '.
                                        htmlspecialchars(sprintf(
                                            '%02s.%02s.%s',
                                            $this->_item_detail_frame__item['birth_day'],
                                            $this->_item_detail_frame__item['birth_month'],
                                            $this->_item_detail_frame__item['birth_year']
                                        )).'</p>':'').
                                ($this->_item_detail_frame__item_sex?'<p><b>Пол:</b> '.
                                        htmlspecialchars($this->_item_detail_frame__item_sex).'</p>':'').
                                ($this->_item_detail_frame__item['passport_ser'] || $this->_item_detail_frame__item['passport_no']?
                                        '<p><b>Паспорт (Серия и Номер):</b> '.
                                        htmlspecialchars(
                                            $this->_item_detail_frame__item['passport_ser'].' '.
                                            $this->_item_detail_frame__item['passport_no']
                                        ).'</p>':'').
                                ($this->_item_detail_frame__item['passport_dep']?'<p><b>Кем выдан паспорт:</b> '.
                                        htmlspecialchars($this->_item_detail_frame__item['passport_dep']).'</p>':'').
                                ($this->_item_detail_frame__item['passport_day']?'<p><b>Дата выдачи паспорта:</b> '.
                                        htmlspecialchars($this->_item_detail_frame__item['passport_day']).'</p>':'').
                                ($this->_item_detail_frame__item['residence_city']?'<p><b>Город:</b> '.
                                        htmlspecialchars($this->_item_detail_frame__item['residence_city']).'</p>':'').
                                ($this->_item_detail_frame__item['residence']?'<p><b>Адрес:</b> '.
                                        htmlspecialchars($this->_item_detail_frame__item['residence']).'</p>':'').
                                ($this->_item_detail_frame__item['phone']?'<p><b>Телефон:</b> '.
                                        htmlspecialchars($this->_item_detail_frame__item['phone']).'</p>':'').
                                ($this->_item_detail_frame__item['phone2']?'<p><b>Дополнительный Телефон:</b> '.
                                        htmlspecialchars($this->_item_detail_frame__item['phone2']).'</p>':'').
                                // TODO: ...
                            '</div>'.
                        '</td>'.
                    '</tr>'.
                    (
                        $this->_item_detail_frame__item['about'] || $this->_item_detail_frame__item['comments']?
                        '<tr>'.
                            '<td>'.
                                '<table class="Height100Per Width100Per">'.
                                    '<tr>'.
                                        (
                                            $this->_item_detail_frame__item['about']?
                                            '<td class="Height100Per Padding10Px">'.
                                                '<p><b>Дополнительное описание:</b></p>'.
                                                '<p class="MarginLeft20Px">'.
                                                    str_replace(
                                                        "\n", '<br />',
                                                        htmlspecialchars(
                                                            $this->_item_detail_frame__item['about']
                                                        )
                                                    ).
                                                '</p>'.
                                            '</td>':
                                            ''
                                        ).
                                        (
                                            $this->_item_detail_frame__item['comments']?
                                            '<td class="Height100Per Padding10Px">'.
                                                '<p><b>Примечание:</b></p>'.
                                                '<p class="MarginLeft20Px">'.
                                                    str_replace(
                                                        "\n", '<br />',
                                                        htmlspecialchars(
                                                            $this->_item_detail_frame__item['comments']
                                                        )
                                                    ).
                                                '</p>'.
                                            '</td>':
                                            ''
                                        ).
                                    '</tr>'.
                                '</table>'.
                            '</td>'.
                        '</tr>':
                        ''
                    ).
                    '<tr><td class="Height100Per"></td></tr>'.
                    (
                        $actions_html?
                        '<tr>'.
                            '<td class="Padding10Px">'.
                                '<b>Действия:</b> '.$this->_item_detail_frame__get_actions_html().
                            '</td>'.
                        '</tr>':
                        ''
                    ).
                    '<tr>'.
                        '<td class="MarginColor">'.
                            '<table class="Width100Per">'.
                                '<tr>'.
                                    '<td class="Padding10Px">'.
                                        '<b>Id:</b> '.
                                                htmlspecialchars($this->_item_detail_frame__item_id).
                                    '</td>'.
                                    '<td class="Padding10Px">'.
                                        '<b>Создал:</b> '.
                                                htmlspecialchars($this->_item_detail_frame__item['item_owner']).
                                    '</td>'.
                                    '<td class="Padding10Px">'.
                                        '<b>Дата создания:</b> '.
                                                htmlspecialchars(@date('r',$this->_item_detail_frame__item['item_created'])).
                                    '</td>'.
                                    '<td class="Padding10Px">'.
                                        '<b>Дата модификации:</b> '.
                                                htmlspecialchars(@date('r',$this->_item_detail_frame__item['item_modified'])).
                                    '</td>'.
                                '</tr>'.
                            '</table>'.
                        '</td>'.
                    '</tr>'.
                '</table>';
        
        return $html;
    }
}

