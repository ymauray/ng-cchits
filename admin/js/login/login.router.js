(function(angular, $, _) {

    'use strict';

    angular
        .module('cchits.admin.login')
        .config(config)
    ;

    config.$inject = ['$stateProvider'];
    function config($stateProvider) {
        $stateProvider
            .state('login', {
                url: '/login',
                templateUrl: 'js/login/login.view.html',
                controller: LoginController,
                controllerAs: 'loginController'
            })
            .state('logout', {
                url: '/logout',
                template: '<data-ui-view></data-ui-view>',
                controller: LogoutController,
                controllerAs: 'logoutController'
            })
        ;

        LoginController.$inject = ['Restangular', '$state', 'authService'];
        function LoginController(Restangular, $state, authService) {
            var _controller = angular.extend(this, {
                username: 'admin',
                password: 'Ch@ng3M3',
                authenticate: function() {
                    Restangular.all('principal').authenticate({
                        username: _controller.username,
                        password: _controller.password
                    }).then(function(data) {
                        if (data.code == 'ok') {
                            authService.setToken(data.token);
                            $state.go('home');
                        } else if (data.code == 'otp') {
                            $state.go('otp');
                        }
                    });
                },
                google: function() {
                    Restangular.all('oauth').one('google', 'config').get().then(function(data) {
                        if (data.code == 'ok') {
                            window.location = data.url;
                        }
                    });
                }
            });
        }

        LogoutController.$inject = ['$state', 'authService'];
        function LogoutController($state, authService) {
            authService.setToken(null);
            $state.go('home');
        }
    }
})(window.angular, window.jQuery, window._);