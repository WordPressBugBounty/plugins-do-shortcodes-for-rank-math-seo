<?php

/**
 * DoShortcodesRankMathSEO Class
 *
 * The main class for the plugin Do Shortcodes for Rank Math SEO
 *
 * @author     Denra.com aka SoftShop Ltd <support@denra.com>
 * @copyright  2020 Denra.com aka SoftShop Ltd
 * @license    GPLv2 or later
 * @version    1.3.2
 * @link       https://www.denra.com/
 */

namespace Denra\Plugins;

class DoShortcodesRankMathSEO extends Plugin {
    
    public function  __construct($id, $data = []) {
        
        // Set text_domain for the framework
        $this->text_domain = 'denra-do-sc-rank-math-seo';
        
        // Set admin menus texts
        $this->admin_title_menu = \__('Do Shortcodes for Rank Math SEO', 'denra-do-sc-rank-math-seo');
        
        $this->settings_default['do_shortcodes'] = [
            'rank_math_seo_title' => 1,
            'rank_math_seo_description' => 1,
            'rank_math_seo_schema' => 1,
            'rank_math_seo_opengraph_facebook_title' => 1,
            'rank_math_seo_opengraph_facebook_description' => 1,
            'rank_math_seo_opengraph_twitter_title' => 1,
            'rank_math_seo_opengraph_twitter_description' => 1,
            'wp_title' => 0,
            'the_content' => 0,
            'nav_menu' => 0,
            'widget' => 0
        ];
        $this->settings_default['filters_priority'] = 10;
        
        parent::__construct($id, $data);
        
        if (class_exists('RankMath')) {
            add_action('init', [$this, 'hookInitDoShortcodesRankMathSEO']);
        }

    }
    
    public function hookInitDoShortcodesRankMathSEO() {
        
        if ($this->settings['do_shortcodes']['rank_math_seo_title']) {
            \add_filter('rank_math/frontend/title', [$this, 'doShortcodes'], $this->settings['filters_priority']);
        }
        
        if ($this->settings['do_shortcodes']['rank_math_seo_description']) {
            \add_filter('rank_math/frontend/description', [$this, 'doShortcodes'], $this->settings['filters_priority']);
        }
        
        if ($this->settings['do_shortcodes']['rank_math_seo_schema']) {
            
            $types = \RankMath\Helper::choices_rich_snippet_types();
            foreach(array_keys($types) as $type) {
               \add_filter('rank_math/snippet/rich_snippet_'.$type.'_entity', [$this, 'doShortcodesSchema'], $this->settings['filters_priority']);
            }
            
            \add_filter('rank_math/json_ld', [$this, 'doShortcodesSchema'], PHP_INT_MAX, 2);
        }
                
        if ($this->settings['do_shortcodes']['rank_math_seo_opengraph_facebook_title']) {
             \add_filter('rank_math/opengraph/facebook/og_title', [$this, 'doShortcodes'], $this->settings['filters_priority']);        }
        
        if ($this->settings['do_shortcodes']['rank_math_seo_opengraph_facebook_description']) {
            \add_filter('rank_math/opengraph/facebook/og_description', [$this, 'doShortcodes'], $this->settings['filters_priority']);
        }
        
        if ($this->settings['do_shortcodes']['rank_math_seo_opengraph_twitter_title']) {
            \add_filter('rank_math/opengraph/twitter/twitter_title', [$this, 'doShortcodes'], $this->settings['filters_priority']);
        }
        
        if ($this->settings['do_shortcodes']['rank_math_seo_opengraph_twitter_description']) {
            \add_filter('rank_math/opengraph/twitter/twitter_description', [$this, 'doShortcodes'], $this->settings['filters_priority']);
        }
        
        if ($this->settings['do_shortcodes']['wp_title']) {
            \add_filter('wp_title', 'do_shortcode', $this->settings['filters_priority']);
            \add_filter('the_title', 'do_shortcode', $this->settings['filters_priority']);
        }
        
        if ($this->settings['do_shortcodes']['the_content']) {
            \add_filter('the_content', 'do_shortcode', $this->settings['filters_priority']);
        }
        
        if ($this->settings['do_shortcodes']['nav_menu']) {
            \add_filter('walker_nav_menu_start_el', 'do_shortcode', $this->settings['filters_priority']);
        }
        
        if ($this->settings['do_shortcodes']['widget']) {
            \add_filter('widget_title', [$this, 'doShortcodes'], $this->settings['filters_priority'], 3);
            \add_filter('widget_text', 'do_shortcode', $this->settings['filters_priority'], 3);
            \add_filter('widget_custom_html_content', 'do_shortcode', $this->settings['filters_priority'], 3);
        }
    }
    
