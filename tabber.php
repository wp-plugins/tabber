<?php

/*

Plugin Name: tabber

Plugin URI: http://blueandhack.com/

Description: tabber

Version: 0.9

Author: blueandhack

Author URI: http://blueandhack.com
*/




//引用jquery
wp_enqueue_script( 'jquery' );

function tabber(){
	
	//获取css地址
	$css_tabber = get_bloginfo("wpurl") . '/wp-content/plugins/tabber/css/tabber.css';
	
	$script_tabber = '<script type="text/javascript" src="' . get_bloginfo('wpurl') . '/wp-content/plugins/tabber/js/tabber.js"></script>';
	
	echo "\n" . '<!-- tabber plugin by blueandhack. -->'."\n";
	echo "\n" . '<link rel="stylesheet" href="' . $css_tabber . '" type="text/css" media="screen" />' . $script_tabber . "\n";
	
	}
	
add_action('wp_head', 'tabber');


//Tabber
   
/**
 * Tabber Class
 */
class Tabber extends WP_Widget {
    /** 构造函数 */
    function Tabber() {
        parent::WP_Widget(false, $name = 'Tabber');	
    }

    /** @see WP_Widget::widget */
    function widget($args, $instance) {		
        extract( $args );
        ?>
              <?php echo $before_widget; ?>
                  <?php echo $before_title
                      . $instance['title']
                      . $after_title; ?>
					<div id="sidebar-tab"> 
					<div id="tab-title"> 
					<h3><span class="selected">最新文章</span><span>近期热评</span><span>热门标签</span></h3> 
					</div> 
					<div id="tab-content"> 
					<ul><?php wp_get_archives('type=postbypost&limit=10'); ?></ul> 
					<ul class="hide"><?php
						global $wpdb;
						$my_email = get_bloginfo ('admin_email');
						$rc_comms = $wpdb->get_results("
  							SELECT ID, post_title, comment_ID, comment_author, comment_author_email, comment_content
  							FROM $wpdb->comments LEFT OUTER JOIN $wpdb->posts
  							ON ($wpdb->comments.comment_post_ID = $wpdb->posts.ID)
  							WHERE comment_approved = '1'
  							AND comment_type = ''
  							AND post_password = ''
  							AND comment_author_email != '$my_email'
  							ORDER BY comment_date_gmt
  							DESC LIMIT 5
						");
						$rc_comments = '';
						foreach ($rc_comms as $rc_comm) {
  							//$a = 'avatar/'.md5(strtolower($rc_comm->comment_author_email)).'.jpg'; // 頭像緩存用的
  							$rc_comments .= "<li>" . get_avatar($rc_comm,$size='40',$default='<path_to_url>' ) . "<a href='"
    						. get_permalink($rc_comm->ID) . "#comment-" . $rc_comm->comment_ID
  							//. htmlspecialchars(get_comment_link( $rc_comm->comment_ID, array('type' => 'comment'))) // 可取代上一行, 會顯示 cpage, 但較耗資源
    						. "' title='on " . $rc_comm->post_title . "'>" . strip_tags($rc_comm->comment_content)
    						. "</a></li>\n";
						}
						$rc_comments = convert_smilies($rc_comments);
						echo $rc_comments;
					?></ul> 
					<ul class="hide"><?php wp_tag_cloud('smallest=6&largest=18'); ?></ul> 
					</div> 
					</div> 
              <?php echo $after_widget; ?>
        <?php
    }

    /** @see WP_Widget::update */
    function update($new_instance, $old_instance) {				
        return $new_instance;
    }

    /** @see WP_Widget::form */
    function form($instance) {				
        $title = esc_attr($instance['title']);
        ?>
            <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></label></p>
        <?php 
    }
	
	

} // class Tabber


// 注册 Tabber 挂件
add_action('widgets_init', create_function('', 'return register_widget("Tabber");'));



?>