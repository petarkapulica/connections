var connections_app = angular.module('connections_app',
    ['ngRoute', 'ui.bootstrap']);

connections_app.config(function ($routeProvider) {

    $routeProvider
        .when('/profile/:id?',
        {
            controller: 'ProfileController',
            templateUrl: 'bundles/connections/scripts/views/profile/profile.html'
        })
        .when('/connections', {
            controller: 'ConnectionsController',
            templateUrl: "bundles/connections/scripts/views/connections/connections.html"
        })
        .otherwise({
            redirectTo: '/'
        });

}).constant("CONFIG", {
    "API_URL": "app.php/api"
});


