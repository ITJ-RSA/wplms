<?php

add_action( 'widgets_init', 'vibe_bp_widgets' );


function vibe_bp_widgets() {
    register_widget('vibe_bp_login');
    register_widget('vibe_course_categories'); 
}


/* Creates the widget itself */

if ( !class_exists('vibe_bp_login') ) {
	class vibe_bp_login extends WP_Widget {
	
		function vibe_bp_login() {
			$widget_ops = array( 'classname' => 'vibe-bp-login', 'description' => __( 'Vibe BuddyPress Login', 'vibe' ) );
			$this->WP_Widget( 'vibe_bp_login', __( 'Vibe BuddyPress Login Widget','vibe' ), $widget_ops);
		}
		
		function widget( $args, $instance ) {
			extract( $args );
			
			echo $before_widget;
			
			
			if ( is_user_logged_in() ) :
				do_action( 'bp_before_sidebar_me' ); ?>
				<div id="sidebar-me">
					<div id="bpavatar">
						<?php bp_loggedin_user_avatar( 'type=full' ); ?>
					</div>
					<ul>
						<li id="username"><a href="<?php bp_loggedin_user_link(); ?>"><?php bp_loggedin_user_fullname(); ?></a></li>
						<li><a href="<?php echo bp_loggedin_user_domain() . BP_XPROFILE_SLUG ?>/" title="<?php _e('View profile','vibe'); ?>"><?php _e('View profile','vibe'); ?></a></li>
						<li id="vbplogout"><a href="<?php echo wp_logout_url( get_permalink() ); ?>" rel="nofollow" class="logout" title="<?php _e( 'Log Out','vibe' ); ?>"><i class="icon-close-off-2"></i> <?php _e('LOGOUT','vibe'); ?></a></li>
						<li id="admin_panel_icon"><?php if (current_user_can("edit_posts"))
					       echo '<a href="'.vibe_site_url() .'wp-admin/" title="'.__('Access admin panel','vibe').'"><i class="icon-settings-1"></i></a>'; ?>
					  </li>
					</ul>	
					<ul>
						<li><a href="<?php echo bp_loggedin_user_domain().BP_COURSE_SLUG   ?>/"><i class="icon-book-open-1"></i> <?php _e('Courses','vibe'); ?></a></li>	
						<li><a href="<?php echo bp_loggedin_user_domain().BP_COURSE_SLUG  ?>/course-stats/"><i class="icon-analytics-chart-graph"></i> <?php _e('Stats','vibe'); ?></a></li>	
						
						<?php 
						if ( bp_is_active( 'messages' ) ) : ?>
						<li><a href="<?php echo bp_loggedin_user_domain() . BP_MESSAGES_SLUG ?>/"><i class="icon-letter-mail-1"></i> <?php _e('Inbox','vibe'); if (messages_get_unread_count()) : echo " <span>" . messages_get_unread_count() . "</span>"; endif; ?></a></li>
						<li><a href="<?php echo bp_loggedin_user_domain() . BP_NOTIFICATIONS_SLUG ?>/"><i class="icon-exclamation"></i> <?php _e('Notifications','vibe'); ?>
						<?php $n=vbp_current_user_notification_count(); if ($n) : echo " <span>" . $n . "</span>"; endif; ?></a></li>
						<?php endif;
						
						if ( bp_is_active( 'groups' ) ) : ?>
						<li><a href="<?php echo bp_loggedin_user_domain() . BP_GROUPS_SLUG ?>/"><i class="icon-myspace-alt"></i> <?php _e('Groups','vibe'); ?></a></li>
						<?php endif; ?>
					</ul>
				
				<?php
				do_action( 'bp_sidebar_me' ); ?>
				</div>
				<?php do_action( 'bp_after_sidebar_me' );
			
			/***** If the user is not logged in, show the log form and account creation link *****/
			
			else :
				if(!isset($user_login))$user_login='';
				do_action( 'bp_before_sidebar_login_form' ); ?>
				
				
				<form name="login-form" id="vbp-login-form" class="standard-form" action="<?php echo apply_filters('wplms_login_widget_action',vibe_site_url( 'wp-login.php', 'login-post' )); ?>" method="post">
					<label><?php _e( 'Username', 'vibe' ); ?><br />
					<input type="text" name="log" id="side-user-login" class="input" value="<?php echo esc_attr( stripslashes( $user_login ) ); ?>" /></label>
					
					<label><?php _e( 'Password', 'vibe' ); ?><br />
					<input type="password" name="pwd" id="sidebar-user-pass" class="input" value="" /></label>
					
					<p class=""><label><input name="rememberme" type="checkbox" id="sidebar-rememberme" value="forever" /><?php _e( 'Remember Me', 'vibe' ); ?></label></p>
					
					<?php do_action( 'bp_sidebar_login_form' ); ?>
					<input type="submit" name="wp-submit" id="sidebar-wp-submit" value="<?php _e( 'Log In','vibe' ); ?>" tabindex="100" />
					<input type="hidden" name="testcookie" value="1" />
					<?php if ( bp_get_signup_allowed() ) :
						printf( __( '<a href="%s" class="vbpregister" title="Create an account">'.__( 'Sign Up','vibe' ).'</a> ', 'vibe' ), site_url( BP_REGISTER_SLUG . '/' ) );
					endif; ?>
				</form>
				
				
				<?php do_action( 'bp_after_sidebar_login_form' );
			endif;
			
			echo $after_widget;
		}
		
		/* Updates the widget */
		
		function update( $new_instance, $old_instance ) {
			$instance = $old_instance;
			return $instance;
		}
		
		/* Creates the widget options form */
		
		function form( $instance ) {
			
		}
	
	} 
} 



          
/*======= Vibe Testimonials ======== */  

