<?php

namespace Osprey\Entity;

/**
 * Class User
 *
 * @package Osprey\Entity
 */
class User extends AbstractEntity implements EntityBundleInterface, EntityTitleInterface, EntityImageInterface, EntityAuthorInterface {

	/**
	 * @var \WP_User
	 */
	protected $object;

	/**
	 * {@inheritdoc}
	 */
	static public function load( $id ) {
		$object = get_user_by( 'id', $id );

		return new self( $object );
	}

	/**
	 * {@inheritdoc}
	 */
	public function type() {
		return 'user';
	}

	/**
	 * {@inheritdoc}
	 */
	public function id() {
		return $this->object->ID;
	}

	/**
	 * {@inheritdoc}
	 */
	public function content() {
		return $this->meta( 'description' );
	}

	/**
	 * {@inheritdoc}
	 */
	public function url() {
		return get_author_posts_url( $this->id() );
	}

	/**
	 * {@inheritdoc}
	 */
	public function slug() {
		return $this->object->user_nicename;
	}

	/**
	 * {@inheritdoc}
	 */
	public function title() {
		return $this->object->display_name;
	}

	/**
	 * {@inheritdoc}
	 */
	public function bundle() {
		return $this->object->roles;
	}

	/**
	 * Roles can act as bundle information. The alternative is that Users don't
	 * have bundles.
	 *
	 * {@inheritdoc}
	 */
	public function bundleInfo() {
		$roles = [];

		foreach ($this->object->roles as $role ) {
			$roles[ $role ] = get_role( $role );
		}

		return $roles;
	}

	/**
	 * {@inheritdoc}
	 */
	public function image( $size = null ) {
		return get_avatar( $this->id(), $size, '', $this->title() );
	}

	/**
	 * {@inheritdoc}
	 */
	public function author() {
		return $this;
	}

	/**
	 * The User's email address.
	 *
	 * @return string
	 */
	public function email() {
		$email = $this->meta( 'user_email' );

		if (!empty($email)) {
			return $email;
		}

		if (!empty($this->object->user_email)) {
			return $this->object->user_email;
		}

		return '';
	}

}