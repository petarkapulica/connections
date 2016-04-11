angular.module("connections_app").factory('ConnectionsService', [
    '$http',
    'CONFIG',
    function($http,CONFIG) {

        return {

            getUserList : function(data){
                return $http({
                    method: 'POST',
                    url: CONFIG.API_URL + "/users",
                    data: data,
                    headers: {'Content-Type' : 'application/json'}
                });
            }

        }

    }]);