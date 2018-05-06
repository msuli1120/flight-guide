<?php get_header(); ?>

	<script type="text/javascript">
			var images = new Array();
			function preload() {
				for (i = 0; i < preload.arguments.length; i++) {
					images[i] = new Image();
					images[i].src = preload.arguments[i]
				}
			}
	 preload(
		 "/wp-content/plugins/MSDGFlightGuide/assets/img/msdg-flight-guide-logo.png",
		 "/wp-content/plugins/MSDGFlightGuide/assets/img/disc-golf-sprite-sm.png"
			)
	</script>


<?php
	if  (current_user_can('manage_options')) {
		include 'functions.php';

		global $wpdb;
		$terms = \MSDG\Base\MSDGTerm::getTerms();
		$brands_in_db = [];
		$brand_color = [];
		$json_needed = [];
		$text_color_array = [];

		if ($_SESSION['msdg_message'] !== null) {
			echo '<p class="success-msg" style="color: green">' . $_SESSION['msdg_message'] . '</p>';
		}

		if ($_SESSION['msdg_error']!== null) {
			echo '<p class="error-msg" style="color: red">' . $_SESSION['msdg_error'] . '</p>';
		}
	}

	$brands = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}msdg_brands", OBJECT );
	$discs = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}msdg_discs WHERE type = 'disc'");
	$putters = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}msdg_discs WHERE type = 'putter'");

	if (\count($brands)) {
		foreach ($brands as $brand) {
			$brands_in_db[] = $brand->brand;
			$brand_color[] = $brand->color;
			$text_color_array[] = $brand->text_color;

			if (strpos($brand->brand, ' ')) {
				$json_needed[] = str_replace(' ', '-', $brand->brand);
			} else {
				$json_needed[] = $brand->brand;
			}
		}
		$diff_array = array_diff(array_values($terms), $brands_in_db);
	} else {
		$diff_array = $terms;
	}


	if (\count($discs)) {
		$vertical_array = [[],[],[],[],[],[],[],[],[],[],[],[],[],[]];

		for ($count = 3; $count < 15; $count++) {
			foreach ($discs as $disc) {
				if ((int) $disc->y === $count) {
					$vertical_array[$count-1][] = $disc;
				}
			}
		}
	}
	

?>


<div id="msdgfg-bnr" class="flight-guide-sprite">
<div class="back-btn fade-in fade-in-longer">
<a href="/">Go back home</a>
</div>
<div class="heading-img-txt fade-in">
<img src="/wp-content/plugins/MSDGFlightGuide/assets/img/msdg-flight-guide-logo.png" alt="Disc Golf Flight Guide" width="369" height="70">
<p>2018 Interactive</p>
<p>
 <span class="dg-txt">Disc Golf</span> <span class="fg-txt">Flight Guide</span>
</p>	
</div>
<div class="disc flight-guide-sprite"></div>
<div class="disc-golfer flight-guide-sprite"></div>
<div class="disc-golf-basket flight-guide-sprite"></div>
</div>


	<div class="manufacturers-title">

		<?php if (current_user_can('manage_options')) { ?>
		    <div class="toggle-admin-options"> <span></span> </div>
    		<button class="admin-option show-form reset-flight-guide" title="Click than confirm">Reset Flight Guide</button>
		    <form class="admin-option popup" action="<?php echo get_permalink(); ?>" method="post">
		      <span class="form-container reset-all-options">
		      <img src="/wp-content/plugins/MSDGFlightGuide/assets/img/atomic-boom.gif" width="320" height="190">
		      <p> You are about to reset the entire flight guide and delete all submitted entries. <i style="color:red;">This can not be reverted. </i> <strong>Continue?</strong></p>
		       <button class="reset-all-btn" name="reset">Yes remove all entries</button>
			   <close class="close-popup-btn">Cancel</close>
		      </span>
		    </form>
		<?php } ?>


	<h2> Disc Manufacturers </h2>

		<?php if (current_user_can('manage_options')) { ?>
		  <div class="add-new-manufacturer-wrapper">
		    <button style="display: none;" class="admin-option show-form add-new-manufacturer button"><span class="plus">+</span> Add Manufacturers</button>
			<form class="add-manufacturer-form popup" method="post">
			  <span class="form-container">

			  	<h2> Add New Manufacturer </h2>

			  	<span class="popup-choice-block-brand">
				  <label for="brand">Choose a brand</label>
				  <select name="brand">
				   <?php foreach ($diff_array as $term) { echo '<option value="'. $term . '">' . $term . '</option>'; } ?>
				  </select>
				</span>

				<span class="popup-choice-block">
				  <p>Choose Background Color:</p>
				  <input type="text" name="color" class="jscolor" id="bg-color"/>
				</span>

				<span class="popup-choice-block">
				  <p>Choose Text Color:</p>
				  <input type="text" name="text_color" class="jscolor" id="text-color">
				</span>

				<span class="popup-btns">
				  <button type="submit" name="add">Submit Brand Entry</button>
				  <close class="close-popup-btn">Cancel</close>
				</span>

			  </span>
			</form>
		  </div>
		<?php } ?>
	</div>


<div class="wrapper">
	<main>
		<!-- Manufacturers -->
		<div class="flex-grid">

			<div class="flex-column flex-grid-item">
				<div class="flex-grid alphabet manufacturers-container">


<?php
	if (\count($brands)) {
		$link = '';

		usort($brands, function($a, $b) {
			return strcmp($a->brand, $b->brand);
		});

		foreach ($brands as $brand) {
			if (strpos($brand->brand, ' ')) {
				$link = str_replace(' ', '-', $brand->brand);
			} else {
				$link = $brand->brand;
			}
			?>
			

			<div class="flex-grid-item manufacturer-item" style="background-color: <?php echo $brand->color; ?>;">
				 <a class="manufacturer-link flex-grid-item" href="<?php echo get_home_url().'/product-category/manufacturers/'.$link; ?>" style="color: <?php echo $brand->text_color; ?>" title='<?php echo $brand->brand; ?>'><?php echo $brand->brand; ?>
				 </a>
				 <span class="hide-manuf disable-products" data-background="<?php echo $brand->color; ?>" data-color="<?php echo $brand->text_color; ?>" title="Disable products under this brand">×</span>

			
			
			<?php
				if  (current_user_can('manage_options')) {
					?>
						<button class="admin-option show-form" title="Edit this brands colors">Edit</button>
						<form method="post" class="edit-disc-form popup">
						  <span class="form-container">
						  	<h2> Edit Manufactuer </h2>
							<input type="hidden" name="brand-id" value="<?php echo $brand->ID; ?>">
							<input type="hidden" name="old-color" value="<?php echo $brand->color; ?>">
							<input type="hidden" name="old_text_color" value="<?php echo $brand->text_color; ?>">
							 <span class="popup-choice-block">
							  <p>Choose Background Color:</p>
							  <input type="text" name="choose-color" class="jscolor" value="<?php echo $brand->color; ?>">
							  </span>
							  <span class="popup-choice-block">
							  <p>Choose Text Color:</p>
							  <input type="text" name="text_color" class="jscolor" value="<?php echo $brand->text_color; ?>">
							 </span>
							<span class="popup-btns">
							  <button class="update-btn" type="submit" name="update-color">Update</button>
							  <button class="delete-btn" type="submit" name="delete-brand">Delete</button>
							  <close class="close-popup-btn">Cancel</close>
							</span>
						  </span>
						</form>
					</div>
					<?php
				} else {
					echo '</div>';
				}
		}
	}
