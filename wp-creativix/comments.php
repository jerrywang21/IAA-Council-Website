
<?php 
	
        if ( post_password_required() ) { ?>
		<p class="nocomments">This post is password protected. Enter the password to view comments.</p>
	<?php
		return;
	}
        
?>

<!-- You can start editing here. -->
<?php // Begin Comments & Trackbacks ?>
<?php if ( have_comments() ) : ?>
<h3 id="comment-wrap"><?php comments_number('No Comments', 'One Comment', '% Comments' );?> to "<?php the_title(); ?>"</h3>
<div class="pagination">
		<?php paginate_comments_links( array('prev_text' => '&laquo;', 'next_text' => '&raquo;') ) ?>
</div>
<ol class="commentlist">
	<?php wp_list_comments('avatar_size=68'); ?>
</ol>

<?php // End Comments ?>

 <?php else : // this is displayed if there are no comments so far ?>

	<?php if ('open' == $post->comment_status) : ?>
		<!-- If comments are open, but there are no comments. -->

	 <?php elseif($post->post_type != "page") : // comments are closed ?>
		<!-- If comments are closed. -->
		<p>Sorry, the comment form is closed at this time.</p>

	<?php endif; ?>
<?php endif; ?>

<?php if ('open' == $post->comment_status) : ?>

<?php comment_form(); ?>

<?php endif; ?>