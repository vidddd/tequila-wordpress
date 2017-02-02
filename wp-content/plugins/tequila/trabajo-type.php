<?php
class sm_trabajo {
    function sm_trabajo() {
		add_action('init',array($this,'create_post_type'));
		add_action('init',array($this,'create_taxonomies'));
		//add_action('manage_sm_project_posts_columns',array($this,'columns'),10,2);
		//add_action('manage_sm_project_posts_custom_column',array($this,'column_data'),11,2);
		//add_filter('posts_join',array($this,'join'),10,1);
		//add_filter('posts_orderby',array($this,'set_default_sort'),20,2);
	}


    function create_post_type() {
      $labels = array(
        'name'               => 'Trabajos',
        'singular_name'      => 'Trabajo',
        'menu_name'          => 'Trabajos',
        'name_admin_bar'     => 'Trabajo',
        'add_new'            => 'Add New',
        'add_new_item'       => 'Add New Trabajo',
        'new_item'           => 'New Trabajo',
        'edit_item'          => 'Edit Trabajo',
        'view_item'          => 'View Trabajo',
        'all_items'          => 'All Trabajos',
        'search_items'       => 'Search Trabajos',
        'parent_item_colon'  => 'Parent Trabajo',
        'not_found'          => 'No Trabajos Found',
        'not_found_in_trash' => 'No Trabajos Found in Trash'
      );

      $args = array(
        'labels'              => $labels,
        'public'              => true,
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        'show_ui'             => true,
        'show_in_nav_menus'   => true,
        'show_in_menu'        => true,
        'show_in_admin_bar'   => true,
        'menu_position'       => 5,
        'menu_icon'           => 'dashicons-admin-appearance',
        'capability_type'     => 'post',
        'hierarchical'        => false,
        'supports'            => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt'),
        'has_archive'         => true,
        'rewrite'             => array( 'slug' => 'trabajos' ),
        'query_var'           => true
      );

      register_post_type( 'sm_trabajo', $args );
    }
    
    function create_taxonomies() {

		// Add new taxonomy, make it hierarchical (like categories)
		$labels = array(
			'name'              => 'Categorias',
			'singular_name'     => 'Categoria',
			'search_items'      => 'Búsqueda de Categoria',
			'all_items'         => 'Todas las Categorias',
			'parent_item'       => 'Categoria Padre',
			'parent_item_colon' => 'Categoria Padre:',
			'edit_item'         => 'Editar Categoria',
			'update_item'       => 'Actualizar Categoria',
			'add_new_item'      => 'Nueva Categoria',
			'new_item_name'     => 'Nuevo nombre de categoria',
			'menu_name'         => 'Categorias de Trabajo',
		);

		$args = array(
			'hierarchical'      => true,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => array( 'slug' => 'categoria' ),
		);

		register_taxonomy('sm_trabajo_type',array('sm_trabajo'),$args);

		// Add new taxonomy, NOT hierarchical (like tags)
		/*
                $labels = array(
			'name'                       => 'Attributes',
			'singular_name'              => 'Attribute',
			'search_items'               => 'Attributes',
			'popular_items'              => 'Popular Attributes',
			'all_items'                  => 'All Attributes',
			'parent_item'                => null,
			'parent_item_colon'          => null,
			'edit_item'                  => 'Edit Attribute',
			'update_item'                => 'Update Attribute',
			'add_new_item'               => 'Add New Attribute',
			'new_item_name'              => 'New Attribute Name',
			'separate_items_with_commas' => 'Separate Attributes with commas',
			'add_or_remove_items'        => 'Add or remove Attributes',
			'choose_from_most_used'      => 'Choose from most used Attributes',
			'not_found'                  => 'No Attributes found',
			'menu_name'                  => 'Attributes',
		);

		$args = array(
			'hierarchical'          => false,
			'labels'                => $labels,
			'show_ui'               => true,
			'show_admin_column'     => true,
			'update_count_callback' => '_update_post_term_count',
			'query_var'             => true,
			'rewrite'               => array( 'slug' => 'attribute' ),
		);

		register_taxonomy('sm_trabajo_attribute','sm_trabajo',$args);
                 * 
                 */
	}   

}