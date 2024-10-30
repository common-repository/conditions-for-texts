<?php
/**
 * Plugin Name: Conditions for texts
 * Plugin URI: https://appfield.net/plug-ins/conditions-for-texts/
 * Description: Use if statements with variables to display text blocks only under certain conditions. For example: Publish other text blocks or headlines at christmas time, than in the rest of the year.
 * Version: 1.0.2
 * Author: appfield.net, Frank Burian
 * Author URI: https://www.appfield.net
 * License: GPL2
 *
 * Text Domain: conditions-for-texts 
 */

if (!defined( 'ABSPATH' )) exit; 

function cft_replace_conditionsForTexts($text) {
    $cft = new ConditionsForTexts($text);
    return $cft->doReplace();
}

add_filter('the_content',               'cft_replace_conditionsForTexts', 10, 1);
add_filter('the_title',                 'cft_replace_conditionsForTexts', 10, 1);
add_filter('wp_title',                  'cft_replace_conditionsForTexts', 10, 1);
add_filter('pre_get_document_title',    'cft_replace_conditionsForTexts', 10, 1);
add_filter('wp_head',                   'cft_replace_conditionsForTexts', 10, 1);
add_filter('wpseo_title',               'cft_replace_conditionsForTexts', 10, 1);
add_filter('wpseo_metadesc',            'cft_replace_conditionsForTexts', 10, 1);
add_action('wp_footer',                 'cft_replace_conditionsForTexts', 10, 1);
add_action('widget_text',               'cft_replace_conditionsForTexts', 10, 1);
add_filter('tablepress_table_output',   'cft_replace_conditionsForTexts', 10, 1);
add_filter('do_shortcode_tag',          'cft_replace_conditionsForTexts', 10, 3);

if (!class_exists('ConditionsForTexts')):

class ConditionsForTexts {
  
    public $text      = null;

    private static $regexStatements = '~\[IF\b(?:(?R)|(?:(?!\[\/?IF).))*\[\/IF]~is'; 
    private static $regexConditions = '~(.*)[\s]?(==|>=|<=|!=|>|<|\&gt;=|\&lt;=|contains)[\s]?([a-zA-Z0-9\-\.\$=\%_ \"\']+(?<!\]))~i';
  
    public function __construct($text) {
        $this->text = $text;    
    }
  
    /**
     * Get value of variables
     */  
    public static function getVariableValue($variableName) {
        if ($variableName == '$day')            return date('d'); // 01
        if ($variableName == '$month')          return date('m'); // 01
        if ($variableName == '$year')           return date('Y'); // 2018
        if ($variableName == '$hour')           return date('H'); // 06
        if ($variableName == '$minute')         return date('i'); // 01
        if ($variableName == '$date')           return date('Y-m-d'); // 2018-12-14
        
        return false;
    }

    /**
     * Find "if statements" in text
     */
    public function findIFStatements($text=null) {        
        if (is_null($text)) $text = $this->text;
        // Match all "if statements" in text
        $result = [];
        preg_match_all(self::$regexStatements, $text, $statements, PREG_SET_ORDER);        
        if ($statements) {
            for ($a=0; $a<count($statements); $a++) {                
                if (!empty($statements[$a][0])) {                    
                    $text  = $statements[$a][0];
                    $array = [];
                    $array['fulltext'] = $text;
                    // Get condition
                    preg_match('~\[IF(.*?)\]~', $text, $match);                    
                    if (!empty($match[1])) {
                        // Set condition to temp array, eg. $month == 12
                        $array['condition'] = trim($match[1]);
                        // Remove first [IF] and last [/IF] from text
                        $text = str_replace($match[0], '', $text);
                        $text = substr($text, 0, strrpos($text, "[/IF]"));
                        $textIF = $text;
                        $textELSE = '';                        
                        // Look for child statements                    
                        preg_match_all('~(\[IF(.*)\]((?:(?!\[\/IF\]).)*)\[\/IF\])~', $text, $childIFStatements);
                        if (!empty($childIFStatements[0])) {
                            $array['childs'] = $this->findIFStatements($text);    
                        }
                             
                        // Has ELSE-Condition                        
                        if ($elsePositon = strpos($text,'[ELSE]')) {
                            $tempText = $text;
                            if (isset($array['childs']) && $array['childs']) {
                                for ($b=0; $b<count($array['childs']); $b++) {
                                    $tempText = str_replace($array['childs'][$b]['fulltext'], str_replace(['[',']'], ['{','}'], $array['childs'][$b]['fulltext']), $tempText);
                                }
                                $elsePositon = strpos($tempText,'[ELSE]');
                            } 
                            $textIF = substr($text, 0, $elsePositon);
                            $textELSE = substr($text, $elsePositon+strlen('[ELSE]'));                            
                        }                        
                                               
                        $array['textIF'] = $textIF;
                        $array['textELSE'] = $textELSE;                        
                        
                        $result[] = $array;
                    }
                }
            }            
        }
        return $result;
    }    
    
