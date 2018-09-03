<?php

namespace Osprey\Form\Style;

class Table extends AbstractStyle {

	/**
	 * {@inheritdoc}
	 */
	public function id() {
		return 'table';
	}

	/**
	 * {@inheritdoc}
	 */
	public function wrapForm( $form ) {
		return "<table class='form form-wrapper-{$this->id()}'>{$form}</table>";
	}

	/**
	 * {@inheritdoc}
	 */
	public function wrapField( $field, $html ) {
		ob_start();
		?>
		<tr  id="<?php echo esc_attr( $field['id'] ) ;?>--wrapper"
		     class="field-wrapper field-wrapper-table">
			<th scope="row">
				<?php if ( !empty( $field['title'] ) ) : ?>
					<label for="<?php echo esc_attr( $field['id'] ); ?>" class="field-label">
						<?php echo $field['title']; ?>
					</label>
				<?php endif; ?>
			</th>
			<td>
				<?php echo $html; ?>

				<?php if ( !empty( $field['description'] ) ) : ?>
					<p class="description"><?php echo $field['description']; ?></p>
				<?php endif; ?>

				<?php if ( !empty( $field['help'] ) ) : ?>
					<p class="description"><?php echo $field['help']; ?></p>
				<?php endif; ?>
			</td>
		</tr>
		<?php
		return ob_get_clean();
	}
}
