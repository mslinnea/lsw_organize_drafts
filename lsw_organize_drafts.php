<?php
/**
 * Plugin Name: Organize Drafts
 * Plugin URI: https://www.linsoftware.com/organize-drafts/
 * Description: Organize WordPress Drafts with "Draft Types."  Think of draft types as folders for sorting your drafts. Use the default types or add your own custom draft types.
 * Author: Linnea Huxford
 * Author URI: https://www.linsoftware.com
 * Version: 1.1.0
 * License: GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: lsw_organize_drafts
 * Tags: organization, organize, workflow, work-flow, folders, drafts, editing
 * Organize WordPress Drafts with "Draft Types."  Think of draft types as folders for sorting your
 * drafts. Use the default types or add your own custom draft types.
 * Improve your editing workflow and de-clutter your drafts.
 * By default, works with posts and pages. See the FAQ for how to configure this to work with custom post types.
 **/


final class LSW_Organize_Drafts {


	/**
	 * @var string
	 */
	public $version = '1.1.0';

	/**
	 * @var LSW_Organize_Drafts The single instance of the class
	 */
	protected static $_instance = null;

	/**
	 * @var array
	 */
	private static $draft_types = array();

	/**
	 * @var array
	 */
	private static $post_types = array();

	/**
	 * Main LSW_Organize_Drafts Instance
	 *
	 * Ensures only one instance of LSW_Organize_Drafts is loaded or can be loaded.
	 *
	 * @static
	 * @return LSW_Organize_Drafts
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	public function __construct() {
		LSW_Organize_Drafts::$post_types  = array( 'post', 'page' );
		LSW_Organize_Drafts::$draft_types = array(
			'Idea',
			'Outline',
			'Rough Draft',
			'In Review',
			'Needs Images',
			'Ready to Publish',
		);
		$this->hooks();
	}

	private function hooks() {
		if ( ! is_admin() ) {
			return;
		}
		add_action( 'init', array( $this, 'setup_taxonomy' ) );
		add_filter( 'manage_posts_columns', array( 'LSW_Organize_Drafts', 'control_columns' ) );
		add_filter( 'manage_pages_columns', array( 'LSW_Organize_Drafts', 'control_columns' ) );
		add_action( 'admin_menu', array( 'LSW_Organize_Drafts', 'update_meta_boxes' ) );
		add_action( 'save_post', array( 'LSW_Organize_Drafts', 'save_taxonomy_data' ), 10, 2 );
		$post_types = apply_filters( 'lsw_default_post_types', LSW_Organize_Drafts::$post_types );
		foreach ( $post_types as $type ) {
			add_filter( 'manage_edit-' . $type . '_sortable_columns', array(
				'LSW_Organize_Drafts',
				'make_column_sortable'
			) );
		}
	}

	public static function make_column_sortable( $sortable_columns ) {

		$sortable_columns['taxonomy-lswdrafttype'] = 'lswdrafttype';

		return $sortable_columns;
	}

	public static function save_taxonomy_data( $post_id, $post ) {

		// if this is autosave, do nothing
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		// verify this came from our screen and with proper authorization.
		if ( ! isset( $_POST['taxonomy_noncename'] ) || ! wp_verify_nonce( $_POST['taxonomy_noncename'],
				'taxonomy_lswdrafttype' ) ) {
			return;
		}

		// Check permissions
		$edit_cap = 'edit_' . $post->post_type;
		if ( ! current_user_can( $edit_cap, $post_id ) ) {
			return;
		}

		// OK, we're authenticated: we need update the draft type
		if ( isset( $_POST['post_lswdrafttype'] ) ) {
			$post_type = sanitize_text_field( wp_unslash( $_POST['post_lswdrafttype'] ?? '' ) );
			wp_set_object_terms( $post_id, $post_type, 'lswdrafttype' );
		}
	}


	public static function update_meta_boxes() {
		$post_types = apply_filters( 'lsw_default_post_types', LSW_Organize_Drafts::$post_types );
		remove_meta_box( 'tagsdiv-lswdrafttype', $post_types, 'side' );
		add_meta_box( 'lswdrafttype_custom', __( 'Draft Type', 'lsw_organize_drafts' ), array(
			'LSW_Organize_Drafts',
			'render_meta_box'
		), $post_types, 'side', 'core' );
	}

	public static function render_meta_box( $post ) {

		if ( $post->post_status !== 'draft' ) {
			printf( esc_html__( 'Not a draft' ) );

			return;
		}
		echo '<input type="hidden" name="taxonomy_noncename" id="taxonomy_noncename" value="' .
			 wp_create_nonce( 'taxonomy_lswdrafttype' ) . '" />';

		// Get all lswdrafttype taxonomy terms
		$types = get_terms( 'lswdrafttype', 'hide_empty=0' );

		?>
		<select name='post_lswdrafttype' id='post_lswdrafttype'>
			<!-- Display types as options -->
			<?php
			$names = get_the_terms( $post->ID, 'lswdrafttype' );
			?>
			<option class='lswdrafttype-option' value=''
				<?php if ( empty( $names ) ) {
					echo "selected";
				} ?>>None
			</option>
			<?php
			foreach ( $types as $type ) {
				if ( ! is_wp_error( $types ) && ! empty( $types ) && ! strcmp( $type->slug, $names[0]->slug ) ) {
					echo "<option class='lswdrafttype-option' value='" . $type->slug . "' selected>" . $type->name . "</option>\n";
				} else {
					echo "<option class='lswdrafttype-option' value='" . $type->slug . "'>" . $type->name . "</option>\n";
				}
			}
			?>
		</select>
		<?php
	}

	public static function control_columns( $columns ) {
		if ( isset( $_GET['post_status'] ) && 'draft' == $_GET['post_status'] ) {
			return $columns;
		} else {
			unset( $columns['taxonomy-lswdrafttype'] );

			return $columns;
		}
	}


	public function setup_taxonomy() {
		$args       = apply_filters( 'lsw_draft_types_args', $this->get_tax_args() );
		$post_types = apply_filters( 'lsw_default_post_types', LSW_Organize_Drafts::$post_types );
		if ( ! taxonomy_exists( 'lswdrafttype' ) ) {
			register_taxonomy( 'lswdrafttype', $post_types, $args );
		}
	}

	public static function activate() {
		$lsw_organize_drafts = LSW_Organize_Drafts::instance();
		$lsw_organize_drafts->setup_taxonomy();
		if ( ! LSW_Organize_Drafts::areDefaultTermsRegistered() ) {
			LSW_Organize_Drafts::registerDefaultTerms();
		}
	}

	public static function deactivate() {
		delete_option( 'lsw_organize_drafts' );
	}

	private static function areDefaultTermsRegistered() {
		$options = LSW_Organize_Drafts::getPluginOptions();
		if ( array_key_exists( 'default_terms_setup', $options ) ) {
			return $options['default_terms_setup'] == 1;
		} else {
			return false;
		}
	}

	private static function getPluginOptions() {
		return get_option( 'lsw_organize_drafts', array() );
	}

	private static function updateOption( $newValue, $key ) {
		$options         = LSW_Organize_Drafts::getPluginOptions();
		$options[ $key ] = $newValue;
		update_option( 'lsw_organize_drafts', $options );
	}

	private static function registerDefaultTerms() {
		foreach ( LSW_Organize_Drafts::$draft_types as $type ) {
			wp_insert_term( $type, 'lswdrafttype' );
		}
		LSW_Organize_Drafts::updateOption( 'default_terms_setup', 1 );
	}

	private function get_tax_args() {
		$labels = array(
			'name'                  => __( 'Draft Types', 'lsw_organize_drafts' ),
			'singular_name'         => __( 'Draft Types', 'lsw_organize_drafts' ),
			'menu_name'             => __( 'Draft Types', 'lsw_organize_drafts' ),
			'all_items'             => __( 'All Draft Types', 'lsw_organize_drafts' ),
			'add_new_item'          => __( 'Add New Type', 'lsw_organize_drafts' ),
			'choose_from_most_used' => __( 'Choose From the Most Used Draft Types', 'lsw_organize_drafts' ),
		);

		return array(
			'public'             => false,
			'hierarchical'       => false,
			'show_in_quick_edit' => false,
			'labels'             => $labels,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'show_admin_column'  => true,
			'query_var'          => true,
		);
	}

}


function LSW_Organize_Drafts() {
	return LSW_Organize_Drafts::instance();
}

LSW_Organize_Drafts();
register_activation_hook( __FILE__, array( 'LSW_Organize_Drafts', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'LSW_Organize_Drafts', 'deactivate' ) );
