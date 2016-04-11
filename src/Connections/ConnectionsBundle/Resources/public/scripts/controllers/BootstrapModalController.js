connections_app.controller('BootstrapModalController',
    function($scope, $modalInstance, dialogOptions){
        $scope.title = dialogOptions.title;
        $scope.alerts = dialogOptions.alerts;
        $scope.question = dialogOptions.question;

        $scope.ok = function () {
            $modalInstance.close(true);
        };

        $scope.cancel = function () {
            $modalInstance.dismiss(false);
        };
    });