<?php
// To improve WooCommerce backend performance, there are several other hooks and optimizations you can implement 
// in addition to the ones you've shared. 


## 1.) Disable WooCommerce Admin (Analytics)
// WooCommerce’s new Admin interface adds extra overhead. Disabling it can improve backend performance.
   
   add_filter( 'woocommerce_admin_disabled', '__return_true' );
 

## 2.) Disable Action Scheduler**
   //WooCommerce uses Action Scheduler for background tasks. If you aren't using features like subscriptions or delayed actions, you can disable it.


   add_filter( 'action_scheduler_disable_default_queue_runner', '__return_true' );
  

## 3.)  Disable Order Status Checks**
   //Prevent WooCommerce from checking order statuses continuously in the admin.

   add_filter( 'woocommerce_order_needs_processing', '__return_false' );
 

## 4.) Remove Dashboard Widgets**
   //WooCommerce adds widgets to the WordPress dashboard, which can slow things down. You can remove them.
  
   add_action( 'wp_dashboard_setup', function() {
       remove_meta_box( 'woocommerce_dashboard_status', 'dashboard', 'normal' );
   } );
 

## 5.)  Disable Admin Notifications**
   //WooCommerce shows various notifications in the admin. Disabling them can speed up load times.

   
   add_filter( 'woocommerce_helper_suppress_admin_notices', '__return_true' );
  

## 6.) Disable Widgets and Blocks**
   //If you're not using the WooCommerce widgets and blocks on the backend, you can remove them to reduce load.

 
   add_action( 'widgets_init', function() {
       unregister_widget( 'WC_Widget_Recent_Reviews' );
       unregister_widget( 'WC_Widget_Recent_Products' );
       // Add more widgets to unregister if needed
   }, 11 );
  

## 7.)  Avoid Loading Unnecessary Scripts**
   //If you have a heavily customized WooCommerce dashboard, you can deregister scripts/styles that aren’t needed on certain admin pages.

   
   add_action( 'admin_enqueue_scripts', function( $hook ) {
       if ( $hook !== 'woocommerce_page_wc-settings' ) {
           wp_dequeue_script( 'woocommerce_admin' );
           wp_dequeue_style( 'woocommerce_admin' );
       }
   } );
   

// Do not add this as a file to your parent theme and do not add this as a file to your child theme. The proper way is to decidee which hooks you need to speed up your backend 
// and apply them. I advise creating a folder structure of inc/hooks/wp/ or inc/hooks/wc/ then add a file called hooks.php inside the appropriate folder. Since this is WooCommerce I
// advise placing a hook.php file inside inc/hooks/wc/hooks.php. Ensure you have started your file with <?php and then add the hook you want to add.
// I take no responisibilty if your site crashes, you use these hooks at your own risk. I do not offer any support for free. It's all paid.
