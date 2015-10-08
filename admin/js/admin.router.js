(function(angular, $, _) {

    'use strict';

    angular
        .module('cchits.admin')
        .config(config)
    ;

    config.$inject = ['$urlRouterProvider', '$stateProvider'];
    function config($urlRouterProvider, $stateProvider) {
        $urlRouterProvider
            .otherwise(function($injector, $location) {
                var $state = $injector.get('$state');
                $state.go('home');
            })
        ;

        $stateProvider
            .state('secured', {
                abstract: true,
                template: '<data-ui-view></data-ui-view>',
                resolve: {
                    principal: getPrincipal
                }
            })
        ;
    }

    getPrincipal.$inject = ['Restangular'];
    function getPrincipal(Restangular) {
        return Restangular.one('principal').get();
    }

})(window.angular, window.jQuery, window._);