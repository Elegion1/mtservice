<?php

use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

// Home
Breadcrumbs::for('home', function (BreadcrumbTrail $trail) {
    $trail->push(__('breadcrumbs.home'), route('home'));
});

// Noleggio (Car Rental)
Breadcrumbs::for('noleggio', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push(__('breadcrumbs.noleggio'), route('noleggio'));
});

// Transfer Taxi
Breadcrumbs::for('transfer', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push(__('breadcrumbs.transfer'), route('transfer'));
});

// Escursioni (Excursions)
Breadcrumbs::for('escursioni', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push(__('breadcrumbs.escursioni'), route('escursioni'));
});

// Prezzi e Destinazioni (Prices and Destinations)
Breadcrumbs::for('prezziDestinazioni', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push(__('breadcrumbs.prezziDestinazioni'), route('prezziDestinazioni'));
});

// Dicono di Noi (Testimonials)
Breadcrumbs::for('diconoDiNoi', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push(__('breadcrumbs.diconoDiNoi'), route('diconoDiNoi'));
});

// Contattaci (Contact Us)
Breadcrumbs::for('contattaci', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push(__('breadcrumbs.contattaci'), route('contattaci'));
});

// Partners
Breadcrumbs::for('partners', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push(__('breadcrumbs.partners'), route('partners'));
});

// FAQ
Breadcrumbs::for('faq', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push(__('breadcrumbs.faq'), route('faq'));
});

// Privacy & Terms
Breadcrumbs::for('privacy', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push(__('breadcrumbs.privacy'), route('privacy'));
});

// Servizi (Services Index)
Breadcrumbs::for('services.index', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push(__('breadcrumbs.services'), route('services.index'));
});

// Servizio specifico (Service Detail)
Breadcrumbs::for('service.show', function (BreadcrumbTrail $trail, $title, $id) {
    $trail->parent('services.index');
    $trail->push(__('breadcrumbs.service_show', ['title' => $title]), route('service.show', [$title, $id]));
});

// Escursione specifica (Excursion Detail)
Breadcrumbs::for('excursion.show', function (BreadcrumbTrail $trail, $name, $id) {
    $trail->parent('escursioni');
    $trail->push(__('breadcrumbs.excursion_show', ['name' => $name]), route('excursion.show', [$name, $id]));
});

Breadcrumbs::for('booking.status', function (BreadcrumbTrail $trail, $booking) {
    $trail->parent('home');
    $trail->push(__('breadcrumbs.bookings', ['booking' => $booking]), route('booking.status', [$booking]));
});

Breadcrumbs::for('booking.status.check', function (BreadcrumbTrail $trail, $booking) {
    $trail->parent('home');
    $trail->push(__('breadcrumbs.bookings', ['booking' => $booking]), route('booking.status', [$booking]));
});

// Dashboard
Breadcrumbs::for('dashboard', function (BreadcrumbTrail $trail) {
    $trail->push(__('breadcrumbs.dashboard'), route('dashboard'));
});

// Dashboard Routes Management
Breadcrumbs::for('dashboard.route', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push(__('breadcrumbs.dashboard_routes'), route('dashboard.route'));
});

// Dashboard Destinations Management
Breadcrumbs::for('dashboard.destination', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push(__('breadcrumbs.dashboard_destinations'), route('dashboard.destination'));
});

// Dashboard Excursions Management
Breadcrumbs::for('dashboard.excursion', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push(__('breadcrumbs.dashboard_excursions'), route('dashboard.excursion'));
});

// Dashboard Cars Management
Breadcrumbs::for('dashboard.car', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push(__('breadcrumbs.dashboard_cars'), route('dashboard.car'));
});

// Dashboard Reviews Management
Breadcrumbs::for('dashboard.review', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push(__('breadcrumbs.dashboard_reviews'), route('dashboard.review'));
});

// Dashboard Bookings Management
Breadcrumbs::for('dashboard.booking', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push(__('breadcrumbs.dashboard_bookings'), route('dashboard.booking'));
});

// Dashboard Contacts Management
Breadcrumbs::for('dashboard.contact', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push(__('breadcrumbs.dashboard_contacts'), route('dashboard.contact'));
});

// Dashboard Services Management
Breadcrumbs::for('dashboard.service', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push(__('breadcrumbs.dashboard_services'), route('dashboard.service'));
});

// Dashboard Partners Management
Breadcrumbs::for('dashboard.partner', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push(__('breadcrumbs.dashboard_partners'), route('dashboard.partner'));
});

// Dashboard Company Data Management
Breadcrumbs::for('dashboard.ownerData', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push(__('breadcrumbs.dashboard_ownerData'), route('dashboard.ownerData'));
});

// Dashboard Contents Management
Breadcrumbs::for('dashboard.content', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push(__('breadcrumbs.dashboard_contents'), route('dashboard.content'));
});

// Dashboard Pages Management
Breadcrumbs::for('dashboard.page', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push(__('breadcrumbs.dashboard_pages'), route('dashboard.page'));
});

// Dashboard Customers Management
Breadcrumbs::for('dashboard.customer', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push(__('breadcrumbs.dashboard_customers'), route('dashboard.customer'));
});

// Dashboard Discounts Management
Breadcrumbs::for('dashboard.discount', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push(__('breadcrumbs.dashboard_discounts'), route('dashboard.discount'));
});
