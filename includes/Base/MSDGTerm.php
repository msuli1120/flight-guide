<?php
	namespace MSDG\Base;

	class MSDGTerm
	{
		public static function getTerms()
		{
			$term = get_term_by('name', 'Manufacturers','product_cat');
			$term_id = $term->term_id;
			$taxonomy = $term->taxonomy;
			$brands = [];
			$term_ids = get_term_children($term_id, $taxonomy);
			foreach ($term_ids as $child_id) {
				$brands[] = get_term_by('id', $child_id, $taxonomy)->name;
			}
			asort($brands);
			return $brands;
		}
	}