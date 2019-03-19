<?php
/*
Plugin Name: GiveWP Integration for AppPresser
Plugin URI: https://apppresser.com
Description: Auto-populates the donation form when linked to from the app.
Version: 1.0.0
Author: AppPresser Team
Author URI: https://apppresser.com
License: GPLv2
*/

defined( 'ABSPATH' ) || exit;

if( !class_exists( 'AppPresser_GiveWP' ) ) {

    /**
     * Main AppPresser_GiveWP class
     *
     * @since       0.1.0
     */
    class AppPresser_GiveWP {

        /**
         * @var         AppPresser_GiveWP $instance The one true AppPresser_GiveWP
         * @since       0.1.0
         */
        private static $instance;


        /**
         * Get active instance
         *
         * @access      public
         * @since       0.1.0
         * @return      self
         */
        public static function instance() {
            
            if( !self::$instance ) {
                self::$instance = new AppPresser_GiveWP();
                self::$instance->hooks();
            }

            return self::$instance;
        }

        public function hooks() {

        	// Hooking into the single form view.
			add_action( 'give_post_form_output', array( $this, 'give_populate_amount_name_email' ) );

        }

        /**
		 * AUTO-POPULATE AMOUNT, NAME, and EMAIL FROM URL STRING
		 *
		 * This jQuery snippet will auto-populate the Give form amount,
		 * first and last name, and email address from a URL you provide
		 * EXAMPLE: https://example.com/donations/give-form/?amount=46.00&first=Peter&last=Joseph&email=testing@givewp.com
		 *
		 * CAVEATS:
		 * -- Your form must support custom amounts
		 * -- This snippet only supports one form per page as-is
		 */
		public function give_populate_amount_name_email() {
			?>

			<script>
				
				// use an enclosure so we don't pollute the global space
				(function(window, document, $, undefined){

					'use strict';

					var giveCustom = {};

					giveCustom.init = function() {

						// Get the amount from the URL
						var getamount = giveCustom.getQueryVariable("amount");
						var amount = '1.00';
						// Set fallback in case URL variable isn't set
						if ( getamount !== false ) {
							amount = getamount;
						}
						var firstname = ( giveCustom.getQueryVariable("first") !== false ) ? decodeURIComponent(giveCustom.getQueryVariable("first")) : '';
						var lastname = ( giveCustom.getQueryVariable("last") !== false ) ? decodeURIComponent(giveCustom.getQueryVariable("last")) : '';
						var email = ( giveCustom.getQueryVariable("email") !== false ) ? decodeURIComponent(giveCustom.getQueryVariable("email")) : '';
						// Populate the amount field, then update the total
						if ( $('#give-amount').length > 0 ) {
							$('#give-amount')
								.attr('value', amount).attr('data-amount', amount);
							$('#give-final-total-wrap .give-final-total-amount').attr('data-total', amount ).text(amount);
							
						}
						if ( firstname !== false && $('#give-first-name-wrap input.give-input').length > 0 ) {
							$('#give-first-name-wrap input.give-input')
								.val(firstname);
						}
						if ( lastname !== false && $('#give-last-name-wrap input.give-input').length > 0 ) {
							$('#give-last-name-wrap input.give-input')
								.val(lastname);
						}
						if ( email !== false && $('#give-email-wrap input.give-input').length > 0 ) {
							$('#give-email-wrap input.give-input')
								.val(email);
						}

					}

					giveCustom.getQueryVariable = function( variable ) {

						var query = window.location.search.substring(1);
						var vars = query.split("&");
						for (var i=0;i<vars.length;i++) {
							var pair = vars[i].split("=");
							if(pair[0] == variable){return pair[1];}
						}
						return(false);

					}

					giveCustom.init();

				})(window, document, jQuery);

			</script>

			<?php
		}

    } // end class

    $appp_givewp = new AppPresser_GiveWP();
    $appp_givewp->instance();

} // end if
