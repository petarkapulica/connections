angular.module("connections_app").factory('ProfileService', [
    '$http',
    'CONFIG',
    function($http,CONFIG) {

        return {

            getProfileData: function (id) {
                return $http({
                    method: 'GET',
                    url: CONFIG.API_URL + "/user-details/" + id
                });
            },

            getDirectFriends : function(id, page){
                return $http({
                    method: 'POST',
                    url: CONFIG.API_URL + "/user-direct-friends/" + id,
                    data: {Page: page},
                    headers: {'Content-Type' : 'application/json'}
                });
            },

            getFriendsOfFriends : function(id, page){
                return $http({
                    method: 'POST',
                    url: CONFIG.API_URL + "/user-fof/" + id,
                    data: {Page: page},
                    headers: {'Content-Type' : 'application/json'}
                });
            },

            getSuggestedFriends : function(id, page){
                return $http({
                    method: 'POST',
                    url: CONFIG.API_URL + "/user-suggested/" + id,
                    data: {Page: page},
                    headers: {'Content-Type' : 'application/json'}
                });
            },

            followUser : function(id){
                return $http({
                    method: 'POST',
                    url: CONFIG.API_URL + "/follow",
                    data: {Id: id},
                    headers: {'Content-Type' : 'application/json'}
                });
            },

            unfollowUser : function(id){
                return $http({
                    method: 'DELETE',
                    url: CONFIG.API_URL + "/unfollow",
                    data: {Id: id},
                    headers: {'Content-Type' : 'application/json'}
                });
            }

        }

    }]);