angular.module("connections_app").controller("ProfileController", [
    "$scope",
    "$routeParams",
    "ProfileService",
    "OnGetService",
    "ErrorService",
    function ($scope, $routeParams, ProfileService, OnGetService, ErrorService) {

        $scope.directFriendsPage = 1;
        $scope.allDirectFriendsLoaded = false;
        $scope.directFriends = [];
        $scope.friendsOfFriendsPage = 1;
        $scope.allFriendsOfFriendsLoaded = false;
        $scope.friendsOfFriends = [];
        $scope.suggestedFriendsPage = 1;
        $scope.allSuggestedFriendsLoaded = false;
        $scope.suggestedFriends = [];

        ProfileService.getProfileData($routeParams.id || 0).then(
            function(response){
                OnGetService.onGetProfileData(response,$scope);
            },
            function(response){
                ErrorService.showError(response);
            }
        );
        getDirectFriends();
        getFriendsOfFriends();
        if(!$routeParams.id){
            getSuggestedFriends();
            $scope.showSuggested = true;
        }

        $scope.loadMore = function(friends){
            switch (friends){
                case "direct":
                    $scope.directFriendsPage++;
                    if(!$scope.allDirectFriendsLoaded){
                        getDirectFriends();
                    }
                    break;
                case "fof":
                    $scope.friendsOfFriendsPage++;
                    if(!$scope.allFriendsOfFriendsLoaded){
                        getFriendsOfFriends();
                    }
                    break;
                case "suggested":
                    $scope.suggestedFriendsPage++;
                    if(!$scope.allSuggestedFriendsLoaded){
                        getSuggestedFriends();
                    }
                    break;
            }
        };

        function getDirectFriends(){
            ProfileService.getDirectFriends($routeParams.id || 0, $scope.directFriendsPage).then(
                function(response){
                    OnGetService.onGetDirectFriends(response,$scope);
                    if(response.data.length < 10){
                        $scope.allDirectFriendsLoaded = true;
                    }
                },
                function(response){
                    ErrorService.showError(response);
                }
            );
        }

        function getFriendsOfFriends(){
            ProfileService.getFriendsOfFriends($routeParams.id || 0, $scope.friendsOfFriendsPage).then(
                function(response){
                    OnGetService.onGetFriendsOfFriends(response,$scope);
                    if(response.data.length < 10){
                        $scope.allFriendsOfFriendsLoaded = true;
                    }
                },
                function(response){
                    ErrorService.showError(response);
                }
            );
        }

        function getSuggestedFriends(){
            ProfileService.getSuggestedFriends($routeParams.id || 0, $scope.suggestedFriendsPage).then(
                function(response){
                    OnGetService.onGetSuggestedFriends(response,$scope);
                    if(response.data.length < 10){
                        $scope.allSuggestedFriendsLoaded = true;
                    }
                },
                function(response){
                    ErrorService.showError(response);
                }
            );
        }

    }]);