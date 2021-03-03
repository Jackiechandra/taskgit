define(
    [
         'ko'
    ],
    function (ko) {
        'use strict';
        
        var customerStoreCreditBalance = ko.observable(0),
            customerStoreCreditBalanceCurrency = ko.observable(0);
        
        return {
            /**
             * Retrieve customer store credit balance
             * 
             * @return {Number}
             */
            customerStoreCreditBalance: customerStoreCreditBalance,

            /**
             * Retrieve customer store credit balance currency
             *
             * @return {Number}
             */
            customerStoreCreditBalanceCurrency: customerStoreCreditBalanceCurrency
        }
    }
);
