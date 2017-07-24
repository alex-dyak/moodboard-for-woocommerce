<style>
    .section{
        margin-left: -20px;
        margin-right: -20px;
        font-family: "Raleway";
    }
    .section h1{
        text-align: center;
        text-transform: uppercase;
        color: #2d789a;
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
    .section .section-title{
        display: table;
    }
    .section .section-title img{
        display: table-cell;
        float: left;
        vertical-align: middle;
        width: auto;
        margin-right: 15px;
    }
    .section .section-title h2{
        display: table-cell;
        vertical-align: middle;
        padding: 0;
        font-size: 24px;
        font-weight: 700;
        color: #306388;
        text-transform: uppercase;
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
    .moodboard-cta{
        background-color: #2d789a;
        color: #fff;
        border-radius: 6px;
        padding: 20px 20px;
    }
    .moodboard-cta:after{
        content: '';
        display: block;
        clear: both;
    }
    .moodboard-cta p{
        margin: 7px 0;
        font-size: 14px;
        font-weight: 500;
        display: inline-block;
    }
    .moodboard-cta a.button{
        border-radius: 6px;
        height: 60px;
        float: right;
        background: url(<?php echo YITH_mdbd_URL?>assets/images/landing/upgrade.png) #ff643f no-repeat 13px 13px;
        border-color: #ff643f;
        box-shadow: none;
        outline: none;
        color: #fff;
        position: relative;
        padding: 9px 50px 9px 70px;
    }
    .moodboard-cta a.button:hover,
    .moodboard-cta a.button:active,
    .moodboard-cta a.button:focus{
        color: #fff;
        background: url(<?php echo YITH_mdbd_URL?>assets/images/landing/upgrade.png) #971d00 no-repeat 13px 13px;
        border-color: #971d00;
        box-shadow: none;
        outline: none;
    }
    .moodboard-cta a.button:focus{
        top: 1px;
    }
    .moodboard-cta a.button span{
        line-height: 13px;
    }
    .moodboard-cta a.button .highlight{
        display: block;
        font-size: 20px;
        font-weight: 700;
        line-height: 20px;
    }
    .moodboard-cta .highlight{
        text-transform: uppercase;
        background: none;
        font-weight: 800;
        color: #fff;
    }

    @media (max-width: 767px) {
        .moodboard-cta p{
            display: block;
            text-align: center;
        }
        .moodboard-cta{
            text-align: center;
        }
        .moodboard-cta a.button{
            float: none;
        }
        .section{
            margin: 0;
        }
    }

    @media (max-width: 480px){
        .wrap{
            margin-right: 0;
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
        .moodboard-cta a.button{
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
            <div class="moodboard-cta">
                <p><?php echo sprintf (__('Upgrade to the %1$spremium version%2$s%3$sof %1$sYITH WooCommerce moodboard%2$s to benefit from all features!','yith-woocommerce-moodboard'),'<span class="highlight">','</span>','<br/>');?></p>
                <a href="<?php echo YITH_mdbd_Admin_Init()->get_premium_landing_uri(); ?>" target="_blank" class="moodboard-cta-button button btn">
                   <?php echo sprintf (__('%1$sUPGRADE%2$s%3$s to the premium version%2$s','yith-woocommerce-moodboard'),'<span class="highlight">','</span>','<span>');?>
                </a>
            </div>
        </div>
    </div>
    <div class="section section-even clear" style="background: url(<?php echo YITH_mdbd_URL ?>assets/images/landing/background-1.png) no-repeat #fff; background-position: 85% 75%">
        <h1><?php _e('Premium Features', 'yith-woocommerce-moodboard');?></h1>
        <div class="landing-container">
            <div class="col-1">
                <img src="<?php echo YITH_mdbd_URL ?>assets/images/landing/01.png" alt="<?php _e('Multiple moodboard', 'yith-woocommerce-moodboard');?>" />
            </div>
            <div class="col-2">
                <div class="section-title">
                    <img src="<?php echo YITH_mdbd_URL ?>assets/images/landing/icon-1.png" alt="icon-1"/>
                    <h2><?php _e('Multiple moodboard', 'yith-woocommerce-moodboard');?></h2>
                </div>
                <p><?php echo sprintf (__('%1$sDoes it ever happened to you to have too many wishes for a single wish list?%2$s%3$s The possibility to manage one\'s wishes is a fundamental feature in a modern e-commerce store and it also lets users\' degree of satisfaction increase.%3$sThe option "multiple moodboard" of %1$sYITH moodboard%2$s makes this feature and many others on your online store available, and thanks to this plugin your customers will be able to create, manage and share their own wish lists.','yith-woocommerce-moodboard'),'<strong>','</strong>','<br/>');?></p>
            </div>
        </div>
    </div>
    <div class="section section-odd clear" style="background: url(<?php echo YITH_mdbd_URL ?>assets/images/landing/background-2.png) no-repeat #f1f1f1; background-position: 15% 100%">
        <div class="landing-container">
            <div class="col-2">
                <div class="section-title">
                    <img src="<?php echo YITH_mdbd_URL ?>assets/images/landing/icon-2.png" alt="icon-2" />
                    <h2><?php _e('moodboard Private', 'yith-woocommerce-moodboard');?></h2>
                </div>
                <p><?php echo sprintf (__('By enabling the option moodboard, users will also have the possibility to %1$smanage the visibility%2$s of their wish lists according to one of the following options:','yith-woocommerce-moodboard'),'<strong>','</strong>');?></p>
                <ul>
                    <li><?php echo sprintf (__('%1$spublic:%2$s all users can look for your wish list and see it;','yith-woocommerce-moodboard'),'<strong>','</strong>');?></li>
                    <li><?php echo sprintf (__('%1$sshared:%2$s only users possessing a direct link to the wish list page can display it;','yith-woocommerce-moodboard'),'<strong>','</strong>');?></li>
                    <li><?php echo sprintf (__('%1$sprivate:%2$s only the wish list creator can see it.','yith-woocommerce-moodboard'),'<strong>','</strong>');?></li>
                </ul>
            </div>
            <div class="col-1">
                <img src="<?php echo YITH_mdbd_URL ?>assets/images/landing/02.png" alt="<?php _e('moodboard Private', 'yith-woocommerce-moodboard');?>" />
            </div>
        </div>
    </div>
    <div class="section section-even clear" style="background: url(<?php echo YITH_mdbd_URL ?>assets/images/landing/background-3.png) no-repeat #fff; background-position: 85% 100%">
        <div class="landing-container">
            <div class="col-1">
                <img src="<?php echo YITH_mdbd_URL ?>assets/images/landing/03.png" alt="<?php _e('Estimate Cost', 'yith-woocommerce-moodboard');?>" />
            </div>
            <div class="col-2">
                <div class="section-title">
                    <img src="<?php echo YITH_mdbd_URL ?>assets/images/landing/icon-3.png" alt="icon-3" />
                    <h2><?php _e('Estimate Cost', 'yith-woocommerce-moodboard');?></h2>
                </div>
                <p><?php echo sprintf (__('%1$sDo you want to add the possibility to ask for estimates of costs into your catalogue?%3$s Do you want to manage customised packets for faithful customers in your store?%2$s%3$sThanks to the feature "estimate cost" of %1$sYITH WooCommerce moodboard%2$s, each registered user will be able to ask for an estimate of their own products in the moodboard and add a text in the popup window that will open just after clicking. Then, they can confirm the text and send an email with all necessary information directly to the address that you have previously set.','yith-woocommerce-moodboard'),'<strong>','</strong>','<br/>');?></p>
            </div>
        </div>
    </div>
    <div class="section section-odd clear" style="background: url(<?php echo YITH_mdbd_URL ?>assets/images/landing/background-4.png) no-repeat #f1f1f1; background-position: 15% 100%">
        <div class="landing-container">
            <div class="col-2">
                <div class="section-title">
                    <img src="<?php echo YITH_mdbd_URL ?>assets/images/landing/icon-4.png" alt="icon-4" />
                    <h2><?php _e('Admin Panel', 'yith-woocommerce-moodboard');?></h2>
                </div>
                <p><?php echo sprintf (__('Thanks to the useful Admin panel, accessible directly among the WooCommerce submenu pages, you will have total control on users\' moodboards. In addition to that, evaluating the degree of appreciation for your products has never been so easy, now that %1$syou can see a useful report,%2$s available directly in the product page, which registers the occurrences of the product in customers\' wish lists.','yith-woocommerce-moodboard'),'<strong>','</strong>');?></p>
            </div>
            <div class="col-1">
                <img src="<?php echo YITH_mdbd_URL ?>assets/images/landing/04.png" alt="<?php _e('Admin Panel', 'yith-woocommerce-moodboard');?>" />
            </div>
        </div>
    </div>
    <div class="section section-even clear" style="background: url(<?php echo YITH_mdbd_URL ?>assets/images/landing/background-5.png) no-repeat #fff; background-position: 85% 100%">
        <div class="landing-container">
            <div class="col-1">
                <img src="<?php echo YITH_mdbd_URL ?>assets/images/landing/05.png" alt="<?php _e('Search moodboards', 'yith-woocommerce-moodboard');?>" />
            </div>
            <div class="col-2">
                <div class="section-title">
                    <img src="<?php echo YITH_mdbd_URL?>assets/images/landing/icon-5.png" alt="icon-5" />
                    <h2><?php _e('Search moodboards', 'yith-woocommerce-moodboard');?></h2>
                </div>
                <p><?php echo sprintf (__('How many times have you been looking for the perfect gift for a important event but you had no idea of what to buy? %1$s\'Search moodboards\'%2$s allows your e-shop users to access public moodboards of anyone, by simply knowing their name or email. This way you can grant %1$shigher visibility%2$s to your products and even encourage users to purchase.','yith-woocommerce-moodboard'),'<strong>','</strong>');?></p>
            </div>
        </div>
    </div>
    <div class="section section-odd clear" style="background: url(<?php echo YITH_mdbd_URL ?>assets/images/landing/06-bg.png) no-repeat #f1f1f1; background-position: 15% 100%">
        <div class="landing-container">
            <div class="col-2">
                <div class="section-title">
                    <img src="<?php echo YITH_mdbd_URL ?>assets/images/landing/06-icon.png" alt="icon-6" />
                    <h2><?php _e('\'ADD TO CART\' CHECKBOX', 'yith-woocommerce-moodboard');?></h2>
                </div>
                <p><?php echo sprintf (__('Your shop offers always a wide selection of products and moodboards of your users get more and more crowded everyday. Give them the possibility to select %1$ssome or all products%2$s in the moodboard and add them to cart just with one click.','yith-woocommerce-moodboard'),'<strong>','</strong>');?></p>
            </div>
            <div class="col-1">
                <img src="<?php echo YITH_mdbd_URL ?>assets/images/landing/06.png" alt="<?php _e('\'ADD TO CART\'', 'yith-woocommerce-moodboard');?>" />
            </div>
        </div>
    </div>
    <div class="section section-even clear" style="background: url(<?php echo YITH_mdbd_URL ?>assets/images/landing/07-bg.png) no-repeat #fff; background-position: 85% 100%">
        <div class="landing-container">
            <div class="col-1">
                <img src="<?php echo YITH_mdbd_URL ?>assets/images/landing/07.png" alt="<?php _e('DISABLE moodboard', 'yith-woocommerce-moodboard');?>" />
            </div>
            <div class="col-2">
                <div class="section-title">
                    <img src="<?php echo YITH_mdbd_URL?>assets/images/landing/07-icon.png" alt="icon-7" />
                    <h2><?php _e('DISABLE moodboard FOR UNLOGGED USERS', 'yith-woocommerce-moodboard');?></h2>
                </div>
                <p><?php echo sprintf (__('Favour users that have registered to your shop and disable plugin functionalities for all users that have not. By disabling this option, each time they try to add a product to the moodboard, they will be %1$sredirected%2$s to "My Account" page and a message will invite them to log in.','yith-woocommerce-moodboard'),'<strong>','</strong>');?></p>
            </div>
        </div>
    </div>
    <div class="section section-odd clear" style="background: url(<?php echo YITH_mdbd_URL ?>assets/images/landing/08-bg.png) no-repeat #f1f1f1; background-position: 15% 100%">
        <div class="landing-container">
            <div class="col-2">
                <div class="section-title">
                    <img src="<?php echo YITH_mdbd_URL ?>assets/images/landing/08-icon.png" alt="icon-08" />
                    <h2><?php _e('MESSAGE TO UNLOGGED USERS', 'yith-woocommerce-moodboard');?></h2>
                </div>
                <p><?php echo sprintf (__('Invite users that are visiting your shop to login if they want to fully benefit from moodboard functionalities. Show a %1$scustomised message%2$s and redirect them to "My Account" page for registration.','yith-woocommerce-moodboard'),'<strong>','</strong>');?></p>
            </div>
            <div class="col-1">
                <img src="<?php echo YITH_mdbd_URL ?>assets/images/landing/08.png" alt="<?php _e('UNLOGGED USERS', 'yith-woocommerce-moodboard');?>" />
            </div>
        </div>
    </div>
    <div class="section section-even clear" style="background: url(<?php echo YITH_mdbd_URL ?>assets/images/landing/09-bg.png) no-repeat #fff; background-position: 85% 100%">
        <div class="landing-container">
            <div class="col-1">
                <img src="<?php echo YITH_mdbd_URL ?>assets/images/landing/09.png" alt="<?php _e('POPULAR TABLE', 'yith-woocommerce-moodboard');?>" />
            </div>
            <div class="col-2">
                <div class="section-title">
                    <img src="<?php echo YITH_mdbd_URL?>assets/images/landing/09-icon.png" alt="icon-09" />
                    <h2><?php _e('POPULAR TABLE', 'yith-woocommerce-moodboard');?></h2>
                </div>
                <p><?php echo sprintf (__('Some products draw customer\'s attention more than others and they do not hesitate to add products to their moodboard. Table %1$s\'Popular\'%2$s allows you, as shop administrator, to track products that appear most frequently in their moodboards.','yith-woocommerce-moodboard'),'<strong>','</strong>');?></p>
            </div>
        </div>
    </div>
    <div class="section section-odd clear" style="background: url(<?php echo YITH_mdbd_URL ?>assets/images/landing/10-bg.png) no-repeat #f1f1f1; background-position: 15% 100%">
        <div class="landing-container">
            <div class="col-2">
                <div class="section-title">
                    <img src="<?php echo YITH_mdbd_URL ?>assets/images/landing/10-icon.png" alt="icon-10" />
                    <h2><?php _e('FUNCTIONALITIES IN ONE CLICK', 'yith-woocommerce-moodboard');?></h2>
                </div>
                <p><?php echo sprintf (__('Users have the possibility to search for a moodboard, create a new one or display those already created. Add these %1$sfunctionalities%2$s through the dedicated widgets or show them immediately after "moodboard" table.','yith-woocommerce-moodboard'),'<strong>','</strong>');?></p>
            </div>
            <div class="col-1">
                <img src="<?php echo YITH_mdbd_URL ?>assets/images/landing/10.png" alt="<?php _e('FUNCTIONALITIES', 'yith-woocommerce-moodboard');?>" />
            </div>
        </div>
    </div>
    <div class="section section-even clear" style="background: url(<?php echo YITH_mdbd_URL ?>assets/images/landing/11-bg.png) no-repeat #fff; background-position: 85% 100%">
        <div class="landing-container">
            <div class="col-1">
                <img src="<?php echo YITH_mdbd_URL ?>assets/images/landing/11.png" alt="<?php _e('PROMOTIONAL EMAIL', 'yith-woocommerce-moodboard');?>" />
            </div>
            <div class="col-2">
                <div class="section-title">
                    <img src="<?php echo YITH_mdbd_URL?>assets/images/landing/11-icon.png" alt="Icon-11" />
                    <h2><?php _e('PROMOTIONAL EMAIL', 'yith-woocommerce-moodboard');?></h2>
                </div>
                <p><?php echo sprintf (__('If you want to give the right input to your users to persuade them to %1$spurchase the products%2$s they have in their moodboards, you need to use this feature! %1$sSend them an email%2$s: customize its whole content from the option panel and add a coupon they can use in your shop, so that they will know you are offering a unique offer!','yith-woocommerce-moodboard'),'<strong>','</strong>');?></p>
            </div>
        </div>
    </div>
    <div class="section section-odd clear" style="background: url(<?php echo YITH_mdbd_URL ?>assets/images/landing/12-bg.png) no-repeat #f1f1f1; background-position: 15% 100%">
        <div class="landing-container">
            <div class="col-2">
                <div class="section-title">
                    <img src="<?php echo YITH_mdbd_URL ?>assets/images/landing/12-icon.png" alt="icon-12" />
                    <h2><?php _e('FROM A moodboard TO ANOTHER', 'yith-woocommerce-moodboard');?></h2>
            </div>
                <p><?php echo sprintf (__('Who said that a product has to remain forever in the same moodboard? With the option %1$s"Show "Move to another moodboard" dropdown menu"%2$s, with just one click users will be free to move a product from a moodboard to another one, managing as they want their lists.','yith-woocommerce-moodboard'),'<strong>','</strong>');?></p>
            </div>
            <div class="col-1">
                <img src="<?php echo YITH_mdbd_URL ?>assets/images/landing/12.png" alt="<?php _e('FROM A moodboard', 'yith-woocommerce-moodboard');?>" />
            </div>
        </div>
    </div>
    <div class="section section-even clear" style="background: url(<?php echo YITH_mdbd_URL ?>assets/images/landing/13-bg.png) no-repeat #fff; background-position: 85% 100%">
        <div class="landing-container">
            <div class="col-1">
                <img src="<?php echo YITH_mdbd_URL ?>assets/images/landing/13.png" alt="<?php _e('DATE', 'yith-woocommerce-moodboard');?>" />
            </div>
            <div class="col-2">
                <div class="section-title">
                    <img src="<?php echo YITH_mdbd_URL?>assets/images/landing/13-icon.png" alt="icon-13" />
                    <h2><?php _e('DATE OF ADDITION TO A moodboard', 'yith-woocommerce-moodboard');?></h2>
                </div>
                <p><?php echo sprintf (__('Activating the %1$s"Show date of addition"%2$s option, users can see the date in which they have added a particular product to their list: a new way to keep you users informed about their operations.','yith-woocommerce-moodboard'),'<strong>','</strong>');?></p>
            </div>
        </div>
    </div>
    <div class="section section-cta section-odd">
        <div class="landing-container">
            <div class="moodboard-cta">
                <p><?php echo sprintf (__('Upgrade to the %1$spremium version%2$s%3$sof %1$sYITH WooCommerce moodboard%2$s to benefit from all features!','yith-woocommerce-moodboard'),'<span class="highlight">','</span>','<br/>');?></p>
                <a href="<?php echo YITH_mdbd_Admin_Init()->get_premium_landing_uri();?>" target="_blank" class="moodboard-cta-button button btn">
                    <?php echo sprintf (__('%1$sUPGRADE%2$s%3$s to the premium version%2$s','yith-woocommerce-moodboard'),'<span class="highlight">','</span>','<span>');?>
                </a>
            </div>
        </div>
    </div>
</div>