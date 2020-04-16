<?php

    //  Diagnostic utilities (loaded only for developers)

//  error_log("Loaded fnord.php.");

    add_shortcode("fnord", "rb_fnord");

    function rb_fnord($args, $content = "") {
        return("fnord Args: " . print_r($args, TRUE) . " Content: " . $content);

/*
        $txt = 'Link to <a href="http://www.fourmilab.ch/" goober="peas" target="_blank" title="Ants!">Fourmilab</a>';
        $txt .= "\n<ul><li>One</li><li>Two</li><li>Three</li></ul>";

        $result = "<p>\n<b>Input:</b> <code>" . esc_html($txt) . "</code><br />\n";
        
        $out = bp_groups_filter_kses($txt);
        
        $result .= "<b>Output:</b> <code>" . esc_html($out) . "</code><br />\n";
        
        $result .= "</p>\n";
        
        return $result;
*/

    }

?>
