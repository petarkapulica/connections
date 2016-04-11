angular.module("connections_app").factory('ErrorService', [
    "BootstrapModalService",
    function(BootstrapModalService) {
        return {
            showError : function(response)
            {
                BootstrapModalService.popup("Whoops", response.data.error, true);
            }
        }
    }]);