<?php

	global $wpdb;
	$brands_table = $wpdb->prefix . 'msdg_brands';
	$discs_table = $wpdb->prefix . 'msdg_discs';


	if (isset($_POST['add'])) {
		if ($_POST['color'] !== '' && $_POST['color'] !== null) {
			$brand_array = [];
			$color = '#' . $_POST['color'];
			$text_color = $_POST['text_color'] ? '#' . $_POST['text_color'] : '#000000';
			$brands = $wpdb->get_results("SELECT brand FROM  {$wpdb->prefix}msdg_brands", OBJECT);
			$color_combo = $wpdb->get_row("SELECT * FROM $brands_table WHERE color = '$color' AND text_color = '$text_color'");
			foreach ($brands as $brand) {
				$brand_array[] = $brand->brand;
			}
			if ($color_combo) {
				$_SESSION['msdg_error'] = 'Color combinations has already existed!';
			} else {
				if (!in_array($_POST['brand'], $brand_array, true) ) {
					$wpdb->insert($brands_table, array('brand' => $_POST['brand'], 'color' => $color, 'text_color' => $text_color));
					$_SESSION['msdg_message'] = 'Successfully add a brand!';
				} else {
					wp_redirect(get_permalink());
				}
			}
		} else {
			$_SESSION['msdg_error'] = 'Please select a color!';
		}
	}

	if (isset($_POST['reset'])) {
		try {
			$wpdb->query("TRUNCATE TABLE {$wpdb->prefix}msdg_brands");
			$wpdb->query("TRUNCATE TABLE {$wpdb->prefix}msdg_discs");
		} catch (Exception $exception) {
			echo 'Caught exception: ',  $exception->getMessage(), "\n";
		}
		wp_redirect(get_permalink());
	}

	if (isset($_POST['delete'])) {
		try {
			$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}msdg_brands");
			$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}msdg_discs");
		} catch (Exception $exception) {
			echo 'Caught exception: ',  $exception->getMessage(), "\n";
		}
	}

	if (isset($_POST['disc-color']) && $_POST['disc'] !== '' && $_POST['disc'] !== null) {
		if ($_POST['brand'] === '' || $_POST['brand'] === null) {
			wp_redirect(get_permalink());
		} else {
			$brand = $_POST['brand'];
			$result = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE brand = '{$brand}'", OBJECT);
			if (!$result) {
				$_SESSION['msdg_error'] = 'Invalid brand!';
			} else {
				$products_array = [];
				$products = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}msdg_discs", OBJECT);
				foreach ($products as $product) {
					$products_array[] = $product->disc;
				}
				if (!in_array($_POST['disc'], $products_array, true)) {
					$brand_id = $result->ID;
					$x = $_POST['x'];
					$y = $_POST['y'];
					$speed = number_format($_POST['speed'], 1, '.', ',');
					$glide = number_format($_POST['glide'], 1, '.', ',');
					$turn = number_format($_POST['turn'], 1, '.', ',');
					$fade = number_format($_POST['fade'], 1, '.', ',');
					$link = $_POST['link'];
					$pic_link = $_POST['pic_link'];
					try {
						$wpdb->insert($discs_table, array('disc' => $_POST['disc'], 'brand_id' => $brand_id, 'x' => $x, 'y' => $y, 'type' => 'disc', 'speed' => $speed, 'glide' => $glide, 'turn' => $turn, 'fade' => $fade, 'link' => $link, 'pic_link' => $pic_link));
					} catch (Exception $e) {
						echo 'Caught exception: ', $e->getMessage(), "\n";
					}
					$_SESSION['msdg_message'] = 'Adding/Updating disc was successful!';
				} else {
					wp_redirect(get_permalink());
				}
			}
		}
	}

	if (isset($_POST['update-color'])) {
		if ($_POST['choose-color'] !== '' && $_POST['text_color'] !== '') {
			$color = '#' . $_POST['choose-color'];
			$text_color = '#' . $_POST['text_color'];
			if ($_POST['old-color'] === '#' . $_POST['choose-color'] && $_POST['old_text_color'] === '#' . $_POST['text_color']) {
				wp_redirect(get_permalink());
			} else {
				$color_combo = $wpdb->get_row("SELECT * FROM $brands_table WHERE color = '$color' AND text_color = '$text_color'");
				if ($color_combo) {
					$_SESSION['msdg_error'] = 'Color combinations has already existed!';
				} else {
					$result = $wpdb->get_row("SELECT * FROM $brands_table WHERE ID = {$_POST['brand-id']}", OBJECT);
					if ($result) {
						$wpdb->update($brands_table, array('color' => '#' . $_POST['choose-color'], 'text_color' => '#' . $_POST['text_color']), array('ID' => $_POST['brand-id']));
						$_SESSION['msdg_message'] = 'Successfully update!';
					} else {
						wp_redirect(get_permalink());
					}
				}
			}
		} else {
			wp_redirect(get_permalink());
		}
	}

	if (isset($_POST['delete-brand'])) {
		$result = $wpdb->get_row("SELECT * FROM $brands_table WHERE ID = {$_POST['brand-id']}", OBJECT);
		if ($result) {
			$wpdb->delete($brands_table, array('ID' => $_POST['brand-id']));
			$wpdb->delete($discs_table, array('brand_id' => $_POST['brand-id']));
			$_SESSION['msdg_message'] = "Successfully removed all {$result->brand} products from flight guide!";
		} else {
			wp_redirect(get_permalink());
		}
	}

	if (isset($_POST['remove_disc'])) {
		$result = $wpdb->get_row("SELECT * FROM $discs_table WHERE ID = {$_POST['disc_id']}", OBJECT);
		if ($result) {
			$wpdb->delete($discs_table, array('ID' => $_POST['disc_id']));
			$_SESSION['msdg_message'] = "Successfully removed {$result->disc}!";
		} else {
			wp_redirect(get_permalink());
		}
	}

	if (isset($_POST['save_putter'])) {
		if ($_POST['putter_brand'] !== null && $_POST['putter_brand'] !== '') {
			if ($_POST['putter_name'] !== '' && $_POST['putter_name'] !== null) {
				$putters_array = [];
				$results = $wpdb->get_results("SELECT * FROM $discs_table WHERE type = 'putter'");
				foreach ($results as $result) {
					$putters_array[] = $result->disc;
				}
				if (! in_array($_POST['putter_name'], $putters_array, true)) {
					$brand = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}msdg_brands WHERE brand = '{$_POST['putter_brand']}'", OBJECT);
					if ($brand) {
						$wpdb->insert($discs_table, array('brand_id' => $brand->ID, 'disc' => $_POST['putter_name'], 'link' => $_POST['putter_link'] ,'type' => 'putter'));
						$_SESSION['msdg_message'] = 'Successfully add ' . $_POST['putter_name'] . '.';
					} else {
						wp_redirect(get_permalink());
					}
				} else {
					wp_redirect(get_permalink());
				}
			} else {
				wp_redirect(get_permalink());
			}
		} else {
			wp_redirect(get_permalink());
		}
	}

	if (isset($_POST['remove_putter'])) {
		$result = $wpdb->get_row("SELECT * FROM $discs_table WHERE ID = {$_POST['putter_id']}", OBJECT);
		if ($result) {
			$wpdb->delete($discs_table, array('ID' => $_POST['putter_id']));
			$_SESSION['msdg_message'] = 'Successfully removed ' . $result->disc . '!';
		} else {
			wp_redirect(get_permalink());
		}
	}