    /**
     * Replace IF-Statements in text
     */    
    public function doReplace($childStatments=null) {
        $statements = is_null($childStatments) ? $this->findIFStatements() : $childStatments;
        for ($a=0; $a<count($statements); $a++) {
            // Has child IF-Statements
            if (isset($statements[$a]['childs'])) {
                $childStatement = $this->doReplace($statements[$a]['childs']);
                $statements[$a]['childs'] = $childStatement;
                for ($b=0; $b<count($childStatement); $b++) {
                    $statements[$a]['textIF'] = str_replace($childStatement[$b]['fulltext'], $childStatement[$b]['replaceText'], $statements[$a]['textIF']);
                    $statements[$a]['textELSE'] = str_replace($childStatement[$b]['fulltext'], $childStatement[$b]['replaceText'], $statements[$a]['textELSE']);
                }                
            }
            // Check condition
            $conditionValue = $this->checkCondition($statements[$a]['condition']);
            // Condition is true
            if ($conditionValue === true) {
                $statements[$a]['replaceText'] = str_replace($statements[$a]['fulltext'], $statements[$a]['textIF'], $statements[$a]['fulltext']);
            }
            // Condition is false
            elseif ($conditionValue === false) {
                $statements[$a]['replaceText'] = str_replace($statements[$a]['fulltext'], $statements[$a]['textELSE'], $statements[$a]['fulltext']);
            }            
        }
        // Replace text at the end
        if ($childStatments == null) {
            for ($a=0; $a<count($statements); $a++) {
                $this->text = str_replace($statements[$a]['fulltext'], $statements[$a]['replaceText'], $this->text);
            }
            return $this->text;
        }
        return $statements;
    }
    
    /**
     * Check condition of IF-Statements
     */
    public function checkCondition($conditionString) {
        // Match condition with operator definition, like [IF $month == 12]
        $conditions = preg_split("/(==|>=|<=|!=|>|<|\&gt;=|\&lt;=|contains)/", trim($conditionString));
        if (count($conditions)==2) {
            $conditionResult  = false;
            $leftValue  = trim($conditions[0]);
            $rightValue = trim($conditions[1]);
            preg_match("/(==|>=|<=|!=|>|<|\&gt;=|\&lt;=|contains)/", trim($conditionString), $operator);
            $operator = $operator[0];                        
            // Modified in types
            if (trim($leftValue)) {
                if ($leftValue == 'true')   $leftValue = true;         
                if ($leftValue == 'false')  $leftValue = false;         
                if (!is_numeric($leftValue) && !is_bool($leftValue) && !is_null($leftValue)) $leftValue = (string)$leftValue;
                if (is_string($leftValue) && preg_match('~^["|\'|„|“|`|‚|‘](.*)["|\'|„|“|`|‚|‘]$~', $leftValue)) $leftValue = substr($leftValue, 1, -1);
            } else $leftValue == null;
            if (trim($rightValue)) {
                if ($rightValue == 'true')  $rightValue = true;         
                if ($rightValue == 'false') $rightValue = false;
                if (!is_numeric($rightValue) && !is_bool($rightValue) && !is_null($rightValue)) $rightValue = (string)$rightValue;
                if (is_string($rightValue) && preg_match('~^["|\'|„|“|`|‚|‘](.*)["|\'|„|“|`|‚|‘]$~', $rightValue)) $rightValue = substr($rightValue, 1, -1);
            } else $rightValue == null;            
            // Is a variable, get value of it
            if (preg_match('~(\$[a-zA-Z0-9]+)~', $leftValue))  $leftValue  = self::getVariableValue($leftValue);
            if (preg_match('~(\$[a-zA-Z0-9]+)~', $rightValue)) $rightValue = self::getVariableValue($rightValue);
            // Check if condition matched for the current if statement
            if (
                (($operator == '==' OR $operator == '=') && $leftValue == $rightValue) ||
                ($operator == '!=' && $leftValue != $rightValue) ||
                (($operator == '>=' OR $operator == '&gt;=') && $leftValue >= $rightValue) ||
                (($operator == '<=' OR  $operator == '&lt;=') && $leftValue <= $rightValue) ||
                ($operator == '>'  && $leftValue > $rightValue)  ||
                ($operator == '<'  && $leftValue < $rightValue) ||
                ($operator == 'contains' && preg_match('~'.preg_quote($rightValue, '~').'~i', $leftValue))
            ) {
                $conditionResult = true;
            }
            return $conditionResult;
        } else
        // If not condition found, like [IF ]
        if (!trim($conditionString)) {        
            return false;        
        } else
        // If condition found, but without operator, like [IF someOutputExists]
        if (!preg_match('~(==|>=|<=|!=|>|<|\&gt;=|\&lt;=|contains)~', $conditionString)) {
            return true;
        }        
        return null;
    }        
}

endif;