    public function doShortcodes($content) {
        $is_url = filter_var( $content, FILTER_VALIDATE_URL );
        if ( ! $is_url ) {
            $content = htmlspecialchars_decode($content);
        }
        $content = \do_shortcode($content);
        if ( ! $is_url ) {
            $content = htmlspecialchars($content);
        }
        return $content;
    }
    
    public function doShortcodesSchema($entity) {
        if (is_array($entity)) {
            foreach($entity as $key => $value) {
                if (is_array($value)) {
                    $entity[$key] = $this->doShortcodesSchema($value);
                }
                else {
                    $entity[$key] = $this->doShortcodes($value);
                }
            }
        }
        return $entity;
    }
    
    public function adminSettingsContent() {
        
        echo '<fieldset>';
        echo '<legend>' .    \__('Do shortcodes for meta title and description', 'denra-do-sc-rank-math-seo') . '</legend>';
        echo '<label for="rank_math_seo_title"><input id="rank_math_seo_title" name="rank_math_seo_title" type="checkbox" value="1"' . ($this->settings['do_shortcodes']['rank_math_seo_title'] ? ' checked' : '') . ' /> ' .    \__('Meta Title', 'denra-do-sc-rank-math-seo') . '</label>';
        echo '<label for="rank_math_seo_description"><input id="rank_math_seo_description" name="rank_math_seo_description" type="checkbox" value="1"' . ($this->settings['do_shortcodes']['rank_math_seo_description'] ? ' checked' : '') . ' /> ' .    \__('Meta Description', 'denra-do-sc-rank-math-seo') . '</label>';
        echo '<label for="rank_math_seo_schema"><input id="rank_math_seo_schema" name="rank_math_seo_schema" type="checkbox" value="1"' . ($this->settings['do_shortcodes']['rank_math_seo_schema'] ? ' checked' : '') . ' /> ' .    \__('In Schema', 'denra-do-sc-rank-math-seo') . '</label>';
        echo '</fieldset>';
        
        echo '<fieldset>';
        echo '<legend>' .    \__('Do shortcodes for Facebook (Open Graph)', 'denra-do-sc-rank-math-seo') . '</legend>';
        echo '<label for="rank_math_seo_opengraph_facebook_title"><input id="rank_math_seo_opengraph_facebook_title" name="rank_math_seo_opengraph_facebook_title" type="checkbox" value="1"' . ($this->settings['do_shortcodes']['rank_math_seo_opengraph_facebook_title'] ? ' checked' : '') . ' /> ' .    \__('Facebook Title', 'denra-do-sc-rank-math-seo') . '</label>';
        echo '<label for="rank_math_seo_opengraph_facebook_description"><input id="rank_math_seo_opengraph_facebook_description" name="rank_math_seo_opengraph_facebook_description" type="checkbox" value="1"' . ($this->settings['do_shortcodes']['rank_math_seo_opengraph_facebook_description'] ? ' checked' : '') . ' /> ' .    \__('Facebook Description', 'denra-do-sc-rank-math-seo') . '</label>';
        echo '</fieldset>';
        
        echo '<fieldset>';
        echo '<legend>'  .    \__('Do shortcodes for Twitter', 'denra-do-sc-rank-math-seo') . '<sup>1</sup></legend>';
        echo '<label for="rank_math_seo_opengraph_twitter_title"><input id="rank_math_seo_opengraph_twitter_title" name="rank_math_seo_opengraph_twitter_title" type="checkbox" value="1"' . ($this->settings['do_shortcodes']['rank_math_seo_opengraph_twitter_title'] ? ' checked' : '') . ' /> ' .    \__('Twitter Title', 'denra-do-sc-rank-math-seo') . '</label>';
        echo '<label for="rank_math_seo_opengraph_twitter_description"><input id="rank_math_seo_opengraph_twitter_description" name="rank_math_seo_opengraph_twitter_description" type="checkbox" value="1"' . ($this->settings['do_shortcodes']['rank_math_seo_opengraph_twitter_description'] ? ' checked' : '') . ' /> ' .    \__('Twitter Description', 'denra-do-sc-rank-math-seo') . '</label>';
        echo '</fieldset>';
        
        echo '<fieldset>';
        echo '<legend>'  .    \__('Do shortcodes in other locations', 'denra-do-sc-rank-math-seo') . '<sup>2</sup></legend>';
        echo '<label for="wp_title"><input id="wp_title" name="wp_title" type="checkbox" value="1"' . ($this->settings['do_shortcodes']['wp_title'] ? ' checked' : '') . ' /> ' .    \__('Post/Page Title', 'denra-do-sc-rank-math-seo') . '</label>';
        echo '<label for="the_content"><input id="the_content" name="the_content" type="checkbox" value="1"' . ($this->settings['do_shortcodes']['the_content'] ? ' checked' : '') . ' /> ' .    \__('Post/Page Content', 'denra-do-sc-rank-math-seo') . '</label>';
        echo '<label for="nav_menu"><input id="nav_menu" name="nav_menu" type="checkbox" value="1"' . ($this->settings['do_shortcodes']['nav_menu'] ? ' checked' : '') . ' /> ' .    \__('Navigation Menu', 'denra-do-sc-rank-math-seo') . '</label>';
        echo '<label for="widget"><input id="widget" name="widget" type="checkbox" value="1"' . ($this->settings['do_shortcodes']['widget'] ? ' checked' : '') . ' /> ' .    \__('Widget Title and Content', 'denra-do-sc-rank-math-seo') . '</label>';
        echo '</fieldset>';
        
        echo '<fieldset>';
        echo '<legend>'  .    \__('Filters\' Priority', 'denra-do-sc-rank-math-seo') . '<sup>3</sup></legend>';
        echo '<label for="filters_priority">' .    \__('Change filters\' priority:', 'denra-do-sc-rank-math-seo') . ' <input id="filters_priority" name="filters_priority" type="number" min="1" max="999999999" size="12" maxlength="9" value="' . $this->settings['filters_priority']. '" /> (1 - 999999999)</label>';
        echo '</fieldset>';
        
        echo '<p><sup>1</sup> '.    \__('Works when separate Twitter data is entered. Does not work when Facebook data is used.', 'denra-do-sc-rank-math-seo');
        echo '<br><sup>2</sup> '.    \__('In case they do not work already.', 'denra-do-sc-rank-math-seo');
        echo '<br><sup>3</sup> '.    \__('In case the filters do not work. Higher is better.', 'denra-do-sc-rank-math-seo') . '</p>';
        
        parent::adminSettingsContent();

    }
    
    public function adminSettingsProcessing() {
        
        parent::adminSettingsProcessing();
        
        foreach (array_keys($this->settings_default['do_shortcodes']) as $key) {
            $this->settings['do_shortcodes'][$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_NUMBER_INT);
        }
        $this->settings['filters_priority'] = abs(filter_input(INPUT_POST, 'filters_priority', FILTER_SANITIZE_NUMBER_INT));
        if ($this->settings['filters_priority'] < 10) {
            $this->settings['filters_priority'] = 10;
        }
        if ($this->settings['filters_priority'] > 999999999) {
            $this->settings['filters_priority'] = 999999999;
        }

    }
}
