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
   

