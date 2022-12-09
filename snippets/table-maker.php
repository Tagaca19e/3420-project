<?php
function make_table($data, $show_header = true) {
    $table_str = "";
    $table_str .= "<table>";

    foreach($data as $row) {
        if ($show_header) {
            $table_str .= "<tr>";
            foreach($row as $column_name => $column_value) {
                $table_str .= sprintf("<th>%s</th>", $column_name);
            }
            $table_str .= "</tr>";
            $show_header = false;
        }
        $table_str .= "<tr>";
        foreach($row as $column_name => $column_value) {
            $table_str .= sprintf("<td>%s</td>", $column_value);
        }
        $table_str .= "</tr>";
    }
    $table_str .= "</table>";
    return $table_str;
}
?>