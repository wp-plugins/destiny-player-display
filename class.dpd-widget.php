<?php
require_once('class.library.php');
/**
 * @package Rabbitsfoot
 */
/*
Plugin Name: Destiny Player Display
Plugin URI: http://rabbitsfoot.co.uk/
Description: Display Destiny player statistics on Wordpress
Version: 1.2
Author: Rabbits Foot Limited
Author URI: http://rabbitsfoot.co.uk/
License: GPLv2 or later
Text Domain: rabbitsfoot
*/

class Destiny_Player_Display_Widget extends WP_Widget
{
    function __construct()
    {
        define('DPD_VERSION', '1.0');
        define('DPD__PLUGIN_URL', plugin_dir_url(__FILE__));

        wp_register_style('dpd.css', DPD__PLUGIN_URL . '_inc/dpd.css', array(), DPD_VERSION);
        wp_enqueue_style('dpd.css');

        parent::__construct(
            'dpd_widget',
            __('Destiny Player Display', 'dpd_widget_domain'),
            array('description' => __('Display your Destiny player on Wordpress', 'dpd_widget_domain'),)
        );
    }

    function widget($args, $instance)
    {
        $library = new DPD_Library();

        if (isset($instance['username'])) {
            $username = $instance['username'];
        } else {
            $username = __('Enter your Destiny Username', 'dpd_widget_domain');
        }

        if (isset($instance['membership_id'])) {
            $membership_id = $instance['membership_id'];
        } else {
            $membership_id = __('Select your Destiny Character', 'dpd_widget_domain');
        }

        if (isset($instance['platform'])) {
            $platform = $instance['platform'];
        } else {
            $platform = __('Select your platform', 'dpd_widget_domain');
        }

        if (isset($instance['character_id'])) {
            $character_id = $instance['character_id'];
        } else {
            $character_id = __('Select your Destiny Character', 'dpd_widget_domain');
        }

        $character = $library->fetchCharacterForWidget($username, $character_id, $platform);
        $stats = $library->fetchHistoricalStatsForWidget($platform, $membership_id, $character_id);
        $grimore_stats = $library->fetchGrimoireForWidget($platform, $membership_id);

        if($character && $stats && $grimore_stats) {?>
        <div class="widget one-third column">
            <div id="dpd-container">
                <div id="banner-block" style="background-image:url('<?php echo $character['background_path']; ?>');">
                    <div id="emblem">
                        <img id="emblem-img" src="<?php echo $character['emblem_path']; ?>"/>
                        <span id="dpd-span">
                            <p id="username"><?php echo sanitize_text_field($username); ?></p>
                            <p><?php echo implode(' ', $character['details']); ?></p>
                        </span>
                    </div>
                </div>
                <div id="story-block">
                    <?php _e('STORY'); ?>
                </div>
                <div id="top-block">
                    <div id="story-kills-title">
                        <?php _e('KILLS:'); ?>
                    </div>
                    <div id="story-kills">
                        <?php echo $stats['story']['kills'] ?>
                    </div>
                    <div id="story-precision-kills-title">
                        <?php _e('PRECISION KILLS:'); ?>
                    </div>
                    <div id="story-ability-kills-title">
                        <?php _e('ABILITY KILLS:'); ?>
                    </div>
                    <div id="story-precision-kills">
                        <?php echo $stats['story']['precision_kills'] ?>
                    </div>
                    <div id="story-ability-kills">
                        <?php echo $stats['story']['ability_kills'] ?>
                    </div>
                    <div id="story-kill-death-title">
                        <?php _e('KILL/DEATH:'); ?>
                    </div>
                    <div id="story-kill-death">
                        <?php echo $stats['story']['kill_death'] ?>
                    </div>
                </div>
                <div id="pvp-block"><?php _e('CRUCIBLE'); ?>
                </div>
                <div id="bottom-block">
                    <div id="pvp-wins-title">
                        <?php _e('WINS:'); ?>
                    </div>
                    <div id="pvp-wins">
                        <?php echo $stats['crucible']['wins'] ?>
                    </div>
                    <div id="pvp-kills-title">
                        <?php _e('KILLS:'); ?>
                    </div>
                    <div id="pvp-kills">
                        <?php echo $stats['crucible']['kills'] ?>
                    </div>
                    <div id="pvp-precision-kills-title">
                        <?php _e('PRECISION KILLS:'); ?>
                    </div>
                    <div id="pvp-ability-kills-title">
                        <?php _e('ABILITY KILLS:'); ?>
                    </div>
                    <div id="pvp-precision-kills">
                        <?php echo $stats['crucible']['precision_kills'] ?>
                    </div>
                    <div id="pvp-ability-kills">
                        <?php echo $stats['crucible']['ability_kills'] ?>
                    </div>
                    <div id="pvp-kill-death-title">
                        <?php _e('KILL/DEATH:'); ?>
                    </div>
                    <div id="pvp-kill-death">
                        <?php echo $stats['crucible']['kill_death'] ?>
                    </div>
                </div>
                <div id="grimore-title">
                    <?php _e('GRIMORE SCORE'); ?>
                </div>
                <div id="grimore-score">
                    <?php echo $grimore_stats['grimoire_cards_acquired']; ?>
                </div>
            </div>
        </div>
        <?php
        } else {
            ?>
            <div id="failed">
                <h3 id="failed-text"><?php _e('Character Failed To Load</br>Guardian Down')?></h3>
            </div>
            <?php
        }
    }