class vibe_course_categories extends WP_Widget {
 
 
    /** constructor -- name this the same as the class above */
    function vibe_course_categories() {
    $widget_ops = array( 'classname' => 'Course Categories', 'description' => __('Course Categories ', 'vibe') );
    $control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'vibe_course_categories' );
    $this->WP_Widget( 'vibe_course_categories', __('Course Categories', 'vibe'), $widget_ops, $control_ops );
  }
        
 
    /** @see WP_Widget::widget -- do not rename this */
    function widget( $args, $instance ) {
    extract( $args );

    //Our variables from the widget settings.
    $title = apply_filters('widget_title', $instance['title'] );
    $exclude_names = esc_attr($instance['exclude_names']);
	$sort = esc_attr($instance['sort']);
	$order = esc_attr($instance['order']); 
    
    echo $before_widget;

    // Display the widget title 
    if ( $title )
    echo $before_title . $title . $after_title;
    
    $terms = get_terms( 'course-cat', array(
				 	'orderby'    => $order,
				 	'order' => $sort,
				 	'exclude' => $exclude_ids
				 ) );
	if ( !empty( $terms ) && !is_wp_error( $terms ) ){
	     echo '<ul>';
	     foreach ( $terms as $term ) {
	       echo '<li><a href="' . get_term_link( $term ) . '" title="' . sprintf(__('View all Courses in %s', 'vibe'), $term->name) . '">' . $term->name . '</a></li>';
	     }
	     echo '</ul>';
	}     
    echo $after_widget;
                
    }
 
    /** @see WP_Widget::update -- do not rename this */
    function update($new_instance, $old_instance) {   
    $instance = $old_instance;
    $instance['title'] = strip_tags($new_instance['title']);
    $instance['exclude_ids'] = $new_instance['exclude_ids'];
    $instance['sort'] = $new_instance['sort'];
    $instance['order'] = $new_instance['order'];
    
    return $instance;
    }
 
    /** @see WP_Widget::form -- do not rename this */
    function form($instance) {  
        $defaults = array( 
                    'title'  => __('Course Categories','vibe'),
                    'exclude_ids'  => '',
                    'sort'  => 'DESC',
                    'order' => ''
                    );
  		
  		$instance = wp_parse_args( (array) $instance, $defaults );
        $title  = esc_attr($instance['title']);
        $exclude_ids = esc_attr($instance['exclude_ids']);
		$sort = esc_attr($instance['sort']);
		$order = esc_attr($instance['order']);                               
        ?>
         
         <p>
          <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:','vibe'); ?></label> 
          <input class="regular_text" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
        </p>
  		<p>
          <label for="<?php echo $this->get_field_id('order'); ?>"><?php _e('Order by','vibe'); ?></label> 
           <select class="select" name="<?php echo $this->get_field_name('order'); ?>">
           		<option value="name" <?php selected('name',$order); ?>><?php _e('Name','vibe'); ?></option>
           		<option value="slug" <?php selected('slug',$order); ?>><?php _e('Slug','vibe'); ?></option>
           		<option value="count" <?php selected('count',$order); ?>><?php _e('Course Count','vibe'); ?></option>
            </select>
        </p>
        <p>
          <label for="<?php echo $this->get_field_id('sort'); ?>"><?php _e('Sort Order ','vibe'); ?></label> 
           <select class="select" name="<?php echo $this->get_field_name('sort'); ?>">
           		<option value="ASC" <?php selected('ASC',$sort); ?>><?php _e('Ascending','vibe'); ?></option>
           		<option value="DESC" <?php selected('DESC',$sort); ?>><?php _e('Descending','vibe'); ?></option>
            </select>
        </p>
        <p>
          <label for="<?php echo $this->get_field_id('exclude_ids'); ?>"><?php _e('Exclude Course Category Terms Ids (comma saperated):','vibe'); ?></label> 
          <input class="regular_text" id="<?php echo $this->get_field_id('exclude_ids'); ?>" name="<?php echo $this->get_field_name('exclude_ids'); ?>" type="text" value="<?php echo $exclude_ids; ?>" />
        </p>
        
        <?php 
        wp_reset_query();
        wp_reset_postdata();
    }
}