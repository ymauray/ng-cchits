(function(angular, $, _) {

    'use strict';

    angular
        .module('cchits.admin', ['ui.router', 'restangular', 'admin.auth', 'cchits.admin.home', 'cchits.admin.login'])
        .config(config)
        .run(run)
    ;

    config.$inject = ['RestangularProvider'];
    function config(RestangularProvider) {
        RestangularProvider.addElementTransformer('principal', true, function(principal) {
            console.log("Adding method 'authenticate' to 'principal'");
            principal.addRestangularMethod('authenticate', 'post', 'authenticate');
            return principal;
        });
    }

    run.$inject = ['Restangular', 'authService'];
    function run(Restangular, authService) {
        Restangular.setBaseUrl('rest');
        Restangular.addFullRequestInterceptor(function(element, operation, what, url, headers, params) {
            var token = authService.getToken();
            console.log('Request interceptor, token =', token);
            if (!_.isEmpty(token)) {
                headers['X-Auth-Token'] = token;
            }
            return {
                headers: headers
            };
        });
    }

})(window.angular, window.jQuery, window._);