?>

				</div>
			</div>
		</div>

		<!-- Top Titles -->
		<div class="flex-grid top-titles speed-row">

			<div class="flex-grid speed-grade">
				<div class="flex-grid">
					<div class="flex-grid-item">Speed</div>
				</div>
			</div>

			<div class="flex-column flex-grid-item very-overstable">
				<div class="flex-grid">
					<div class="flex-grid-item">Very Overstable</div>
				</div>
				<div class="flex-grid alphabet">
					<div class="flex-grid-item flex-column stab-a">
						<div class="flex-grid-item">A</div>
					</div>
					<div class="flex-grid-item flex-column stab-b">
						<div class="flex-grid-item">B</div>
					</div>
					<div class="flex-grid-item flex-column stab-c">
						<div class="flex-grid-item">C</div>
					</div>
				</div>
			</div>

			<div class="flex-column flex-grid-item overstable">
				<div class="flex-grid">
					<div class="flex-grid-item">Overstable</div>
				</div>
				<div class="flex-grid alphabet">
					<div class="flex-grid-item flex-column stab-d">
						<div class="flex-grid-item">D</div>
					</div>
					<div class="flex-grid-item flex-column stab-e">
						<div class="flex-grid-item">E</div>
					</div>
					<div class="flex-grid-item flex-column stab-f">
						<div class="flex-grid-item">F</div>
					</div>
					<div class="flex-grid-item flex-column stab-g">
						<div class="flex-grid-item">G</div>
					</div>
				</div>
			</div>
			<div class="flex-column flex-grid-item stable">
				<div class="flex-grid">
					<div class="flex-grid-item">Stable</div>
				</div>
				<div class="flex-grid alphabet">
					<div class="flex-grid-item flex-column stab-h">
						<div class="flex-grid-item">H</div>
					</div>
					<div class="flex-grid-item flex-column stab-i">
						<div class="flex-grid-item">I</div>
					</div>
					<div class="flex-grid-item flex-column stab-j">
						<div class="flex-grid-item">J</div>
					</div>
				</div>
			</div>
			<div class="flex-column flex-grid-item understable">
				<div class="flex-grid">
					<div class="flex-grid-item">Understable</div>
				</div>
				<div class="flex-grid alphabet">
					<div class="flex-grid-item flex-column stab-k">
						<div class="flex-grid-item">K</div>
					</div>
					<div class="flex-grid-item flex-column stab-l">
						<div class="flex-grid-item">L</div>
					</div>
					<div class="flex-grid-item flex-column stab-m">
						<div class="flex-grid-item">M</div>
					</div>
					<div class="flex-grid-item flex-column stab-n">
						<div class="flex-grid-item">N</div>
					</div>
				</div>
			</div>
			<div class="flex-column flex-grid-item very-understable">
				<div class="flex-grid">
					<div class="flex-grid-item">Very Understable</div>
				</div>
				<div class="flex-grid alphabet">
					<div class="flex-grid-item flex-column stab-o">
						<div class="flex-grid-item">O</div>
					</div>
					<div class="flex-grid-item flex-column stab-p">
						<div class="flex-grid-item">P</div>
					</div>
					<div class="flex-grid-item flex-column stab-q">
						<div class="flex-grid-item">Q</div>
					</div>
				</div>
			</div>
		</div>

		<!-- Speed 14 -->
		<div class="flex-grid speed-grade-14 speed-row">

			<div class="flex-grid speed-grade">
				<div class="flex-grid">
					<div class="flex-grid-item">14</div>
				</div>
			</div>

			<div class="flex-column flex-grid-item very-overstable">
				<div class="flex-grid">
					<div class="flex-grid-item">Very Overstable</div>
				</div>
				<div class="flex-grid alphabet">
					<div class="flex-grid-item flex-column stab-a">
						<div class="flex-grid-item">A</div>
						<?php
							if  (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="A" data-y="14" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?> data-texts=<?php echo json_encode($text_color_array); ?>>+</span></span>
								<?php
							}
							if ( !empty($vertical_array[13]) ) {
								foreach ($vertical_array[13] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'A') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
										echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
					<div class="flex-grid-item flex-column stab-b">
						<div class="flex-grid-item">B</div>
						<?php
							if  (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="B" data-y="14" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?> data-texts=<?php echo json_encode($text_color_array); ?>>+</span></span>
								<?php
							}
							if ( !empty($vertical_array[13]) ) {
								foreach ($vertical_array[13] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'B') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
											echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
					<div class="flex-grid-item flex-column stab-c">
						<div class="flex-grid-item">C</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="C" data-y="14" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?> data-texts=<?php echo json_encode($text_color_array); ?>>+</span></span>
								<?php
							}
							if ( !empty($vertical_array[13]) ) {
								foreach ($vertical_array[13] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'C') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
											echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
				</div>
			</div>

			<div class="flex-column flex-grid-item overstable">
				<div class="flex-grid">
					<div class="flex-grid-item">Overstable</div>
				</div>
				<div class="flex-grid alphabet">
					<div class="flex-grid-item flex-column stab-d">
						<div class="flex-grid-item">D</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="D" data-y="14" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?> data-texts=<?php echo json_encode($text_color_array); ?>>+</span></span>
								<?php
							}
							if ( !empty($vertical_array[13]) ) {
								foreach ($vertical_array[13] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'D') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
											echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
					<div class="flex-grid-item flex-column stab-e">
						<div class="flex-grid-item">E</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="E" data-y="14" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?> data-texts=<?php echo json_encode($text_color_array); ?>>+</span></span>
								<?php
							}
							if ( !empty($vertical_array[13]) ) {
								foreach ($vertical_array[13] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'E') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
											echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
					<div class="flex-grid-item flex-column stab-f">
						<div class="flex-grid-item">F</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="F" data-y="14" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?> data-texts=<?php echo json_encode($text_color_array); ?>>+</span></span>
								<?php
							}
							if ( !empty($vertical_array[13]) ) {
								foreach ($vertical_array[13] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'F') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
											echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
					<div class="flex-grid-item flex-column stab-g">
						<div class="flex-grid-item">G</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="G" data-y="14" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?> data-texts=<?php echo json_encode($text_color_array); ?>>+</span></span>
								<?php
							}
							if ( !empty($vertical_array[13]) ) {
								foreach ($vertical_array[13] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'G') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
											echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
				</div>
			</div>
			<div class="flex-column flex-grid-item stable">
				<div class="flex-grid">
					<div class="flex-grid-item">Stable</div>
				</div>
				<div class="flex-grid alphabet">
					<div class="flex-grid-item flex-column stab-h">
						<div class="flex-grid-item">H</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="H" data-y="14" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?> data-texts=<?php echo json_encode($text_color_array); ?>>+</span></span>
								<?php
							}
							if ( !empty($vertical_array[13]) ) {
								foreach ($vertical_array[13] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'H') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
											echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
					<div class="flex-grid-item flex-column stab-i">
						<div class="flex-grid-item">I</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="I" data-y="14" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?> data-texts=<?php echo json_encode($text_color_array); ?>>+</span></span>
								<?php
							}
							if ( !empty($vertical_array[13]) ) {
								foreach ($vertical_array[13] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'I') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
											echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
					<div class="flex-grid-item flex-column stab-j">
						<div class="flex-grid-item">J</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="J" data-y="14" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?> data-texts=<?php echo json_encode($text_color_array); ?>>+</span></span>
								<?php
							}
							if ( !empty($vertical_array[13]) ) {
								foreach ($vertical_array[13] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'J') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
											echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
				</div>
			</div>
			<div class="flex-column flex-grid-item understable">
				<div class="flex-grid">
					<div class="flex-grid-item">Understable</div>
				</div>
				<div class="flex-grid alphabet">
					<div class="flex-grid-item flex-column stab-k">
						<div class="flex-grid-item">K</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="K" data-y="14" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?> data-texts=<?php echo json_encode($text_color_array); ?>>+</span></span>
								<?php
							}
							if ( !empty($vertical_array[13]) ) {
								foreach ($vertical_array[13] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'K') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
											echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
					<div class="flex-grid-item flex-column stab-l">
						<div class="flex-grid-item">L</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="L" data-y="14" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?> data-texts=<?php echo json_encode($text_color_array); ?>>+</span></span>
								<?php
							}
							if ( !empty($vertical_array[13]) ) {
								foreach ($vertical_array[13] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'L') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
											echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
					<div class="flex-grid-item flex-column stab-m">
						<div class="flex-grid-item">M</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="M" data-y="14" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?> data-texts=<?php echo json_encode($text_color_array); ?>>+</span></span>
								<?php
							}
							if ( !empty($vertical_array[13]) ) {
								foreach ($vertical_array[13] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'M') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
											echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
					<div class="flex-grid-item flex-column stab-n">
						<div class="flex-grid-item">N</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="N" data-y="14" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?> data-texts=<?php echo json_encode($text_color_array); ?>>+</span></span>
								<?php
							}
							if ( !empty($vertical_array[13]) ) {
								foreach ($vertical_array[13] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'N') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
											echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
				</div>
			</div>
			<div class="flex-column flex-grid-item very-understable">
				<div class="flex-grid">
					<div class="flex-grid-item">Very Understable</div>
				</div>
				<div class="flex-grid alphabet">
					<div class="flex-grid-item flex-column stab-o">
						<div class="flex-grid-item">O</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="O" data-y="14" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?> data-texts=<?php echo json_encode($text_color_array); ?>>+</span></span>
								<?php
							}
							if ( !empty($vertical_array[13]) ) {
								foreach ($vertical_array[13] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'O') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
											echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
					<div class="flex-grid-item flex-column stab-p">
						<div class="flex-grid-item">P</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="P" data-y="14" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?> data-texts=<?php echo json_encode($text_color_array); ?>>+</span></span>
								<?php
							}
							if ( !empty($vertical_array[13]) ) {
								foreach ($vertical_array[13] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'P') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
											echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
					<div class="flex-grid-item flex-column stab-q">
						<div class="flex-grid-item">Q</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="Q" data-y="14" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?> data-texts=<?php echo json_encode($text_color_array); ?>>+</span></span>
								<?php
							}
							if ( !empty($vertical_array[13]) ) {
								foreach ($vertical_array[13] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'Q') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
											echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
				</div>
			</div>
		</div>

		<!-- Speed 13 -->
		<div class="flex-grid speed-grade-13 speed-row">

			<div class="flex-grid speed-grade">
				<div class="flex-grid">
					<div class="flex-grid-item">13</div>
				</div>
			</div>

			<div class="flex-column flex-grid-item very-overstable">
				<div class="flex-grid">
					<div class="flex-grid-item">Very Overstable</div>
				</div>
				<div class="flex-grid alphabet">
					<div class="flex-grid-item flex-column stab-a">
						<div class="flex-grid-item">A</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="A" data-y="13" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?> data-texts=<?php echo json_encode($text_color_array); ?>>+</span></span>
								<?php
							}
							if ( !empty($vertical_array[12]) ) {
								foreach ($vertical_array[12] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'A') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
										echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
					<div class="flex-grid-item flex-column stab-b">
						<div class="flex-grid-item">B</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="B" data-y="13" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?> data-texts=<?php echo json_encode($text_color_array); ?>>+</span></span>
								<?php
							}
							if ( !empty($vertical_array[12]) ) {
								foreach ($vertical_array[12] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'B') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
										echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
					<div class="flex-grid-item flex-column stab-c">
						<div class="flex-grid-item">C</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="C" data-y="13" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?> data-texts=<?php echo json_encode($text_color_array); ?>>+</span></span>
								<?php
							}
							if ( !empty($vertical_array[12]) ) {
								foreach ($vertical_array[12] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'C') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
										echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
				</div>
			</div>

			<div class="flex-column flex-grid-item overstable">
				<div class="flex-grid">
					<div class="flex-grid-item">Overstable</div>
				</div>
				<div class="flex-grid alphabet">
					<div class="flex-grid-item flex-column stab-d">
						<div class="flex-grid-item">D</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="D" data-y="13" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?> data-texts=<?php echo json_encode($text_color_array); ?>>+</span></span>
								<?php
							}
							if ( !empty($vertical_array[12]) ) {
								foreach ($vertical_array[12] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'D') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
										echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
					<div class="flex-grid-item flex-column stab-e">
						<div class="flex-grid-item">E</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="E" data-y="13" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?> data-texts=<?php echo json_encode($text_color_array); ?>>+</span></span>
								<?php
							}
							if ( !empty($vertical_array[12]) ) {
								foreach ($vertical_array[12] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'E') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
										echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
					<div class="flex-grid-item flex-column stab-f">
						<div class="flex-grid-item">F</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="F" data-texts=<?php echo json_encode($text_color_array); ?> data-y="13" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[12]) ) {
								foreach ($vertical_array[12] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'F') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
											echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
					<div class="flex-grid-item flex-column stab-g">
						<div class="flex-grid-item">G</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="G" data-texts=<?php echo json_encode($text_color_array); ?> data-y="13" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[12]) ) {
								foreach ($vertical_array[12] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'G') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
										echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
				</div>
			</div>
			<div class="flex-column flex-grid-item stable">
				<div class="flex-grid">
					<div class="flex-grid-item">Stable</div>
				</div>
				<div class="flex-grid alphabet">
					<div class="flex-grid-item flex-column stab-h">
						<div class="flex-grid-item">H</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="H" data-texts=<?php echo json_encode($text_color_array); ?> data-y="13" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[12]) ) {
								foreach ($vertical_array[12] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'H') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
										echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
					<div class="flex-grid-item flex-column stab-i">
						<div class="flex-grid-item">I</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="I" data-texts=<?php echo json_encode($text_color_array); ?> data-y="13" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[12]) ) {
								foreach ($vertical_array[12] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'I') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
										echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
					<div class="flex-grid-item flex-column stab-j">
						<div class="flex-grid-item">J</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="J" data-texts=<?php echo json_encode($text_color_array); ?> data-y="13" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[12]) ) {
								foreach ($vertical_array[12] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'J') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
										echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
				</div>
			</div>
			<div class="flex-column flex-grid-item understable">
				<div class="flex-grid">
					<div class="flex-grid-item">Understable</div>
				</div>
				<div class="flex-grid alphabet">
					<div class="flex-grid-item flex-column stab-k">
						<div class="flex-grid-item">K</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="K" data-texts=<?php echo json_encode($text_color_array); ?> data-y="13" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[12]) ) {
								foreach ($vertical_array[12] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'K') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
										echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
					<div class="flex-grid-item flex-column stab-l">
						<div class="flex-grid-item">L</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="L" data-texts=<?php echo json_encode($text_color_array); ?> data-y="13" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[12]) ) {
								foreach ($vertical_array[12] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'L') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
										echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
					<div class="flex-grid-item flex-column stab-m">
						<div class="flex-grid-item">M</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="M" data-texts=<?php echo json_encode($text_color_array); ?> data-y="13" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[12]) ) {
								foreach ($vertical_array[12] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'M') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
										echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
					<div class="flex-grid-item flex-column stab-n">
						<div class="flex-grid-item">N</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="N" data-texts=<?php echo json_encode($text_color_array); ?> data-y="13" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[12]) ) {
								foreach ($vertical_array[12] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'N') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
										echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
				</div>
			</div>
			<div class="flex-column flex-grid-item very-understable">
				<div class="flex-grid">
					<div class="flex-grid-item">Very Understable</div>
				</div>
				<div class="flex-grid alphabet">
					<div class="flex-grid-item flex-column stab-o">
						<div class="flex-grid-item">O</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="O" data-texts=<?php echo json_encode($text_color_array); ?> data-y="13" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[12]) ) {
								foreach ($vertical_array[12] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'O') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
										echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
					<div class="flex-grid-item flex-column stab-p">
						<div class="flex-grid-item">P</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="P" data-texts=<?php echo json_encode($text_color_array); ?> data-y="13" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[12]) ) {
								foreach ($vertical_array[12] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'P') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
										echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
					<div class="flex-grid-item flex-column stab-q">
						<div class="flex-grid-item">Q</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="Q" data-texts=<?php echo json_encode($text_color_array); ?> data-y="13" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[12]) ) {
								foreach ($vertical_array[12] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'Q') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
										echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
				</div>
			</div>
		</div>


		<!-- Speed 12 -->
		<div class="flex-grid speed-grade-12 speed-row">

			<div class="flex-grid speed-grade">
				<div class="flex-grid">
					<div class="flex-grid-item">12</div>
				</div>
			</div>

			<div class="flex-column flex-grid-item very-overstable">
				<div class="flex-grid">
					<div class="flex-grid-item">Very Overstable</div>
				</div>
				<div class="flex-grid alphabet">
					<div class="flex-grid-item flex-column stab-a">
						<div class="flex-grid-item">A</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="A" data-texts=<?php echo json_encode($text_color_array); ?> data-y="12" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[11]) ) {
								foreach ($vertical_array[11] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'A') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
										echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
					<div class="flex-grid-item flex-column stab-b">
						<div class="flex-grid-item">B</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="B" data-texts=<?php echo json_encode($text_color_array); ?> data-y="12" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[11]) ) {
								foreach ($vertical_array[11] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'B') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
										echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
					<div class="flex-grid-item flex-column stab-c">
						<div class="flex-grid-item">C</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="C" data-texts=<?php echo json_encode($text_color_array); ?> data-y="12" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[11]) ) {
								foreach ($vertical_array[11] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'C') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
										echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
				</div>
			</div>

			<div class="flex-column flex-grid-item overstable">
				<div class="flex-grid">
					<div class="flex-grid-item">Overstable</div>
				</div>
				<div class="flex-grid alphabet">
					<div class="flex-grid-item flex-column stab-d">
						<div class="flex-grid-item">D</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="D" data-texts=<?php echo json_encode($text_color_array); ?> data-y="12" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[11]) ) {
								foreach ($vertical_array[11] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'D') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
										echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
					<div class="flex-grid-item flex-column stab-e">
						<div class="flex-grid-item">E</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="E" data-texts=<?php echo json_encode($text_color_array); ?> data-y="12" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[11]) ) {
								foreach ($vertical_array[11] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'E') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
										echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
					<div class="flex-grid-item flex-column stab-f">
						<div class="flex-grid-item">F</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="F" data-texts=<?php echo json_encode($text_color_array); ?> data-y="12" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[11]) ) {
								foreach ($vertical_array[11] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'F') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
										echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
					<div class="flex-grid-item flex-column stab-g">
						<div class="flex-grid-item">G</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="G" data-texts=<?php echo json_encode($text_color_array); ?> data-y="12" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[11]) ) {
								foreach ($vertical_array[11] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'G') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
											echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
				</div>
			</div>
			<div class="flex-column flex-grid-item stable">
				<div class="flex-grid">
					<div class="flex-grid-item">Stable</div>
				</div>
				<div class="flex-grid alphabet">
					<div class="flex-grid-item flex-column stab-h">
						<div class="flex-grid-item">H</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="H" data-texts=<?php echo json_encode($text_color_array); ?> data-y="12" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[11]) ) {
								foreach ($vertical_array[11] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'H') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
											echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
					<div class="flex-grid-item flex-column stab-i">
						<div class="flex-grid-item">I</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="I" data-texts=<?php echo json_encode($text_color_array); ?> data-y="12" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[11]) ) {
								foreach ($vertical_array[11] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'I') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
											echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
					<div class="flex-grid-item flex-column stab-j">
						<div class="flex-grid-item">J</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="J" data-texts=<?php echo json_encode($text_color_array); ?> data-y="12" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[11]) ) {
								foreach ($vertical_array[11] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'J') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
											echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
				</div>
			</div>
			<div class="flex-column flex-grid-item understable">
				<div class="flex-grid">
					<div class="flex-grid-item">Understable</div>
				</div>
				<div class="flex-grid alphabet">
					<div class="flex-grid-item flex-column stab-k">
						<div class="flex-grid-item">K</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="K" data-texts=<?php echo json_encode($text_color_array); ?> data-y="12" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[11]) ) {
								foreach ($vertical_array[11] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'K') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
											echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
					<div class="flex-grid-item flex-column stab-l">
						<div class="flex-grid-item">L</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="L" data-texts=<?php echo json_encode($text_color_array); ?> data-y="12" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[11]) ) {
								foreach ($vertical_array[11] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'L') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
											echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
					<div class="flex-grid-item flex-column stab-m">
						<div class="flex-grid-item">M</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="M" data-texts=<?php echo json_encode($text_color_array); ?> data-y="12" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[11]) ) {
								foreach ($vertical_array[11] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'M') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
											echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
					<div class="flex-grid-item flex-column stab-n">
						<div class="flex-grid-item">N</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="N" data-texts=<?php echo json_encode($text_color_array); ?> data-y="12" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[11]) ) {
								foreach ($vertical_array[11] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'N') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
											echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
				</div>
			</div>
			<div class="flex-column flex-grid-item very-understable">
				<div class="flex-grid">
					<div class="flex-grid-item">Very Understable</div>
				</div>
				<div class="flex-grid alphabet">
					<div class="flex-grid-item flex-column stab-o">
						<div class="flex-grid-item">O</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="O" data-texts=<?php echo json_encode($text_color_array); ?> data-y="12" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[11]) ) {
								foreach ($vertical_array[11] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'O') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
											echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
					<div class="flex-grid-item flex-column stab-p">
						<div class="flex-grid-item">P</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="P" data-texts=<?php echo json_encode($text_color_array); ?> data-y="12" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[11]) ) {
								foreach ($vertical_array[11] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'P') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
											echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
					<div class="flex-grid-item flex-column stab-q">
						<div class="flex-grid-item">Q</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="Q" data-texts=<?php echo json_encode($text_color_array); ?> data-y="12" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[11]) ) {
								foreach ($vertical_array[11] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'Q') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
											echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
				</div>
			</div>
		</div>

		<!-- Speed 11 -->
		<div class="flex-grid speed-grade-11 speed-row">

			<div class="flex-grid speed-grade">
				<div class="flex-grid">
					<div class="flex-grid-item">11</div>
				</div>
			</div>

			<div class="flex-column flex-grid-item very-overstable">
				<div class="flex-grid">
					<div class="flex-grid-item">Very Overstable</div>
				</div>
				<div class="flex-grid alphabet">
					<div class="flex-grid-item flex-column stab-a">
						<div class="flex-grid-item">A</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="A" data-texts=<?php echo json_encode($text_color_array); ?> data-y="11" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[10]) ) {
								foreach ($vertical_array[10] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'A') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
											echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
					<div class="flex-grid-item flex-column stab-b">
						<div class="flex-grid-item">B</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="B" data-texts=<?php echo json_encode($text_color_array); ?> data-y="11" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[10]) ) {
								foreach ($vertical_array[10] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'B') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
											echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
					<div class="flex-grid-item flex-column stab-c">
						<div class="flex-grid-item">C</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="C" data-texts=<?php echo json_encode($text_color_array); ?> data-y="11" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[10]) ) {
								foreach ($vertical_array[10] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'C') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
											echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
				</div>
			</div>

			<div class="flex-column flex-grid-item overstable">
				<div class="flex-grid">
					<div class="flex-grid-item">Overstable</div>
				</div>
				<div class="flex-grid alphabet">
					<div class="flex-grid-item flex-column stab-d">
						<div class="flex-grid-item">D</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="D" data-texts=<?php echo json_encode($text_color_array); ?> data-y="11" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[10]) ) {
								foreach ($vertical_array[10] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'D') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
											echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
					<div class="flex-grid-item flex-column stab-e">
						<div class="flex-grid-item">E</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="E" data-texts=<?php echo json_encode($text_color_array); ?> data-y="11" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[10]) ) {
								foreach ($vertical_array[10] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'E') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
											echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
					<div class="flex-grid-item flex-column stab-f">
						<div class="flex-grid-item">F</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="F" data-texts=<?php echo json_encode($text_color_array); ?> data-y="11" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[10]) ) {
								foreach ($vertical_array[10] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'F') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
											echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
					<div class="flex-grid-item flex-column stab-g">
						<div class="flex-grid-item">G</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="G" data-texts=<?php echo json_encode($text_color_array); ?> data-y="11" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[10]) ) {
								foreach ($vertical_array[10] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'G') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
										echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
				</div>
			</div>
			<div class="flex-column flex-grid-item stable">
				<div class="flex-grid">
					<div class="flex-grid-item">Stable</div>
				</div>
				<div class="flex-grid alphabet">
					<div class="flex-grid-item flex-column stab-h">
						<div class="flex-grid-item">H</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="H" data-texts=<?php echo json_encode($text_color_array); ?> data-y="11" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[10]) ) {
								foreach ($vertical_array[10] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'H') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
										echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
					<div class="flex-grid-item flex-column stab-i">
						<div class="flex-grid-item">I</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="I" data-texts=<?php echo json_encode($text_color_array); ?> data-y="11" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[10]) ) {
								foreach ($vertical_array[10] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'I') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
										echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
					<div class="flex-grid-item flex-column stab-j">
						<div class="flex-grid-item">J</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="J" data-texts=<?php echo json_encode($text_color_array); ?> data-y="11" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[10]) ) {
								foreach ($vertical_array[10] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'J') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
										echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
				</div>
			</div>
			<div class="flex-column flex-grid-item understable">
				<div class="flex-grid">
					<div class="flex-grid-item">Understable</div>
				</div>
				<div class="flex-grid alphabet">
					<div class="flex-grid-item flex-column stab-k">
						<div class="flex-grid-item">K</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="K" data-texts=<?php echo json_encode($text_color_array); ?> data-y="11" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[10]) ) {
								foreach ($vertical_array[10] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'K') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
										echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
					<div class="flex-grid-item flex-column stab-l">
						<div class="flex-grid-item">L</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="L" data-texts=<?php echo json_encode($text_color_array); ?> data-y="11" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[10]) ) {
								foreach ($vertical_array[10] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'L') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
										echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
					<div class="flex-grid-item flex-column stab-m">
						<div class="flex-grid-item">M</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="M" data-texts=<?php echo json_encode($text_color_array); ?> data-y="11" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[10]) ) {
								foreach ($vertical_array[10] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'M') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
										echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
					<div class="flex-grid-item flex-column stab-n">
						<div class="flex-grid-item">N</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="N" data-texts=<?php echo json_encode($text_color_array); ?> data-y="11" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[10]) ) {
								foreach ($vertical_array[10] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'N') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
										echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
				</div>
			</div>
			<div class="flex-column flex-grid-item very-understable">
				<div class="flex-grid">
					<div class="flex-grid-item">Very Understable</div>
				</div>
				<div class="flex-grid alphabet">
					<div class="flex-grid-item flex-column stab-o">
						<div class="flex-grid-item">O</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="O" data-texts=<?php echo json_encode($text_color_array); ?> data-y="11" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[10]) ) {
								foreach ($vertical_array[10] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'O') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
										echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
					<div class="flex-grid-item flex-column stab-p">
						<div class="flex-grid-item">P</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="P" data-texts=<?php echo json_encode($text_color_array); ?> data-y="11" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[10]) ) {
								foreach ($vertical_array[10] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'P') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
										echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
					<div class="flex-grid-item flex-column stab-q">
						<div class="flex-grid-item">Q</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="Q" data-texts=<?php echo json_encode($text_color_array); ?> data-y="11" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[10]) ) {
								foreach ($vertical_array[10] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'Q') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
										echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
				</div>
			</div>
		</div>

		<!-- Speed 10 -->
		<div class="flex-grid speed-grade-10 speed-row">

			<div class="flex-grid speed-grade">
				<div class="flex-grid">
					<div class="flex-grid-item">10</div>
				</div>
			</div>

			<div class="flex-column flex-grid-item very-overstable">
				<div class="flex-grid">
					<div class="flex-grid-item">Very Overstable</div>
				</div>
				<div class="flex-grid alphabet">
					<div class="flex-grid-item flex-column stab-a">
						<div class="flex-grid-item">A</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="A" data-texts=<?php echo json_encode($text_color_array); ?> data-y="10" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[9]) ) {
								foreach ($vertical_array[9] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'A') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
										echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
					<div class="flex-grid-item flex-column stab-b">
						<div class="flex-grid-item">B</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="B" data-texts=<?php echo json_encode($text_color_array); ?> data-y="10" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[9]) ) {
								foreach ($vertical_array[9] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'B') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
										echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
					<div class="flex-grid-item flex-column stab-c">
						<div class="flex-grid-item">C</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="C" data-texts=<?php echo json_encode($text_color_array); ?> data-y="10" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[9]) ) {
								foreach ($vertical_array[9] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'C') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
										echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
				</div>
			</div>

			<div class="flex-column flex-grid-item overstable">
				<div class="flex-grid">
					<div class="flex-grid-item">Overstable</div>
				</div>
				<div class="flex-grid alphabet">
					<div class="flex-grid-item flex-column stab-d">
						<div class="flex-grid-item">D</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="D" data-texts=<?php echo json_encode($text_color_array); ?> data-y="10" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[9]) ) {
								foreach ($vertical_array[9] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'D') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
										echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
					<div class="flex-grid-item flex-column stab-e">
						<div class="flex-grid-item">E</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="E" data-texts=<?php echo json_encode($text_color_array); ?> data-y="10" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[9]) ) {
								foreach ($vertical_array[9] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'E') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
										echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
					<div class="flex-grid-item flex-column stab-f">
						<div class="flex-grid-item">F</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="F" data-texts=<?php echo json_encode($text_color_array); ?> data-y="10" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[9]) ) {
								foreach ($vertical_array[9] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'F') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
										echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
					<div class="flex-grid-item flex-column stab-g">
						<div class="flex-grid-item">G</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="G" data-texts=<?php echo json_encode($text_color_array); ?> data-y="10" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[9]) ) {
								foreach ($vertical_array[9] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'G') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
										echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
				</div>
			</div>
			<div class="flex-column flex-grid-item stable">
				<div class="flex-grid">
					<div class="flex-grid-item">Stable</div>
				</div>
				<div class="flex-grid alphabet">
					<div class="flex-grid-item flex-column stab-h">
						<div class="flex-grid-item">H</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="H" data-texts=<?php echo json_encode($text_color_array); ?> data-y="10" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[9]) ) {
								foreach ($vertical_array[9] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'H') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
										echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
					<div class="flex-grid-item flex-column stab-i">
						<div class="flex-grid-item">I</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="I" data-texts=<?php echo json_encode($text_color_array); ?> data-y="10" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[9]) ) {
								foreach ($vertical_array[9] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'I') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
										echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
					<div class="flex-grid-item flex-column stab-j">
						<div class="flex-grid-item">J</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="J" data-texts=<?php echo json_encode($text_color_array); ?> data-y="10" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[9]) ) {
								foreach ($vertical_array[9] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'J') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
										echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
				</div>
			</div>
			<div class="flex-column flex-grid-item understable">
				<div class="flex-grid">
					<div class="flex-grid-item">Understable</div>
				</div>
				<div class="flex-grid alphabet">
					<div class="flex-grid-item flex-column stab-k">
						<div class="flex-grid-item">K</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="K" data-texts=<?php echo json_encode($text_color_array); ?> data-y="10" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[9]) ) {
								foreach ($vertical_array[9] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'K') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
										echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
					<div class="flex-grid-item flex-column stab-l">
						<div class="flex-grid-item">L</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="L" data-texts=<?php echo json_encode($text_color_array); ?> data-y="10" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[9]) ) {
								foreach ($vertical_array[9] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'L') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
										echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
					<div class="flex-grid-item flex-column stab-m">
						<div class="flex-grid-item">M</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="M" data-texts=<?php echo json_encode($text_color_array); ?> data-y="10" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[9]) ) {
								foreach ($vertical_array[9] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'M') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
										echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
					<div class="flex-grid-item flex-column stab-n">
						<div class="flex-grid-item">N</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="N" data-texts=<?php echo json_encode($text_color_array); ?> data-y="10" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[9]) ) {
								foreach ($vertical_array[9] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'N') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
										echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
				</div>
			</div>
			<div class="flex-column flex-grid-item very-understable">
				<div class="flex-grid">
					<div class="flex-grid-item">Very Understable</div>
				</div>
				<div class="flex-grid alphabet">
					<div class="flex-grid-item flex-column stab-o">
						<div class="flex-grid-item">O</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="O" data-texts=<?php echo json_encode($text_color_array); ?> data-y="10" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[9]) ) {
								foreach ($vertical_array[9] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'O') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
										echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
					<div class="flex-grid-item flex-column stab-p">
						<div class="flex-grid-item">P</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="P" data-texts=<?php echo json_encode($text_color_array); ?> data-y="10" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[9]) ) {
								foreach ($vertical_array[9] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'P') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
										echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
					<div class="flex-grid-item flex-column stab-q">
						<div class="flex-grid-item">Q</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="Q" data-texts=<?php echo json_encode($text_color_array); ?> data-y="10" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[9]) ) {
								foreach ($vertical_array[9] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'Q') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
										echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
				</div>
			</div>
		</div>

		<!-- Speed 9 -->
		<div class="flex-grid speed-grade-9 speed-row">

			<div class="flex-grid speed-grade">
				<div class="flex-grid">
					<div class="flex-grid-item">9</div>
				</div>
			</div>

			<div class="flex-column flex-grid-item very-overstable">
				<div class="flex-grid">
					<div class="flex-grid-item">Very Overstable</div>
				</div>
				<div class="flex-grid alphabet">
					<div class="flex-grid-item flex-column stab-a">
						<div class="flex-grid-item">A</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="A" data-texts=<?php echo json_encode($text_color_array); ?> data-y="9" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[8]) ) {
								foreach ($vertical_array[8] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'A') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
										echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
					<div class="flex-grid-item flex-column stab-b">
						<div class="flex-grid-item">B</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="B" data-texts=<?php echo json_encode($text_color_array); ?> data-y="9" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[8]) ) {
								foreach ($vertical_array[8] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'B') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
										echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
					<div class="flex-grid-item flex-column stab-c">
						<div class="flex-grid-item">C</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="C" data-texts=<?php echo json_encode($text_color_array); ?> data-y="9" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[8]) ) {
								foreach ($vertical_array[8] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'C') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
										echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
				</div>
			</div>

			<div class="flex-column flex-grid-item overstable">
				<div class="flex-grid">
					<div class="flex-grid-item">Overstable</div>
				</div>
				<div class="flex-grid alphabet">
					<div class="flex-grid-item flex-column stab-d">
						<div class="flex-grid-item">D</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="D" data-texts=<?php echo json_encode($text_color_array); ?> data-y="9" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[8]) ) {
								foreach ($vertical_array[8] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'D') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
										echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
					<div class="flex-grid-item flex-column stab-e">
						<div class="flex-grid-item">E</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="E" data-texts=<?php echo json_encode($text_color_array); ?> data-y="9" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[8]) ) {
								foreach ($vertical_array[8] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'E') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
										echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
					<div class="flex-grid-item flex-column stab-f">
						<div class="flex-grid-item">F</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="F" data-texts=<?php echo json_encode($text_color_array); ?> data-y="9" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[8]) ) {
								foreach ($vertical_array[8] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'F') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
										echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
					<div class="flex-grid-item flex-column stab-g">
						<div class="flex-grid-item">G</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="G" data-texts=<?php echo json_encode($text_color_array); ?> data-y="9" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[8]) ) {
								foreach ($vertical_array[8] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'G') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
										echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
				</div>
			</div>
			<div class="flex-column flex-grid-item stable">
				<div class="flex-grid">
					<div class="flex-grid-item">Stable</div>
				</div>
				<div class="flex-grid alphabet">
					<div class="flex-grid-item flex-column stab-h">
						<div class="flex-grid-item">H</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="H" data-texts=<?php echo json_encode($text_color_array); ?> data-y="9" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[8]) ) {
								foreach ($vertical_array[8] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'H') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
										echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
					<div class="flex-grid-item flex-column stab-i">
						<div class="flex-grid-item">I</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="I" data-texts=<?php echo json_encode($text_color_array); ?> data-y="9" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[8]) ) {
								foreach ($vertical_array[8] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'I') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
										echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
					<div class="flex-grid-item flex-column stab-j">
						<div class="flex-grid-item">J</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="J" data-texts=<?php echo json_encode($text_color_array); ?> data-y="9" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[8]) ) {
								foreach ($vertical_array[8] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'J') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
										echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
				</div>
			</div>
			<div class="flex-column flex-grid-item understable">
				<div class="flex-grid">
					<div class="flex-grid-item">Understable</div>
				</div>
				<div class="flex-grid alphabet">
					<div class="flex-grid-item flex-column stab-k">
						<div class="flex-grid-item">K</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="K" data-texts=<?php echo json_encode($text_color_array); ?> data-y="9" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[8]) ) {
								foreach ($vertical_array[8] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'K') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
										echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
					<div class="flex-grid-item flex-column stab-l">
						<div class="flex-grid-item">L</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="L" data-texts=<?php echo json_encode($text_color_array); ?> data-y="9" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[8]) ) {
								foreach ($vertical_array[8] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'L') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
										echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
					<div class="flex-grid-item flex-column stab-m">
						<div class="flex-grid-item">M</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="M" data-texts=<?php echo json_encode($text_color_array); ?> data-y="9" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[8]) ) {
								foreach ($vertical_array[8] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'M') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
										echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
					<div class="flex-grid-item flex-column stab-n">
						<div class="flex-grid-item">N</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="N" data-texts=<?php echo json_encode($text_color_array); ?> data-y="9" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[8]) ) {
								foreach ($vertical_array[8] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'N') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
										echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
				</div>
			</div>
			<div class="flex-column flex-grid-item very-understable">
				<div class="flex-grid">
					<div class="flex-grid-item">Very Understable</div>
				</div>
				<div class="flex-grid alphabet">
					<div class="flex-grid-item flex-column stab-o">
						<div class="flex-grid-item">O</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="O" data-texts=<?php echo json_encode($text_color_array); ?> data-y="9" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[8]) ) {
								foreach ($vertical_array[8] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'O') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
										echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
					<div class="flex-grid-item flex-column stab-p">
						<div class="flex-grid-item">P</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="P" data-texts=<?php echo json_encode($text_color_array); ?> data-y="9" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[8]) ) {
								foreach ($vertical_array[8] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'P') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
										echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
					<div class="flex-grid-item flex-column stab-q">
						<div class="flex-grid-item">Q</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="Q" data-texts=<?php echo json_encode($text_color_array); ?> data-y="9" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[8]) ) {
								foreach ($vertical_array[8] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'Q') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
										echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
				</div>
			</div>
		</div>

		<!-- Speed 8 -->
		<div class="flex-grid speed-grade-8 speed-row">

			<div class="flex-grid speed-grade">
				<div class="flex-grid">
					<div class="flex-grid-item">8</div>
				</div>
			</div>

			<div class="flex-column flex-grid-item very-overstable">
				<div class="flex-grid">
					<div class="flex-grid-item">Very Overstable</div>
				</div>
				<div class="flex-grid alphabet">
					<div class="flex-grid-item flex-column stab-a">
						<div class="flex-grid-item">A</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="A" data-texts=<?php echo json_encode($text_color_array); ?> data-y="8" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[7]) ) {
								foreach ($vertical_array[7] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'A') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
										echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
					<div class="flex-grid-item flex-column stab-b">
						<div class="flex-grid-item">B</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="B" data-texts=<?php echo json_encode($text_color_array); ?> data-y="8" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[7]) ) {
								foreach ($vertical_array[7] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'B') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
										echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
					<div class="flex-grid-item flex-column stab-c">
						<div class="flex-grid-item">C</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="C" data-texts=<?php echo json_encode($text_color_array); ?> data-y="8" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[7]) ) {
								foreach ($vertical_array[7] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'C') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
										echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
				</div>
			</div>

			<div class="flex-column flex-grid-item overstable">
				<div class="flex-grid">
					<div class="flex-grid-item">Overstable</div>
				</div>
				<div class="flex-grid alphabet">
					<div class="flex-grid-item flex-column stab-d">
						<div class="flex-grid-item">D</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="D" data-texts=<?php echo json_encode($text_color_array); ?> data-y="8" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[7]) ) {
								foreach ($vertical_array[7] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'D') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
										echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
					<div class="flex-grid-item flex-column stab-e">
						<div class="flex-grid-item">E</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="E" data-texts=<?php echo json_encode($text_color_array); ?> data-y="8" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[7]) ) {
								foreach ($vertical_array[7] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'E') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
										echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
					<div class="flex-grid-item flex-column stab-f">
						<div class="flex-grid-item">F</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="F" data-texts=<?php echo json_encode($text_color_array); ?> data-y="8" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[7]) ) {
								foreach ($vertical_array[7] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'F') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
											echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
					<div class="flex-grid-item flex-column stab-g">
						<div class="flex-grid-item">G</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="G" data-texts=<?php echo json_encode($text_color_array); ?> data-y="8" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[7]) ) {
								foreach ($vertical_array[7] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'G') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
											echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
				</div>
			</div>
			<div class="flex-column flex-grid-item stable">
				<div class="flex-grid">
					<div class="flex-grid-item">Stable</div>
				</div>
				<div class="flex-grid alphabet">
					<div class="flex-grid-item flex-column stab-h">
						<div class="flex-grid-item">H</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="H" data-texts=<?php echo json_encode($text_color_array); ?> data-y="8" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[7]) ) {
								foreach ($vertical_array[7] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'H') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
											echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
					<div class="flex-grid-item flex-column stab-i">
						<div class="flex-grid-item">I</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="I" data-texts=<?php echo json_encode($text_color_array); ?> data-y="8" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[7]) ) {
								foreach ($vertical_array[7] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'I') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
											echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
					<div class="flex-grid-item flex-column stab-j">
						<div class="flex-grid-item">J</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="J" data-texts=<?php echo json_encode($text_color_array); ?> data-y="8" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[7]) ) {
								foreach ($vertical_array[7] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'J') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
											echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
				</div>
			</div>
			<div class="flex-column flex-grid-item understable">
				<div class="flex-grid">
					<div class="flex-grid-item">Understable</div>
				</div>
				<div class="flex-grid alphabet">
					<div class="flex-grid-item flex-column stab-k">
						<div class="flex-grid-item">K</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="K" data-texts=<?php echo json_encode($text_color_array); ?> data-y="8" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[7]) ) {
								foreach ($vertical_array[7] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'K') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
											echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
					<div class="flex-grid-item flex-column stab-l">
						<div class="flex-grid-item">L</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="L" data-texts=<?php echo json_encode($text_color_array); ?> data-y="8" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[7]) ) {
								foreach ($vertical_array[7] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'L') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
											echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
					<div class="flex-grid-item flex-column stab-m">
						<div class="flex-grid-item">M</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="M" data-texts=<?php echo json_encode($text_color_array); ?> data-y="8" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[7]) ) {
								foreach ($vertical_array[7] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'M') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
											echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
					<div class="flex-grid-item flex-column stab-n">
						<div class="flex-grid-item">N</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="N" data-texts=<?php echo json_encode($text_color_array); ?> data-y="8" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[7]) ) {
								foreach ($vertical_array[7] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'N') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
											echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
				</div>
			</div>
			<div class="flex-column flex-grid-item very-understable">
				<div class="flex-grid">
					<div class="flex-grid-item">Very Understable</div>
				</div>
				<div class="flex-grid alphabet">
					<div class="flex-grid-item flex-column stab-o">
						<div class="flex-grid-item">O</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="O" data-texts=<?php echo json_encode($text_color_array); ?> data-y="8" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[7]) ) {
								foreach ($vertical_array[7] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'O') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
											echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
					<div class="flex-grid-item flex-column stab-p">
						<div class="flex-grid-item">P</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="P" data-texts=<?php echo json_encode($text_color_array); ?> data-y="8" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[7]) ) {
								foreach ($vertical_array[7] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'P') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
											echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
					<div class="flex-grid-item flex-column stab-q">
						<div class="flex-grid-item">Q</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="Q" data-texts=<?php echo json_encode($text_color_array); ?> data-y="8" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[7]) ) {
								foreach ($vertical_array[7] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'Q') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
											echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
				</div>
			</div>
		</div>

		<!-- Speed 7 -->
		<div class="flex-grid speed-grade-7 speed-row">

			<div class="flex-grid speed-grade">
				<div class="flex-grid">
					<div class="flex-grid-item">7</div>
				</div>
			</div>

			<div class="flex-column flex-grid-item very-overstable">
				<div class="flex-grid">
					<div class="flex-grid-item">Very Overstable</div>
				</div>
				<div class="flex-grid alphabet">
					<div class="flex-grid-item flex-column stab-a">
						<div class="flex-grid-item">A</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="A" data-texts=<?php echo json_encode($text_color_array); ?> data-y="7" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[6]) ) {
								foreach ($vertical_array[6] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'A') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
											echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
					<div class="flex-grid-item flex-column stab-b">
						<div class="flex-grid-item">B</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="B" data-texts=<?php echo json_encode($text_color_array); ?> data-y="7" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[6]) ) {
								foreach ($vertical_array[6] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'B') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
											echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
					<div class="flex-grid-item flex-column stab-c">
						<div class="flex-grid-item">C</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="C" data-texts=<?php echo json_encode($text_color_array); ?> data-y="7" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[6]) ) {
								foreach ($vertical_array[6] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'C') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
											echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
				</div>
			</div>

			<div class="flex-column flex-grid-item overstable">
				<div class="flex-grid">
					<div class="flex-grid-item">Overstable</div>
				</div>
				<div class="flex-grid alphabet">
					<div class="flex-grid-item flex-column stab-d">
						<div class="flex-grid-item">D</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="D" data-texts=<?php echo json_encode($text_color_array); ?> data-y="7" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[6]) ) {
								foreach ($vertical_array[6] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'D') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
											echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
					<div class="flex-grid-item flex-column stab-e">
						<div class="flex-grid-item">E</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="E" data-texts=<?php echo json_encode($text_color_array); ?> data-y="7" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[6]) ) {
								foreach ($vertical_array[6] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'E') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
											echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
					<div class="flex-grid-item flex-column stab-f">
						<div class="flex-grid-item">F</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="F" data-texts=<?php echo json_encode($text_color_array); ?> data-y="7" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[6]) ) {
								foreach ($vertical_array[6] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'F') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
											echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
					<div class="flex-grid-item flex-column stab-g">
						<div class="flex-grid-item">G</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="G" data-texts=<?php echo json_encode($text_color_array); ?> data-y="7" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[6]) ) {
								foreach ($vertical_array[6] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'G') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
											echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
				</div>
			</div>
			<div class="flex-column flex-grid-item stable">
				<div class="flex-grid">
					<div class="flex-grid-item">Stable</div>
				</div>
				<div class="flex-grid alphabet">
					<div class="flex-grid-item flex-column stab-h">
						<div class="flex-grid-item">H</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="H" data-texts=<?php echo json_encode($text_color_array); ?> data-y="7" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[6]) ) {
								foreach ($vertical_array[6] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'H') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
											echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
					<div class="flex-grid-item flex-column stab-i">
						<div class="flex-grid-item">I</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="I" data-texts=<?php echo json_encode($text_color_array); ?> data-y="7" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[6]) ) {
								foreach ($vertical_array[6] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'I') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
											echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
					<div class="flex-grid-item flex-column stab-j">
						<div class="flex-grid-item">J</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="J" data-texts=<?php echo json_encode($text_color_array); ?> data-y="7" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[6]) ) {
								foreach ($vertical_array[6] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'J') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
											echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
				</div>
			</div>
			<div class="flex-column flex-grid-item understable">
				<div class="flex-grid">
					<div class="flex-grid-item">Understable</div>
				</div>
				<div class="flex-grid alphabet">
					<div class="flex-grid-item flex-column stab-k">
						<div class="flex-grid-item">K</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="K" data-texts=<?php echo json_encode($text_color_array); ?> data-y="7" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[6]) ) {
								foreach ($vertical_array[6] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'K') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
											echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
					<div class="flex-grid-item flex-column stab-l">
						<div class="flex-grid-item">L</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="L" data-texts=<?php echo json_encode($text_color_array); ?> data-y="7" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[6]) ) {
								foreach ($vertical_array[6] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'L') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
											echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
					<div class="flex-grid-item flex-column stab-m">
						<div class="flex-grid-item">M</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="M" data-texts=<?php echo json_encode($text_color_array); ?> data-y="7" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[6]) ) {
								foreach ($vertical_array[6] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'M') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
											echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
					<div class="flex-grid-item flex-column stab-n">
						<div class="flex-grid-item">N</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="N" data-texts=<?php echo json_encode($text_color_array); ?> data-y="7" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[6]) ) {
								foreach ($vertical_array[6] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'N') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
											echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
				</div>
			</div>
			<div class="flex-column flex-grid-item very-understable">
				<div class="flex-grid">
					<div class="flex-grid-item">Very Understable</div>
				</div>
				<div class="flex-grid alphabet">
					<div class="flex-grid-item flex-column stab-o">
						<div class="flex-grid-item">O</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="O" data-texts=<?php echo json_encode($text_color_array); ?> data-y="7" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[6]) ) {
								foreach ($vertical_array[6] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'O') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
											echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
					<div class="flex-grid-item flex-column stab-p">
						<div class="flex-grid-item">P</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="P" data-texts=<?php echo json_encode($text_color_array); ?> data-y="7" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[6]) ) {
								foreach ($vertical_array[6] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'P') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
											echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
					<div class="flex-grid-item flex-column stab-q">
						<div class="flex-grid-item">Q</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="Q" data-texts=<?php echo json_encode($text_color_array); ?> data-y="7" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[6]) ) {
								foreach ($vertical_array[6] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'Q') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
											echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
				</div>
			</div>
		</div>

		<!-- Speed 6 -->
		<div class="flex-grid speed-grade-6 speed-row">

			<div class="flex-grid speed-grade">
				<div class="flex-grid">
					<div class="flex-grid-item">6</div>
				</div>
			</div>

			<div class="flex-column flex-grid-item very-overstable">
				<div class="flex-grid">
					<div class="flex-grid-item">Very Overstable</div>
				</div>
				<div class="flex-grid alphabet">
					<div class="flex-grid-item flex-column stab-a">
						<div class="flex-grid-item">A</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="A" data-texts=<?php echo json_encode($text_color_array); ?> data-y="6" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[5]) ) {
								foreach ($vertical_array[5] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'A') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
											echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
					<div class="flex-grid-item flex-column stab-b">
						<div class="flex-grid-item">B</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="B" data-texts=<?php echo json_encode($text_color_array); ?> data-y="6" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[5]) ) {
								foreach ($vertical_array[5] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'B') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
											echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
					<div class="flex-grid-item flex-column stab-c">
						<div class="flex-grid-item">C</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="C" data-texts=<?php echo json_encode($text_color_array); ?> data-y="6" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[5]) ) {
								foreach ($vertical_array[5] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'C') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
											echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
				</div>
			</div>

			<div class="flex-column flex-grid-item overstable">
				<div class="flex-grid">
					<div class="flex-grid-item">Overstable</div>
				</div>
				<div class="flex-grid alphabet">
					<div class="flex-grid-item flex-column stab-d">
						<div class="flex-grid-item">D</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="D" data-texts=<?php echo json_encode($text_color_array); ?> data-y="6" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[5]) ) {
								foreach ($vertical_array[5] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'D') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
											echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
					<div class="flex-grid-item flex-column stab-e">
						<div class="flex-grid-item">E</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="E" data-texts=<?php echo json_encode($text_color_array); ?> data-y="6" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[5]) ) {
								foreach ($vertical_array[5] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'E') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
											echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
					<div class="flex-grid-item flex-column stab-f">
						<div class="flex-grid-item">F</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="F" data-texts=<?php echo json_encode($text_color_array); ?> data-y="6" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[5]) ) {
								foreach ($vertical_array[5] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'F') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
											echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
					<div class="flex-grid-item flex-column stab-g">
						<div class="flex-grid-item">G</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="G" data-texts=<?php echo json_encode($text_color_array); ?> data-y="6" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[5]) ) {
								foreach ($vertical_array[5] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'G') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
											echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
				</div>
			</div>
			<div class="flex-column flex-grid-item stable">
				<div class="flex-grid">
					<div class="flex-grid-item">Stable</div>
				</div>
				<div class="flex-grid alphabet">
					<div class="flex-grid-item flex-column stab-h">
						<div class="flex-grid-item">H</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="H" data-texts=<?php echo json_encode($text_color_array); ?> data-y="6" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[5]) ) {
								foreach ($vertical_array[5] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'H') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
											echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
					<div class="flex-grid-item flex-column stab-i">
						<div class="flex-grid-item">I</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="I" data-texts=<?php echo json_encode($text_color_array); ?> data-y="6" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[5]) ) {
								foreach ($vertical_array[5] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'I') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
											echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
					<div class="flex-grid-item flex-column stab-j">
						<div class="flex-grid-item">J</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="J" data-texts=<?php echo json_encode($text_color_array); ?> data-y="6" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[5]) ) {
								foreach ($vertical_array[5] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'J') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
											echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
				</div>
			</div>
			<div class="flex-column flex-grid-item understable">
				<div class="flex-grid">
					<div class="flex-grid-item">Understable</div>
				</div>
				<div class="flex-grid alphabet">
					<div class="flex-grid-item flex-column stab-k">
						<div class="flex-grid-item">K</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="K" data-texts=<?php echo json_encode($text_color_array); ?> data-y="6" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[5]) ) {
								foreach ($vertical_array[5] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'K') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
											echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
					<div class="flex-grid-item flex-column stab-l">
						<div class="flex-grid-item">L</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="L" data-texts=<?php echo json_encode($text_color_array); ?> data-y="6" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[5]) ) {
								foreach ($vertical_array[5] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'L') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
											echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
					<div class="flex-grid-item flex-column stab-m">
						<div class="flex-grid-item">M</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="M" data-texts=<?php echo json_encode($text_color_array); ?> data-y="6" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[5]) ) {
								foreach ($vertical_array[5] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'M') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
											echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
					<div class="flex-grid-item flex-column stab-n">
						<div class="flex-grid-item">N</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="N" data-texts=<?php echo json_encode($text_color_array); ?> data-y="6" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[5]) ) {
								foreach ($vertical_array[5] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'N') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
											echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
				</div>
			</div>
			<div class="flex-column flex-grid-item very-understable">
				<div class="flex-grid">
					<div class="flex-grid-item">Very Understable</div>
				</div>
				<div class="flex-grid alphabet">
					<div class="flex-grid-item flex-column stab-o">
						<div class="flex-grid-item">O</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="O" data-texts=<?php echo json_encode($text_color_array); ?> data-y="6" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[5]) ) {
								foreach ($vertical_array[5] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'O') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
											echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
					<div class="flex-grid-item flex-column stab-p">
						<div class="flex-grid-item">P</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="P" data-texts=<?php echo json_encode($text_color_array); ?> data-y="6" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[5]) ) {
								foreach ($vertical_array[5] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'P') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
											echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
					<div class="flex-grid-item flex-column stab-q">
						<div class="flex-grid-item">Q</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="Q" data-texts=<?php echo json_encode($text_color_array); ?> data-y="6" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[5]) ) {
								foreach ($vertical_array[5] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'Q') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
											echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
				</div>
			</div>
		</div>

		<!-- Speed 5 -->
		<div class="flex-grid speed-grade-5 speed-row">

			<div class="flex-grid speed-grade">
				<div class="flex-grid">
					<div class="flex-grid-item">5</div>
				</div>
			</div>

			<div class="flex-column flex-grid-item very-overstable">
				<div class="flex-grid">
					<div class="flex-grid-item">Very Overstable</div>
				</div>
				<div class="flex-grid alphabet">
					<div class="flex-grid-item flex-column stab-a">
						<div class="flex-grid-item">A</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="A" data-texts=<?php echo json_encode($text_color_array); ?> data-y="5" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[4]) ) {
								foreach ($vertical_array[4] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'A') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
											echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
					<div class="flex-grid-item flex-column stab-b">
						<div class="flex-grid-item">B</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="B" data-texts=<?php echo json_encode($text_color_array); ?> data-y="5" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[4]) ) {
								foreach ($vertical_array[4] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'B') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
											echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
					<div class="flex-grid-item flex-column stab-c">
						<div class="flex-grid-item">C</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="C" data-texts=<?php echo json_encode($text_color_array); ?> data-y="5" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[4]) ) {
								foreach ($vertical_array[4] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'C') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
											echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
				</div>
			</div>

			<div class="flex-column flex-grid-item overstable">
				<div class="flex-grid">
					<div class="flex-grid-item">Overstable</div>
				</div>
				<div class="flex-grid alphabet">
					<div class="flex-grid-item flex-column stab-d">
						<div class="flex-grid-item">D</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="D" data-texts=<?php echo json_encode($text_color_array); ?> data-y="5" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[4]) ) {
								foreach ($vertical_array[4] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'D') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
											echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
					<div class="flex-grid-item flex-column stab-e">
						<div class="flex-grid-item">E</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="E" data-texts=<?php echo json_encode($text_color_array); ?> data-y="5" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[4]) ) {
								foreach ($vertical_array[4] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'E') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
											echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
					<div class="flex-grid-item flex-column stab-f">
						<div class="flex-grid-item">F</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="F" data-texts=<?php echo json_encode($text_color_array); ?> data-y="5" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[4]) ) {
								foreach ($vertical_array[4] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'F') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
											echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
					<div class="flex-grid-item flex-column stab-g">
						<div class="flex-grid-item">G</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="G" data-texts=<?php echo json_encode($text_color_array); ?> data-y="5" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[4]) ) {
								foreach ($vertical_array[4] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'G') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
											echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
				</div>
			</div>
			<div class="flex-column flex-grid-item stable">
				<div class="flex-grid">
					<div class="flex-grid-item">Stable</div>
				</div>
				<div class="flex-grid alphabet">
					<div class="flex-grid-item flex-column stab-h">
						<div class="flex-grid-item">H</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="H" data-texts=<?php echo json_encode($text_color_array); ?> data-y="5" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[4]) ) {
								foreach ($vertical_array[4] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'H') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
											echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
					<div class="flex-grid-item flex-column stab-i">
						<div class="flex-grid-item">I</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="I" data-texts=<?php echo json_encode($text_color_array); ?> data-y="5" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[4]) ) {
								foreach ($vertical_array[4] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'I') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
											echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
					<div class="flex-grid-item flex-column stab-j">
						<div class="flex-grid-item">J</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="J" data-texts=<?php echo json_encode($text_color_array); ?> data-y="5" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[4]) ) {
								foreach ($vertical_array[4] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'J') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
											echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
				</div>
			</div>
			<div class="flex-column flex-grid-item understable">
				<div class="flex-grid">
					<div class="flex-grid-item">Understable</div>
				</div>
				<div class="flex-grid alphabet">
					<div class="flex-grid-item flex-column stab-k">
						<div class="flex-grid-item">K</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="K" data-texts=<?php echo json_encode($text_color_array); ?> data-y="5" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[4]) ) {
								foreach ($vertical_array[4] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'K') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
											echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
					<div class="flex-grid-item flex-column stab-l">
						<div class="flex-grid-item">L</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="L" data-texts=<?php echo json_encode($text_color_array); ?> data-y="5" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[4]) ) {
								foreach ($vertical_array[4] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'L') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
											echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
					<div class="flex-grid-item flex-column stab-m">
						<div class="flex-grid-item">M</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="M" data-texts=<?php echo json_encode($text_color_array); ?> data-y="5" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[4]) ) {
								foreach ($vertical_array[4] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'M') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
											echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
					<div class="flex-grid-item flex-column stab-n">
						<div class="flex-grid-item">N</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="N" data-texts=<?php echo json_encode($text_color_array); ?> data-y="5" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[4]) ) {
								foreach ($vertical_array[4] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'N') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
											echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
				</div>
			</div>
			<div class="flex-column flex-grid-item very-understable">
				<div class="flex-grid">
					<div class="flex-grid-item">Very Understable</div>
				</div>
				<div class="flex-grid alphabet">
					<div class="flex-grid-item flex-column stab-o">
						<div class="flex-grid-item">O</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="O" data-texts=<?php echo json_encode($text_color_array); ?> data-y="5" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[4]) ) {
								foreach ($vertical_array[4] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'O') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
											echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
					<div class="flex-grid-item flex-column stab-p">
						<div class="flex-grid-item">P</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="P" data-texts=<?php echo json_encode($text_color_array); ?> data-y="5" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[4]) ) {
								foreach ($vertical_array[4] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'P') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
											echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
					<div class="flex-grid-item flex-column stab-q">
						<div class="flex-grid-item">Q</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="Q" data-texts=<?php echo json_encode($text_color_array); ?> data-y="5" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[4]) ) {
								foreach ($vertical_array[4] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'Q') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
											echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
				</div>
			</div>
		</div>

		<!-- Speed 4 -->
		<div class="flex-grid speed-grade-4 speed-row">

			<div class="flex-grid speed-grade">
				<div class="flex-grid">
					<div class="flex-grid-item">4</div>
				</div>
			</div>

			<div class="flex-column flex-grid-item very-overstable">
				<div class="flex-grid">
					<div class="flex-grid-item">Very Overstable</div>
				</div>
				<div class="flex-grid alphabet">
					<div class="flex-grid-item flex-column stab-a">
						<div class="flex-grid-item">A</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="A" data-texts=<?php echo json_encode($text_color_array); ?> data-y="4" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[3]) ) {
								foreach ($vertical_array[3] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'A') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
											echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
					<div class="flex-grid-item flex-column stab-b">
						<div class="flex-grid-item">B</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="B" data-texts=<?php echo json_encode($text_color_array); ?> data-y="4" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[3]) ) {
								foreach ($vertical_array[3] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'B') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
											echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
					<div class="flex-grid-item flex-column stab-c">
						<div class="flex-grid-item">C</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="C" data-texts=<?php echo json_encode($text_color_array); ?> data-y="4" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[3]) ) {
								foreach ($vertical_array[3] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'C') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
											echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
				</div>
			</div>

			<div class="flex-column flex-grid-item overstable">
				<div class="flex-grid">
					<div class="flex-grid-item">Overstable</div>
				</div>
				<div class="flex-grid alphabet">
					<div class="flex-grid-item flex-column stab-d">
						<div class="flex-grid-item">D</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="D" data-texts=<?php echo json_encode($text_color_array); ?> data-y="4" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[3]) ) {
								foreach ($vertical_array[3] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'D') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
											echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
					<div class="flex-grid-item flex-column stab-e">
						<div class="flex-grid-item">E</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="E" data-texts=<?php echo json_encode($text_color_array); ?> data-y="4" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[3]) ) {
								foreach ($vertical_array[3] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'E') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
											echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
					<div class="flex-grid-item flex-column stab-f">
						<div class="flex-grid-item">F</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="F" data-texts=<?php echo json_encode($text_color_array); ?> data-y="4" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[3]) ) {
								foreach ($vertical_array[3] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'F') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
											echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
					<div class="flex-grid-item flex-column stab-g">
						<div class="flex-grid-item">G</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="G" data-texts=<?php echo json_encode($text_color_array); ?> data-y="4" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[3]) ) {
								foreach ($vertical_array[3] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'G') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
											echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
				</div>
			</div>
			<div class="flex-column flex-grid-item stable">
				<div class="flex-grid">
					<div class="flex-grid-item">Stable</div>
				</div>
				<div class="flex-grid alphabet">
					<div class="flex-grid-item flex-column stab-h">
						<div class="flex-grid-item">H</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="H" data-texts=<?php echo json_encode($text_color_array); ?> data-y="4" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[3]) ) {
								foreach ($vertical_array[3] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'H') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
											echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
					<div class="flex-grid-item flex-column stab-i">
						<div class="flex-grid-item">I</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="I" data-texts=<?php echo json_encode($text_color_array); ?> data-y="4" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[3]) ) {
								foreach ($vertical_array[3] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'I') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
											echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
					<div class="flex-grid-item flex-column stab-j">
						<div class="flex-grid-item">J</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="J" data-texts=<?php echo json_encode($text_color_array); ?> data-y="4" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[3]) ) {
								foreach ($vertical_array[3] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'J') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
											echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
				</div>
			</div>
			<div class="flex-column flex-grid-item understable">
				<div class="flex-grid">
					<div class="flex-grid-item">Understable</div>
				</div>
				<div class="flex-grid alphabet">
					<div class="flex-grid-item flex-column stab-k">
						<div class="flex-grid-item">K</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="K" data-texts=<?php echo json_encode($text_color_array); ?> data-y="4" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[3]) ) {
								foreach ($vertical_array[3] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'K') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
											echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
					<div class="flex-grid-item flex-column stab-l">
						<div class="flex-grid-item">L</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="L" data-texts=<?php echo json_encode($text_color_array); ?> data-y="4" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[3]) ) {
								foreach ($vertical_array[3] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'L') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
											echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
					<div class="flex-grid-item flex-column stab-m">
						<div class="flex-grid-item">M</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="M" data-texts=<?php echo json_encode($text_color_array); ?> data-y="4" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[3]) ) {
								foreach ($vertical_array[3] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'M') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
											echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
					<div class="flex-grid-item flex-column stab-n">
						<div class="flex-grid-item">N</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="N" data-texts=<?php echo json_encode($text_color_array); ?> data-y="4" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[3]) ) {
								foreach ($vertical_array[3] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'N') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
											echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
				</div>
			</div>
			<div class="flex-column flex-grid-item very-understable">
				<div class="flex-grid">
					<div class="flex-grid-item">Very Understable</div>
				</div>
				<div class="flex-grid alphabet">
					<div class="flex-grid-item flex-column stab-o">
						<div class="flex-grid-item">O</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="O" data-texts=<?php echo json_encode($text_color_array); ?> data-y="4" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[3]) ) {
								foreach ($vertical_array[3] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'O') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
											echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
					<div class="flex-grid-item flex-column stab-p">
						<div class="flex-grid-item">P</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="P" data-texts=<?php echo json_encode($text_color_array); ?> data-y="4" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[3]) ) {
								foreach ($vertical_array[3] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'P') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
											echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
					<div class="flex-grid-item flex-column stab-q">
						<div class="flex-grid-item">Q</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="Q" data-texts=<?php echo json_encode($text_color_array); ?> data-y="4" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[3]) ) {
								foreach ($vertical_array[3] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'Q') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
											echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
				</div>
			</div>
		</div>

		<!-- Speed 3 -->
		<div class="flex-grid speed-grade-3 speed-row">

			<div class="flex-grid speed-grade">
				<div class="flex-grid">
					<div class="flex-grid-item">3</div>
				</div>
			</div>

			<div class="flex-column flex-grid-item very-overstable">
				<div class="flex-grid">
					<div class="flex-grid-item">Very Overstable</div>
				</div>
				<div class="flex-grid alphabet">
					<div class="flex-grid-item flex-column stab-a">
						<div class="flex-grid-item">A</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="A" data-texts=<?php echo json_encode($text_color_array); ?> data-y="3" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[2]) ) {
								foreach ($vertical_array[2] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'A') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
											echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
					<div class="flex-grid-item flex-column stab-b">
						<div class="flex-grid-item">B</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="B" data-texts=<?php echo json_encode($text_color_array); ?> data-y="3" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[2]) ) {
								foreach ($vertical_array[2] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'B') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
											echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
					<div class="flex-grid-item flex-column stab-c">
						<div class="flex-grid-item">C</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="C" data-texts=<?php echo json_encode($text_color_array); ?> data-y="3" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[2]) ) {
								foreach ($vertical_array[2] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'C') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
											echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
				</div>
			</div>

			<div class="flex-column flex-grid-item overstable">
				<div class="flex-grid">
					<div class="flex-grid-item">Overstable</div>
				</div>
				<div class="flex-grid alphabet">
					<div class="flex-grid-item flex-column stab-d">
						<div class="flex-grid-item">D</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="D" data-texts=<?php echo json_encode($text_color_array); ?> data-y="3" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[2]) ) {
								foreach ($vertical_array[2] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'D') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
											echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
					<div class="flex-grid-item flex-column stab-e">
						<div class="flex-grid-item">E</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="E" data-texts=<?php echo json_encode($text_color_array); ?> data-y="3" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[2]) ) {
								foreach ($vertical_array[2] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'E') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
											echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
					<div class="flex-grid-item flex-column stab-f">
						<div class="flex-grid-item">F</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="F" data-texts=<?php echo json_encode($text_color_array); ?> data-y="3" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[2]) ) {
								foreach ($vertical_array[2] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'F') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
											echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
					<div class="flex-grid-item flex-column stab-g">
						<div class="flex-grid-item">G</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="G" data-texts=<?php echo json_encode($text_color_array); ?> data-y="3" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[2]) ) {
								foreach ($vertical_array[2] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'G') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
											echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
				</div>
			</div>
			<div class="flex-column flex-grid-item stable">
				<div class="flex-grid">
					<div class="flex-grid-item">Stable</div>
				</div>
				<div class="flex-grid alphabet">
					<div class="flex-grid-item flex-column stab-h">
						<div class="flex-grid-item">H</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="H" data-texts=<?php echo json_encode($text_color_array); ?> data-y="3" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[2]) ) {
								foreach ($vertical_array[2] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'H') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
											echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
					<div class="flex-grid-item flex-column stab-i">
						<div class="flex-grid-item">I</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="I" data-texts=<?php echo json_encode($text_color_array); ?> data-y="3" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[2]) ) {
								foreach ($vertical_array[2] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'I') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
											echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
					<div class="flex-grid-item flex-column stab-j">
						<div class="flex-grid-item">J</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="J" data-texts=<?php echo json_encode($text_color_array); ?> data-y="3" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[2]) ) {
								foreach ($vertical_array[2] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'J') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
											echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
				</div>
			</div>
			<div class="flex-column flex-grid-item understable">
				<div class="flex-grid">
					<div class="flex-grid-item">Understable</div>
				</div>
				<div class="flex-grid alphabet">
					<div class="flex-grid-item flex-column stab-k">
						<div class="flex-grid-item">K</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="K" data-texts=<?php echo json_encode($text_color_array); ?> data-y="3" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[2]) ) {
								foreach ($vertical_array[2] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'K') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
											echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
					<div class="flex-grid-item flex-column stab-l">
						<div class="flex-grid-item">L</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="L" data-texts=<?php echo json_encode($text_color_array); ?> data-y="3" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[2]) ) {
								foreach ($vertical_array[2] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'L') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
											echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
					<div class="flex-grid-item flex-column stab-m">
						<div class="flex-grid-item">M</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="M" data-texts=<?php echo json_encode($text_color_array); ?> data-y="3" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[2]) ) {
								foreach ($vertical_array[2] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'M') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
											echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
					<div class="flex-grid-item flex-column stab-n">
						<div class="flex-grid-item">N</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="N" data-texts=<?php echo json_encode($text_color_array); ?> data-y="3" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[2]) ) {
								foreach ($vertical_array[2] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'N') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
											echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
				</div>
			</div>
			<div class="flex-column flex-grid-item very-understable">
				<div class="flex-grid">
					<div class="flex-grid-item">Very Understable</div>
				</div>
				<div class="flex-grid alphabet">
					<div class="flex-grid-item flex-column stab-o">
						<div class="flex-grid-item">O</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="O" data-texts=<?php echo json_encode($text_color_array); ?> data-y="3" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[2]) ) {
								foreach ($vertical_array[2] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'O') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
											echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
					<div class="flex-grid-item flex-column stab-p">
						<div class="flex-grid-item">P</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="P" data-texts=<?php echo json_encode($text_color_array); ?> data-y="3" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[2]) ) {
								foreach ($vertical_array[2] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'P') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
											echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
					<div class="flex-grid-item flex-column stab-q">
						<div class="flex-grid-item">Q</div>
						<?php
							if (current_user_can('manage_options')) {
								?>
								<span class="admin-option">
								<span class="myBtn" title="Add new disc to this section" data-x="Q" data-texts=<?php echo json_encode($text_color_array); ?> data-y="3" data-brands=<?php echo json_encode($json_needed); ?> data-colors=<?php echo json_encode($brand_color); ?>>+</span>
				</span>				<?php
							}
							if ( !empty($vertical_array[2]) ) {
								foreach ($vertical_array[2] as $vertical_fourteen) {
									if ($vertical_fourteen->x === 'Q') {
										$color = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = $vertical_fourteen->brand_id");
											echo '<div class="flex-grid-item disc-item" style="background-color:' . $color->color . '" data-id="' . $vertical_fourteen->ID . '" data-manage="' . current_user_can('manage_options') . '" data-text="' . $color->text_color . '" data-speed="' . $vertical_fourteen->speed . '" data-turn="' . $vertical_fourteen->turn . '" data-fade="' . $vertical_fourteen->fade . '" data-glide="' . $vertical_fourteen->glide . '" data-title="'. $vertical_fourteen->disc .'" data-link="' . $vertical_fourteen->link . '" data-pic="' . $vertical_fourteen->pic_link . '" data-bg="' . $color->color . '"><a style="color:' . $color->text_color . '">' . $vertical_fourteen->disc . '</a></div>';
									}
								}
							}
						?>
					</div>
				</div>
			</div>
		</div>
	</main>
</div>

	<div id="myModal" class="modal">
		<div class="modal-content">
			<span class="close">&times;</span>
			<div id="add-product" class="add-product"></div>
		</div>
	</div>


	<div id="speedInfo" class="speedInfo" style="display: none"></div>

	<?php
		if (current_user_can('manage_options')) {
			?>
				<div>
					<form method="post" class="add-putter-form popup">
					 <span class="form-container">
						<select name="putter_brand">
						<option value="">Choose a brand</option>
						<?php
						  if (count($brands_in_db)) {
							foreach($brands_in_db as $key => $brand) {
							   echo '<option value="' . $brand . '" style="background-color: ' . $brand_color[$key] . '; color: ' . $text_color_array[$key] . ';">' . $brand . '</option>';
							}
						  }
						?>

						</select>
					<input type="text" name="putter_name" placeholder="Enter a product" required>
					 <input type="text" name="putter_link" placeholder="Enter the link of the product" required>
					<button type="submit" name="save_putter">Save</button>
					<close class="close-add-putter-form-btn close-popup-btn">Cancel</close>
				</span>
			</form>
		</div>
	  <?php } ?>
<div class="putter-parent not-really-the-parent-but-has-classes-i-want putter-title">
		<div class="putter-child putter-speed-grade putter-speed-grade2"></div>
		<button class="admin-option show-form add-new-putter">+ Add New Putter</button>
		<h2 class="putter-child">Putters</h2>
</div>
<div class="putter-parent">
		<div class="putter-child putter-speed-grade"></div>
		<?php
		if ($putters) {
			usort($putters, function($a, $b){
				return strcmp($a->disc, $b->disc);
			});
			foreach ($putters as $putter) {
				$result = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE ID = {$putter->brand_id}");
				$color = $result->color;
				echo '<div class="putter-child" style="background-color: ' . $color . '"><p><a style="color: ' . $result->text_color . '" href="' . $putter->link . '">' . $putter->disc . '</a></p></div>';
				if (current_user_can('manage_options')) {
					?>
					<form method="post">
						<input type="hidden" name="putter_id" value="<?php echo $putter->brand_id; ?>">
						<button type="submit" name="remove_putter">-</button>
					</form>
					<?php
				}
		}
	}
?>
</div>
<!---->
<!--	<form method="post">-->
<!--		<button type="submit" name="delete">DELETE ALL DATABASE</button>-->
<!--	</form>-->
<link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
<?php get_footer(); ?>