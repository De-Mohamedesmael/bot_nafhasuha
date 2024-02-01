<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used during authentication for various
    | messages that we need to display to the user. You are free to modify
    | these language lines according to your application's requirements.
    |
    */


        'order_step1' => [
            'title' => 'Your order has been successfully created',
            'body' => 'Your order #:code has been successfully created and is pending approval or quotations.',
        ],
        'order_step2' => [
            'title' => 'Your order has been approved',
            'body' => 'Your order #:code has been approved.',
        ],
        'order_step3' => [
            'title' => 'Your order has been completed',
            'body' => 'Your order #:code has been completed. Thank you for using our services.',
        ],
        'order_step4' => [
            'title'=>'You have a new quote',
            'body'=>'You have a price quote for your order #:code Thank you for using it. We will check it',
        ],
        'order_step1_provider' => [
            'title' => 'You have a new order',
            'body' => 'You have a new order with code #:code.',
        ],
    'PriceRequest' => [
        'title'=>'You have a new offer',
        'body'=>'You have a new offer for your order number #:code . Thank you for using Nafhasuha',
    ],


    'order_step_transporter0' => [
        'title' => 'Approval Pending for Tow Truck',
        'body' => 'Your request #:code has been approved and is awaiting a tow truck.',
    ],
    'order_step_transporter1' => [
        'title' => 'Tow Truck En Route to You',
        'body' => 'The tow truck is on its way to you. Please wait.',
    ],
    'order_step_transporter1-2' => [
        'title' => 'Your Request is Completed',
        'body' => 'Your request #:code has been completed, and the tow truck is en route to the center to pick up the vehicle.',
    ],
    'order_step_transporter1-3' => [
        'title' => 'Tow Truck En Route',
        'body' => 'The tow truck is on its way to the center to pick up the vehicle.',
    ],

    'order_step_transporter_canceled' => [
        'title' => 'Your Request has been Successfully Canceled',
        'body' => 'Your request #:code has been canceled, and the tow truck is en route to the center to pick up the vehicle.',
    ],
    'order_step_transporter2' => [
        'title' => 'Picked up by the Tow Truck',
        'body' => 'The vehicle has been picked up by the tow truck, and delivery to the destination is in progress.',
    ],
    'order_step_transporter3' => [
        'title' => 'Successfully Delivered',
        'body' => 'The vehicle has been successfully delivered to the destination. Thank you.',
    ],
    'order_step_transporter4' => [
        'title' => 'Successfully Delivered to the Center',
        'body' => 'The vehicle has been successfully delivered to the center. Thank you.',
    ],

    'order_step_MaintenanceReport0' => [
        'title' => 'Maintenance Report Issued',
        'body' => 'The maintenance report has been issued. Please review the maintenance reports.',
    ],
    'order_step_MaintenanceReport2' => [
        'title' => 'Report Approved',
        'body' => 'The report for request #:code has been approved. You can now start the maintenance process.',
    ],

    'order_step10' => [
        'title'=>'Searching for another service provider',
        'body'=>'Your request has been canceled and we are looking for another service provider. Thank you for choosing Nafhasuha',
    ],

];