    function form($instance)
    {
        $library = new DPD_Library();
        $widget_id = esc_attr(str_replace($this->id_base . '-', '', $this->id));

        if (isset($instance['username'])) {
            $username = $instance['username'];
        } else {
            $username = __('Enter your Destiny Username', 'dpd_widget_domain');
        }

        if (isset($instance['platform'])) {
            $platform = $instance['platform'];
        } else {
            $platform = __('Select your platform', 'dpd_widget_domain');
        }

        if (isset($instance['character_id'])) {
            $character_id = $instance['character_id'];
        } else {
            $character_id = __('Select your Destiny Character', 'dpd_widget_domain');
        }

        ?>
        <p>
            <label for="<?php echo $this->get_field_id('username'); ?>"><?php _e('Destiny Username:'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('username'); ?>"
                   name="<?php echo $this->get_field_name('username'); ?>" type="text"
                   value="<?php echo esc_attr($username); ?>"/>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('platform'); ?>"><?php _e('Platform:'); ?></label>
            <select id="<?php echo $this->get_field_id('platform'); ?>"
                    name="<?php echo $this->get_field_name('platform'); ?>">
                <option value="0">Please select your network</option>
                <option value="1" <?php echo($platform == 1 ? 'selected' : '')?>>XBOX</option>
                <option value="2" <?php echo($platform == 2 ? 'selected' : '')?>>PSN</option>
            </select>
        </p>
        <?php $request_failed = false ?>
        <?php if ($username !== 'Enter your Destiny Username' && in_array($platform, array(1, 2))) {
        if ($character_descriptions = $library->fetchCharacterDescriptions($username, $platform)) {
            $membership_id = $library->fetchMembershipId($username, $platform)
            ?>
            <p>
                <label for="<?php echo $this->get_field_id('membership_id'); ?>"><?php _e('Membership ID:'); ?></label>
                <input class="widefat" id="<?php echo $this->get_field_id('membership_id'); ?>"
                       name="<?php echo $this->get_field_name('membership_id'); ?>" type="text"
                       value="<?php echo esc_attr($membership_id); ?>"
                       readonly="readonly"/>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('character_id'); ?>"><?php _e('Character:'); ?></label>
                <select id="<?php echo $this->get_field_id('character_id'); ?>"
                        name="<?php echo $this->get_field_name('character_id'); ?>">
                    <option value="0">Please select a character</option>
                    <?php
                    foreach ($character_descriptions as $character) {
                        $selected = ($character['character_id'] == $character_id) ? 'selected' : '';
                        echo '<option value="' . $character['character_id'] . '" ' . $selected . '>' . $character['description'] . '</option>';
                    }
                    ?>
                </select>
            </p>
            <p>
                <label
                    for="<?php echo $this->get_field_id('widget_id'); ?>"><?php _e('Wordpress Shortcode:'); ?></label>
                <input class="widefat" id="<?php echo $this->get_field_id('widget_id'); ?>"
                       name="<?php echo $this->get_field_name('widget_id'); ?>" type="text"
                       value="<?php echo "[dpd id=" . $widget_id . "]"; ?>"
                       readonly="readonly"/>
            </p>
        <?php
        } else {
            $request_failed = true;
        }
    } else {
        $request_failed = true;
    }?>
        <?php
        if ($request_failed) {
            ?>
            <p class="error-message">
                <strong><?php _e("Couldn't find your Destiny characters. Please ensure your username and platform are correct!") ?></strong>
            </p>
        <?php
        }
    }

    function update($new_instance, $old_instance)
    {
        $instance = array();
        $instance['username'] = (!empty($new_instance['username'])) ? strip_tags($new_instance['username']) : '';
        $instance['membership_id'] = (!empty($new_instance['membership_id'])) ? strip_tags($new_instance['membership_id']) : '';
        $instance['platform'] = (!empty($new_instance['platform'])) ? strip_tags($new_instance['platform']) : '';
        $instance['character_id'] = (!empty($new_instance['character_id'])) ? strip_tags($new_instance['character_id']) : '';

        return $instance;
    }
}

add_shortcode('dpd', function ($args) {
    if ($id = isset($args['id']) ? $args['id'] : false) {
        $widget = new Destiny_Player_Display_Widget();
        $settings = $widget->get_settings();

        if (isset($settings[$id])) {
            ob_start();
            the_widget('Destiny_Player_Display_Widget', $settings[$id]);
            $contents = ob_get_clean();
            return $contents;
        }
    }
    return false;
});

add_action('widgets_init', function () {
    register_widget('Destiny_Player_Display_Widget');
});