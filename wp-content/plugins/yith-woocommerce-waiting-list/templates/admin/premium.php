<?php
/**
 * Admin View: Premium Tab
 *
 * @author  YITH <plugins@yithemes.com>
 * @package YITH WooCommerce Waiting List
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>

<style>
    .section{
        margin-left: -20px;
        margin-right: -20px;
        font-family: "Raleway",san-serif;
    }
    .section h1{
        text-align: center;
        text-transform: uppercase;
        color: #808a97;
        font-size: 35px;
        font-weight: 700;
        line-height: normal;
        display: inline-block;
        width: 100%;
        margin: 50px 0 0;
    }
    .section:nth-child(even){
        background-color: #fff;
    }
    .section:nth-child(odd){
        background-color: #f1f1f1;
    }
    .section .section-title img{
        display: table-cell;
        vertical-align: middle;
        width: auto;
        margin-right: 15px;
    }
    .section h2,
    .section h3 {
        display: inline-block;
        vertical-align: middle;
        padding: 0;
        font-size: 24px;
        font-weight: 700;
        color: #808a97;
        text-transform: uppercase;
    }

    .section .section-title h2{
        display: table-cell;
        vertical-align: middle;
        line-height: 25px;
        border: none;
        background: transparent;
    }

    .section-title{
        display: table;
    }

    .section h3 {
        font-size: 14px;
        line-height: 28px;
        margin-bottom: 0;
        display: block;
    }

    .section p{
        font-size: 13px;
        margin: 25px 0;
    }
    .section ul li{
        margin-bottom: 4px;
    }
    .landing-container{
        max-width: 750px;
        margin-left: auto;
        margin-right: auto;
        padding: 50px 0 30px;
    }
    .landing-container:after{
        display: block;
        clear: both;
        content: '';
    }
    .landing-container .col-1,
    .landing-container .col-2{
        float: left;
        box-sizing: border-box;
        padding: 0 15px;
    }
    .landing-container .col-1 img{
        width: 100%;
    }
    .landing-container .col-1{
        width: 55%;
    }
    .landing-container .col-2{
        width: 45%;
    }
    .premium-cta{
        background-color: #808a97;
        color: #fff;
        border-radius: 6px;
        padding: 20px 15px;
    }
    .premium-cta:after{
        content: '';
        display: block;
        clear: both;
    }
    .premium-cta p{
        margin: 7px 0;
        font-size: 15px;
        font-weight: 500;
        display: inline-block;
        width: 60%;
    }
    .premium-cta a.button{
        border-radius: 6px;
        height: 60px;
        float: right;
        background: url('<?php echo esc_url( YITH_WCWTL_URL ); ?>assets/images/upgrade.png') #ff643f no-repeat 13px 13px;
        border-color: #ff643f;
        box-shadow: none;
        outline: none;
        color: #fff;
        position: relative;
        padding: 9px 50px 9px 70px;
    }
    .premium-cta a.button:hover,
    .premium-cta a.button:active,
    .premium-cta a.button:focus{
        color: #fff;
        background: url(<?php echo esc_url( YITH_WCWTL_URL ); ?>assets/images/upgrade.png) #971d00 no-repeat 13px 13px;
        border-color: #971d00;
        box-shadow: none;
        outline: none;
    }
    .premium-cta a.button:focus{
        top: 1px;
    }
    .premium-cta a.button span{
        line-height: 13px;
    }
    .premium-cta a.button .highlight{
        display: block;
        font-size: 20px;
        font-weight: 700;
        line-height: 20px;
    }
    .premium-cta .highlight{
        text-transform: uppercase;
        background: none;
        font-weight: 800;
        color: #fff;
    }

    @media (max-width: 768px) {
        .section{margin: 0}
        .premium-cta p{
            width: 100%;
        }
        .premium-cta{
            text-align: center;
        }
        .premium-cta a.button{
            float: none;
        }
    }

    @media (max-width: 480px){
        .wrap{
            margin-right: 0;
        }
        .section{
            margin: 0;
        }
        .landing-container .col-1,
        .landing-container .col-2{
            width: 100%;
            padding: 0 15px;
        }
        .section-odd .col-1 {
            float: left;
            margin-right: -100%;
        }
        .section-odd .col-2 {
            float: right;
            margin-top: 65%;
        }
    }

    @media (max-width: 320px){
        .premium-cta a.button{
            padding: 9px 20px 9px 70px;
        }

        .section .section-title img{
            display: none;
        }
    }
</style>
<div class="landing">
	<div class="section section-cta section-odd">
		<div class="landing-container">
			<div class="premium-cta">
				<p>
					<?php
					// translators: %s: //TODO description.
					echo sprintf( esc_html__( 'Upgrade to %1$spremium version%2$s of %1$sYITH WooCommerce Waiting List%2$s to benefit from all features!', 'yith-woocommerce-waiting-list' ), '<span class="highlight">', '</span>' );
					?>
				</p>
				<a href="<?php echo esc_url( YITH_WCWTL_Admin()->get_premium_landing_uri() ); ?>" target="_blank" class="premium-cta-button button btn">
					<span class="highlight"><?php esc_html_e( 'UPGRADE', 'yith-woocommerce-waiting-list' ); ?></span>
					<span><?php esc_html_e( 'to the premium version', 'yith-woocommerce-waiting-list' ); ?></span>
				</a>
			</div>

		</div>
	</div>
	<div class="section section-even clear" style="background: url(<?php echo esc_url( YITH_WCWTL_URL ); ?>assets/images/01-bg.png) no-repeat #fff; background-position: 85% 75%">
		<h1>Premium Features</h1>
		<div class="landing-container">
			<div class="col-1">
				<img src="<?php echo esc_url( YITH_WCWTL_URL ); ?>assets/images/01.png" />
			</div>
			<div class="col-2">
				<div class="section-title">
					<img src="<?php echo esc_url( YITH_WCWTL_URL ); ?>assets/images/01-icon.png"/>
					<h2><?php esc_html_e( 'Custom messages', 'yith-woocommerce-waiting-list' ); ?></h2>
				</div>
				<?php
				// translators: %s: //TODO description.
				echo sprintf( esc_html__( 'Notifications shown to your users during their subscription to the list are entirely customizable.%3$s Write the message text you want to show both for %1$ssuccessful subscription%2$s and for %1$sunsuccessful subscription%2$s', 'yith-woocommerce-waiting-list' ), '<b>', '</b>', '<br>' );
				?>
			</div>
		</div>
	</div>
	<div class="section section-odd clear" style="background: url(<?php echo esc_url( YITH_WCWTL_URL ); ?>assets/images/02-bg.png) no-repeat #f1f1f1; background-position: 15% 100%">
		<div class="landing-container">
			<div class="col-2">
				<div class="section-title">
					<img src="<?php echo esc_url( YITH_WCWTL_URL ); ?>assets/images/02-icon.png"/>
					<h2><?php esc_html_e( 'Send emails automatically', 'yith-woocommerce-waiting-list' ); ?></h2>
				</div>
				<p>
					<?php
					// translators: %s: //TODO description.
					echo sprintf( esc_html__( 'An option explicitly conceived to relieve you of the task to manually generate the email as soon as the product status is set as “Available”. %3$sIn fact, with the premium version, %1$semail sending is automatic%2$s and allows you to automatically manage them and keep your users up to date.', 'yith-woocommerce-waiting-list' ), '<b>', '</b>', '<br>' );
					?>
				</p>
				<p>

				</p>
			</div>
			<div class="col-1">
				<img src="<?php echo esc_url( YITH_WCWTL_URL ); ?>assets/images/02.png" />
			</div>
		</div>
	</div>
	<div class="section section-even clear" style="background: url(<?php echo esc_url( YITH_WCWTL_URL ); ?>assets/images/03-bg.png) no-repeat #fff; background-position: 85% 100%">
		<div class="landing-container">
			<div class="col-1">
				<img src="<?php echo esc_url( YITH_WCWTL_URL ); ?>assets/images/03.png" />
			</div>
			<div class="col-2">
				<div class="section-title">
					<img src="<?php echo esc_url( YITH_WCWTL_URL ); ?>assets/images/03-icon.png" />
					<h2><?php esc_html_e( 'Keep the list after sending the email', 'yith-woocommerce-waiting-list' ); ?></h2>
				</div>
				<p>
					<?php
					// translators: %s: //TODO description.
					echo sprintf( esc_html__( 'If you do not want your list is emptied after the product comes back as "available", enable the option %1$s"Keep the list after email"%2$s and you will be able to generate a new email for users in that list whenever you want.', 'yith-woocommerce-waiting-list' ), '<b>', '</b>' );
					?>
				</p>
			</div>
		</div>
	</div>
	<div class="section section-odd clear" style="background: url(<?php echo esc_url( YITH_WCWTL_URL ); ?>assets/images/04-bg.png) no-repeat #f1f1f1; background-position: 15% 100%">
		<div class="landing-container">
			<div class="col-2">
				<div class="section-title">
					<img src="<?php echo esc_url( YITH_WCWTL_URL ); ?>assets/images/04-icon.png" />
					<h2><?php esc_html_e( 'Customize the style', 'yith-woocommerce-waiting-list' ); ?></h2>
				</div>
				<p>
					<?php
					// translators: %s: //TODO description.
					echo sprintf( esc_html__( 'A rich panel option from which you can shape buttons for subscription and deletion from the list and suit them to the layout of your shop. %1$sDetails are what make the difference%2$s and you must have the best tools to get the best results.', 'yith-woocommerce-waiting-list' ), '<b>', '</b>' );
					?>
				</p>
			</div>
			<div class="col-1">
				<img src="<?php echo esc_url( YITH_WCWTL_URL ); ?>assets/images/04.png" />
			</div>
		</div>
	</div>
	<div class="section section-even clear" style="background: url(<?php echo esc_url( YITH_WCWTL_URL ); ?>assets/images/05-bg.png) no-repeat #fff; background-position: 85% 75%">
		<div class="landing-container">
			<div class="col-1">
				<img src="<?php echo esc_url( YITH_WCWTL_URL ); ?>assets/images/05.png" />
			</div>
			<div class="col-2">
				<div class="section-title">
					<img src="<?php echo esc_url( YITH_WCWTL_URL ); ?>assets/images/05-icon.png" />
					<h2><?php esc_html_e( 'Notification email', 'yith-woocommerce-waiting-list' ); ?></h2>
				</div>
				<p>
					<?php
					// translators: %s: //TODO description.
					echo sprintf( esc_html__( 'Any time users sends a subscription request, they will be instantly sent a notification email that confirms they have been successfully added to the list. %3$sMoreover, from the same email %1$sthey will be able to unsubscribe%2$s from the list whenever they wanted.', 'yith-woocommerce-waiting-list' ), '<b>', '</b>', '<br>' );
					?>
				</p>
				<p>

				</p>
			</div>
		</div>
	</div>
	<div class="section section-odd clear" style="background: url(<?php echo esc_url( YITH_WCWTL_URL ); ?>assets/images/06-bg.png) no-repeat #f1f1f1; background-position: 15% 100%">
		<div class="landing-container">
			<div class="col-2">
				<div class="section-title">
					<img src="<?php echo esc_url( YITH_WCWTL_URL ); ?>assets/images/06-icon.png" />
					<h2><?php esc_html_e( 'Custom email content', 'yith-woocommerce-waiting-list' ); ?></h2>
				</div>
				<p>
					<?php
					// translators: %s: //TODO description.
					echo sprintf( esc_html__( 'The plugin generates two types of email, one that confirms a successful subscription and abother one to inform users that the product is back in store. For both of them, %1$syou can customize contents and template file as you like%2$s. Product data can be recovered dynamically using specific placeholders.', 'yith-woocommerce-waiting-list' ), '<b>', '</b>', '<br>' );
					?>
				</p>
				<p>

				</p>
			</div>
			<div class="col-1">
				<img src="<?php echo esc_url( YITH_WCWTL_URL ); ?>assets/images/06.png" />
			</div>
		</div>
	</div>
	<div class="section section-even clear" style="background: url(<?php echo esc_url( YITH_WCWTL_URL ); ?>assets/images/07-bg.png) no-repeat #fff; background-position: 85% 100%">
		<div class="landing-container">
			<div class="col-1">
				<img src="<?php echo esc_url( YITH_WCWTL_URL ); ?>assets/images/07.png" />
			</div>
			<div class="col-2">
				<div class="section-title">
					<img src="<?php echo esc_url( YITH_WCWTL_URL ); ?>assets/images/07-icon.png" />
					<h2><?php esc_html_e( 'Exclusion List', 'yith-woocommerce-waiting-list' ); ?></h2>
				</div>
				<p>
					<?php
					// translators: %s: //TODO description.
					echo sprintf( esc_html__( 'Do you want that the plugin works only for some and not all "out of stock" products? %1$sExclusion list table%2$s has been developed to meet your need and to allow you to exclude some specific products for which no email has to be sent as they come back in stock.', 'yith-woocommerce-waiting-list' ), '<b>', '</b>' );
					?>
				</p>
			</div>
		</div>
	</div>
	<div class="section section-odd clear" style="background: url(<?php echo esc_url( YITH_WCWTL_URL ); ?>assets/images/08-bg.png) no-repeat #f1f1f1; background-position: 15% 100%">
		<div class="landing-container">
			<div class="col-2">
				<div class="section-title">
					<img src="<?php echo esc_url( YITH_WCWTL_URL ); ?>assets/images/08-icon.png" />
					<h2><?php esc_html_e( 'Waiting list Checklist', 'yith-woocommerce-waiting-list' ); ?></h2>
				</div>
				<p>
					<?php
					// translators: %s: //TODO description.
					echo sprintf( esc_html__( '"Waiting List Checklist" tab allows you to check any moment the status of waiting lists for %1$sout-of-stock products%2$s. ', 'yith-woocommerce-waiting-list' ), '<b>', '</b>' );
					?>
				</p>
				<p>
					<?php
					// translators: %s: //TODO description.
					echo sprintf( esc_html__( 'For each of them, you have the following options available: %1$sdelete list%2$s, %1$ssend email%2$s and a %1$sbutton through which you can access to the list with users%2$s. %3$sNothing prevents you from adding a new user to the list whenever you want.', 'yith-woocommerce-waiting-list' ), '<b>', '</b>', '<br>' );
					?>
				</p>
			</div>
			<div class="col-1">
				<img src="<?php echo esc_url( YITH_WCWTL_URL ); ?>assets/images/08.png" />
			</div>
		</div>
	</div>
	<div class="section section-even clear" style="background: url(<?php echo esc_url( YITH_WCWTL_URL ); ?>assets/images/09-bg.png) no-repeat #fff; background-position: 85% 100%">
		<div class="landing-container">
			<div class="col-1">
				<img src="<?php echo esc_url( YITH_WCWTL_URL ); ?>assets/images/09.png" />
			</div>
			<div class="col-2">
				<div class="section-title">
					<img src="<?php echo esc_url( YITH_WCWTL_URL ); ?>assets/images/09-icon.png" />
					<h2><?php esc_html_e( 'Mandrill', 'yith-woocommerce-waiting-list' ); ?></h2>
				</div>
				<p>
					<?php
					// translators: %s: //TODO description.
					echo sprintf( esc_html__( 'The use of Mandrill is recommended to those who need to manage in an %1$sadvanced way%2$s the email sending for their shop.%3$s The integration with %1$sMandrill%2$s allows managing also those emails sent by YITH WooCommerce Waiting List in an optimal way.', 'yith-woocommerce-waiting-list' ), '<b>', '</b>', '<br>' );
					?>
				</p>
			</div>
		</div>
	</div>
	<div class="section section-odd clear" style="background: url(<?php echo esc_url( YITH_WCWTL_URL ); ?>assets/images/10-bg.png) no-repeat #f1f1f1; background-position: 15% 100%">
		<div class="landing-container">
			<div class="col-2">
				<div class="section-title">
					<img src="<?php echo esc_url( YITH_WCWTL_URL ); ?>assets/images/10-icon.png" />
					<h2><?php esc_html_e( 'Shortcode', 'yith-woocommerce-waiting-list' ); ?></h2>
				</div>
				<p>
					<?php
					// translators: %s: //TODO description.
					echo sprintf( esc_html__( 'The shortcode has been conceived to allow your users to register to a specific product %1$swaiting list%2$s in any spot of the site not only necessarily on the product page.%3$s Manage your contents in the best possible way and place the button %1$sstrategically%2$s in your pages.', 'yith-woocommerce-waiting-list' ), '<b>', '</b>', '<br>' );
					?>
				</p>
				<p>

				</p>
			</div>
			<div class="col-1">
				<img src="<?php echo esc_url( YITH_WCWTL_URL ); ?>assets/images/10.png" />
			</div>
		</div>
	</div>
	<div class="section section-cta section-odd">
		<div class="landing-container">
			<div class="premium-cta">
				<p>
					<?php
					// translators: %s: //TODO description.
					echo sprintf( esc_html__( 'Upgrade to %1$spremium version%2$s of %1$sYITH WooCommerce Waiting List%2$s to benefit from all features!', 'yith-woocommerce-waiting-list' ), '<span class="highlight">', '</span>' );
					?>
				</p>
				<a href="<?php echo esc_url( YITH_WCWTL_Admin()->get_premium_landing_uri() ); ?>" target="_blank" class="premium-cta-button button btn">
					<span class="highlight"><?php esc_html_e( 'UPGRADE', 'yith-woocommerce-waiting-list' ); ?></span>
					<span><?php esc_html_e( 'to the premium version', 'yith-woocommerce-waiting-list' ); ?></span>
				</a>
			</div>
		</div>
	</div>
</div>

