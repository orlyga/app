/*user.js*/
function UserCtrl($scope,$http,'UserService',){
	$scope.url='/users/login';
	$scope.result=[];
	$scope.handleLogin = function(data,status){
	$scope.results=data;
	}
$scope.fetch=functin(){
	$http.post($scope.url).success($scope.handleLogin);
	}

}
var userModule = angular.module('userModule', []);
userModule.factory('UserService', function() {
  var sdo = {
		isLogged: false,
		username: ''
	};
	return sdo;
});
app.controller('UserCtrl', function($scope, 'userAuthService') {
      
   //to do something with it...
   userAuthService.getUserInfo().then(function(data) {
       //this will execute when the 
       //AJAX call completes.
       $scope.userInfo = data;
       console.log(data);
   });
};