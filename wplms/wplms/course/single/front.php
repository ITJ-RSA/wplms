<?php
global $post;
$id= get_the_ID();

do_action('wplms_course_before_front_main');

if(have_posts()):
while(have_posts()):the_post();
?>

<div class="course_title">
	<?php vibe_breadcrumbs(); ?>
	<h1><?php the_title(); ?></h1>
	<h6><?php the_excerpt(); ?></h6>
</div>
<div class="students_undertaking">
	<?php
	$students_undertaking=array();
	$students_undertaking = bp_course_get_students_undertaking();
	$students=get_post_meta(get_the_ID(),'vibe_students',true);

	echo '<strong>'.$students.__(' STUDENTS ENROLLED','vibe').'</strong>';

	echo '<ul>';
	$i=0;
	foreach($students_undertaking as $student){
		$i++;
		echo '<li>'.get_avatar($student).'</li>';
		if($i>5)
			break;
	}
	echo '</ul>';
	?>
</div>
<?php
do_action('wplms_before_course_description');
?>
<div class="course_description" itemprop="description">
	<div class="small_desc">
	<?php 
		$content=get_the_content(); 
		$middle=strpos( $post->post_content, '<!--more-->' );
		if($middle){
			echo apply_filters('the_content',substr($content, 0, $middle));
		}else{
			if ( !in_array( 'js_composer/js_composer.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
				$limit=apply_filters('wplms_course_excerpt_limit',1200);
				$middle = strrpos(substr($content, 0, $limit), " ");
				echo apply_filters('the_content',substr($content, 0, $middle));
			}else{
				echo apply_filters('the_content',$content);
			}
		}
	?>
	<?php 
		echo '<a href="#" id="more_desc" class="link" data-middle="'.$middle.'">'.__('READ MORE','vibe').'</a>';
	?>
	</div>
	<div class="full_desc">
	<?php 
		echo apply_filters('the_content',substr($content, $middle,-1));
	?>
	<?php 
		echo '<a href="#" id="less_desc" class="link">'.__('LESS','vibe').'</a>';
	?>
	</div>
</div>

<?php
do_action('wplms_after_course_description');
?>

<div class="course_reviews">
<?php
	 comments_template('/course-review.php',true);
?>
</div>

<?php
endwhile;
endif;
?>