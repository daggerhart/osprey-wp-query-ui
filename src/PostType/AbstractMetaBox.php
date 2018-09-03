<?php

namespace Osprey\PostType;

use Osprey\Form\Form;

abstract class AbstractMetaBox implements MetaBoxInterface {

	/**
	 * {@inheritdoc}
	 */
	abstract function id();

	/**
	 * {@inheritdoc}
	 */
	abstract function title();

	/**
	 * {@inheritdoc}
	 */
	public function embed( $post, $box ) {
		wp_nonce_field( "osprey_{$this->id()}_action", "osprey_{$this->id()}" );
	}

	/**
	 * {@inheritdoc}
	 */
	public function save( $post_id ) {
		// Add nonce for security and authentication.
		$nonce_name   = isset( $_POST["osprey_{$this->id()}"] ) ? $_POST["osprey_{$this->id()}"] : '';
		$nonce_action = "osprey_{$this->id()}_action";

		// Check if nonce is set.
		if ( empty( $nonce_name ) ) {
			return FALSE;
		}

		// Check if nonce is valid.
		if ( ! wp_verify_nonce( $nonce_name, $nonce_action ) ) {
			return FALSE;
		}

		// Check if user has permissions to save data.
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return FALSE;
		}

		// Check if not an autosave.
		if ( wp_is_post_autosave( $post_id ) ) {
			return FALSE;
		}

		// Check if not a revision.
		if ( wp_is_post_revision( $post_id ) ) {
			return FALSE;
		}

		return TRUE;
	}

	/**
	 * Create and return a form object.
	 *
	 * @param array $form_args
	 *
	 * @return Form
	 */
	public function form( $form_args = [] ) {
		$form_args = array_replace([
			'field_prefix' => $this->id(),
		], $form_args);

		return new Form($form_args);
	}
}
