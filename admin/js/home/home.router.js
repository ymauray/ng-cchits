(function(angular, $, _) {

    'use strict';

    angular
        .module('cchits.admin.home')
        .config(config)
    ;

    config.$inject = ['$stateProvider'];
    function config($stateProvider) {
        $stateProvider
            .state('home', {
                parent: 'secured',
                url: '/home',
                templateUrl: 'js/home/home.view.html',
                controller: HomeController,
                controllerAs: 'homeController'
            })
        ;
    }

    HomeController.$inject = ['principal'];
    function HomeController(principal) {
    }

})(window.angular, window.jQuery, window._);