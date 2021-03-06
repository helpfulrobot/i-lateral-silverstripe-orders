<?php

// If subsites is installed
if(class_exists('Users_Account_Controller')) {
    Users_Account_Controller::add_extension('OrdersUserAccountControllerExtension');
    Member::add_extension('OrdersMemberExtension');
}

// If subsites is installed
if(class_exists('Subsite')) {
    Order::add_extension('SubsitesOrdersExtension');
    OrderAdmin::add_extension('SubsiteMenuExtension');
}

// If subsites is installed
if(class_exists('SiteConfig')) {
    SiteConfig::add_extension('OrdersSiteConfigExtension');
}
