<?php
/**
 * Created by IntelliJ IDEA.
 * User: jck
 * Date: 24/03/2014
 * Time: 14:09
 */
?>

<h2>Instajet Aircraft Attributes</h2>
<div class="wrap">



<?php

$loop = new WP_Query( array( 'post_type' => 'aircraft', 'posts_per_page' => -10 ) ); ?>

<table class="wp-list-table widefat fixed posts" cellspacing="0">
	<thead>
	<tr>
		<th scope="col" id="cb" class="manage-column column-cb check-column" style="">
			<label class="screen-reader-text" for="cb-select-all-1">Select All</label>
			<input id="cb-select-all-1" type="checkbox">
		</th>

		<th scope="col" id="title" class="manage-column column-title sortable desc">
			<a href="?orderby=title&amp;order=asc"><span>Aircraft</span><span class="sorting-indicator"></span></a>
		</th>

		<th scope="col" id="author">Author</th>
		<th scope="col" id="jetcategory">Jet Category</th>


		<th scope="col" id="cruise_speed">Cruise Speed</th>
		<th scope="col" id="max_passengers">Max Passengers</th>
		<th scope="col" id="max_range">Max Range</th>
		<th scope="col" id="cost_per_hour">Cost Per Hour</th>

		<th scope="col" id="base_cost">Base Cost</th>

		<th scope="col" id="date" class="manage-column column-date sortable asc" style="">
			<a href="?orderby=date&amp;order=desc"><span>Date</span><span class="sorting-indicator"></span></a></th>
	</tr>
	</thead>

	<tfoot>
	<tr>
		<th scope="col" class="manage-column column-cb check-column" style=""><label class="screen-reader-text" for="cb-select-all-2">Select All</label><input id="cb-select-all-2" type="checkbox"></th>

		<th scope="col" class="manage-column column-title sortable desc" style=""><a href="?orderby=title&amp;order=asc"><span>Aircraft</span><span class="sorting-indicator"></span></a></th>
		<th scope="col" class="manage-column column-author" style="">Author</th>
		<th scope="col" class="manage-column column-categories" style="">Jet Category</th>
		<th scope="col" id="cruise_speed">Cruise Speed</th>
		<th scope="col" id="max_passengers">Max Passengers</th>
		<th scope="col" id="max_range">Max Range</th>
		<th scope="col" id="cost_per_hour">Cost Per Hour</th>
		<th scope="col" id="base_cost">Base Cost</th>
		<th scope="col" class="manage-column column-date sortable asc" style=""><a href="?orderby=date&amp;order=desc"><span>Date</span><span class="sorting-indicator"></span></a></th>	</tr>
	</tfoot>

	<tbody id="the-list">
		
		<?php while ($loop->have_posts()) : $loop->the_post();

		// wp_get_post_categories( the_ID(), $args );

		?>

		<tr id="post-<?php the_ID(); ?>" class="post-<?php the_ID(); ?> type-post status-publish format-standard hentry category-uncategorised alternate iedit author-self level-0" valign="top">
				
				<th scope="row" class="check-column">
					<label class="screen-reader-text" for="cb-select-1">Select <?php the_title(); ?></label>
					<input id="cb-select-1" type="checkbox" name="post[]" value="1">
					<div class="locked-indicator"></div>
				</th>

			<td class="post-title page-title column-title">
				
				<strong><a class="row-title" href="action=edit" title="Edit “<?php the_title(); ?>”"><?php the_title(); ?></a></strong>
				
				<div class="locked-info"><span class="locked-avatar"></span> <span class="locked-text"></span></div>
				
				<div class="row-actions">
					<span class="edit"><a href="/post.php?post=<?php the_ID(); ?>&amp;action=edit" title="Edit this item">Edit</a> | </span>
					<span class="inline hide-if-no-js"><a href="#" class="editinline" title="Edit this item inline">Quick&nbsp;Edit</a> | </span>
					<span class="trash"><a class="submitdelete" title="Move this item to the Rubbish Bin" href="action=trash&amp;_wpnonce=495e8446b7">Bin</a> | </span>
					<span class="view"><a href="http://localhost/wordpress/hello-world/" title="View “<?php the_title(); ?>”" rel="permalink">View</a></span>
				</div>
						
						<div class="hidden" id="inline_1">
								<div class="post_title"><?php the_title(); ?></div>
								<div class="post_name">hello-world</div>
								<div class="post_author">1</div>
								<div class="comment_status">open</div>
								<div class="ping_status">open</div>
								<div class="_status">publish</div>
								<div class="jj">15</div>
								<div class="mm">08</div>
								<div class="aa">2013</div>
								<div class="hh">08</div>
								<div class="mn">28</div>
								<div class="ss">09</div>
								<div class="post_password"></div>

								<div class="post_category" id="category_1">1</div>
								<div class="tags_input" id="post_tag_1"></div>
								<div class="sticky"></div>
								<div class="post_format"></div>
						</div>
			</td>

			<td class="author column-author"><a href="edit.php?post_type=post&amp;author=1">admin</a></td>
			
			<td class="categories column-categories"><a href="edit.php?category_name=uncategorised">Uncategorised</a></td>
			<td class=""><?php the_field('cruise_speed'); ?></td>
			<td class=""><?php the_field('max_capacity'); ?></td>
			<td class=""><?php the_field('max_range'); ?></td>
			<td class=""><?php the_field('cost_per_hour'); ?></td>
			<td class=""><?php the_field('base_cost'); ?></td>
			<td class="date column-date"><abbr title="2013/08/15 8:28:09 AM">2013/08/15</abbr><br>Published</td>

		</tr>


	<?php endwhile; wp_reset_query();?>





		</tbody>
</table>	

<button type="submit" class="button-primary large" value="<?php //_e('Save Changes') ?>">Update</button>

</div>
        

