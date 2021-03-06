<?php
class sm_cliente {
    function sm_cliente() {
		add_action('init',array($this,'create_post_type'));
		add_action('init',array($this,'create_taxonomies'));
		//add_action('manage_sm_project_posts_columns',array($this,'columns'),10,2);
		//add_action('manage_sm_project_posts_custom_column',array($this,'column_data'),11,2);
		//add_filter('posts_join',array($this,'join'),10,1);
		//add_filter('posts_orderby',array($this,'set_default_sort'),20,2);
	}


    function create_post_type() {
      $labels = array(
        'name'               => 'Clientes',
        'singular_name'      => 'Cliente',
        'menu_name'          => 'Clientes',
        'name_admin_bar'     => 'Cliente',
        'add_new'            => 'Add New',
        'add_new_item'       => 'Add New Cliente',
        'new_item'           => 'New Cliente',
        'edit_item'          => 'Edit Cliente',
        'view_item'          => 'View Cliente',
        'all_items'          => 'All Clientes',
        'search_items'       => 'Search Clientes',
        'parent_item_colon'  => 'Parent Cliente',
        'not_found'          => 'No Clientes Found',
        'not_found_in_trash' => 'No Clientes Found in Trash'
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
        'rewrite'             => array( 'slug' => 'clientes' ),
        'query_var'           => true
      );

      register_post_type( 'sm_cliente', $args );
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
			'menu_name'         => 'Categorias de Cliente',
		);

		$args = array(
			'hierarchical'      => true,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => array( 'slug' => 'categoria' ),
		);

		register_taxonomy('sm_cliente_type',array('sm_cliente'),$args);

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

		register_taxonomy('sm_cliente_attribute','sm_cliente',$args);
                 * 
                 */
	}   

}