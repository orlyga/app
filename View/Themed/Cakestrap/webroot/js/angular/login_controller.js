var LoginController = function($scope, $http) {
    $scope.login = {};
    $scope.login.user = null;

    $scope.login.connect = function() {
        $http.get('/login').success(function(data, status) {
            alert("hi");
            if (status < 200 || status >= 300)
                return;
            $scope.login.user = data;
        });
    };
    
    $scope.login.disconnect = function() {
        $scope.login.user = null;
    };

   $("submit_login").click(function(){
    $scope.$apply(function(){
      $http.get('/login').success(function(data, status) {
            alert("hi");
            if (status < 200 || status >= 300)
                return;
            $scope.login.user = data;
        });
    });
   });
};