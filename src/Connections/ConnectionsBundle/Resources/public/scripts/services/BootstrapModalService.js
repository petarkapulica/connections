angular.module("connections_app").factory('BootstrapModalService', [
    '$uibModal',
    function($uibModal) {

        return {
            popup: function (title,message,animationsEnabled) {
                return $uibModal.open({
                    animation: animationsEnabled,
                    backdrop: "static",
                    templateUrl: 'bundles/connections/scripts/template/modal.html',
                    controller: 'BootstrapModalController',
                    resolve: {
                        dialogOptions: function() {
                            return {
                                title: title,
                                question: message
                            }
                        }
                    }
                });
            }
        }

    }]);