<?php
/*
Plugin Name: Remove Stop Words
Plugin URI: http://kaneandre.ws
Description: Remove stop words from the slug URL in your permalinks for search engine optimization.
Version: 1.2
Author: Kane Andrews
Author URI: http://kaneandre.ws
License: GPL2
*/

add_action('admin_init', 'remove_stop_words_init' );
add_action('admin_menu', 'register_remove_stop_words_menu_page');

add_filter('post_link', 'remove_stop_words');
add_filter('wp_unique_post_slug', 'remove_stop_words');
add_filter('sanitize_title', 'remove_stop_words');

// Init plugin options to white list our options
function remove_stop_words_init(){
	register_setting( 'remove_stop_words', 'remove_stop_words');
}

function register_remove_stop_words_menu_page() {
	add_options_page('Remove Stop Words', 'Remove Stop Words', 'manage_options', 'remove_stop_words', 'remove_stop_words_admin');
}

function remove_stop_words_admin() {
	?>
	<div class="wrap">
	<?php screen_icon(); ?>
	<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
	<p>You can use a predefined list of stop words from <a href="http://kaneandre.ws/wp-content/uploads/stop-words.txt">here</a>.</p>
	<form method="post" action="options.php">
        <?php settings_fields( 'remove_stop_words' ); ?>
        <table class="form-table">
            <tr valign="top">
                <th scope="row">Word list (comma separated)</th>
                <td><textarea rows="4" cols="50" name="remove_stop_words"/><?php echo sanitize_text_field( get_option('remove_stop_words') ); ?> </textarea> </td>
            </tr>
        </table>
        <?php submit_button(); ?>
    </form>
</div>
<?php
}

function remove_stop_words($slug) {
	// List of stop words comma separated
	$keys_false = sanitize_text_field( get_option('remove_stop_words') );
	$slug = explode('-', $slug);
	foreach ($slug as $k => $word) {
		$keys = explode(', ', $keys_false);		
		foreach ($keys as $l => $wordfalse) {
			if ($word==$wordfalse) {
				unset($slug[$k]);
			}
		}
	}    
	$slug = implode('-', $slug);
	return $slug;
} 
?>