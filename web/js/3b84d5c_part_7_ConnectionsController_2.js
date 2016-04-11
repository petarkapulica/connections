angular.module("connections_app").controller("ConnectionsController", [
    "$scope",
    "ConnectionsService",
    "BootstrapModalService",
    "ProfileService",
    "OnGetService",
    "ErrorService",
    function ($scope, ConnectionsService, BootstrapModalService, ProfileService, OnGetService, ErrorService) {

        $scope.paginationData = {
            Page: 1,
            Order: null
        };

        getUserList();

        function getUserList(){
            ConnectionsService.getUserList($scope.paginationData).then(
                function(response){
                    OnGetService.onGetUserList(response,$scope);
                },
                function(response){
                    ErrorService.showError(response);
                }
            );
        }

        $scope.sortUsers = function(order){
            $scope.paginationData.Order = order;
            getUserList();
        };

        $scope.getUsersPerPage = function(page){
            if(page != $scope.paginationData.Page){
                $scope.paginationData.Page = page;
                getUserList();
            }
        };

        $scope.follow = function(id){
            ProfileService.followUser(id).then(
                function(response){
                    getUserList();
                    BootstrapModalService.popup("Success!", response.data.success, true);
                },
                function(response){
                    ErrorService.showError(response);
                }
            );
        };

        $scope.unfollow = function(id){
            ProfileService.unfollowUser(id).then(
                function(response){
                    getUserList();
                    BootstrapModalService.popup("Success!", response.data.success, true);
                },
                function(response){
                    ErrorService.showError(response);
                }
            );
        };

    }]);