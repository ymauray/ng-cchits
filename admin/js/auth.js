(function(angular, $, _) {

    'use strict';

    angular
        .module('admin.auth', ['angular-jwt', 'LocalStorageModule'])
        .constant('tokenStorageKey', 'ng.cchits.token')
        .factory('authService', authServiceFactory)
    ;

    authServiceFactory.$inject = ['jwtHelper', 'localStorageService', 'tokenStorageKey'];
    function authServiceFactory(jwtHelper, localStorageService, tokenStorageKey) {

        return {
            getToken: getToken,
            setToken: setToken
        };

        function getToken() {
            return localStorageService.get(tokenStorageKey);
        }

        function setToken(token) {
            localStorageService.set(tokenStorageKey, token);
        }
    }

})(window.angular, window.jQuery, window._);