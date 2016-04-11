angular.module("connections_app").factory('OnGetService', [
    function() {

        return {

            onGetProfileData : function(response, $scope)
            {
                $scope.user = response.data[0];
            },

            onGetDirectFriends : function(response, $scope)
            {
                $scope.directFriends = $scope.directFriends.concat(response.data);
            },

            onGetFriendsOfFriends : function(response, $scope)
            {
               $scope.friendsOfFriends = $scope.friendsOfFriends.concat(response.data);
            },

            onGetSuggestedFriends : function(response, $scope)
            {
                $scope.suggestedFriends = $scope.suggestedFriends.concat(response.data);
            },

            onGetUserList : function(response, $scope)
            {
                $scope.users = response.data.Users;
                $scope.totalRecords = response.data.TotalRecords;
            }


        }

    }]);