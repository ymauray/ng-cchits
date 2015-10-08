(function(angular, $, _) {

    'use strict';

    angular
        .module('cchits.admin')
        .controller('AdminController', AdminController)
    ;

    AdminController.$inject = ['$rootScope', '$state'];
    function AdminController($rootScope, $state) {
        $rootScope.$on('$stateChangeError', function(event, to, toParams, from, fromParams, error) {
            console.log('State change error detected, going from "' + from.name + '" to "' + to.name + '" :', error);
            $state.go('login');
        });
        $rootScope.$state = $state;
    }

})(window.angular, window.jQuery, window._);