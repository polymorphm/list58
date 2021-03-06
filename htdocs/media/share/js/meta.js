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

(function() {
    'use strict'
    
    var meta_module_name = '/2010/07/07/List58/share/meta'
    var html_ns = 'http://www.w3.org/1999/xhtml'
    
    function MetaModule() {}
    
    function new_meta_module() {
        var meta_module = new MetaModule
        meta_module.init()
        return meta_module
    }
    
    MetaModule.prototype.init = function() {}
    
    MetaModule.prototype.get_json_params = function(params_name) {
        for(var in_root_node = document.firstChild;
                in_root_node;
                in_root_node = in_root_node.nextSibling) {
            if(in_root_node.nodeType == Node.ELEMENT_NODE &&
                    in_root_node.localName == 'html' &&
                    in_root_node.namespaceURI == html_ns) {
                for(var in_html_node = in_root_node.firstChild;
                        in_html_node;
                        in_html_node = in_html_node.nextSibling) {
                    if(in_html_node.nodeType == Node.ELEMENT_NODE &&
                            in_html_node.localName == 'head' &&
                            in_html_node.namespaceURI == html_ns) {
                        for(var in_head_node = in_html_node.firstChild;
                                in_head_node;
                                in_head_node = in_head_node.nextSibling) {
                            if(in_head_node.nodeType == Node.ELEMENT_NODE) {
                                var in_head_node_name = in_head_node.getAttributeNS('', 'name')
                                var in_head_node_content = in_head_node.getAttributeNS('', 'content')
                                
                                if(in_head_node_name == params_name && in_head_node_content) {
                                    var params_content = JSON.parse(in_head_node_content)
                                    
                                    return params_content
                                }
                            }
                        }
                    }
                }
            }
        }
    }
    
    if(!window[meta_module_name]) {
        window[meta_module_name] = new_meta_module()
    }
})()

