<?php

namespace Osprey\Form\Field;

use Osprey\Form\Form;

class ItemList implements FieldInterface {

	/**
	 * Simple item list
	 *  - expects an array of items as $field['items']
	 *
	 * @param $field
	 *
	 * @return string
	 */
	function render( $field ) {
		ob_start();
		?>
		<ul class="<?php echo esc_attr( $field['classes'] ); ?>"
			<?php echo Form::attributes( $field['attributes'] ); ?>>
			<?php
			foreach ( $field['items'] as $item ) { ?>
				<li><?php print $item; ?></li>
				<?php
			}
			?>
		</ul>
		<?php
		return ob_get_clean();
	}

}
