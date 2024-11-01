<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
use Google\Web_Stories\Story_Renderer\HTML;
use Google\Web_Stories\Model\Story;

$current_post = get_post($singlid);

$story = new Story();
$story->load_from_post($current_post);

$args = '';
$html = '';

$showbtn = ($settings['wsae_button'] == "yes") ? 'block' : 'none';

$defaults = [
    'align' => 'none',
    'height' => '600px',
    'width' => '360px',
];
$args = wp_parse_args($args, $defaults);
$align = sprintf('align%s', esc_attr($args['align']));
$url = esc_url($story->get_url());
$title = esc_html($story->get_title());
$poster = !empty($story->get_poster_portrait()) ? esc_url($story->get_poster_portrait()) : '';
$margin = ('center' === $args['align']) ? 'auto' : '0';
// Player style and poster style, both escaped properly.
$player_style = sprintf('margin: %s;width: %s;height: %s', esc_attr($margin), esc_attr($args['width']), esc_attr($args['height']));
$poster_style = !empty($poster) ? sprintf('--story-player-poster: url(%s)', $poster) : '';

if (
    (function_exists('amp_is_request') && amp_is_request()) ||
    (function_exists('is_amp_endpoint') && is_amp_endpoint())
) {
    $player_style = sprintf('margin: %s', esc_attr($margin));

    
}

$imageSrc = esc_url($poster);
$wsae_circle = $settings['wsae_style'] == "circle" ? 'wsae_circle' : '';
if(esc_url($poster) == ""){
$imageSrc = esc_url(WSAE_URL . 'assets/images/default_poster.png');
}

$html .= '<div class="wsae-wrapper wp-block-web-stories-embed ' . esc_attr($wsae_circle) . ' ' . esc_attr($align) . '">';
if($settings['wsae_style'] == "circle"){
    $html .= '   <a href="' . esc_url($url) . '" style="text-decoration:none;"> 
    <div class="borderDiv">
    <img src="' . esc_url($imageSrc) . '" alt="' . esc_attr($title) . '" >
    </div>';
    $html .= '</a>';
}else{
    $html .= '      <amp-story-player class="wsae-amp" >
                        <a href="' . esc_url($url) . '" style="' . esc_attr($poster_style) . '">' . esc_html($title) . '</a>
                    </amp-story-player>
                    <a href="' . esc_url($url) . '" >
                      <button class="wae_btn_setting" style="display:' . esc_attr($showbtn) . ';">' . esc_html($settings['wsae_btn_text']) . '</button>
                    </a>';
                }
$html .= ' </div>';
            
echo $html;