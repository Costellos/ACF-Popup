<?php
/**
* Plugin Name: ACF Popup
* Plugin URI: https://www.costellocoding.com/wp/plugins/acf-popup
* Description: Plugin that uses Advacned Custom Fields Pro to control a Global Popup
* Version: 1.0.0
* Author: Steven Costello
* Author URI: https://www.costellocoding.com
**/



function acf_popup_scripts() {
	$plugin_data = get_plugin_data( __FILE__ );
	$plugin_version = $plugin_data['Version'];
	wp_enqueue_style( 'acf_popup_style', plugin_dir_url(__FILE__).'css/style.min.css', '', $plugin_version);
	
	wp_register_script('magnific_popup', plugin_dir_url(__FILE__) . 'js/jquery.magnific-popup.min.js' , array('jquery'), '1.1.0', true );
	wp_register_script('acf_popup_script', plugin_dir_url(__FILE__) . 'js/acf_popup.min.js' , array('jquery','magnific_popup'), $plugin_version, true );
}

add_action('wp_enqueue_scripts', 'callback_for_setting_up_scripts');

add_action('acf/init', 'my_acf_op_init');

function acf_popup_opt() {
	//Add Option Pagaes
	include_once('admin/acf_options.php');
	//Add Fields to Option Pages
	include_once('admin/acf_fields.php');
}



function acf_popup_content() {
	//Popup Options Variables
	$popup_enabled = get_field('popup_enabled','options');

	if($popup_enabled){

		wp_enqueue_style('acf_popup_style');
		wp_enqueue_script('magnific_popup');
		wp_enqueue_script('acf_popup_script');

		$popup_cookies  = get_field('popup_cookies','options');
		if($popup_cookies){
			$add_something_nonce = wp_create_nonce( "acf_pupup_cookies" );
			$acf_popup_use_cookies = $popup_cookies;
			$acf_popup_cookie_timeout = get_field('popup_cookie_timeout','options');
			$acf_popup_cookie_name = get_field('popup_cookie_name','options');

			wp_localize_script('acf_popup_script', 'acf_popup_object', 
				array( 
					'acf_popup_use_cookies' => $acf_popup_use_cookies,
					'acf_popup_cookie_timeout'=> $acf_popup_cookie_timeout,
					'acf_popup_cookie_name' => $acf_popup_cookie_name,
					'add_something_nonce' => $add_something_nonce
				) 
			);
		}
		
		$popup_method = get_field('popup_method','options');
		if($popup_method == 'Specific Pages'){
			$popup_pages = get_field('popup_pages','options');
		}

		$popup_animation = get_field('popup_animation', 'options');
		


		//Popup Content Variables
		$popup_title = get_field('popup_title','options');
		$popup_text = get_field('popup_text','options');
		$popup_image = get_field('popup_image','options');
		if(!empty($popup_image)){
			$popup_img_tempalte = '<img src="'.esc_url($popup_image['url']).'" alt="'.esc_attr($popup_image['alt']).'" />';
		}


		$popup_button_text = get_field('popup_button_text','options');

		$popup_link_type = get_field('popup_link_type','options');
		if($popup_link_type == 'inp'){
			$popup_btn_link_target = '_self';
			$popup_link =  get_field('page_link','options');
		}elseif($popup_link_type == 'inl'){
			$popup_btn_link_target = '_self';
			$popup_link = get_field('popup_link','options');
		}elseif($popup_link_type == 'ex'){
			$popup_btn_link_target = '_blank';
			$popup_link = get_field('popup_link','options');
		}else{
			$popup_btn_link_target = '_self';
			$popup_link = get_field('popup_link','options');
		}

		if($popup_link){
			$popup_link_template = '<a href="'.$popup_link.'" target="'.$popup_btn_link_target.'">';
		}

		//Template Start
		$template = '';
		
		//Hidden Trigger Button
		$template .= '<a href="#page_popup" class="page_popup_btn" data-effect="'.$popup_animation.'">Page Popup</a>';

		$template .= '<div id="page_popup" class="white-popup mfp-with-anim mfp-hide">';

		//Add Title
		if($popup_title){
			$template .= '<div class="page_popup_title_con">'.$popup_title.'</div>';
		}
		
		//Add Text
		if($popup_text){
			$template .= '<div class="page_popup_text_con">'.$popup_text.'</div>';
		}

		//Add Image
		if(!empty($popup_image)){
			if($popup_link){
				$template .= '<div class="page_popup_img_con">'.$popup_link_template.''.$popup_img_tempalte.'</div>';
			}else{
				$template .= '<div class="page_popup_img_con">'.$popup_img_tempalte.'</div>';
			}
		}

		//Add Button
		if($popup_link){
			$template .= '<div class="page_popup_btn_con">'.$popup_link_template.''.$popup_button_text.'</a></div>';
		}

		//Template End
		$template .= '</div>';

		if($popup_method == 'Specific Pages'){
			$cur_page_id = get_the_ID();
			$is_on_page = in_array($cur_page_id, $popup_pages, true);

			if($is_on_page){
				echo($template);
			}
		}else{
			echo($template);
		}
	}
}


add_action( 'wp_footer', 'acf_popup_content' );