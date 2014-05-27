<?php

/**
 * Use filter in tables list
 * @author Jakub Vrana, http://www.vrana.cz/ & Michal Brašna
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License, version 2 (one or other)
 */
class AdminerTablesFilter
{

        function tablesPrint($tables)
        {
                echo "<p class='jsonly'><input id='table_filter'></p>";
                echo "<p id='tables'>\n";
                foreach ($tables as $table => $type) {
                        echo '<span><a href="' . h(ME) . 'select=' . urlencode($table) . '"' . bold($_GET["select"] == $table) . ">vypsat</a> ";
                        echo '<a href="' . h(ME) . 'table=' . urlencode($table) . '"' . bold($_GET["table"] == $table) . ">" . h($table) . "</a><br></span>\n";
                }
                ?>

                <script type="text/javascript">
                        function debounce(func, threshold) {
                                var timeout;
                                return function debounced() {
                                        var obj = this, args = arguments;

                                        if (timeout) {
                                                clearTimeout(timeout);
                                        }

                                        timeout = setTimeout(function () {
                                                func.apply(obj, args);
                                                timeout = null;
                                        }, threshold || 100);
                                };

                        }

                        function tablesFilter(value) {
                                var tables = document.getElementById('tables').getElementsByTagName('span');
                                for (var i = tables.length; i--; ) {
                                        var a = tables[i].children[1];
                                        var text = a.innerText || a.textContent;
                                        a.innerHTML = text.replace(value, '<u>' + value + '</u>');
                                        tables[i].className = (text.indexOf(value) == -1 ? 'hidden' : '');
                                }
                        }

                        document.getElementById("table_filter").onkeyup = debounce(function () {
                                tablesFilter(this.value);
                        }, 350);
                </script>
                <?php

                return true;
        }

}

