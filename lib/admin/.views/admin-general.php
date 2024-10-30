<h2>Instajet Options</h2>
<div class="wrap">

		<?php
			if ( isset($_GET['updated']) && 'true' == esc_attr( $_GET['updated'] ) ) echo '<div class="updated" ><p>Settings updated.</p></div>';
			//if ( isset ( $_GET['tab'] ) ) ij_admin_tabs($_GET['tab']); else ij_admin_tabs('homepage');
		?>

<div class="alertwrapper">
<div class="alert warning">
	<p>InstaJet has encountered a problem. <strong>Your theme is not compatible with InstaJet.</strong> <a href="#">Go to Theme Options &rarr;</a></p>
</div>
</div>


		<div class="panelwrap">
			<div class="panel medium">

				<div class="panelinner">

				<h1 class="icon-settings">General Settings</h1>

					<form method="post" action="options.php" class="form-options">
		            <?php settings_fields( 'ij-customisation' ); ?>
		            <?php do_settings_sections( 'instajet' ); ?>	
		            <?php submit_button(); ?>
					</form>

				</div>     

			</div>
</div>